<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inquiry;
use App\Rules\GoogleCaptcha;
use Illuminate\Support\Facades\Validator;

class InquiryApiController extends Controller
{
    public function store(Request $request)
    {
        if (!$request->has('g-recaptcha-response') && $request->has('captcha')) {
            $request->merge(['g-recaptcha-response' => $request->input('captcha')]);
        }

        $validator = Validator::make($request->all(), [
            'type'      => 'required|in:product,catalogue,calculator,general',
            'reference' => 'nullable|string|max:255',
            'name'      => 'required|string|max:255',
            'phone'     => 'required|string|max:50',
            'email'     => 'required|email|max:255',
            'city'      => 'required|string|max:255',
            'company'   => 'nullable|string|max:255',
            'role'      => 'required|string|max:255',
            'message'   => 'nullable|string|max:1000',
            'g-recaptcha-response' => ['required', new GoogleCaptcha],
        ], [
            'g-recaptcha-response.required' => 'Please complete Google Captcha verification.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $inquiry = Inquiry::create([
                'type' => $request->type,
                'reference' => $request->reference,
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'city' => $request->city,
                'company' => $request->company,
                'role' => $request->role,
                'message' => $request->message,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Inquiry submitted successfully.',
                'id'      => $inquiry->id
            ], 201);
        } catch (\Exception $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit inquiry.'
            ], 500);
        }
    }
}
