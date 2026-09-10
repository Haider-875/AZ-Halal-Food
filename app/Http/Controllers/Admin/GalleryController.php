<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryItem;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index()
    {
        $items = GalleryItem::orderBy('display_order')->latest()->get();

        return view('admin.gallery.index', compact('items'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'src' => 'required|string|max:500',
            'caption' => 'required|string|max:255',
            'tag' => 'required|string|max:50',
            'size' => 'required|in:normal,tall,wide',
            'display_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['alt'] = $validated['caption'];
        $validated['is_active'] = $request->boolean('is_active', true);

        GalleryItem::create($validated);

        return redirect()->route('admin.gallery.index')->with('success', 'Gallery item added successfully.');
    }

    public function update(Request $request, GalleryItem $gallery)
    {
        $validated = $request->validate([
            'src' => 'required|string|max:500',
            'caption' => 'required|string|max:255',
            'tag' => 'required|string|max:50',
            'size' => 'required|in:normal,tall,wide',
            'display_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['alt'] = $validated['caption'];
        $validated['is_active'] = $request->boolean('is_active');

        $gallery->update($validated);

        return redirect()->route('admin.gallery.index')->with('success', 'Gallery item updated successfully.');
    }

    public function destroy(GalleryItem $gallery)
    {
        $gallery->delete();

        return redirect()->route('admin.gallery.index')->with('success', 'Gallery item removed.');
    }
}
