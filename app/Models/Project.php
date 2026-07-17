<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'client',
        'manager_id',
        'status',
        'priority',
        'budget',
        'starts_at',
        'due_at',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'budget' => 'decimal:2',
            'starts_at' => 'date',
            'due_at' => 'date',
        ];
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function getProgressAttribute(): int
    {
        $total = $this->tasks_count ?? $this->tasks()->count();

        if ($total === 0) {
            return 0;
        }

        $completed = $this->completed_tasks_count
            ?? $this->tasks()->where('status', 'completed')->count();

        return (int) round(($completed / $total) * 100);
    }
}

