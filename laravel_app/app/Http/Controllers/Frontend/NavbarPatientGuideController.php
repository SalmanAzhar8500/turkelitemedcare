<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\PatientGuide;
use Illuminate\Support\Collection;

class NavbarPatientGuideController extends Controller
{
    public function getNavbarPatientGuides(): Collection
    {
        return PatientGuide::query()
            ->select(['id', 'name', 'slug'])
            ->where('type', 'main')
            ->orderBy('name')
            ->with([
                'children' => function ($query) {
                    $query->select(['id', 'name', 'slug', 'parentid'])
                        ->orderBy('name')
                        ->with([
                            'children' => function ($childQuery) {
                                $childQuery->select(['id', 'name', 'slug', 'parentid'])
                                    ->orderBy('name');
                            }
                        ]);
                }
            ])
            ->get();
    }
}
