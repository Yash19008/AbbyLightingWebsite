<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class CustomScriptApiController extends Controller
{
    /**
     * Get custom header and footer scripts for frontend consumption
     */
    public function index(): JsonResponse
    {
        $data = Cache::remember('site_custom_scripts_v1', 3600, function () {
            $enabled = Setting::getValue('CUSTOM_SCRIPTS_ENABLED', '1') === '1';
            $headerScripts = $enabled ? (Setting::getValue('CUSTOM_HEADER_SCRIPTS') ?? '') : '';
            $footerScripts = $enabled ? (Setting::getValue('CUSTOM_FOOTER_SCRIPTS') ?? '') : '';

            return [
                'enabled' => $enabled,
                'header_scripts' => $headerScripts,
                'footer_scripts' => $footerScripts,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
}

