<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseItem extends Model
{
    protected $fillable = ['project_financial_id', 'description', 'amount', 'category', 'date'];
    protected $casts = ['amount' => 'decimal:2', 'date' => 'date'];

    public function financial()
    {
        return $this->belongsTo(ProjectFinancial::class, 'project_financial_id');
    }
}
