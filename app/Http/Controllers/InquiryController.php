<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'company' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'inquiry_type' => 'nullable|string|max:100',
            'message' => 'required|string|max:2000',
        ]);

        $validated['status'] = 'new';
        $inquiry = Inquiry::create($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'message' => "Thank you, {$validated['name']}. We'll get back to you at {$validated['email']} within 24–48 hours.",
            ]);
        }

        return redirect()->route('contact')->with([
            'inquiry_received' => true,
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);
    }
}
