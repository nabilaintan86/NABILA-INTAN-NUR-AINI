<?php

namespace App\Services\Ssh;

use App\Models\Device;
use Illuminate\Support\Facades\Process;

/**
 * Satu-satunya pintu masuk untuk menjalankan perintah di perangkat jaringan
 * lewat ssh(1). Dipakai oleh Speed Test supaya aturan keamanannya hanya ada
 * di satu tempat.
 *
 * Aturan yang dijaga kelas ini:
 *
 *  1. Tidak ada password yang pernah dibaca, disimpan, atau dikirim.
 *     Autentikasi murni key-based dengan BatchMode=yes, sehingga ssh gagal
 *     dengan rapi — bukan menggantung — saat kunci ditolak.
 *  2. ssh(1) dipanggil sebagai ARRAY lewat Process::run() tanpa shell, dan
 *     semua argumen berasal dari config — bukan dari request pengguna.
 *  3. Host tujuan punya dua jalan masuk, keduanya dibatasi:
 *     - forDevice(): device dari database yang ip_address-nya lolos validasi
 *       IP. Tidak ada input pengguna yang sampai ke nama host.
 *     - forManualHost(): alamat yang diketik Super Admin. Lewat saringan
 *       bentuk (IP/host) DAN daftar CIDR di SSH_ALLOWED_CIDRS, yang kosong
 *       secara default sehingga fitur ini mati sampai sengaja diaktifkan.
 *  4. Perintah remote mengikuti aturan ssh: argumen setelah nama host
 *     dirangkai jadi satu string yang dieksekusi shell di sisi perangkat,
 *     jadi setiap argumen wajib dibungkus escapeshellarg(). Daftar perintah
 *     sendiri tetap berasal dari kode, bukan dari pengguna.
 *  5. Kegagalan apa pun tidak dilempar keluar, melainkan dikembalikan sebagai
 *     hasil kosong, dan pesan aslinya (yang bisa memuat jejak server) tidak
 *     pernah diteruskan ke layar.
 */
class SshConnection
{
    /** Nilai StrictHostKeyChecking yang diterima. Sisanya diabaikan. */
    private const HOST_KEY_MODES = ['accept-new', 'yes', 'no'];

    private function __construct(
        private readonly string $host,
        private readonly string $user,
        private readonly int $port,
        private readonly string $identityFile,
        private readonly string $hostKeyMode,
        private readonly ?string $knownHostsFile,
        private readonly int $connectTimeout,
        private readonly int $timeout,
    ) {
    }

    /**
     * Apakah fitur berbasis SSH diaktifkan sama sekali di server ini?
     */
    public static function enabled(): bool
    {
        return (bool) config('ssh.enabled', false);
    }

    /**
     * Apakah server ini siap menjalankan perintah SSH: fitur aktif, user
     * terisi, dan private key benar-benar terbaca.
     *
     * Dipakai halaman untuk menampilkan/men Sembunyikan pilihan perangkat
     * supaya Super Admin tidak memilih target yang pasti gagal.
     */
    public static function isReady(): bool
    {
        $identityFile = trim((string) config('ssh.identity_file', ''));

        return self::enabled()
            && trim((string) config('ssh.user', '')) !== ''
            && $identityFile !== ''
            && is_readable($identityFile);
    }

    /**
     * Pola nama host yang diterima untuk target manual: label DNS maksimal 63
     * karakter (huruf/angka/tespis), dipisah titik, tidak boleh diawali atau
     * diakhiri tanda hubung. Tidak ada karakter lain yang lolos.
     */
    private const HOSTNAME_PATTERN = '/^(?=.{1,253}$)([a-z0-9]([a-z0-9-]{0,61}[a-z0-9])?)(\.[a-z0-9]([a-z0-9-]{0,61}[a-z0-9])?)*$/i';

    /**
     * Bangun koneksi ke satu perangkat, sekaligus memastikan konfigurasi
     * server benar-benar siap dipakai.
     *
     * @throws SshUnavailableException
     */
    public static function forDevice(Device $device): self
    {
        $host = trim((string) ($device->ip_address ?? ''));

        if (filter_var($host, FILTER_VALIDATE_IP) === false) {
            throw new SshUnavailableException(
                'Perangkat ini belum punya alamat IP yang valid, jadi tidak bisa diperiksa dari jauh.'
            );
        }

        // Perangkat dari database boleh langsung dipakai: alamatnya bukan
        // input pengguna, dan sudah jadi bagian dari data aplikasi.
        return self::build($host);
    }

