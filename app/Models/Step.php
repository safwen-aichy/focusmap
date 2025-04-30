<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Step extends Model
{
    use HasFactory;

    protected $fillable = [
        'goal_id',
        'title',
        'description',
        'deadline',
        'is_completed',
    ];


    public function goal()
    {
        return $this->belongsTo(Goal::class);
    }


    public function scopeForGoal($query, $goalId)
    {
        return $query->where('goal_id', $goalId);
    }


    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }


    public function scopeIncomplete($query)
    {
        return $query->where('is_completed', false);
    }
}
