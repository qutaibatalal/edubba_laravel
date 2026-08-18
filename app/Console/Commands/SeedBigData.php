<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SeedBigData extends Command
{
    protected $signature = 'seed:bigdata';

    protected $description = 'Seed the database with big data demo (2000+ students, full modules)';

    public function handle(): int
    {
        $this->info('╔══════════════════════════════════════════╗');
        $this->info('║   Edubba Big Data Demo Seeder           ║');
        $this->info('║   2000+ students, 100 faculty, full     ║');
        $this->info('║   attendance, exams, fees, tutoring...  ║');
        $this->info('╚══════════════════════════════════════════╝');
        $this->newLine();

        if (!$this->confirm('This will reset and re-seed the database. Continue?', true)) {
            return self::SUCCESS;
        }

        $this->info('Resetting database...');
        Artisan::call('migrate:fresh', ['--force' => true]);
        $this->info(Artisan::output());

        $this->info('Running base seeders...');
        Artisan::call('db:seed', ['--force' => true]);
        $this->info(Artisan::output());

        $this->info('Running Big Data Seeder...');
        Artisan::call('db:seed', ['--class' => 'BigDataSeeder', '--force' => true]);
        $this->info(Artisan::output());

        $this->newLine();
        $this->info('🎉 Big Data Demo is ready!');
        $this->newLine();
        $this->info('Login credentials:');
        $this->info('  Admin:   admin@edubba.test / password');
        $this->info('  Faculty: faculty@edubba.test / password');
        $this->info('  API:     student1@... / password');
        $this->newLine();
        $this->info('Run: php artisan serve');

        return self::SUCCESS;
    }
}
