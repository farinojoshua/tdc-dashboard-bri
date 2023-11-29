<?php

namespace App\Models\Deployment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeploymentServerType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'module_id',
        'is_active',
    ];

    // Relationships
    public function module()
    {
        return $this->belongsTo(DeploymentModule::class);
    }

    public function deployments()
    {
        return $this->hasMany(Deployment::class);
    }
}
