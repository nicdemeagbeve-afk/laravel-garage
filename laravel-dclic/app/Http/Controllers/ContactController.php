<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    //
    public function show()
    {
        return view('contact');
    }
    public function submit(Request $request)
    {
        // Validate and process the contact form submission
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
        ]);

        // Here you can handle the validated data, e.g., send an email or save to database

        return redirect()->back()->with('success', 'Thank you for contacting us!');
    }
    
}
