<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class PolicySettingAdminController extends Controller
{
    /**
     * Display the CMS settings page for Privacy Policy and Terms of Use
     */
    public function index(Request $request)
    {
        $activeTab = $request->get('tab', 'privacy');

        $privacyContent = Setting::getValue('PRIVACY_POLICY_CONTENT');
        if (empty($privacyContent)) {
            $defaultFile = resource_path('views/pages/default_privacy_policy.html');
            $privacyContent = file_exists($defaultFile) ? file_get_contents($defaultFile) : '';
        }

        $privacyTitle = Setting::getValue('PRIVACY_POLICY_TITLE', 'Privacy Policy | Abby Lighting');
        $privacyDescription = Setting::getValue('PRIVACY_POLICY_DESCRIPTION', 'Privacy Policy for Abby Lighting website. Learn how we collect, use, and protect your personal information.');

        $termsContent = Setting::getValue('TERMS_AND_CONDITIONS_CONTENT');
        if (empty($termsContent)) {
            $defaultFile = resource_path('views/pages/default_terms.html');
            $termsContent = file_exists($defaultFile) ? file_get_contents($defaultFile) : '';
        }

        $termsTitle = Setting::getValue('TERMS_AND_CONDITIONS_TITLE', 'Terms & Conditions | Abby Lighting');
        $termsDescription = Setting::getValue('TERMS_AND_CONDITIONS_DESCRIPTION', 'Terms and conditions for Abby Lighting products and services.');
        $termsBanner = Setting::getValue('TERMS_BANNER_IMAGE', 'img/terms/new-banner.png');

        $main_module = 'Settings';
        $sub_module = 'Policy Pages';

        return view('admin.policies.index', compact(
            'activeTab',
            'privacyContent',
            'privacyTitle',
            'privacyDescription',
            'termsContent',
            'termsTitle',
            'termsDescription',
            'termsBanner',
            'main_module',
            'sub_module'
        ));
    }

    /**
     * Update Privacy Policy or Terms of Use content
     */
    public function update(Request $request)
    {
        $tab = $request->input('tab', 'privacy');

        if ($tab === 'privacy') {
            $request->validate([
                'privacy_title' => 'required|string|max:255',
                'privacy_description' => 'nullable|string|max:1000',
                'privacy_content' => 'required|string',
            ]);

            Setting::updateOrCreate(
                ['key' => 'PRIVACY_POLICY_TITLE'],
                ['value' => $request->input('privacy_title'), 'type' => 'text']
            );

            Setting::updateOrCreate(
                ['key' => 'PRIVACY_POLICY_DESCRIPTION'],
                ['value' => $request->input('privacy_description'), 'type' => 'textarea']
            );

            Setting::updateOrCreate(
                ['key' => 'PRIVACY_POLICY_CONTENT'],
                ['value' => $request->input('privacy_content'), 'type' => 'textarea']
            );

            return redirect()->route('admin.policies.index', ['tab' => 'privacy'])
                ->with('success', 'Privacy Policy content updated successfully.');
        }

        if ($tab === 'terms') {
            $request->validate([
                'terms_title' => 'required|string|max:255',
                'terms_description' => 'nullable|string|max:1000',
                'terms_content' => 'required|string',
                'terms_banner' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:5120',
            ]);

            Setting::updateOrCreate(
                ['key' => 'TERMS_AND_CONDITIONS_TITLE'],
                ['value' => $request->input('terms_title'), 'type' => 'text']
            );

            Setting::updateOrCreate(
                ['key' => 'TERMS_AND_CONDITIONS_DESCRIPTION'],
                ['value' => $request->input('terms_description'), 'type' => 'textarea']
            );

            Setting::updateOrCreate(
                ['key' => 'TERMS_AND_CONDITIONS_CONTENT'],
                ['value' => $request->input('terms_content'), 'type' => 'textarea']
            );

            if ($request->hasFile('terms_banner')) {
                $file = $request->file('terms_banner');
                $filename = time() . '_terms_banner.' . ($file->getClientOriginalExtension() ?: 'png');
                $uploadDir = public_path('uploads/settings');
                if (!File::isDirectory($uploadDir)) {
                    File::makeDirectory($uploadDir, 0777, true, true);
                }
                $file->move($uploadDir, $filename);
                $bannerPath = 'uploads/settings/' . $filename;

                Setting::updateOrCreate(
                    ['key' => 'TERMS_BANNER_IMAGE'],
                    ['value' => $bannerPath, 'type' => 'image']
                );
            }

            return redirect()->route('admin.policies.index', ['tab' => 'terms'])
                ->with('success', 'Terms & Conditions content updated successfully.');
        }

        return redirect()->route('admin.policies.index');
    }

    /**
     * Upload an inline image from TinyMCE rich text editor
     */
    public function uploadImage(Request $request)
    {
        $file = $request->file('upload') ?: $request->file('file');

        if ($file) {
            $extension = $file->getClientOriginalExtension() ?: 'jpg';
            $fileName = time() . '_' . uniqid() . '.' . $extension;

            $uploadDir = public_path('uploads/policies/content');
            if (!File::isDirectory($uploadDir)) {
                File::makeDirectory($uploadDir, 0777, true, true);
            }

            $file->move($uploadDir, $fileName);
            $relativePath = '/uploads/policies/content/' . $fileName;

            return response()->json([
                'url' => $relativePath,
                'location' => $relativePath,
            ]);
        }

        return response()->json(['error' => 'No image uploaded'], 400);
    }
}

