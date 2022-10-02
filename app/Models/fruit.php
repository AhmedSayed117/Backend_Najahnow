<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class fruit extends Model
{
    use HasFactory;

    protected $table = 'fruits';
    protected $fillable = ['Apple' , 'Mango' , 'Banana', 'Orange' , 'Tangerine' , 'Green Grapes' , 'Watermelon' , 'Strawberry' , 'Peaches' , 'Kiwi'];
    public $timestamps = false;
}
