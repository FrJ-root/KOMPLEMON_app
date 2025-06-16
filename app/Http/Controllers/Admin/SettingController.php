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
            'address' => 'nullable|string',
            'maintenance_mode' => 'boolean',
            'social_twitter' => 'nullable|url',
            'contact_email' => 'required|email',
            'social_facebook' => 'nullable|url',
            'social_instagram' => 'nullable|url',
            'site_description' => 'nullable|string',
            'site_name' => 'required|string|max:100',
            'phone_number' => 'nullable|string|max:20',
            'favicon' => 'nullable|image|mimes:ico,png|max:1024',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('public/settings');
            $this->settings->set('logo', str_replace('public/', 'storage/', $logoPath));
        }
        
        if ($request->hasFile('favicon')) {
            $faviconPath = $request->file('favicon')->store('public/settings');
            $this->settings->set('favicon', str_replace('public/', 'storage/', $faviconPath));
        }
        
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