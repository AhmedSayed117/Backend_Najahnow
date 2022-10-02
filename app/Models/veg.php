<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class veg extends Model
{
    use HasFactory;

    protected $table = 'vegs';
    protected $fillable = ['tomato' , 'cucummber' , 'lettuce' , 'zucchini' , 'eggplant' , 'spinach' , 'mushroom'];
    public $timestamps = false;

}
