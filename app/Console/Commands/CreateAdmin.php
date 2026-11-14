<?php

namespace App\Console\Commands;

use App\Models\Admin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdmin extends Command
{
    protected $signature = 'admin:create';

    protected $description = 'Create or update an administrator account interactively';

    public function handle(): int
    {
        $name = $this->ask('Admin name');
        $email = strtolower((string) $this->ask('Admin email'));

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Enter a valid email address.');

            return self::FAILURE;
        }

        $password = $this->secret('Admin password (at least 12 characters)');
        $confirmation = $this->secret('Confirm password');

        if (strlen((string) $password) < 12 || $password !== $confirmation) {
            $this->error('Passwords must match and contain at least 12 characters.');

            return self::FAILURE;
        }

        Admin::updateOrCreate(
            ['email' => $email],
            ['name' => $name, 'password' => Hash::make($password)]
        );

        $this->info('Administrator account saved. Keep the password private.');

        return self::SUCCESS;
    }
}
