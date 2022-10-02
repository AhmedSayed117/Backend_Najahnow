<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    protected $fillable = [
        'duration', 'description', 'price', 'limit_of_frozen_days', 'available_classes', 'discount','title','branch_id'
    ];
    public function members(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Member::class);
    }

  public function branch()
  {
    return $this->belongsTo(Branch::class);
  }
}
