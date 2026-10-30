<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $defaults = [
            [
                'key' => 'CUSTOM_HEADER_SCRIPTS',
                'value' => '',
                'type' => 'textarea',
            ],
            [
                'key' => 'CUSTOM_FOOTER_SCRIPTS',
                'value' => '',
                'type' => 'textarea',
            ],
            [
                'key' => 'CUSTOM_SCRIPTS_ENABLED',
                'value' => '1',
                'type' => 'text',
            ],
        ];

        foreach ($defaults as $item) {
            Setting::firstOrCreate(
                ['key' => $item['key']],
                ['value' => $item['value'], 'type' => $item['type']]
            );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Setting::whereIn('key', [
            'CUSTOM_HEADER_SCRIPTS',
            'CUSTOM_FOOTER_SCRIPTS',
            'CUSTOM_SCRIPTS_ENABLED',
        ])->delete();
    }
};
