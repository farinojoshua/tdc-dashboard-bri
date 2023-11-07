<?php

namespace App\Models\Brisol;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyTarget extends Model
{
    use HasFactory;

    protected $table = 'brisol_monthly_target';

    protected $fillable = ['month', 'year', 'monthly_target_value'];
}
