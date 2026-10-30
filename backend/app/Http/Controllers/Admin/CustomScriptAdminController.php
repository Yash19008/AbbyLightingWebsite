<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CustomScriptAdminController extends Controller
{
    /**
     * Display the CMS page for Header and Footer Custom Scripts
     */
    public function index()
    {
        $headerScripts = Setting::getValue('CUSTOM_HEADER_SCRIPTS', '');
        $footerScripts = Setting::getValue('CUSTOM_FOOTER_SCRIPTS', '');
        $enabled = Setting::getValue('CUSTOM_SCRIPTS_ENABLED', '1') === '1';

        $main_module = 'Settings';
        $sub_module = 'Custom Scripts';

        return view('admin.custom_scripts.index', compact(
            'headerScripts',
            'footerScripts',
            'enabled',
            'main_module',
            'sub_module'
        ));
    }

    /**
     * Update custom scripts in the database
     */
    public function update(Request $request)
    {
        $headerScripts = $request->input('header_scripts', '');
        $footerScripts = $request->input('footer_scripts', '');
        $enabled = $request->has('enabled') ? '1' : '0';

        Setting::updateOrCreate(
            ['key' => 'CUSTOM_HEADER_SCRIPTS'],
            ['value' => $headerScripts, 'type' => 'textarea']
        );

        Setting::updateOrCreate(
            ['key' => 'CUSTOM_FOOTER_SCRIPTS'],
            ['value' => $footerScripts, 'type' => 'textarea']
        );

        Setting::updateOrCreate(
            ['key' => 'CUSTOM_SCRIPTS_ENABLED'],
            ['value' => $enabled, 'type' => 'text']
        );

        // Invalidate frontend cache immediately
        Cache::forget('site_custom_scripts_v1');

        return redirect()->route('admin.custom_scripts.index')
            ->with('success', 'Custom header and footer scripts updated successfully.');
    }
}

