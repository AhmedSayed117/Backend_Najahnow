<?php

namespace App\Providers;

use App\Models\Classes;
use App\Models\NutritionistSession;
use App\Models\User;
use App\Policies\ClassesPolicy;
use App\Policies\NutritionistSessionPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
        'App\Models\Exercise' => 'App\Policies\ExercisePolicy',
        'App\Models\Announcement' => 'App\Policies\AnnouncementPolicy',
        'App\Models\Answer' => 'App\Policies\AnswerPolicy',
        'App\Models\Event' => 'App\Policies\EventPolicy',
        'App\Models\FeedbackComplaints' => 'App\Policies\FeedbackComplaintsPolicy',
        'App\Models\Invitation' => 'App\Policies\InvitationPolicy',
        'App\Models\Question' => 'App\Policies\QuestionPolicy',
        'App\Models\Supplementary' => 'App\Policies\SupplementaryPolicy',
        'App\Models\Set' => 'App\Policies\SetPolicy',
        'App\Models\Group' => 'App\Policies\GroupPolicy',
        'App\Models\PrivateSession' => 'App\Policies\PrivateSessionPolicy',
        'App\Models\Item' => 'App\Policies\ItemPolicy',
        'App\Models\Meal' => 'App\Policies\MealPolicy',
        'App\Models\Fitness_Summary' => 'App\Policies\FitnessSummaryPolicy',
        'App\Models\Plan' => 'App\Policies\PlanPolicy',
        'App\Models\Member' => 'App\Policies\MemberPolicy',
//        'App\Models\User' => 'App\Policies\UserPolicy',
        User::class => UserPolicy::class,
        Classes::class => ClassesPolicy::class,
        NutritionistSession::class => NutritionistSessionPolicy::class,

    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::before(function ($user) {
            if ($user->role == "admin") {
                return true;
            }
        }
        );
    }
}
