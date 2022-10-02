<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplementary extends Model
{
    protected $guarded = [];
    public $table = 'supplementaries';
    // protected $fillable = [
    //   'title', 'picture', 'description', 'price'
    // ];

    public function branches()
    {
        return $this->belongsToMany(Branch::class);
    }
}
