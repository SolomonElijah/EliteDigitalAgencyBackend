<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailCampaign;
use App\Models\CampaignRecipient;
use App\Mail\CampaignMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\RecipientsImport;

class EmailCampaignController extends Controller
{
    public function index()
    {
        $campaigns = EmailCampaign::latest()->paginate(15);
        return view('admin.campaigns.index', compact('campaigns'));
    }

    public function create()
    {
        return view('admin.campaigns.form', ['campaign' => new EmailCampaign()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:200',
            'subject'    => 'required|string|max:300',
            'body'       => 'required|string',
            'from_name'  => 'nullable|string|max:100',
            'from_email' => 'nullable|email',
        ]);

        $campaign = EmailCampaign::create($data);

        return redirect()->route('admin.campaigns.recipients', $campaign)
            ->with('success', 'Campaign created! Now add recipients.');
    }

    public function recipients(EmailCampaign $campaign)
    {
        $recipients = $campaign->recipients()->paginate(20);
        return view('admin.campaigns.recipients', compact('campaign', 'recipients'));
    }

    public function addRecipients(Request $request, EmailCampaign $campaign)
    {
        $request->validate([
            'recipients'          => 'nullable|string',
            'excel_file'          => 'nullable|file|mimes:xlsx,xls,csv|max:5120',
        ]);

        $added = 0;

        // Manual textarea input (one per line: name,email,company OR just email)
        if ($request->recipients) {
            $lines = explode("\n", trim($request->recipients));
            foreach ($lines as $line) {
                $line = trim($line);
                if (!$line) continue;
                $parts = array_map('trim', explode(',', $line));
                $email = filter_var(end($parts), FILTER_VALIDATE_EMAIL)
                    ? end($parts)
                    : (filter_var($parts[0], FILTER_VALIDATE_EMAIL) ? $parts[0] : null);

                if ($email && !$campaign->recipients()->where('email', $email)->exists()) {
                    $campaign->recipients()->create([
                        'name'    => count($parts) >= 2 ? $parts[0] : null,
                        'email'   => $email,
                        'company' => count($parts) >= 3 ? $parts[1] : null,
                        'status'  => 'pending',
                    ]);
                    $added++;
                }
            }
        }

        // Excel import
        if ($request->hasFile('excel_file')) {
            $import = new RecipientsImport($campaign);
            Excel::import($import, $request->file('excel_file'));
            $added += $import->importedCount;
        }

        $campaign->update(['total_recipients' => $campaign->recipients()->count()]);

        return redirect()->route('admin.campaigns.recipients', $campaign)
            ->with('success', "Added {$added} recipients.");
    }

    public function send(EmailCampaign $campaign)
    {
        if ($campaign->status === 'sent') {
            return back()->with('error', 'Campaign already sent.');
        }

        $campaign->update(['status' => 'sending']);

        $sent   = 0;
        $failed = 0;

        $campaign->recipients()->where('status', 'pending')->each(function ($recipient) use ($campaign, &$sent, &$failed) {
            try {
                Mail::to($recipient->email, $recipient->name)
                    ->send(new CampaignMail($campaign, $recipient));

                $recipient->update(['status' => 'sent', 'sent_at' => now()]);
                $sent++;
            } catch (\Exception $e) {
                $recipient->update(['status' => 'failed', 'error_message' => substr($e->getMessage(), 0, 255)]);
                $failed++;
            }
        });

        $campaign->update([
            'status'           => 'sent',
            'sent_count'       => $sent,
            'failed_count'     => $failed,
            'sent_at'          => now(),
        ]);

        return redirect()->route('admin.campaigns.index')
            ->with('success', "Campaign sent! {$sent} delivered, {$failed} failed.");
    }

    public function destroy(EmailCampaign $campaign)
    {
        $campaign->delete();
        return redirect()->route('admin.campaigns.index')->with('success', 'Campaign deleted.');
    }
}
