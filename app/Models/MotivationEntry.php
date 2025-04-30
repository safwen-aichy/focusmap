<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MotivationEntry extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'rating',
        'notes',
        'date'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date' => 'date',
        'rating' => 'integer',
    ];

    /**
     * Get the user that owns the motivation entry.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
