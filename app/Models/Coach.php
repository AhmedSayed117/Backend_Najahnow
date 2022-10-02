<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coach extends Model
{
    //
    protected $fillable = [
        'is_checked','avg_rate'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userinfo(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(UserInfo::class, 'user_id','user_id');
    }

    public function classes(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Classes::class, 'classes_coach');
    }

    public function members(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Member::class, 'user_id');
    }

    public function memberss(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Member::class, 'coach_member')->withpivot('start_date','end_date');
    }

    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    public function sets()
    {
        return $this->hasMany(Set::class);
    }

    public function privateSessions()
    {
        return $this->hasMany(PrivateSession::class);
    }

    public function exercises()
    {
        return $this->hasMany(Exercise::class);
    }
}
