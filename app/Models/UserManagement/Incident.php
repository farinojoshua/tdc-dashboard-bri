<?php

namespace App\Models\UserManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    use HasFactory;

    protected $table = 'usman_incident';

    protected $fillable = ['reported_date', 'type_id', 'branch_id', 'req_status', 'exec_status', 'execution_date', 'sla_category'];

    public function reqType()
    {
        return $this->belongsTo(ReqType::class, 'type_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
