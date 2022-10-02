<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\NutritionistFactory;

class Nutritionist extends Model
{
    //
    protected $fillable = [
        'is_checked'
    ];
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function userinfo(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(UserInfo::class, 'user_id','user_id');
    }

    public function members(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Member::class, 'member_nutritionist')->withpivot('start_date','end_date');
    }

    public function nutritionistSessions()
    {
        return $this->belongsToMany(Member::class, 'nutritionist_sessions');
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }
    public function meals()
    {
        return $this->hasMany(Meal::class);
    }
    public function plans()
    {
        return $this->hasMany(Plan::class);
    }
}
