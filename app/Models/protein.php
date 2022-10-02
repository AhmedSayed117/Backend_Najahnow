<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class protein extends Model
{
    use HasFactory;

    protected $table = 'protein';
    protected $fillable = ['Lean Beef' , 'Chicken Breast' , 'White Fish' , 'Shrimp'];
    public $timestamps = false;

}
