<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
  protected $guarded = [];

  public function coach()
  {
    return $this->belongsTo(Coach::class);
  }
  public function exercises()
  {
        return $this->belongsToMany(Exercise::class)->withTimestamps()->withPivot('break_duration', 'order');
  }
  public function sets()
  {
        return $this->belongsToMany(Set::class, 'set_group')->withTimestamps()->withPivot('break_duration', 'order');
  }
  public function members()
  {
    return $this->belongsToMany(Member::class)->withTimestamps();
  }
}
