<?php

namespace App\Models\Deployment;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DeploymentModule extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
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
