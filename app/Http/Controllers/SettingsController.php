<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            // Business
            'business_name'        => 'nullable|string|max:191',
            'business_email'       => 'nullable|email|max:191',
            'business_phone'       => 'nullable|string|max:50',
            'business_address'     => 'nullable|string',
            'business_tax_number'  => 'nullable|string|max:50',
            'logo'                 => 'nullable|image|max:2048',

            // Currency
            'currency_code'        => 'nullable|string|max:10',
            'currency_symbol'      => 'nullable|string|max:10',
            'currency_position'    => 'nullable|in:before,after',
            'decimal_places'       => 'nullable|integer|min:0|max:4',

            // Tax
            'default_tax_rate'     => 'nullable|numeric|min:0|max:100',
            'tax_inclusive'        => 'nullable|boolean',

            // Invoice
            'invoice_prefix'       => 'nullable|string|max:20',
            'invoice_footer'       => 'nullable|string',
            'invoice_terms'        => 'nullable|string',

            // Number format
            'thousand_separator'   => 'nullable|string|max:5',

            // Date/Time
            'date_format'          => 'nullable|string|max:20',
            'timezone'             => 'nullable|string|max:50',

            // Notifications
            'notify_low_stock'     => 'nullable|boolean',
            'notify_out_of_stock'  => 'nullable|boolean',
            'notify_payment_due'   => 'nullable|boolean',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $oldLogo = Setting::get('logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }
            $data['logo'] = $request->file('logo')->store('settings', 'public');
        }

        // Handle checkbox → boolean conversion
        foreach (['tax_inclusive', 'notify_low_stock', 'notify_out_of_stock', 'notify_payment_due'] as $bool) {
            $data[$bool] = $request->has($bool) ? '1' : '0';
        }

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        \Cache::forget('app_settings');

        return back()->with('success', 'Settings saved successfully.');
    }
}