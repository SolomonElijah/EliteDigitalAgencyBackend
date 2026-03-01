<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Mail\ContactReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::latest()->paginate(20);
        return view('admin.contacts.index', compact('contacts'));
    }

    public function show(Contact $contact)
    {
        if ($contact->status === 'new') {
            $contact->update(['status' => 'read']);
        }
        return view('admin.contacts.show', compact('contact'));
    }

    public function updateStatus(Request $request, Contact $contact)
    {
        $contact->update(['status' => $request->status]);
        return back()->with('success', 'Status updated.');
    }

    public function reply(Request $request, Contact $contact)
    {
        $request->validate([
            'reply_message' => 'required|string|min:5',
        ]);

        Mail::to($contact->email, $contact->name)
            ->send(new \App\Mail\ContactReplyMail($contact, $request->reply_message));

        $contact->update([
            'status'      => 'replied',
            'admin_notes' => $request->reply_message,
        ]);

        return back()->with('success', 'Reply sent to ' . $contact->email);
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();
        return redirect()->route('admin.contacts.index')->with('success', 'Contact deleted.');
    }
}
