<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NutritionistSession extends Model
{
    protected $fillable = [
        'date'
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function nutritionist()
    {
        return $this->belongsTo(Nutritionist::class);
    }
}
