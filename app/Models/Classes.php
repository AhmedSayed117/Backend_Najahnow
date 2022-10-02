<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    protected $fillable=['title','description','level','capacity','link','price','duration','date'];
    public function members(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Member::class,'classes_member')->withPivot('favourite');
    }
    public function coaches()
    {
        return $this->belongsToMany(Coach::class,'classes_coach');
    }

}
