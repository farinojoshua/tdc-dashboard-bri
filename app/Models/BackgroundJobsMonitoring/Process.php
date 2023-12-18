<?php

namespace App\Models\BackgroundJobsMonitoring;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Process extends Model
{
    use HasFactory;

    protected $table = 'bjm_processes';

    protected $fillable = ['name', 'type', 'is_active'];

    public function backgroundJobs()
    {
        return $this->hasMany(BackgroundJob::class, 'process_id');
    }
}
