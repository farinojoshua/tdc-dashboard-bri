<?php

namespace App\Models\Deployment;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Deployment extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'module_id',
        'server_type_id',
        'deploy_date',
        'document_status',
        'document_description',
        'cm_status',
        'cm_description',
    ];

    // Relationships
    public function module()
    {
        return $this->belongsTo(DeploymentModule::class);
    }

    public function serverType()
    {
        return $this->belongsTo(DeploymentServerType::class);
    }
}
