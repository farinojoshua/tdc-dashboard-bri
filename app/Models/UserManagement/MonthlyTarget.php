<?php

namespace App\Models\UserManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyTarget extends Model
{
    use HasFactory;

    protected $table = 'usman_monthly_target';

    protected $fillable = ['month', 'year', 'monthly_target_value'];
}