    /**
     * Bangun koneksi ke alamat yang diketik Super Admin sendiri.
     *
     * Berbeda dari forDevice(), alamat di sini berasal dari request, jadi
     * harus disaring dua kali:
     *
     *  1. Bentuknya harus IP atau nama host yang memenuhi pola di atas.
     *  2. Harus ada di SSH_ALLOWED_CIDRS. Nama host di-resolve oleh server,
     *     lalu IP hasilnya ikut diperiksa — supaya nama domain tidak bisa
     *     dipakai untuk menyelundupkan alamat yang sebenarinya di luar
     *     daftar (mis. lewat entri DNS yang arahnya beda).
     *
     * @throws SshUnavailableException
     */
    public static function forManualHost(string $rawHost): self
    {
        $host = trim($rawHost);

        if ($host === '') {
            throw new SshUnavailableException('Alamat target belum diisi.');
        }

        $isIp = filter_var($host, FILTER_VALIDATE_IP) !== false;

        if (! $isIp && ! preg_match(self::HOSTNAME_PATTERN, $host)) {
            throw new SshUnavailableException(
                'Alamat itu bukan IP atau nama host yang valid. Contoh yang benar: 10.0.0.1 atau router.pop-a.'
            );
        }

        if (! self::isAllowedAddress($host, $isIp)) {
            throw new SshUnavailableException(
                'Alamat itu di luar jaringan yang diizinkan untuk pengujian. '
                . 'Tambahkan ke SSH_ALLOWED_CIDRS di server ini bila memang perlu diuji.'
            );
        }

        return self::build($host);
    }

