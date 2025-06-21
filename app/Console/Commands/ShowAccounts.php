<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class ShowAccounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'accounts:show';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display all user accounts with login information';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== DAFTAR AKUN LAPANGKUY LARAVEL ===');
        $this->newLine();

        try {
            $users = User::orderBy('role')->orderBy('name')->get();
            
            if ($users->isEmpty()) {
                $this->warn('Tidak ada user dalam database.');
                $this->info('Silakan jalankan: php artisan db:seed --class=UserSeeder');
                return 0;
            }

            // Group users by role
            $adminUsers = $users->where('role', 'admin');
            $ownerUsers = $users->where('role', 'field_owner');
            $regularUsers = $users->where('role', 'user');

            // Display Admin Users
            if ($adminUsers->count() > 0) {
                $this->info('🔑 ADMIN ACCOUNTS:');
                $this->line(str_repeat('=', 50));
                foreach ($adminUsers as $user) {
                    $this->line("Nama     : {$user->name}");
                    $this->line("Email    : {$user->email}");
                    $this->line("Password : password");
                    $this->line("Role     : {$user->role}");
                    $this->line("Phone    : " . ($user->phone ?? 'N/A'));
                    $this->line("Address  : " . ($user->address ?? 'N/A'));
                    $this->line(str_repeat('-', 50));
                }
                $this->newLine();
            }

            // Display Field Owner Users
            if ($ownerUsers->count() > 0) {
                $this->info('🏢 FIELD OWNER ACCOUNTS:');
                $this->line(str_repeat('=', 50));
                foreach ($ownerUsers as $user) {
                    $this->line("Nama     : {$user->name}");
                    $this->line("Email    : {$user->email}");
                    $this->line("Password : password");
                    $this->line("Role     : {$user->role}");
                    $this->line("Phone    : " . ($user->phone ?? 'N/A'));
                    $this->line("Address  : " . ($user->address ?? 'N/A'));
                    $this->line(str_repeat('-', 50));
                }
                $this->newLine();
            }

            // Display Regular Users
            if ($regularUsers->count() > 0) {
                $this->info('👤 REGULAR USER ACCOUNTS:');
                $this->line(str_repeat('=', 50));
                foreach ($regularUsers as $user) {
                    $this->line("Nama     : {$user->name}");
                    $this->line("Email    : {$user->email}");
                    $this->line("Password : password");
                    $this->line("Role     : {$user->role}");
                    $this->line("Phone    : " . ($user->phone ?? 'N/A'));
                    $this->line("Address  : " . ($user->address ?? 'N/A'));
                    $this->line(str_repeat('-', 50));
                }
                $this->newLine();
            }

            // Summary
            $this->info('📋 SUMMARY:');
            $this->line(str_repeat('=', 50));
            $this->line("Admin Users        : {$adminUsers->count()}");
            $this->line("Field Owner Users  : {$ownerUsers->count()}");
            $this->line("Regular Users      : {$regularUsers->count()}");
            $this->line("Total Users        : {$users->count()}");
            $this->newLine();

            // Login Information
            $this->info('🔐 LOGIN INFORMATION:');
            $this->line(str_repeat('=', 50));
            $this->line('Default Password untuk semua akun: password');
            $this->line('Gunakan email di atas untuk login.');
            $this->newLine();

            // URLs
            $this->info('🌐 ACCESS URLs:');
            $this->line(str_repeat('=', 50));
            $this->line('Website          : http://localhost/lapangkuy_laravel/public');
            $this->line('Login Page       : http://localhost/lapangkuy_laravel/public/login');
            $this->line('Register Page    : http://localhost/lapangkuy_laravel/public/register');
            $this->line('Admin Dashboard  : http://localhost/lapangkuy_laravel/public/admin/dashboard');
            $this->line('Owner Dashboard  : http://localhost/lapangkuy_laravel/public/owner/dashboard');
            $this->line('User Dashboard   : http://localhost/lapangkuy_laravel/public/user/dashboard');
            $this->newLine();

            return 0;
            
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            $this->info('Pastikan database sudah di-setup dan migrasi sudah dijalankan.');
            return 1;
        }
    }
}
