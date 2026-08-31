<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SliderController extends Controller
{
    /**
     * Display a listing of the sliders.
     */
    public function index(): View
    {
        $sliders = Slider::orderBy('display_order', 'asc')->latest()->get();

        return view('admin.sliders.index', compact('sliders'));
    }

    /**
     * Show the form for creating a new slider.
     */
    public function create(): View
    {
        return view('admin.sliders.create');
    }

    /**
     * Store a newly created slider in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'badge' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,svg|max:5120',
            'image_url' => 'nullable|string|max:500',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|string|max:255',
            'secondary_button_text' => 'nullable|string|max:100',
            'secondary_button_link' => 'nullable|string|max:255',
            'display_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        // Require either an uploaded file or an image URL
        if (! $request->hasFile('image_file') && empty($request->image_url)) {
            return back()->withInput()->withErrors(['image_file' => 'Please either upload an image file or provide an image URL.']);
        }

        $imagePath = $request->image_url;

        if ($request->hasFile('image_file')) {
            $uploadDirectory = public_path('uploads/sliders');
            if (! File::isDirectory($uploadDirectory)) {
                File::makeDirectory($uploadDirectory, 0755, true, true);
            }

            $file = $request->file('image_file');
            $filename = 'slider_'.time().'_'.Str::random(10).'.'.$file->getClientOriginalExtension();
            $file->move($uploadDirectory, $filename);
            $imagePath = 'uploads/sliders/'.$filename;
        }

        Slider::create([
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'] ?? null,
            'badge' => $validated['badge'] ?? null,
            'description' => $validated['description'] ?? null,
            'image' => $imagePath,
            'button_text' => $validated['button_text'] ?? null,
            'button_link' => $validated['button_link'] ?? null,
            'secondary_button_text' => $validated['secondary_button_text'] ?? null,
            'secondary_button_link' => $validated['secondary_button_link'] ?? null,
            'display_order' => (int) ($validated['display_order'] ?? 0),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.sliders.index')->with('success', 'Slider created successfully.');
    }

    /**
     * Show the form for editing the specified slider.
     */
    public function edit(Slider $slider): View
    {
        return view('admin.sliders.edit', compact('slider'));
    }

    /**
     * Update the specified slider in storage.
     */
    public function update(Request $request, Slider $slider): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'badge' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,svg|max:5120',
            'image_url' => 'nullable|string|max:500',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|string|max:255',
            'secondary_button_text' => 'nullable|string|max:100',
            'secondary_button_link' => 'nullable|string|max:255',
            'display_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $imagePath = $slider->image;

        if ($request->hasFile('image_file')) {
            $uploadDirectory = public_path('uploads/sliders');
            if (! File::isDirectory($uploadDirectory)) {
                File::makeDirectory($uploadDirectory, 0755, true, true);
            }

            // Remove old uploaded file if it exists locally
            if (! empty($slider->image) && ! str_starts_with($slider->image, 'http') && File::exists(public_path($slider->image))) {
                File::delete(public_path($slider->image));
            }

            $file = $request->file('image_file');
            $filename = 'slider_'.time().'_'.Str::random(10).'.'.$file->getClientOriginalExtension();
            $file->move($uploadDirectory, $filename);
            $imagePath = 'uploads/sliders/'.$filename;
        } elseif ($request->filled('image_url')) {
            $imagePath = $request->image_url;
        }

        $slider->update([
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'] ?? null,
            'badge' => $validated['badge'] ?? null,
            'description' => $validated['description'] ?? null,
            'image' => $imagePath,
            'button_text' => $validated['button_text'] ?? null,
            'button_link' => $validated['button_link'] ?? null,
            'secondary_button_text' => $validated['secondary_button_text'] ?? null,
            'secondary_button_link' => $validated['secondary_button_link'] ?? null,
            'display_order' => (int) ($validated['display_order'] ?? 0),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.sliders.index')->with('success', 'Slider updated successfully.');
    }

    /**
     * Remove the specified slider from storage.
     */
    public function destroy(Slider $slider): RedirectResponse
    {
        if (! empty($slider->image) && ! str_starts_with($slider->image, 'http') && File::exists(public_path($slider->image))) {
            File::delete(public_path($slider->image));
        }

        $slider->delete();

        return redirect()->route('admin.sliders.index')->with('success', 'Slider deleted successfully.');
    }
}
