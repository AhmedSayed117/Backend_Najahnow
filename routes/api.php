<?php

/////////////////////////////////////////////////////
/////////////////////////////////////////////////////
/////////////////      TODO         /////////////////
/////////////////////////////////////////////////////
/////////////////////////////////////////////////////
/*
1- Validation
2- User_id foregin key
3- complete features
*/

use App\Http\Controllers\AudioController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('upload-image', 'ImageUploadController@postImage');
Route::get('image/{filename}','ImageUploadController@fetchImage');
// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::post('/exercises/create', 'ExerciseController@store');
// Route::get('/exercises/{exercise}', 'ExerciseController@show');
// Route::put('/exercises/{exercise}/edit', 'ExerciseController@update');
// Route::delete('/exercises/{exercise}', 'ExerciseController@destroy');



// Route::post('/sets/create', 'SetController@store');
// Route::get('/sets/{set}', 'SetController@show');
// Route::get('/sets/{set}/details', 'SetController@showDetails');
// Route::put('/sets/{set}/edit', 'SetController@update');
// Route::delete('/sets/{set}', 'SetController@destroy');


// Route::post('/groups/create', 'GroupController@store');
// Route::get('/groups/{group}', 'GroupController@show');
// Route::get('/groups/{group}/details', 'GroupController@showDetails');
// Route::put('/groups/{group}/edit', 'GroupController@update');
// Route::delete('/groups/{group}', 'GroupController@destroy');


// // Route::post('/sessions/create', 'PrivateSessionController@store');
// // Route::get('/sessions/{session}', 'PrivateSessionController@show');
// // Route::post('/sessions/{session}/book', 'PrivateSessionController@bookSession');
// // Route::post('/sessions/{session}/cancel', 'PrivateSessionController@cancelSession');
// // Route::put('/sessions/{session}/edit', 'PrivateSessionController@update');
// // Route::delete('/sessions/{session}', 'PrivateSessionController@destroy');


/**********************************
 ******TEAM-4-5-APIS***************
 **********************************/
//////////////////////////////////MembershipApis////////////////////////////////
Route::group(['prefix'=>'memberships','middleware' => ['api']],function (){
    Route::get('getAll','MembershipController@index');
    Route::get('show/{id}', 'MembershipController@show');
    Route::post('store', 'MembershipController@store');
    Route::post('update/{id}', 'MembershipController@update');
    Route::delete('delete/{id}', 'MembershipController@delete');
});

//////////////////////////////////MemberApis////////////////////////////////
Route::group(['prefix'=>'members','middleware' => ['api']],function (){
    Route::get('getAll','MemberController@index');
    Route::get('show/{id}', 'MemberController@show');
    Route::post('store', 'MemberController@store');
    Route::post('update/{id}', 'MemberController@update');
    Route::delete('delete/{id}', 'MemberController@delete');
    Route::post('workoutsummaries', 'MemberController@storeWorkoutSummary');
    Route::get('workoutsummaries/self', 'MemberController@getMyWorkoutSummaries');
});

//////////////////////////////////EquipmentApis////////////////////////////////
Route::group(['prefix'=>'equipments','middleware' => ['api']],function (){
    Route::get('getAll','EquipmentController@index');
    Route::get('show/{id}', 'EquipmentController@show');
    Route::post('store', 'EquipmentController@store');
    Route::put('update/{id}', 'EquipmentController@update');
    Route::delete('delete/{id}', 'EquipmentController@delete');
});
//////////////////////////////////BranchApis////////////////////////////////
Route::group(['prefix'=>'branches','middleware' => ['api']],function (){
    Route::get('getAll','BranchController@index');
    Route::get('show/{id}', 'BranchController@show');
    Route::post('store', 'BranchController@store');
    Route::put('update/{id}', 'BranchController@update');
    Route::delete('delete/{id}', 'BranchController@delete');
    Route::put('assignEquipment/{id}', 'BranchController@assignEquipment');
    Route::get('getAllEquipments/{id}','BranchController@showEquipments');

});



