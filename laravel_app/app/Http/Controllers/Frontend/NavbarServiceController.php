<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Support\Collection;

class NavbarServiceController extends Controller
{
    public function getNavbarServices(): Collection
    {
        return Service::query()
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
