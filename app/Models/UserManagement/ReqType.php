<?php

namespace App\Models\UserManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReqType extends Model
{
    use HasFactory;

    protected $table = 'usman_req_type';

    protected $fillable = ['name'];
}