//////////////////////////////////CoachApis////////////////////////////////
Route::group(['prefix'=>'coaches','middleware' => ['api']],function (){
    Route::get('getAll','CoachController@index');
    Route::get('show/{id}', 'CoachController@show');
    Route::post('store', 'CoachController@store');
    Route::post('update/{id}', 'CoachController@update');
    Route::delete('delete/{id}', 'CoachController@delete');
    Route::post('assignMember/{id}', 'CoachController@assignMember');
    Route::get('getAllMembers/{id}','CoachController@showMembers');

});

Route::get('/coaches/exercises/self', 'CoachController@fetchExercises');
Route::get('/coaches/sets/self', 'CoachController@fetchSets');
Route::get('/coaches/groups/self', 'CoachController@fetchGroups');
Route::get('/coaches/privateSessions/self', 'CoachController@fetchPrivateSessions');

//////////////////////////////////ClassesApis////////////////////////////////
Route::group(['prefix'=>'classes','middleware' => ['api']],function ()
{
    Route::get('getAll','ClassesController@index');
    Route::get('get','ClassesController@self');
    Route::get('get/{id}', 'ClassesController@show');
    Route::post('store', 'ClassesController@store');
    Route::put('update/{id}', 'ClassesController@update');
    Route::delete('delete/{id}', 'ClassesController@delete');
    Route::post('assignCoach/{id}', 'ClassesController@assignCoach');
    Route::post('assignMember/{id}', 'ClassesController@assignMember');
    Route::get('getAllMembers/{id}','ClassesController@showMembers');
    Route::get('getAllCoaches/{id}','ClassesController@showCoaches');
});
//////////////////////////////////UserApi////////////////////////////////
Route::group(['prefix'=>'users','middleware' => ['api']],function ()
{
    Route::get('getAll','UserController@index');
    Route::get('show/{id}', 'UserController@show');
    Route::post('store', 'UserController@store');
    Route::put('update/{id}', 'UserController@update');
//    Route::delete('delete/{id}', 'UserController@delete');
});
////////////////////////////NutritionistSessions////////////////////////////
Route::group(['prefix'=>'nutSessions','middleware' => ['api']],function ()
{
    Route::get('getAll','NutritionistSessionController@index');
    Route::get('show/{id}', 'NutritionistSessionController@show');
    Route::post('store', 'NutritionistSessionController@store');
    Route::put('update/{id}', 'NutritionistSessionController@update');
    Route::delete('delete/{id}', 'NutritionistSessionController@delete');
});
////////////////////////////Nutritionist////////////////////////////////////
Route::group(['prefix'=>'nutritionists','middleware' => ['api']],function ()
{
    Route::get('getAll','NutritionistController@index');
    Route::get('show/{id}', 'NutritionistController@show');
    Route::post('store', 'NutritionistController@store');
    Route::post('update/{id}', 'NutritionistController@update');
    Route::delete('delete/{id}', 'NutritionistController@delete');
    Route::post('assignMember/{id}', 'NutritionistController@assignMember');
    Route::get('getAllMembers/{id}', 'NutritionistController@showMembers');
    Route::get('members/self', 'NutritionistController@fetchMyMembers');
});

Route::group(['middleware'=>'auth:api'],function (){
    Route::post('login',[
        'uses' => 'Auth\LoginController@login',
        'as' => 'login'
    ]);
});


/////////////////////////////////////////////////////
/////////////////////////////////////////////////////
/////////////////      Events       /////////////////
/////////////////////////////////////////////////////
/////////////////////////////////////////////////////

