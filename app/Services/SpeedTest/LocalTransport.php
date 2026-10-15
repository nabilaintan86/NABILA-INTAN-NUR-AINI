<?php

namespace App\Services\SpeedTest;

use App\Services\Ssh\CommandResult;
use Illuminate\Support\Facades\Process;

/**
 * Menjalankan perintah langsung di server NOC.
 */
class LocalTransport extends CommandTransport
{
    /**
     * {@inheritDoc}
     */
    public function run(array $command, ?string $input = null, int $timeout = 60): CommandResult
    {
        try {
            $process = Process::timeout($timeout);

            if ($input !== null) {
                // Body payload dibaca lewat stdin, bukan argumen kedua run()
                // yang sebenarnya untuk environment.
                $process = $process->input($input);
            }

            $result = $process->run($command);
        } catch (\Throwable) {
            return CommandResult::failed();
        }

        return new CommandResult($result->successful(), $result->output());
    }

    /**
     * {@inheritDoc}
     */
    public function isAvailable(string $binary): bool
    {
        $result = $this->run(['which', $binary], null, 5);

        return $result->ok && trim($result->output) !== '';
    }

    /**
     * {@inheritDoc}
     */
    public function source(): string
    {
        return 'server';
    }
}
