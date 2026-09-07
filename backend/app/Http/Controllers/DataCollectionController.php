<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\Contact;
use App\Mail\Product;
use App\Models\Inquiry;
use App\Rules\GoogleCaptcha;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;

class DataCollectionController extends Controller
{
    public function contact(Request $request)
    {
        try {
            $request->validate([
                'g-recaptcha-response' => ['required', new GoogleCaptcha]
            ]);

            Inquiry::create(Arr::except(request()->all(), 'g-recaptcha-response'));
            Mail::to(config('mail.contact_email'))->send(new Contact(request()->all()));
        } catch (\Exception $e) {
            \Log::error('Error in sending mail - contact');
            \Log::error($e);
        }
        return redirect()->route('page.contact')->with('success', 'Thank you for contacting us.');
    }

    public function productInquiry(Request $request)
    {
        try {
            $request->validate([
                'g-recaptcha-response' => ['required', new GoogleCaptcha]
            ]);

            Inquiry::create(Arr::except(request()->all(), 'g-recaptcha-response'));
            Mail::to(config('mail.contact_email'))->send(new Product(request()->all()));
        } catch (\Exception $e) {
            \Log::error('Error in sending mail - Inquiry');
            \Log::error($e);
        }
        return response()->json(['success' => true]);
    }
}
