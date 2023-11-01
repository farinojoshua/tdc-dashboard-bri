<?php

namespace App\Models\Brisol;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    use HasFactory;

    protected $table = 'brisol_incident';

    public $incrementing = false;
    protected $primaryKey = 'inc_id';

    protected $fillable = [
        'inc_id',
        'reported_date',
        'resolved_date',
        'region',
        'service_ci',
        'prd_tier1',
        'prd_tier2',
        'prd_tier3',
        'ctg_tier1',
        'ctg_tier2',
        'ctg_tier3',
        'resolution_category',
        'priority',
        'status',
        'slm_status',
    ];
}
