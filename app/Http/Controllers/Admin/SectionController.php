<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSection;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function index()
    {
        $sections = SiteSection::all()->groupBy('section_group');
        return view('admin.sections.index', compact('sections'));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token', '_method']);

        foreach ($data as $key => $content) {
            SiteSection::where('key', $key)->update(['content' => $content]);
        }

        return redirect()->route('admin.sections.index')->with('success', 'Website dynamic content updated successfully.');
    }
}