    /**
     * Apakah target manual boleh dipakai, menurut SSH_ALLOWED_CIDRS?
     *
     * Daftar kosong berarti tidak ada yang boleh diuji — pilihan aman, supaya
     * fitur ini mati secara default sampai sengaja diaktifkan.
     */
    private static function isAllowedAddress(string $host, bool $isIp): bool
    {
        $cidrs = (array) config('ssh.allowed_cidrs', []);

        if ($cidrs === []) {
            return false;
        }

        // Alamat IP dicek langsung. Nama host di-resolve di sini, dan
        // SETIAP IP hasilnya harus lolos — kalau satu saja di luar daftar,
        // nama host itu ditolak.
        $addresses = $isIp ? [$host] : self::resolve($host);

        if ($addresses === []) {
            return false;
        }

        foreach ($addresses as $address) {
            if (! self::matchesAnyCidr($address, $cidrs)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return list<string> alamat IP hasil resolusi, tanpa duplikat
     */
    private static function resolve(string $hostname): array
    {
        $addresses = [];

        // filter_var dengan FILTER_FLAG_IPV4|IPV6 mengembalikan alamat yang
        // sudah tervalidasi, jadi tidak ada input mentah yang ikut lolos.
        $records = dns_get_record($hostname, DNS_A | DNS_AAAA) ?: [];

        foreach ($records as $record) {
            foreach (['ip', 'ipv6'] as $field) {
                if (isset($record[$field]) && filter_var($record[$field], FILTER_VALIDATE_IP) !== false) {
                    $addresses[] = $record[$field];
                }
            }
        }

        return array_values(array_unique($addresses));
    }

    /**
     * @param  list<string>  $cidrs
     */
    private static function matchesAnyCidr(string $address, array $cidrs): bool
    {
        foreach ($cidrs as $cidr) {
            if (self::matchesCidr($address, $cidr)) {
                return true;
            }
        }

        return false;
    }

    private static function matchesCidr(string $address, string $cidr): bool
    {
        $target = @inet_pton($address);

        if ($target === false) {
            return false;
        }

        $entry = trim($cidr);

        if ($entry === '') {
            return false;
        }

        // Satu IP polos dianggap sebagai /32 (atau /128 untuk IPv6).
        if (! str_contains($entry, '/')) {
            $entry .= str_contains($entry, ':') ? '/128' : '/32';
        }

        [$subnetAddress, $prefix] = explode('/', $entry, 2);

        $subnet = @inet_pton($subnetAddress);

        // Prefix harus berupa angka dan tidak boleh melebihi panjang alamat
        // (32 bit untuk IPv4, 128 bit untuk IPv6).
        if ($subnet === false || ! ctype_digit($prefix)) {
            return false;
        }

        $bits = (int) $prefix;

        if ($bits > strlen($subnet) * 8) {
            return false;
        }

        // IPv4 tidak boleh dicocokkan dengan entri IPv6, dan sebaliknya.
        if (strlen($subnet) !== strlen($target)) {
            return false;
        }

        // Perbandingan bit demi bit sepanjang panjang prefix.
        $bytes = intdiv($bits, 8);
        $left = $bits % 8;

        if ($bytes > 0 && substr($subnet, 0, $bytes) !== substr($target, 0, $bytes)) {
            return false;
        }

        if ($left === 0) {
            return true;
        }

        $mask = ~((1 << (8 - $left)) - 1) & 0xFF;

        return (ord($subnet[$bytes]) & $mask) === (ord($target[$bytes]) & $mask);
    }

    /**
     * Bagian yang benar-benar membangun objek koneksi, dipakai bersama oleh
     * forDevice() dan forManualHost() supaya keduanya tidak bisa berbeda dalam
     * hal konfigurasi kredensial.
     *
     * @throws SshUnavailableException
     */
    private static function build(string $host): self
    {
        if (! self::enabled()) {
            throw new SshUnavailableException(
                'Pengujian ke perangkat luar belum diaktifkan di server ini (SSH_ENABLED).'
            );
        }

        $user = trim((string) config('ssh.user', ''));
        if ($user === '') {
            throw new SshUnavailableException(
                'User SSH untuk perangkat luar belum diisi (SSH_USER).'
            );
        }

        $identityFile = trim((string) config('ssh.identity_file', ''));
        if ($identityFile === '' || ! is_readable($identityFile)) {
            throw new SshUnavailableException(
                'Private key SSH tidak ditemukan atau tidak bisa dibaca (SSH_IDENTITY_FILE).'
            );
        }

        $hostKeyMode = (string) config('ssh.host_key_checking', 'accept-new');
        if (! in_array($hostKeyMode, self::HOST_KEY_MODES, true)) {
            $hostKeyMode = 'accept-new';
        }

        return new self(
            host: $host,
            user: $user,
            port: max(1, min(65535, (int) config('ssh.port', 22))),
            identityFile: $identityFile,
            hostKeyMode: $hostKeyMode,
            knownHostsFile: trim((string) config('ssh.known_hosts_file', '')) ?: null,
            connectTimeout: max(1, (int) config('ssh.connect_timeout', 10)),
            timeout: max(10, (int) config('ssh.timeout', 120)),
        );
    }

    public function host(): string
    {
        return $this->host;
    }

    /**
     * Jalankan satu perintah di perangkat dan kembalikan hasilnya. Tidak pernah
     * melempar exception; kegagalan apa pun jadi hasil kosong.
     *
     * @param  list<string>  $command
     */
    public function run(array $command, ?string $input = null, ?int $timeout = null): CommandResult
    {
        $process = Process::timeout($this->clampTimeout($timeout));

        // Payload (mis. body uji unggah) dikirim lewat stdin. Saat kosong,
        // Symfony langsung menutup stdin sehingga ssh tidak menggantung
        // menunggu ketikan.
        if ($input !== null) {
            $process->input($input);
        }

        try {
            $result = $process->run($this->sshCommand($command));
        } catch (\Throwable) {
            return CommandResult::failed();
        }

        return new CommandResult($result->successful(), $result->output());
    }

    /**
     * Apakah sebuah biner tersedia di perangkat tujuan?
     *
     * "command -v" adalah builtin shell remote, makanya perintahnya memang
     * ditafsirkan shell di sisi perangkat — bukan bug.
     */
    public function commandExists(string $binary): bool
    {
        $result = $this->run(['command', '-v', $binary], null, 15);

        return $result->ok && trim($result->output) !== '';
    }

    /**
     * Batas waktu efektif: tidak boleh melebihi batas configured, supaya
     * satu perintah tidak bisa menggantungkan request.
     */
    private function clampTimeout(?int $timeout): int
    {
        if ($timeout === null) {
            return $this->timeout;
        }

        return max(1, min($this->timeout, $timeout));
    }

    /**
     * Argumen ssh(1) lengkap dengan perintah yang dijalankan di perangkat.
     *
     * @param  list<string>  $command
     * @return list<string>
     */
    private function sshCommand(array $command): array
    {
        $options = [
            '-p', (string) $this->port,
            '-i', $this->identityFile,
            '-o', 'BatchMode=yes',
            '-o', 'StrictHostKeyChecking='.$this->hostKeyMode,
            '-o', 'ConnectTimeout='.$this->connectTimeout,
            '-o', 'ServerAliveInterval=15',
            '-o', 'ServerAliveCountMax=2',
            '-o', 'LogLevel=ERROR',
        ];

        if ($this->knownHostsFile !== null) {
            $options[] = '-o';
            $options[] = 'UserKnownHostsFile='.$this->knownHostsFile;
        }

        return [
            'ssh',
            ...$options,
            $this->user.'@'.$this->host,
            $this->joinForShell($command),
        ];
    }

    /**
     * Gabung argumen menjadi satu string perintah untuk shell remote.
     *
     * @param  list<string>  $command
     */
    private function joinForShell(array $command): string
    {
        return implode(' ', array_map(
            static fn (string $part): string => escapeshellarg($part),
            $command,
        ));
    }
}
