<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::all();
        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.pages.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'slug' => 'required|unique:pages,slug',
            'title' => 'required',
            'content' => 'nullable',
            'image' => 'nullable|image',
        ]);
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('pages', 'public');
        }
        Page::create($data);
        return redirect()->route('admin.pages.index')->with('success', 'Page created!');
    }

    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $data = $request->validate([
            'slug' => 'required|unique:pages,slug,' . $page->id,
            'title' => 'required',
            'content' => 'nullable',
            'image' => 'nullable|image',
        ]);
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('pages', 'public');
        }
        $page->update($data);
        return redirect()->route('admin.pages.index')->with('success', 'Page updated!');
    }

    public function destroy(Page $page)
    {
        $page->delete();
        return redirect()->route('admin.pages.index')->with('success', 'Page deleted!');
    }
}
