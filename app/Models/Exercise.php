<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// 1-1 hasOne
// 1-1 (inv) belongsTo
// 1-M hasMany
// 1-M (inv) belongsTo
// M-M belongsToMany
// M-M (inv) belongsToMany

class Exercise extends Model
{
  protected $guarded = [];

  public function equipments()
  {
    return $this->belongsToMany(Equipment::class);
  }

  public function coach()
  {
    return $this->belongsTo(Coach::class);
  }

  public function sets()
  {
    return $this->belongsToMany(Set::class)->withTimestamps();
  }

  public function groups()
  {
    return $this->belongsToMany(Group::class)->withTimestamps();
  }
}
