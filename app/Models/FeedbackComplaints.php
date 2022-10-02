<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedbackComplaints extends Model
{
    protected $guarded = [];
    // protected $fillable = [
    //     'title', 'description', 'type'
    // ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
