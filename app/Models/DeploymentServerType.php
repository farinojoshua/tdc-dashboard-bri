<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeploymentServerType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'module_id',
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
