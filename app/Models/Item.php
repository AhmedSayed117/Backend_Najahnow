<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    //
    protected $table = 'item';
    protected $fillable = [
        'title', 'description', 'cal', 'level', 'image', 'nutritionist_id' //must be removed!
    ];
    //protected $hidden = ['pivot'];

    public function nutritionist()
    {
        return $this->belongsTo(Nutritionist::class);
    }

    public function meals()
    {
        return $this->belongsToMany(Meal::class, 'item_meal');
    }

    public function plans()
    {
        return $this->belongsToMany(Plan::class, 'item_plan');
    }
}
