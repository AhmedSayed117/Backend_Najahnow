<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    //
    protected $table = 'meal';
    protected $fillable = [
        'title', 'description', 'nutritionist_id' //remove this!
    ];

    public function nutritionist()
    {
        return $this->belongsTo(Nutritionist::class);
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'item_meal')->as('info')->withPivot('quantity');
    }

    public function plans()
    {
        return $this->belongsToMany(Plan::class, 'meal_plan');
    }
}
