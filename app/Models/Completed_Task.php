<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Completed_Task extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'task_title',
        'task_body',
        'task_start',
        'task_end',
        'completed_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsto(User::class);
    }
}
