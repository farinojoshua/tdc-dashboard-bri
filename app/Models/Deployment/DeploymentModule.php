<?php

namespace App\Models\Deployment;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DeploymentModule extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_active',
    ];

    // Relationships
    public function deployments()
    {
        return $this->hasMany(Deployment::class);
    }

    public function serverTypes()
    {
        return $this->hasMany(DeploymentServerType::class);
    }
}
