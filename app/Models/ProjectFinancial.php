<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectFinancial extends Model
{
    protected $fillable = [
        'project_id', 'company_id', 'project_name',
        'project_cost', 'expenses', 'payment_status',
        'amount_paid', 'start_date', 'end_date', 'notes',
    ];

    protected $casts = [
        'project_cost' => 'decimal:2',
        'expenses'     => 'decimal:2',
        'amount_paid'  => 'decimal:2',
        'start_date'   => 'date',
        'end_date'     => 'date',
    ];

    // profit is a stored computed column in DB
    // but we also define an accessor for safety
    public function getProfitAttribute($value)
    {
        return $value ?? ($this->project_cost - $this->expenses);
    }

    public function getBalanceDueAttribute()
    {
        return $this->project_cost - $this->amount_paid;
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function expenseItems()
    {
        return $this->hasMany(ExpenseItem::class);
    }
}
