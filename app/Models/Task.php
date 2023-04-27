<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'task_title',
        'task_body',
        'task_date',
        'image'
    ];

    public function user(): BelongsTo {
        return $this->belongsto(User::class);
    } 
}
