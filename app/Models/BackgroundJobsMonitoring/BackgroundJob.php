<?php

namespace App\Models\BackgroundJobsMonitoring;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackgroundJob extends Model
{
    use HasFactory;

    protected $table = 'bjm_background_jobs';

    protected $fillable = [
        'type',
        'process_id',
        'data_amount_to_EIM',
        'data_amount_to_S4GL',
        'status',
        'duration_to_EIM',
        'duration_to_S4GL',
        'notes',
        'execution_date',
    ];

    public function process()
    {
        return $this->belongsTo(Process::class, 'process_id');
    }
}
