<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Batas ukuran foto profil: 2 MB.
     */
    private const MAX_PHOTO_KB = 2048;

    /**
     * Format foto yang diterima (hasil pemeriksaan MIME, bukan ekstensi).
     */
    private const PHOTO_MIMES = ['jpg', 'jpeg', 'png', 'webp'];

    /**
     * Dimensi maksimum foto (piksel) untuk mencegah gambar dimensi raksasa.
     */
    private const MAX_PHOTO_WIDTH = 4000;

    private const MAX_PHOTO_HEIGHT = 4000;

    /**
     * Halaman profile user yang sedang login.
     */
    public function index()
    {
        $user = auth()->user();

        return view('admin.profile', [
            'user' => $user,
            'notesCount' => $user->notes()->count(),
        ]);
    }

    /**
     * Simpan foto profil milik user yang sedang login.
     *
     * Identitas user selalu diambil dari session/auth, tidak pernah dari
     * input request, sehingga tidak ada cara mengganti foto user lain.
     */
    public function updatePhoto(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'photo' => [
                'required',
                // "image" memverifikasi isi file lewat MIME, bukan ekstensi nama.
                'image',
                'mimes:'.implode(',', self::PHOTO_MIMES),
                'max:'.self::MAX_PHOTO_KB,
                'dimensions:max_width='.self::MAX_PHOTO_WIDTH.',max_height='.self::MAX_PHOTO_HEIGHT,
                Rule::file()->max(self::MAX_PHOTO_KB),
            ],
        ], [
            'photo.required' => 'Pilih foto profil terlebih dahulu.',
            'photo.image' => 'File harus berupa gambar.',
            'photo.mimes' => 'Format foto harus JPG, PNG, atau WEBP.',
            'photo.max' => 'Ukuran foto maksimal 2 MB.',
            'photo.dimensions' => 'Dimensi foto maksimal 4000 x 4000 piksel.',
        ]);

        $photo = $validated['photo'];

        // Sanitasi nama file: hanya karakter aman, ekstensi dari daftar putih.
        $extension = strtolower($photo->guessExtension() ?: 'jpg');
        $filename = 'avatar-'.time().'-'.bin2hex(random_bytes(6)).'.'.$extension;
        $directory = User::AVATAR_DIRECTORY.'/'.$user->id;

        $path = $photo->storeAs($directory, $filename, ['disk' => 'public']);

        if ($path === false) {
            return redirect()
                ->route('admin.profile')
                ->with('error', 'Foto profil gagal disimpan. Silakan coba lagi.');
        }

        $previous = trim((string) $user->avatar);
        $user->forceFill(['avatar' => $path])->save();

        $this->deleteOwnedPhoto($previous, $user->id);

        return redirect()
            ->route('admin.profile')
            ->with('success', 'Foto profil berhasil diperbarui.');
    }

    /**
     * Simpan informasi akun milik user yang sedang login (nama + email).
     *
     * Role, status akun, tanggal terdaftar, dan status Telegram TIDAK bisa
     * diubah dari sini: field tersebut tidak divalidasi, jadi input apa pun
     * ke field itu diabaikan. User juga hanya bisa mengubah dirinya sendiri
     * karena target update selalu auth()->user().
     */
    public function updateAccount(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
        ], [
            'name.required' => 'Nama tidak boleh kosong.',
            'name.min' => 'Nama minimal 3 karakter.',
            'name.max' => 'Nama maksimal 255 karakter.',
            'email.required' => 'Email tidak boleh kosong.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email tersebut sudah dipakai user lain.',
        ]);

        $user->forceFill([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ])->save();

        return redirect()
            ->route('admin.profile')
            ->with('success', 'Informasi akun berhasil diperbarui.');
    }

    /**
     * Ganti password user yang sedang login.
     *
     * Password lama harus cocok, password baru minimal 8 karakter dan harus
     * sama dengan konfirmasi. Password disimpan lewat hashing Laravel
     * (bcrypt) dan tidak pernah ditampilkan kembali.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'current_password' => ['required', 'string', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'current_password.current_password' => 'Password saat ini salah.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user->forceFill([
            'password' => Hash::make($validated['password']),
        ])->save();

        return redirect()
            ->route('admin.profile')
            ->with('success', 'Password berhasil diubah.');
    }

    /**
     * Hapus foto lama milik user tersebut saja. Path di luar folder user
     * (mis. file yang diunggah manual) tidak pernah dihapus.
     */
    private function deleteOwnedPhoto(?string $path, int $userId): void
    {
        $path = trim((string) $path);

        if ($path === '') {
            return;
        }

        if (! str_starts_with($path, User::AVATAR_DIRECTORY.'/'.$userId.'/')) {
            return;
        }

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
