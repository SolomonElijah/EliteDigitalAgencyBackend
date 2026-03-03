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
            'firstName' => 'required|string|max:100',
            'lastName'  => 'required|string|max:100',

            'email' => 'required|email|max:150',
            'phone' => 'nullable|string|max:30',

            'company' => 'nullable|string|max:200',
            'service' => 'required|string|max:200',
            'budget'  => 'nullable|string|max:100',

            'message' => 'required|string|min:10|max:5000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        // Build a nice subject for email + DB
        $subject = 'New Contact Message';
        if (!empty($data['service'])) {
            $subject .= ' - ' . $data['service'];
        }
        if (!empty($data['company'])) {
            $subject .= ' (' . $data['company'] . ')';
        }

        // Store (adapt to your table columns)
        $contact = Contact::create([
            'name'    => trim($data['firstName'] . ' ' . $data['lastName']),
            'email'   => $data['email'],
            'phone'   => $data['phone'] ?? null,
            'subject' => $subject,
            'message' => $data['message'],

            // Optional extra fields (only if your contacts table has these columns)
            // 'company' => $data['company'] ?? null,
            // 'service' => $data['service'],
            // 'budget'  => $data['budget'] ?? null,
        ]);

        // Notify admin (do not fail request if email fails)
        try {
            $to = config('mail.admin_email') ?: env('ADMIN_EMAIL');
            if ($to) {
                Mail::to($to)->send(new ContactNotification($contact));
            }
        } catch (\Exception $e) {
            logger()->error('Contact mail failed: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => "Your message has been received! We'll get back to you shortly.",
        ], 201);
    }
}