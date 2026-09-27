<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            ['key' => 'business_name',       'value' => 'My Inventory Store',       'group' => 'business'],
            ['key' => 'business_email',      'value' => 'info@example.com',          'group' => 'business'],
            ['key' => 'business_phone',      'value' => '+92 300 0000000',          'group' => 'business'],
            ['key' => 'business_address',    'value' => 'Karachi, Pakistan',        'group' => 'business'],
            ['key' => 'business_tax_number', 'value' => '',                         'group' => 'business'],

            ['key' => 'currency_code',       'value' => 'PKR',                      'group' => 'currency'],
            ['key' => 'currency_symbol',     'value' => 'Rs',                       'group' => 'currency'],
            ['key' => 'currency_position',   'value' => 'before',                   'group' => 'currency'],
            ['key' => 'decimal_places',      'value' => '2',                        'group' => 'currency'],
            ['key' => 'thousand_separator',  'value' => ',',                        'group' => 'currency'],

            ['key' => 'default_tax_rate',    'value' => '0',                        'group' => 'tax'],
            ['key' => 'tax_inclusive',       'value' => '0',                        'group' => 'tax'],

            ['key' => 'invoice_prefix',      'value' => 'INV-',                     'group' => 'invoice'],
            ['key' => 'invoice_footer',      'value' => 'Thank you for your business!', 'group' => 'invoice'],
            ['key' => 'invoice_terms',       'value' => '',                         'group' => 'invoice'],

            ['key' => 'date_format',         'value' => 'd/m/Y',                    'group' => 'general'],
            ['key' => 'timezone',            'value' => 'Asia/Karachi',             'group' => 'general'],

            ['key' => 'notify_low_stock',    'value' => '1',                        'group' => 'notifications'],
            ['key' => 'notify_out_of_stock', 'value' => '1',                        'group' => 'notifications'],
            ['key' => 'notify_payment_due',  'value' => '1',                        'group' => 'notifications'],
        ];

        foreach ($defaults as $row) {
            Setting::updateOrCreate(['key' => $row['key']], $row);
        }
    }
}