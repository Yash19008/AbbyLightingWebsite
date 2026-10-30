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
        $defaultPpFile = resource_path('views/pages/default_privacy_policy.html');
        $defaultPpContent = file_exists($defaultPpFile) ? file_get_contents($defaultPpFile) : '';

        $defaultTcFile = resource_path('views/pages/default_terms.html');
        $defaultTcContent = file_exists($defaultTcFile) ? file_get_contents($defaultTcFile) : '';

        $defaults = [
            [
                'key' => 'PRIVACY_POLICY_CONTENT',
                'value' => $defaultPpContent,
                'type' => 'textarea',
            ],
            [
                'key' => 'PRIVACY_POLICY_TITLE',
                'value' => 'Privacy Policy | Abby Lighting',
                'type' => 'text',
            ],
            [
                'key' => 'PRIVACY_POLICY_DESCRIPTION',
                'value' => 'Privacy Policy for Abby Lighting website. Learn how we collect, use, and protect your personal information.',
                'type' => 'textarea',
            ],
            [
                'key' => 'TERMS_AND_CONDITIONS_CONTENT',
                'value' => $defaultTcContent,
                'type' => 'textarea',
            ],
            [
                'key' => 'TERMS_AND_CONDITIONS_TITLE',
                'value' => 'Terms & Conditions | Abby Lighting',
                'type' => 'text',
            ],
            [
                'key' => 'TERMS_AND_CONDITIONS_DESCRIPTION',
                'value' => 'Terms and conditions for Abby Lighting products and services.',
                'type' => 'textarea',
            ],
            [
                'key' => 'TERMS_BANNER_IMAGE',
                'value' => 'img/terms/new-banner.png',
                'type' => 'image',
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
            'PRIVACY_POLICY_CONTENT',
            'PRIVACY_POLICY_TITLE',
            'PRIVACY_POLICY_DESCRIPTION',
            'TERMS_AND_CONDITIONS_CONTENT',
            'TERMS_AND_CONDITIONS_TITLE',
            'TERMS_AND_CONDITIONS_DESCRIPTION',
            'TERMS_BANNER_IMAGE',
        ])->delete();
    }
};
