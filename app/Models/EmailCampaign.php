<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailCampaign extends Model
{
    protected $fillable = [
        'name', 'subject', 'body', 'from_name', 'from_email',
        'status', 'total_recipients', 'sent_count', 'failed_count', 'sent_at',
    ];

    protected $casts = ['sent_at' => 'datetime'];

    public function recipients()
    {
        return $this->hasMany(CampaignRecipient::class);
    }

    public function getSuccessRateAttribute()
    {
        if ($this->total_recipients === 0) return 0;
        return round(($this->sent_count / $this->total_recipients) * 100, 1);
    }
}
