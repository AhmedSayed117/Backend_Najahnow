<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    //
    protected $table = 'plan';
    protected $fillable = [
        'title', 'description', 'nutritionist_id'
    ];

    public function nutritionist()
    {
        return $this->belongsTo(Nutritionist::class);
    }
    public function currentMembers()
    {
        return $this->hasMany(Member::class, 'current_plan');
    }
    public function meals()
    {
        return $this->belongsToMany(Meal::class, 'meal_plan')->as('info')->withPivot(['type', 'day', 'id']);
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'item_plan')->as('info')->withPivot(['type', 'day', 'id', 'quantity']);
    }

    public function member()
    {
        return $this->belongsToMany(Member::class);
    }
}
