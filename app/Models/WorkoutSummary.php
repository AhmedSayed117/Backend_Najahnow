<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class WorkoutSummary extends Model
{
    //

    public $table = 'workout_summaries';

    protected $fillable = [
        'calories_burnt', 'duration', 'date', 'member_id'
    ];

    public function member(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}
