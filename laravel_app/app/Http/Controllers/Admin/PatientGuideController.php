<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PatientGuide;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PatientGuideController extends Controller
{
    public function index()
    {
        return view('admin.sitesetting.patientguides.index');
    }

    public function data()
    {
        $query = PatientGuide::whereNull('parentid');
        $total = $query->count();

        if (request()->has('search') && request('search')['value']) {
            $search = request('search')['value'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        $filtered = $query->count();
        $start = request('start', 0);
        $length = request('length', 10);

        $guides = $query->skip($start)->take($length)->get();

        $data = $guides->map(function ($guide, $index) use ($start) {
            $hasChildren = PatientGuide::where('parentid', $guide->id)->exists();
            $viewBtn = $hasChildren
                ? '<button class="btn btn-sm btn-info viewGuideBtn" data-slug="' . $guide->slug . '">View</button>'
                : '';

            return [
                'DT_RowIndex' => $index + 1 + $start,
                'name' => $guide->name,
                'slug' => $guide->slug,
                'action' => $viewBtn . '<button class="btn btn-sm btn-primary editGuideBtn" data-id="' . $guide->id . '">Edit</button>
                <button class="btn btn-sm btn-danger deleteGuideBtn" data-id="' . $guide->id . '">Delete</button>'
            ];
        });

        return response()->json([
            'draw' => request('draw', 1),
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $data,
        ]);
    }

    public function add()
    {
        return view('admin.sitesetting.patientguides.add');
    }

    public function getParentGuides()
    {
        $parents = PatientGuide::where('type', 'main')->orderBy('name')->get(['id', 'name']);

        return response()->json($parents);
    }

    public function getGuidesByParent($parentId)
    {
        $children = PatientGuide::where('parentid', $parentId)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($children);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'parentid' => ['nullable', 'integer', 'exists:patient_guides,id'],
            'childid' => ['nullable', 'integer', 'exists:patient_guides,id'],
        ]);

        $parentGuide = null;
        $childGuide = null;

        if (!empty($validated['parentid'])) {
            $parentGuide = PatientGuide::find($validated['parentid']);

            if (!$parentGuide || $parentGuide->type !== 'main') {
                return response()->json([
                    'message' => 'Selected parent guide must be a main guide.',
                    'errors' => ['parentid' => ['Selected parent guide must be a main guide.']]
                ], 422);
            }
        }

        if (!empty($validated['childid'])) {
            $childGuide = PatientGuide::find($validated['childid']);

            if (!$childGuide || !$parentGuide || (int) $childGuide->parentid !== (int) $parentGuide->id) {
                return response()->json([
                    'message' => 'Selected child guide does not belong to selected main guide.',
                    'errors' => ['childid' => ['Selected child guide does not belong to selected main guide.']]
                ], 422);
            }
        }

        if (!empty($validated['childid']) && !empty($validated['parentid'])) {
            $type = 'prechild';
            $finalId = $childGuide->id;
        } elseif (!empty($validated['parentid']) && empty($validated['childid'])) {
            $type = 'child';
            $finalId = $parentGuide->id;
        } else {
            $type = 'main';
            $finalId = null;
        }

        $baseSlug = Str::slug($validated['name']);
        $slug = $baseSlug;
        $counter = 1;

        while (PatientGuide::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        PatientGuide::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'parentid' => $finalId,
            'type' => $type,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Patient guide added successfully.',
        ]);
    }

    public function getGuideData($slug)
    {
        $guide = PatientGuide::with('parent')->where('slug', $slug)->firstOrFail();

        $breadcrumb = [];
        $current = $guide;

        while ($current) {
            array_unshift($breadcrumb, $current->name);
            $current = $current->parent;
        }

        return response()->json([
            'breadcrumb' => $breadcrumb,
        ]);
    }

    public function getGuideChildren($slug)
    {
        $parent = PatientGuide::where('slug', $slug)->firstOrFail();

        $query = PatientGuide::where('parentid', $parent->id);
        $total = $query->count();

        if (request()->has('search') && request('search')['value']) {
            $search = request('search')['value'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        $filtered = $query->count();
        $start = request('start', 0);
        $length = request('length', 10);

        $children = $query->skip($start)->take($length)->get();

        $data = $children->map(function ($child, $index) use ($start) {
            $hasChildren = PatientGuide::where('parentid', $child->id)->exists();
            $viewBtn = $hasChildren
                ? '<button class="btn btn-sm btn-info viewGuideBtn" data-slug="' . $child->slug . '">View</button>'
                : '';

            return [
                'DT_RowIndex' => $index + 1 + $start,
                'name' => $child->name,
                'slug' => $child->slug,
                'action' => $viewBtn . '<button class="btn btn-sm btn-primary editGuideBtn" data-id="' . $child->id . '">Edit</button>
                <button class="btn btn-sm btn-danger deleteGuideBtn" data-id="' . $child->id . '">Delete</button>'
            ];
        });

        return response()->json([
            'draw' => request('draw', 1),
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $data,
        ]);
    }

    public function edit($id)
    {
        $guide = PatientGuide::with('parent')->findOrFail($id);

        return response()->json([
            'id' => $guide->id,
            'name' => $guide->name,
            'parent_name' => $guide->parent->name ?? null,
            'type' => $guide->type,
        ]);
    }

    public function update(Request $request, $id)
    {
        $guide = PatientGuide::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        if ($guide->name !== $validated['name']) {
            $baseSlug = Str::slug($validated['name']);
            $slug = $baseSlug;
            $counter = 1;

            while (PatientGuide::where('slug', $slug)->where('id', '!=', $guide->id)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }

            $guide->slug = $slug;
        }

        $guide->name = $validated['name'];
        $guide->save();

        return response()->json([
            'success' => true,
            'message' => 'Patient guide updated successfully',
        ]);
    }

    public function delete($id)
    {
        $guide = PatientGuide::findOrFail($id);
        $this->deleteRecursive($guide);

        return response()->json([
            'success' => true,
            'message' => 'Patient guide deleted successfully',
        ]);
    }

    private function deleteRecursive(PatientGuide $guide): void
    {
        foreach ($guide->children as $child) {
            $this->deleteRecursive($child);
        }

        $guide->delete();
    }
}