//ُGet All Events
Route::get('/events', 'EventController@index');
//ُGet event by ID
Route::get('/events/{eventID}', 'EventController@GetEventByID');
//Add new event
Route::post('/events/create', 'EventController@AddEvent');
//Edit new event
Route::post('/events/edit/{eventID}', 'EventController@EditEvent');
//Delete new event
Route::delete('/events/{eventID}', 'EventController@DeleteEvent');
//Get User Events
Route::get('/users/events', 'EventController@GetUserEvents');
//Get Upcoming User Events
Route::get('/users/upcomingevents', 'EventController@GetUpcomingUserEvents');
//Get past User Events
Route::get('/users/pastevents', 'EventController@GetPastUserEvents');
//Register in event
Route::post('/events/{eventID}/register', 'EventController@RegisterInEvent');
//Cancel registeration
Route::post('/events/{eventID}/cancel', 'EventController@CancelRegisteration');

/////////////////////////////////////////////////////
/////////////////////////////////////////////////////
////////////      Announcments       ////////////////
/////////////////////////////////////////////////////
/////////////////////////////////////////////////////

//ُGet All Announcments
Route::get('/announcements', 'AnnouncementController@index');
//ُGet Announcment by ID
Route::get('/announcements/{announcmentID}', 'AnnouncementController@GetAnnouncmentByID');
//Add new announcement
Route::post('/announcements/create', 'AnnouncementController@AddAnnouncement');
//Edit new announcement
Route::post('/announcements/edit/{announcementID}', 'AnnouncementController@EditAnnouncement');
//Delete new announcement
Route::delete('/announcements/{announcementID}', 'AnnouncementController@DeleteAnnouncement');

/////////////////////////////////////////////////////
/////////////////////////////////////////////////////
///////////////      Questions       ////////////////
/////////////////////////////////////////////////////
/////////////////////////////////////////////////////

//ُGet All Questions
Route::get('/questions', 'QuestionController@index');
//ُGet Question by ID
Route::get('/questions/{questionID}', 'QuestionController@GetQuestionByID');
//Add new question
Route::post('/questions/create', 'QuestionController@AddQuestion');
//Edit new question
Route::post('/questions/edit/{questionID}', 'QuestionController@EditQuestion');
//Delete new question
Route::delete('/questions/{questionID}', 'QuestionController@DeleteQuestion');

/////////////////////////////////////////////////////
/////////////////////////////////////////////////////
/////////////////      Answers       ////////////////
/////////////////////////////////////////////////////
/////////////////////////////////////////////////////

//ُGet All Answers
Route::get('/answers', 'AnswerController@index');
//ُGet Answer by ID
Route::get('/answers/{answerID}', 'AnswerController@GetAnswerByID');
//ُGet all Answers by in question
Route::get('/questions/{questionID}/answers', 'AnswerController@GetAnswersForQuestion');
//Add new answer
Route::post('/answers/create', 'AnswerController@AddAnswer');
//Edit new answer
Route::post('/answers/edit/{answerID}', 'AnswerController@EditAnswer');
//Delete new answer
Route::delete('/answers/{answerID}', 'AnswerController@DeleteAnswer');

/////////////////////////////////////////////////////
/////////////////////////////////////////////////////
/////////////////      Feedbacks       //////////////
/////////////////////////////////////////////////////
/////////////////////////////////////////////////////

//ُGet All feedbacks
Route::get('/feedbacks', 'FeedbackComplaintsController@index');
//ُGet feedback by ID
Route::get('/feedbacks/{feedbackID}', 'FeedbackComplaintsController@GetFeedbackByID');
//Add new feedback
Route::post('/feedbacks/create', 'FeedbackComplaintsController@AddFeedbackComplaints');
//Edit new feedback
Route::post('/feedbacks/edit/{feedbackID}', 'FeedbackComplaintsController@EditFeedbackComplaints');
//Delete new feedback
Route::delete('/feedbacks/{feedbackID}', 'FeedbackComplaintsController@DeleteFeedbackComplaints');

/////////////////////////////////////////////////////
/////////////////////////////////////////////////////
///////////////      Invitations       //////////////
/////////////////////////////////////////////////////
/////////////////////////////////////////////////////

