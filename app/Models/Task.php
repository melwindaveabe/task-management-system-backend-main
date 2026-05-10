<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        "title",
        "description",
        "status",
        "priority",
        "order",
        "user_id"
    ];

    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopePriority($query, string $priority)
    {
        return $query->where('priority', $priority);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected static function booted()
    {
        static::deleted(function ($task) {
            info('Task deleted', [
                'id' => $task->id,
                'title' => $task->title,
                'user_id' => $task->user_id,
                'deleted_by' => auth()->user()->id,
                'deleted_at' => now(),
            ]);
        });
    }
}
