<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\Guide;
use App\Models\Procedure;
use App\Models\Specialty;
use App\Models\PatientStory;
use App\Models\SitePage;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TreatmentContentController extends Controller
{
    public function procedure(Specialty $specialty, Procedure $procedure): View
    {
        abort_unless($procedure->specialty_id === $specialty->id && $procedure->is_active, 404);
        return view('pages.catalog.detail', [
            'item' => $procedure,
            'specialty' => $specialty,
            'kind' => 'Procedure',
            'relatedProcedures' => Procedure::published()
                ->where('specialty_id', $specialty->id)
                ->whereKeyNot($procedure->id)
                ->limit(3)
                ->get(),
            'clinics' => Clinic::published()
                ->whereIn('slug', procedure_clinic_slugs($procedure))
                ->get()
                ->sortBy(fn (Clinic $clinic): int => ($pos = array_search($clinic->slug, procedure_clinic_slugs($procedure), true)) === false ? 999 : $pos)
                ->take(3)
                ->values(),
            'decisionGuides' => Guide::published()->limit(3)->get(),
        ]);
    }

    public function clinics(Request $request): View
    {
        $allClinics = Clinic::published()->get();
        $cities = $allClinics->pluck('location')->filter()->unique()->sort()->values();
        $selectedCity = $request->string('city')->trim()->toString();
        $clinics = $selectedCity === ''
            ? $allClinics
            : $allClinics->filter(fn (Clinic $clinic): bool => $clinic->location === $selectedCity)->values();

        return view('pages.clinics.index', compact('allClinics', 'cities', 'selectedCity', 'clinics'))->with('page', site_page('clinics.index'));
    }

    public function clinic(Clinic $clinic): View
    {
        abort_unless($clinic->is_active, 404);

        $featuredDoctors = Doctor::published()
            ->with('treatments')
            ->where('clinic_id', $clinic->id)
            ->limit(3)
            ->get();

        $clinicSpecialties = $featuredDoctors
            ->flatMap(fn (Doctor $doctor) => $doctor->treatments)
            ->unique('id')
            ->values();

        if ($clinicSpecialties->isEmpty()) {
            $clinicSpecialties = $clinic->slug === 'clinic-expert'
                ? Specialty::published()->whereIn('slug', ['dental', 'hair-restoration', 'cosmetic-surgery', 'bariatric-surgery'])
                    ->orderBy('sort_order')->get()
                : Specialty::published()->limit(4)->get();
        }

        return view('pages.clinics.show', [
            'page' => site_page('clinics.show'),
            'clinic' => $clinic,
            'featuredDoctors' => $featuredDoctors,
            'clinicSpecialties' => $clinicSpecialties,
        ]);
    }

    public function doctor(Doctor $doctor): View
    {
        abort_unless($doctor->is_active, 404);

        return view('doctors.show', [
            'doctor' => $doctor->load(['clinic', 'treatments', 'procedures.specialty']),
        ]);
    }

    public function doctors(): View
    {
        return view('pages.doctors.index', [
            'doctors' => Doctor::published()->with(['clinic', 'treatments'])->get(),
        ]);
    }

    public function guide(Guide $guide): View|\Illuminate\Http\RedirectResponse
    {
        abort_unless($guide->is_active, 404);

        return view('pages.guides.show', compact('guide'));
    }

    public function stories(): View
    {
        return view('pages.stories.index', ['stories' => PatientStory::published()->get(), 'page' => SitePage::resolve('stories.index')]);
    }

    public function story(PatientStory $story): View
    {
        abort_unless($story->is_active, 404);

        return view('pages.stories.show', compact('story'));
    }
}
