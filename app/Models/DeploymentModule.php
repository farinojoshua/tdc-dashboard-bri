<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeploymentModule extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function deployments()
    {
        return $this->hasMany(Deployment::class);
    }

    public function serverTypes()
    {
        return $this->hasMany(DeploymentServerType::class);
    }
}
