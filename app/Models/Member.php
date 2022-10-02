<?php

namespace App\Models;


use App\Models\WorkoutSummary;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\MemberFactory;

class Member extends Model
{

    protected $fillable = [
        'is_checked', 'start_date', 'end_date', 'medical_physical_history', 'medical_allergic_history', 'available_frozen_days', 'available_membership_days', 'active_days', 'coach_id', 'rate_count'
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->BelongsTo(User::class, 'user_id');
    }

    public function userinfo(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(UserInfo::class, 'user_id','user_id');
    }

    public function plan(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Plan::class, 'current_plan');
    }

    public function membership(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Membership::class);
    }

    public function classes(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Classes::class, 'classes_member')->withPivot('favourite');
    }

    public function coaches(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Coach::class, 'coach_member')->withpivot('start_date', 'end_date');
    }

    public function nutritionists(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Nutritionist::class, 'member_nutritionist')->withpivot('start_date', 'end_date');
    }

    public function workoutSummaries(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(WorkoutSummary::class);
    }

    public function fitnessSummary(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Fitness_Summary::class);
    }

    public function payments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function nutritionistSessions()
    {
        return $this->belongsToMany(Nutritionist::class, 'nutritionist_sessions');
    }

    public function privateSessions()
    {
        return $this->belongsToMany(PrivateSession::class, 'session_member', 'member_id', 'session_id')->withTimestamps();
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_member')->withTimestamps();
    }

    public function plans()
    {
        return $this->belongsToMany(Plan::class, 'plan_member')->withTimestamps()->withPivot(['duration', 'id']);
    }
}
