<?php

use Illuminate\Database\Seeder;

use Illuminate\Database\Eloquent\Model;
use App\Models;
use App\database\seeds;
use App\Models\Meal;
use App\Models\Item;
use App\Models\Plan;
use App\Models\Exercise;
use App\Models\Group;
use App\Models\PrivateSession;
use App\Models\Set;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

//        $this->call('UserSeeder');
//        $this->call('UserInfoSeeder');

        Model::unguard();
        $this->call('UserSeeder');
        $this->call('BranchSeeder');
        $this->call('MembershipSeeder');
        $this->call('UserInfoSeeder');

        $this->call('NutritionistSeeder');
        $this->call([PlanSeeder::class]);
        $this->call('MemberSeeder');
        $this->call('ClassesSeeder');
        $this->call('CoachSeeder');
        $this->call('CoachMemberSeeder');
        $this->call('ClassCoachSeeder');
        $this->call('NutritionistMemberSeeder');
        $this->call('EquipmentSeeder');
        $this->call('NutritionistSessionSeeder'); // 2 tables have the same relations with the same 2 ids  causing error aas laravel deals with the 2 tables as 1 table
        $this->call('BranchEquipmentSeeder');
        $this->call('WorkoutSummarySeeder');
        $this->call('ClassMemberSeeder');

        $this->call(EventSeeder::class);
        $this->call(EventUserSeeder::class);
        $this->call(QuestionSeeder::class);
        $this->call(AnswerSeeder::class);
        $this->call(InvitationSeeder::class);
        $this->call(SupplementarySeeder::class);
        $this->call(BranchSupplementarySeeder::class);
        $this->call(AnnouncementSeeder::class);
        $this->call(FeedbackComplaintsSeeder::class);
        $this->call(PaymentSeeder::class);

        $this->call([ItemSeeder::class]);
        $this->call([MealSeeder::class]);
        $this->call([FitnessSummarySeeder::class]);
        $this->call([ExerciseSeeder::class]);
        $this->call([SetSeeder::class]);
        $this->call([GroupSeeder::class]);
        $this->call([PrivateSessionSeeder::class]);
        $this->call([ExerciseSetSeeder::class]);
        $this->call([ExerciseGroupSeeder::class]);
        $this->call([SetGroupSeeder::class]);
        $this->call([ItemMealSeeder::class]);
        $this->call([ItemPlanSeeder::class]);
        $this->call([MealPlanSeeder::class]);
        $this->call([PrivateSessionMemberSeeder::class]);
        $this->call([GroupMemberSeeder::class]);
        $this->call([EquipmentExerciseSeeder::class]);
    }
}
