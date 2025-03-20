<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Setting;

class SettingController extends Controller
{
    public function edit()
    {
        $settings = Setting::firstOrCreate([]);
        return view('settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'excluded_days' => 'nullable|array',
            'excluded_dates' => 'nullable|string',
            'email_days' => 'required|array|min:1',
            'greeting_text' => 'nullable|string|max:255',
            'email_message' => 'nullable|string',
        ]);

        $settings = Setting::firstOrCreate([]);
        $settings->update([
            'excluded_days' => $request->excluded_days,
            'excluded_dates' => $request->excluded_dates ? explode(',', str_replace(' ', '', $request->excluded_dates)) : [],
            'email_days' => $request->email_days,
            'greeting_text' => $request->greeting_text,
            'email_message' => $request->email_message,
        ]);

        return redirect()->back()->with('success', 'Settings updated successfully!');
    }
}