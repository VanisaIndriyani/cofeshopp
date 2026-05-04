<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminSettingController extends Controller
{
    public function edit()
    {
        return view('admin.settings.edit', [
            'settings' => Setting::allAsArray(),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'store_name' => ['required', 'string', 'max:80'],
            'address' => ['nullable', 'string', 'max:200'],
            'whatsapp' => ['nullable', 'string', 'max:30'],
            'tax_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'service_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'transfer_proof_required' => ['nullable', 'boolean'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'qris_image' => ['nullable', 'image', 'max:4096'],
        ]);

        $current = Setting::allAsArray();

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('settings', 'public');
            if (! empty($current['logo_path'])) {
                Storage::disk('public')->delete($current['logo_path']);
            }
            Setting::set('logo_path', $path, 'branding');
        }

        if ($request->hasFile('qris_image')) {
            $path = $request->file('qris_image')->store('settings', 'public');
            if (! empty($current['qris_image_path'])) {
                Storage::disk('public')->delete($current['qris_image_path']);
            }
            Setting::set('qris_image_path', $path, 'payment');
        }

        Setting::set('store_name', $data['store_name'], 'general');
        Setting::set('address', $data['address'] ?? '', 'general');
        Setting::set('whatsapp', $data['whatsapp'] ?? '', 'general');
        Setting::set('tax_percent', (string) ($data['tax_percent'] ?? 0), 'pricing');
        Setting::set('service_percent', (string) ($data['service_percent'] ?? 0), 'pricing');
        Setting::set('transfer_proof_required', $request->boolean('transfer_proof_required') ? '1' : '0', 'payment');

        $request->session()->flash('toast', ['type' => 'success', 'message' => 'Pengaturan disimpan.']);
        return back();
    }
}
