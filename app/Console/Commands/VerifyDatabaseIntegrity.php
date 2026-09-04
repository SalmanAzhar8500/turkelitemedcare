<?php

namespace App\Console\Commands;

use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\Guide;
use App\Models\PatientService;
use App\Models\PatientStory;
use App\Models\Procedure;
use App\Models\Specialty;
use Illuminate\Console\Command;

class VerifyDatabaseIntegrity extends Command
{
    protected $signature = 'site:factory:verify-db';
    protected $description = 'Fail when the live database does not match the FINAL presentation content contract.';

    public function handle(): int
    {
        $errors = [];
        $checks = [
            ['Specialties', Specialty::query()->count(), 10],
            ['Procedures', Procedure::query()->count(), 100],
            ['Clinics', Clinic::query()->count(), 1],
            ['Doctors', Doctor::query()->count(), 0],
            ['Guides', Guide::query()->count(), 8],
            ['Patient services', PatientService::query()->count(), 9],
            ['Patient stories', PatientStory::query()->count(), 0],
        ];

        foreach ($checks as [$name, $actual, $expected]) {
            if ($actual !== $expected) {
                $errors[] = "{$name}: expected {$expected}, found {$actual}.";
            }
        }

        $priority = config('seo.priority_procedures', []);
        $priorityCount = Procedure::query()->whereIn('slug', $priority)->count();
        if ($priorityCount !== 20) {
            $errors[] = "Priority procedures: expected 20, found {$priorityCount}.";
        }

        $linkedCount = Procedure::query()->whereNotNull('specialty_id')->count();
        if ($linkedCount !== 100) {
            $errors[] = "Procedure links: expected 100 linked procedures, found {$linkedCount}.";
        }

        foreach (Specialty::query()->orderBy('sort_order')->get() as $specialty) {
            $count = Procedure::query()->where('specialty_id', $specialty->id)->count();
            if ($count !== 10) {
                $errors[] = "{$specialty->slug}: expected 10 procedures, found {$count}.";
            }
        }

        if ($errors !== []) {
            $this->error('FINAL database integrity FAILED. Do not present this build.');
            foreach ($errors as $error) $this->line(' - '.$error);
            $this->line('Repair with: php artisan site:factory:rebuild --content=content/en --yes');
            return self::FAILURE;
        }

        $this->info('FINAL database integrity PASS: 10 specialties / 100 procedures / 20 priority procedures / 1 configured clinic / 0 synthetic doctors / 0 unverified patient stories.');
        return self::SUCCESS;
    }
}