//ُGet All feedbacks
Route::get('/invitations', 'InvitationController@index');
//ُGet feedback by ID
Route::get('/invitations/{invitationID}', 'InvitationController@GetInvitationByID');
//Add new invitation
Route::post('/invitations/create', 'InvitationController@AddInvitation');
//Edit new invitation
Route::post('/invitations/edit/{invitationID}', 'InvitationController@EditInvitation');
//Delete new invitation
Route::delete('/invitations/{invitationID}', 'InvitationController@DeleteInvitation');

/////////////////////////////////////////////////////
/////////////////////////////////////////////////////
/////////////      Supplementary       //////////////
/////////////////////////////////////////////////////
/////////////////////////////////////////////////////

//ُGet All Supplementaries
Route::get('/supplementaries', 'SupplementaryController@index');
//ُGet Supplementary by ID
Route::get('/supplementaries/{supplementaryID}', 'SupplementaryController@GetSupplementaryByID');
//Add new supplementaries
Route::post('/supplementaries/create', 'SupplementaryController@AddSupplementary');
//Edit new supplementaries
Route::post('/supplementaries/edit/{supplementaryID}', 'SupplementaryController@EditSupplementary');
//Delete new supplementaries
Route::delete('/supplementaries/{supplementaryID}', 'SupplementaryController@DeleteSupplementary');
//Get Branch supplementaries
Route::get('/branches/{branchID}/supplementaries', 'SupplementaryController@GetBranchSupplementaries');
//Add supplementary to branch
Route::post('/branches/{branchID}/add/{supplementaryID}', 'SupplementaryController@AddSupplementaryToBranch');
//Remove supplementary from branch
Route::post('/branches/{branchID}/remove/{supplementaryID}', 'SupplementaryController@RemoveSupplementaryFromBranch');


//////////////////Item///////////////////////

//search
Route::get('/items/search', 'ItemController@showSearch');

//retrieve an item
Route::get('/items/{item}', 'ItemController@index');

//retrieve all items
Route::get('/items', 'ItemController@show');

//create an item
Route::post('/items/create', 'ItemController@create');

//delete an item
Route::delete('/items/{item}', 'ItemController@destroy');

//edit an item
Route::put('/items/edit/{item}', 'ItemController@update');


/////////////////Meal///////////////////////

//search
Route::get('/meals/search', 'MealController@showSearch');

//retrieve a meal
Route::get('/meals/{meal}', 'MealController@index');

//retrieve all meals
Route::get('/meals', 'MealController@show');

//delete a meal
Route::delete('/meals/{meal}', 'MealController@destroy');

//create a meal
Route::post('/meals/create', 'MealController@create');

//update a meal
Route::put('/meals/{meal}', 'MealController@update');

/////////////////Plan///////////////////////

//search
Route::get('/plans/search', 'PlanController@showSearch');

//retrieve a plan
Route::get('/plans/{plan}', 'PlanController@show');

//retrieve all plans
Route::get('/plans', 'PlanController@index');

//delete a plan
Route::delete('/plans/{plan}', 'PlanController@destroy');

//create a plan
Route::post('/plans/create', 'PlanController@store');

//update a plan
Route::put('/plans/{plan}', 'PlanController@update');

///////////////////Plan_Meal//////////////////

//add item to a meal
Route::put('/plans/{plan}/addMeal', 'PlanMealController@addMeal');

//delete item from a meal
Route::put('/plans/{plan}/removeMeal', 'PlanMealController@removeMeal');

//Update item quantity in a meal
Route::put('/plans/{plan}/updateMeal', 'PlanMealController@updateMeal');

///////////////////Plan_Item//////////////////

//add item to a meal
Route::put('/plans/{plan}/addItem', 'PlanItemController@addItem');

//delete item from a plan
Route::put('/plans/{plan}/removeItem', 'PlanItemController@removeItem');

//Update item quantity in a meal
Route::put('/plans/{meal}/updateItem', 'PlanItemController@updateItem');
///////////////////Meal_Item//////////////////

//add item to a meal
Route::put('/meals/{meal}/addItem', 'MealItemController@addItem');

//delete item from a meal
Route::put('/meals/{meal}/removeItem', 'MealItemController@removeItem');

