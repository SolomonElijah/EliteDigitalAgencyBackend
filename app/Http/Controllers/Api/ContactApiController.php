<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Mail\ContactNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

/**
 * Public Contact Form API
 */
class ContactApiController extends Controller
{
    /**
     * POST /api/contact
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|max:150',
            'phone'   => 'nullable|string|max:20',
            'subject' => 'nullable|string|max:200',
            'message' => 'required|string|min:10|max:5000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $contact = Contact::create($validator->validated());

        // Notify admin
        try {
            Mail::to(config('mail.admin_email', env('ADMIN_EMAIL')))
                ->send(new ContactNotification($contact));
        } catch (\Exception $e) {
            // log but don't fail the user request
            logger()->error('Contact mail failed: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Your message has been received! We\'ll get back to you shortly.',
        ], 201);
    }
}
