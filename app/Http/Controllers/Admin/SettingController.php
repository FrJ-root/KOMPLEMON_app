<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Settings;

class SettingController extends Controller
{
    protected $settings;
    
    public function __construct(Settings $settings)
    {
        $this->middleware('role:administrateur');
        $this->settings = $settings;
    }
    
    public function index()
    {
        $settings = $this->settings->all();
        return view('admin.settings.index', compact('settings'));
    }
    
    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:100',
            'site_description' => 'nullable|string',
            'contact_email' => 'required|email',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'social_facebook' => 'nullable|url',
            'social_twitter' => 'nullable|url',
            'social_instagram' => 'nullable|url',
            'maintenance_mode' => 'boolean',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'favicon' => 'nullable|image|mimes:ico,png|max:1024',
        ]);
        
        // Handle file uploads
        if ($request->hasFile('logo')) {
            // Store logo and update setting
            $logoPath = $request->file('logo')->store('public/settings');
            $this->settings->set('logo', str_replace('public/', 'storage/', $logoPath));
        }
        
        if ($request->hasFile('favicon')) {
            // Store favicon and update setting
            $faviconPath = $request->file('favicon')->store('public/settings');
            $this->settings->set('favicon', str_replace('public/', 'storage/', $faviconPath));
        }
        
        // Update other settings
        foreach ($validated as $key => $value) {
            if (!in_array($key, ['logo', 'favicon']) && $request->has($key)) {
                $this->settings->set($key, $value);
            }
        }
        
        $this->settings->save();
        
        return redirect()->route('admin.settings.index')
            ->with('success', 'Paramètres mis à jour avec succès.');
    }
}
