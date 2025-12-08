<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class SettingController extends Controller
{
    /**
     * Show profile page
     */
    public function profile()
    {
        $user = Auth::guard('admin')->user();
        return view('admin.settings.profile', compact('user'));
    }

    /**
     * Update profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::guard('admin')->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Handle foto upload
        if ($request->hasFile('foto')) {
            // Delete old foto
            if ($user->foto) {
                Storage::disk('public')->delete($user->foto);
            }
            $file = $request->file('foto');
            $fileName = 'admin_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $validated['foto'] = $file->storeAs('admin-photos', $fileName, 'public');
        }

        $user->update($validated);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::guard('admin')->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }

    /**
     * Show website settings
     */
    public function website()
    {
        // Get settings from file or database
        $settings = $this->getSettings();
        return view('admin.settings.website', compact('settings'));
    }

    /**
     * Update website settings
     */
    public function updateWebsite(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'site_tagline' => 'nullable|string|max:500',
            'site_description' => 'nullable|string|max:1000',
            'site_email' => 'nullable|email|max:255',
            'site_phone' => 'nullable|string|max:50',
            'site_address' => 'nullable|string|max:500',
            'site_logo' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
            'site_favicon' => 'nullable|image|mimes:ico,png|max:1024',
            'social_facebook' => 'nullable|url|max:255',
            'social_instagram' => 'nullable|url|max:255',
            'social_twitter' => 'nullable|url|max:255',
            'social_youtube' => 'nullable|url|max:255',
            'maps_embed' => 'nullable|string',
            'visi' => 'nullable|string|max:1000',
            'misi' => 'nullable|array',
            'misi.*' => 'nullable|string|max:500',
        ]);

        // Handle logo upload
        if ($request->hasFile('site_logo')) {
            $oldSettings = $this->getSettings();
            if (!empty($oldSettings['site_logo'])) {
                Storage::disk('public')->delete($oldSettings['site_logo']);
            }
            $file = $request->file('site_logo');
            $fileName = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
            $validated['site_logo'] = $file->storeAs('settings', $fileName, 'public');
        }

        // Handle favicon upload
        if ($request->hasFile('site_favicon')) {
            $oldSettings = $this->getSettings();
            if (!empty($oldSettings['site_favicon'])) {
                Storage::disk('public')->delete($oldSettings['site_favicon']);
            }
            $file = $request->file('site_favicon');
            $fileName = 'favicon_' . time() . '.' . $file->getClientOriginalExtension();
            $validated['site_favicon'] = $file->storeAs('settings', $fileName, 'public');
        }

        // Filter empty misi
        if (isset($validated['misi']) && is_array($validated['misi'])) {
            $validated['misi'] = array_values(array_filter($validated['misi'], function($item) {
                return !empty(trim($item));
            }));
        }

        $this->saveSettings($validated);

        // Clear SettingHelper cache
        \App\Helpers\SettingHelper::clearCache();

        return back()->with('success', 'Pengaturan website berhasil diperbarui.');
    }

    /**
     * Get settings from JSON file
     */
    private function getSettings()
    {
        $path = storage_path('app/settings.json');

        if (file_exists($path)) {
            return json_decode(file_get_contents($path), true) ?? $this->defaultSettings();
        }

        return $this->defaultSettings();
    }

    /**
     * Save settings to JSON file
     */
    private function saveSettings(array $newSettings)
    {
        $path = storage_path('app/settings.json');
        $currentSettings = $this->getSettings();

        // Merge settings (keep old values for fields not in new settings)
        $settings = array_merge($currentSettings, $newSettings);

        file_put_contents($path, json_encode($settings, JSON_PRETTY_PRINT));
    }

    /**
     * Default settings
     */
    private function defaultSettings()
    {
        return [
            'site_name' => 'Sekretariat Daerah Kabupaten Katingan',
            'site_tagline' => 'Melayani dengan Sepenuh Hati',
            'site_description' => 'Website resmi Sekretariat Daerah Kabupaten Katingan, Provinsi Kalimantan Tengah',
            'site_email' => 'setda@katingankab.go.id',
            'site_phone' => '(0537) 31234',
            'site_address' => 'Jl. Cilik Riwut, Kasongan, Katingan, Kalimantan Tengah',
            'site_logo' => '',
            'site_favicon' => '',
            'social_facebook' => '',
            'social_instagram' => '',
            'social_twitter' => '',
            'social_youtube' => '',
            'maps_embed' => '',
            'visi' => '"Terwujudnya Kabupaten Katingan yang Maju, Sejahtera, Berkeadilan dan Berakhlak Mulia"',
            'misi' => [
                'Mewujudkan suasana kehidupan yang rukun, aman, damai dan sejahtera',
                'Mewujudkan kehidupan masyarakat yang religius dan harmonis',
                'Mewujudkan kualitas sumber daya manusia yang handal dan berdaya saing',
                'Mewujudkan tingkat kesehatan masyarakat yang baik dan memenuhi standar',
                'Mewujudkan pelayanan publik yang memuaskan dan membahagiakan',
                'Mewujudkan infrastruktur yang baik dan mantap',
                'Mewujudkan kenyamanan dalam berusaha dan berinvestasi',
            ],
        ];
    }
}
