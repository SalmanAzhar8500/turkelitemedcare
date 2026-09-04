<?php

namespace Database\Seeders;

use App\Models\SitePage;
use App\Models\PatientService;
use App\Models\Specialty;
use Illuminate\Database\Seeder;

class WebsiteContentSeeder extends Seeder
{
    public function run(): void
    {
        SitePage::seedDefaults();
        Specialty::seedDefaults();
        PatientService::seedDefaults();
    }
}
