<?php

namespace App\Imports;

use App\Models\CampaignRecipient;
use App\Models\EmailCampaign;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;

/**
 * Excel format expected:
 * | name | email | company |
 * OR
 * | email |
 */
class RecipientsImport implements ToCollection, WithHeadingRow
{
    public int $importedCount = 0;
    private EmailCampaign $campaign;

    public function __construct(EmailCampaign $campaign)
    {
        $this->campaign = $campaign;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $email = $row['email'] ?? null;
            if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) continue;

            if (!$this->campaign->recipients()->where('email', $email)->exists()) {
                CampaignRecipient::create([
                    'email_campaign_id' => $this->campaign->id,
                    'name'              => $row['name'] ?? null,
                    'email'             => $email,
                    'company'           => $row['company'] ?? null,
                    'status'            => 'pending',
                ]);
                $this->importedCount++;
            }
        }
    }
}