//Update item quantity in a meal
Route::put('/meals/{meal}/updateItem', 'MealItemController@updateItem');

//////////////////Fitness_Summary/////////////

//get a fitness summary
Route::get('/fitness_summaries/{fitness_summary}', 'FitnessSummaryController@index');

//create a fitness summary
Route::post('/fitness_summaries/create', 'FitnessSummaryController@create');

//delete a fitness summary
Route::delete('/fitness_summaries/{fitness_summary}', 'FitnessSummaryController@destroy');

//retrieve all fitness summaries
Route::get('/fitness_summaries', 'FitnessSummaryController@show');

////////////////Plan_Member///////////////////

//Update current plan of a member
Route::put('/members/{member}/addPlan', 'PlanMemberController@addPlan');

//Update current plan of a member
Route::put('/members/{member}/removePlan', 'PlanMemberController@removePlan');

//Update current plan of a member
Route::put('/members/{member}/updatePlan', 'PlanMemberController@updatePlan');

//get current plan of a member
Route::get('/members/{member}/getActivePlan', 'PlanMemberController@getActivePlan');

//get all plans of a member
Route::get('/members/{member}/plans', 'PlanMemberController@getPlans');
Route::get('/members/weekGroups', 'MemberController@fetchWeekGroups');
/////////////////////////////////////////////


Route::apiResource('exercises', 'ExerciseController');
Route::get('/exercises/{exercise}/details', 'ExerciseController@showDetails');


Route::apiResource('sets', 'SetController');
Route::get('/sets/{set}/details', 'SetController@showDetails');

Route::apiResource('groups', 'GroupController');
Route::get('/groups/{group}/details', 'GroupController@showDetails');


Route::get('/sessions/index', 'PrivateSessionController@showMySessions');
Route::get('/sessions/booked', 'PrivateSessionController@showBookedSessions');
Route::get('/sessions/requests', 'PrivateSessionController@showRequestedSessions');
Route::apiResource('sessions', 'PrivateSessionController');
Route::get('/sessions/{session}/details', 'PrivateSessionController@showDetails');
Route::post('/sessions/{session}/request', 'PrivateSessionController@requestSession');
Route::post('/sessions/{session}/cancel', 'PrivateSessionController@cancelSession');
Route::post('/sessions/{session}/accept', 'PrivateSessionController@acceptSession');
Route::post('/sessions/{session}/reject', 'PrivateSessionController@rejectSession');


Route::get('/coaches/exercises/self', 'CoachController@fetchExercises');
Route::get('/coaches/sets/self', 'CoachController@fetchSets');
Route::get('/coaches/groups/self', 'CoachController@fetchGroups');
Route::get('/coaches/privateSessions/self', 'CoachController@fetchPrivateSessions');
Route::get('/coaches/schedule/self', 'CoachController@fetchSchedule');
Route::get('/coaches/members/self', 'CoachController@fetchMembers');
Route::post('/coaches/assign-group-member', 'CoachController@assignGroupsToMember');
Route::get('/coaches/classes/self', 'CoachController@fetchClasses');


Route::post('/login', 'Auth\LoginController@login');
Route::post('/logout', 'Auth\LoginController@logout')->middleware('api');

Route::options('/{any}', function ($any) {
    return response()->json([], 200, [
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Methods' => 'GET, OPTIONS, POST, PUT',
        'Access-Control-Allow-Headers' => 'Content-Type, Authorization, Access-Control-Allow-Methods, Access-Control-Allow-Headers',
    ]);
})->where('any', '.*');

//new Team

Route::get('fetch',[AudioController::class,'fetchMusic'])->middleware('auth:api');

Route::get('/MacrosCalculator', 'MemberController@MacrosCalc');
Route::get('/CaloriesCalculator', 'MemberController@CaloriesCalculator');
Route::get('/Rating', 'MemberController@rate_coach');

/// nutrition plans route stand by ///

Route::get('/test', 'MemberController@Nutrition');

Route::post('/upload', 'MemberController@upload');
Route::get('/download', 'MemberController@download');
