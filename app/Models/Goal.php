<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{

    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'location_latitude',
        'location_longitude',
        'deadline',
        'visibility',
        'progress',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function steps()
    {
        return $this->hasMany(Step::class);
    }
}
