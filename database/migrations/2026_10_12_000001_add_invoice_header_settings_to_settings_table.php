<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $settings = [
            [
                'key' => 'invoice_header_contacts_left',
                'value' => "Faisal Mehmood\n0325 4682466\nFurqaan Shakeel\n03044576200",
                'type' => 'text',
                'group' => 'company',
                'label' => 'Invoice Header Contacts (Left)',
                'description' => 'Contact persons and phone numbers on the left of the invoice header',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'invoice_header_contacts_right',
                'value' => "Anjum Shakeel\n03008033184\nTayyab Shakeel\n03054453139\nPTCL #: 042 37322272\n042 37232370",
                'type' => 'text',
                'group' => 'company',
                'label' => 'Invoice Header Contacts (Right)',
                'description' => 'Contact persons and phone numbers on the right of the invoice header',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'invoice_header_tagline',
                'value' => "Power Bank, Battery, Data Cable, Charger, Head Phone\nHandsfree, Bluetooth & Multimedia",
                'type' => 'text',
                'group' => 'company',
                'label' => 'Invoice Header Deals In / Tagline',
                'description' => 'Product lines or tagline displayed below company name in invoice header',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($settings as $setting) {
            $exists = DB::table('settings')->where('key', $setting['key'])->exists();
            if (!$exists) {
                DB::table('settings')->insert($setting);
            }
        }
    }

    public function down(): void
    {
        DB::table('settings')->whereIn('key', [
            'invoice_header_contacts_left',
            'invoice_header_contacts_right',
            'invoice_header_tagline',
        ])->delete();
    }
};
