<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'name', 'email', 'phone', 'website',
        'contact_person', 'address', 'notes', 'status',
    ];

    public function financials()
    {
        return $this->hasMany(ProjectFinancial::class);
    }

    public function getTotalRevenueAttribute()
    {
        return $this->financials()->sum('project_cost');
    }

    public function getTotalProfitAttribute()
    {
        return $this->financials()->selectRaw('SUM(project_cost - expenses) as total')->value('total') ?? 0;
    }
}
