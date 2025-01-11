<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectMonitoring extends Model
{
    use HasFactory;

    protected $casts = ['id' => 'string'];

    protected $fillable = [
        "id",
        "project_name",
        "client",
        "project_leader_image",
        "project_leader_name",
        "project_leader_email",
        "start_date",   
        "end_date",
        "progress",
        "created_at",
        "update_at"
    ];
}
