<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class carb extends Model
{
    use HasFactory;

    protected $table = 'carbs';
    protected $fillable = ['pasta','white rice', 'potato', 'sweet potato', 'chickpeas', 'lima beans', 'fava beans'];
    public $timestamps = false;


}
