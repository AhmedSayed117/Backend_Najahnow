<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Set extends Model
{
  protected $guarded = [];

  public function coach()
  {
    return $this->belongsTo(Coach::class);
  }
  public function exercises()
  {
    return $this->belongsToMany(Exercise::class)->withTimestamps()->withPivot('break_duration');
  }
  public function groups()
  {
    return $this->belongsToMany(Group::class, 'set_group')->withTimestamps();
  }
}
