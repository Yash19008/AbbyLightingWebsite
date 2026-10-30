<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class PolicyPageController extends Controller
{
    /**
     * Display the dynamic Privacy Policy page
     */
    public function privacyPolicy()
    {
        $content = Setting::getValue('PRIVACY_POLICY_CONTENT');
        if (empty($content)) {
            $defaultFile = resource_path('views/pages/default_privacy_policy.html');
            $content = file_exists($defaultFile) ? file_get_contents($defaultFile) : '';
        }

        $meta_title = Setting::getValue('PRIVACY_POLICY_TITLE', 'Privacy Policy | Abby Lighting');
        $meta_description = Setting::getValue('PRIVACY_POLICY_DESCRIPTION', 'Privacy Policy for Abby Lighting website. Learn how we collect, use, and protect your personal information.');

        return view('pages.privacy-policy', compact('content', 'meta_title', 'meta_description'));
    }

    /**
     * Display the dynamic Terms & Conditions page
     */
    public function termsAndConditions()
    {
        $content = Setting::getValue('TERMS_AND_CONDITIONS_CONTENT');
        if (empty($content)) {
            $defaultFile = resource_path('views/pages/default_terms.html');
            $content = file_exists($defaultFile) ? file_get_contents($defaultFile) : '';
        }

        $meta_title = Setting::getValue('TERMS_AND_CONDITIONS_TITLE', 'Terms & Conditions | Abby Lighting');
        $meta_description = Setting::getValue('TERMS_AND_CONDITIONS_DESCRIPTION', 'Terms and conditions for Abby Lighting products and services.');
        $banner_image = Setting::getValue('TERMS_BANNER_IMAGE', 'img/terms/new-banner.png');

        return view('pages.terms-and-conditions', compact('content', 'meta_title', 'meta_description', 'banner_image'));
    }
}

