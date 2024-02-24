<?php
use Illuminate\Support\Facades\Auth;
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


// Route::get('test/{course_id}', 'App\Http\Controllers\web\director\ChapterController@tests');
Route::get('state/{id}', 'App\Http\Controllers\web\director\ChapterController@registeredTestStudents');
// Route::get('test/{id}', 'App\Http\Controllers\api\v1\CourseController@tests');
// Route::post('testApproval', 'App\Http\Controllers\web\director\ChapterController@testApproval');
Route::get('courses', 'App\Http\Controllers\api\v1\CourseController@index');
Route::get('courseQuestion/{id}', 'App\Http\Controllers\api\v1\QuestionController@chapters');
Route::get('question/{id}', 'App\Http\Controllers\api\v1\QuestionController@questions');

Route::get('result/{id}', 'App\Http\Controllers\api\v1\CourseController@startTest');
Route::get('courseVideo/{id}', 'App\Http\Controllers\api\v1\VideoController@courseVideo');
Route::get('myNotification', 'App\Http\Controllers\web\director\CourseController@myNotification');


// Route::get('testChapter/{course_id}', 'App\Http\Controllers\web\director\ChapterController@tests');
// Route::post('testApproval', 'App\Http\Controllers\web\director\ChapterController@testApproval');



     Route::get('program', 'App\Http\Controllers\api\v1\ProgramController@program');
     Route::get('logout', 'LoginController@logout ');
     Route::get('school', 'App\Http\Controllers\api\v1\ProgramController@school');
     Route::get('studentType', 'App\Http\Controllers\api\v1\ProgramController@channel');
     Route::post('review', 'App\Http\Controllers\api\v1\ReviewController@review');
     Route::post('/login', 'App\Http\Controllers\api\v1\StaliteController@login');
     Route::get('/level', 'App\Http\Controllers\api\v1\ProgramController@level');
     Route::get('faculty', 'App\Http\Controllers\api\v1\FacultyController@index');
    Route::get('department/{id}', 'App\Http\Controllers\api\v1\DepartmentController@show');

    //  Route::get('departmentalLecturers', 'App\Http\Controllers\web\director\CourseController@departmentalLecturers');


Route::prefix('aspirant')->group(function(){
    Route::post('/login', 'App\Http\Controllers\api\v1\AspirantController@login');
    Route::post('/register', 'App\Http\Controllers\api\v1\AspirantController@register');
    Route::post('/review', 'App\Http\Controllers\api\v1\ReviewController@review');

});
Route::prefix('remedial')->group(function(){
    Route::post('/login', 'App\Http\Controllers\api\v1\RemedialController@login');
    Route::post('/register', 'App\Http\Controllers\api\v1\RemedialController@register');
    Route::post('/review', 'App\Http\Controllers\api\v1\RemedialController@review');
    Route::get('department', 'App\Http\Controllers\api\v1\DepartmentController@remedialDepartment');




});
Route::prefix('stalite')->group(function(){
    Route::post('/register', 'App\Http\Controllers\api\v1\StaliteController@register');
    Route::post('/review', 'App\Http\Controllers\api\v1\StaliteController@review');
    Route::post('/transactionVerify', 'App\Http\Controllers\api\v1\StalitepaymentController@transactionVerify');
    // Route::get('courseVideo/{id}', 'App\Http\Controllers\api\v1\VideoController@courseVideo');
   // Route::get('registeredCourseVideo', 'App\Http\Controllers\api\v1\StaliteController@registeredCourseVideo');
  // Route::get('departmentalCourse/{id},{semester},{level}', 'App\Http\Controllers\api\v1\DepartmentController@departmentalCourse');
  //Route::post('/transactionVerify', 'App\Http\Controllers\api\v1\StalitepaymentController@transactionVerify');
  Route::get('courseVideo/{id}', 'App\Http\Controllers\api\v1\VideoController@courseVideo');


});
Route::prefix('lecturer')->group(function(){
    Route::get('level', 'App\Http\Controllers\api\v1\ProgramController@level');
    Route::post('/login', 'App\Http\Controllers\api\v1\StaliteController@login');
    Route::post('/register', 'App\Http\Controllers\api\v1\StaliteController@register');
    Route::post('/review', 'App\Http\Controllers\api\v1\StaliteController@review');
    Route::post('/transactionVerify', 'App\Http\Controllers\api\v1\StalitepaymentController@transactionVerify');
    // Route::get('courseVideo/{id}', 'App\Http\Controllers\api\v1\VideoController@courseVideo');
   // Route::get('registeredCourseVideo', 'App\Http\Controllers\api\v1\StaliteController@registeredCourseVideo');
  // Route::get('departmentalCourse/{id},{semester},{level}', 'App\Http\Controllers\api\v1\DepartmentController@departmentalCourse');
  //Route::post('/transactionVerify', 'App\Http\Controllers\api\v1\StalitepaymentController@transactionVerify');
  Route::get('courseVideo/{id}', 'App\Http\Controllers\api\v1\VideoController@courseVideo');


});
// Route::group(function(){
//     Route::post('/login', 'App\Http\Controllers\api\v1\RemedialController@login');

// });


Route::middleware('auth:sanctum')->prefix('stalite')->group(function(){
    Route::get('course', 'App\Http\Controllers\api\v1\CourseController@index');
    Route::get('registeredCourse', 'App\Http\Controllers\api\v1\StaliteController@registeredCourse');
    Route::post('setup', 'App\Http\Controllers\api\v1\StaliteController@staliteSetup');
    // Route::get('subscriptionPlan', 'App\Http\Controllers\api\v1\SubscriptionController@staliteSubscription');
    Route::get('liveEvent', 'App\Http\Controllers\api\v1\StaliteController@liveEvents');
    Route::get('registeredCourseVideo', 'App\Http\Controllers\api\v1\StaliteController@registeredCourseVideo');
    Route::get('myDetails', 'App\Http\Controllers\api\v1\StaliteController@myDetails');
    Route::post('transactionInit', 'App\Http\Controllers\api\v1\StalitepaymentController@transactionInit');
    Route::get('logout', 'App\Http\Controllers\api\v1\StaliteController@logout');
    Route::get('sentForgetCode/{email}', 'App\Http\Controllers\api\v1\CourseController@sentForgetCode');
    Route::get('matchForgetCode/{email},{code}', 'App\Http\Controllers\api\v1\CourseController@matchForgetCode');
    Route::post('saveForgetCodePassword', 'App\Http\Controllers\api\v1\CourseController@saveForgetCodePassword');
    Route::get('faculty', 'App\Http\Controllers\api\v1\FacultyController@index');
    Route::get('myupdates', 'App\Http\Controllers\api\v1\StaliteController@myUpdates');
    Route::get('stories', 'App\Http\Controllers\api\v1\StoryController@index');
    Route::post('likestory', 'App\Http\Controllers\api\v1\StoryController@likeStory');
    Route::get('storyviewer/{id}', 'App\Http\Controllers\api\v1\StoryController@storyViewUser');
    Route::post('dislikestory', 'App\Http\Controllers\api\v1\StoryController@dislikeStory');
    Route::post('updatestorycount', 'App\Http\Controllers\api\v1\StoryController@updateStoryCount');
    Route::get('deleteStroy/{id}', 'App\Http\Controllers\api\v1\StoryController@destroy');
    Route::get('departmentalCourse/{id},{semester},{level}', 'App\Http\Controllers\api\v1\DepartmentController@departmentalCourse');
    Route::post('addToCart', 'App\Http\Controllers\api\v1\CartController@addToCart');
    Route::get('cartdelete/{courseCode}', 'App\Http\Controllers\api\v1\CartController@cartdelete');
    Route::get('emptyCart', 'App\Http\Controllers\api\v1\CartController@emptyCart');
     Route::get('chapters', 'App\Http\Controllers\api\v1\CourseController@chapters');
     Route::get('test/{id}', 'App\Http\Controllers\api\v1\CourseController@tests');
     Route::get('testApproval/{id}', 'App\Http\Controllers\api\v1\CourseController@testApproval');
     Route::get('testQuestion/{testId}', 'App\Http\Controllers\api\v1\CourseController@testIndex');
     Route::get('startTest/{questionId}', 'App\Http\Controllers\api\v1\CourseController@startTest');
     Route::post('result', 'App\Http\Controllers\api\v1\CourseController@testResult');

    Route::get('courseVideoChapter/{course_id}', 'App\Http\Controllers\api\v1\CourseController@courseVideoChapter');
    Route::get('courseQuestion', 'App\Http\Controllers\api\v1\QuestionController@courseQuestion');
     Route::get('courseVideo/{id}', 'App\Http\Controllers\api\v1\VideoController@courseVideo');
     Route::get('courseAudio/{id}', 'App\Http\Controllers\api\v1\VideoController@courseAudio');
     Route::post('/transactionVerify', 'App\Http\Controllers\api\v1\StalitepaymentController@transactionVerify');
     Route::get('subscriptionPlan', 'App\Http\Controllers\api\v1\SubscriptionController@staliteSubscription');
});
Route::middleware('auth:sanctum')->prefix('remedial')->group(function(){
    Route::get('registeredCourse', 'App\Http\Controllers\api\v1\RemedialController@registeredCourse');
    Route::get('liveEvent', 'App\Http\Controllers\api\v1\RemedialController@liveEvents');
    Route::get('registeredCourseVideo', 'App\Http\Controllers\api\v1\RemedialController@registeredCourseVideo');
    Route::post('transactionInit', 'App\Http\Controllers\api\v1\StalitepaymentController@transactionInit');
    Route::post('/transactionVerify', 'App\Http\Controllers\api\v1\StalitepaymentController@transactionVerify');
    Route::get('logout', 'App\Http\Controllers\api\v1\RemedialController@logout');
    Route::get('chapters', 'App\Http\Controllers\api\v1\CourseController@chapters');
    Route::get('storyviewer/{id}', 'App\Http\Controllers\api\v1\StoryController@storyViewUser');
    Route::get('myDetails', 'App\Http\Controllers\api\v1\RemedialController@myDetails');
    Route::get('myupdates', 'App\Http\Controllers\api\v1\RemedialController@myUpdates');

    Route::get('stories', 'App\Http\Controllers\api\v1\StoryController@index');
    Route::post('likestory', 'App\Http\Controllers\api\v1\StoryController@likeStory');
    Route::post('dislikestory', 'App\Http\Controllers\api\v1\StoryController@dislikeStory');
    Route::post('updatestorycount', 'App\Http\Controllers\api\v1\StoryController@updateStoryCount');
    Route::get('deleteStroy/{id}', 'App\Http\Controllers\api\v1\StoryController@destroy');
    Route::get('courseVideoChapter/{course_id}', 'App\Http\Controllers\api\v1\CourseController@courseVideoChapter');
    Route::get('courseQuestion', 'App\Http\Controllers\api\v1\QuestionController@courseQuestion');
    Route::get('courseVideo/{id}', 'App\Http\Controllers\api\v1\VideoController@courseVideo');
    Route::get('courseAudio/{id}', 'App\Http\Controllers\api\v1\VideoController@courseAudio');
    Route::get('subscriptionPlan', 'App\Http\Controllers\api\v1\SubscriptionController@remedialSubscription');
});
Route::middleware('auth:sanctum')->prefix('aspirant')->group(function(){
    Route::get('registeredCourse', 'App\Http\Controllers\api\v1\StaliteController@registeredCourse');
    Route::get('liveEvent', 'App\Http\Controllers\api\v1\AspirantController@liveEvents');
    Route::get('registeredCourseVideo', 'App\Http\Controllers\api\v1\AspirantController@registeredCourseVideo');
    Route::post('transactionInit', 'App\Http\Controllers\api\v1\StalitepaymentController@transactionInit');
    Route::post('/transactionVerify', 'App\Http\Controllers\api\v1\StalitepaymentController@transactionVerify');
    Route::get('stories', 'App\Http\Controllers\api\v1\StoryController@index');
    Route::get('storyviewer/{id}', 'App\Http\Controllers\api\v1\StoryController@storyViewUser');
    Route::post('likestory', 'App\Http\Controllers\api\v1\StoryController@likeStory');
    Route::post('dislikestory', 'App\Http\Controllers\api\v1\StoryController@dislikeStory');
    Route::post('updatestorycount', 'App\Http\Controllers\api\v1\StoryController@updateStoryCount');
    Route::get('deleteStroy/{id}', 'App\Http\Controllers\api\v1\StoryController@destroy');
    Route::get('logout', 'App\Http\Controllers\api\v1\AspirantController@logout');
    Route::get('chapters', 'App\Http\Controllers\api\v1\CourseController@chapters');
    Route::get('myDetails', 'App\Http\Controllers\api\v1\AspirantController@myDetails');
    Route::get('myupdates', 'App\Http\Controllers\api\v1\AspirantController@myUpdates');

    Route::get('courseVideoChapter/{course_id}', 'App\Http\Controllers\api\v1\CourseController@courseVideoChapter');
    Route::get('courseQuestion', 'App\Http\Controllers\api\v1\QuestionController@courseQuestion');
    Route::get('courseVideo/{id}', 'App\Http\Controllers\api\v1\VideoController@courseVideo');
    Route::get('courseAudio/{id}', 'App\Http\Controllers\api\v1\VideoController@courseAudio');
Route::get('subscriptionPlan', 'App\Http\Controllers\api\v1\SubscriptionController@aspirantSubscription');
});


// lecturers routes

Route::middleware('auth:sanctum')->prefix('lecturer')->group(
    function(){
     Route::get('/dashboard', 'App\Http\Controllers\web\director\DashboardController@index');
     Route::get('course', 'App\Http\Controllers\web\director\CourseController@index');
     Route::get('departmentalCourse', 'App\Http\Controllers\web\director\CourseController@departmentalCourses');
     Route::get('myNotification', 'App\Http\Controllers\web\director\CourseController@myNotification');
     Route::post('sendNotification', 'App\Http\Controllers\web\director\CourseController@sendNotification');
     Route::get('departmentalLecturers', 'App\Http\Controllers\web\director\CourseController@departmentalLecturers');
     Route::get('courseLecturers/{course_id}', 'App\Http\Controllers\web\director\CourseController@courseLecturers');
     Route::get('delete-course/{course_id}', 'App\Http\Controllers\web\director\CourseController@deleteCourse');
     Route::post('save-course', 'App\Http\Controllers\web\director\CourseController@saveCourse');
     Route::post('courseManagement', 'App\Http\Controllers\web\director\CourseController@courseManagement');
         //chapter routes
     Route::get('chapter/{course_id},{tutor_role}', 'App\Http\Controllers\web\director\ChapterController@index');
     Route::post('isChapterPublished', 'App\Http\Controllers\web\director\ChapterController@isChapterPublished');
     Route::post('saveChapter', 'App\Http\Controllers\web\director\ChapterController@saveChapter');
     Route::get('deleteChapter/{chapter_id}', 'App\Http\Controllers\web\director\ChapterController@deleteChapter');
     Route::post('chapterManagement', 'App\Http\Controllers\web\director\ChapterController@chapterManagement');

     //    Test routes
     Route::post('testApproval', 'App\Http\Controllers\web\director\ChapterController@testApproval');
     Route::get('testChapter/{course_id}', 'App\Http\Controllers\web\director\ChapterController@tests');
     Route::get('myDetails', 'App\Http\Controllers\web\director\ChapterController@myDetails');
     Route::post('saveTest', 'App\Http\Controllers\web\director\ChapterController@saveTest');
     Route::post('isTestChapterPublished', 'App\Http\Controllers\web\director\ChapterController@isTestChapterPublished');
     Route::post('isTestStarted', 'App\Http\Controllers\web\director\ChapterController@isTestStarted');
     Route::get('deleteTest/{test_id}', 'App\Http\Controllers\web\director\ChapterController@deleteTest');
     Route::get('registeredTestStudent/{test_id}', 'App\Http\Controllers\web\director\ChapterController@registeredTestStudent');
     Route::get('result/{test_id}', 'App\Http\Controllers\web\director\ChapterController@testResults');

    //      Question routes
    Route::get('question/{questionId}', 'App\Http\Controllers\web\director\QuestionController@index');
     Route::post('saveQuestion', 'App\Http\Controllers\web\director\QuestionController@saveQuestion');
     Route::post('saveTestQuestion', 'App\Http\Controllers\web\director\QuestionController@saveTestQuestion');
     Route::post('isQuestionPublished', 'App\Http\Controllers\web\director\QuestionController@isQuestionPublished');
     Route::get('deleteQuestion/{questionId}', 'App\Http\Controllers\web\director\QuestionController@deleteQuestion');
    Route::get('testQuestion/{questionId}', 'App\Http\Controllers\web\director\QuestionController@testIndex');
    Route::post('uploadQuestion', 'App\Http\Controllers\web\director\QuestionController@uploadQuestion');
     Route::post('uploadTestQuestion', 'App\Http\Controllers\web\director\QuestionController@uploadTestQuestion');
     Route::post('isTestQuestionPublished', 'App\Http\Controllers\web\director\QuestionController@isTestQuestionPublished');
     Route::get('deleteTestQuestion/{questionId}', 'App\Http\Controllers\web\director\QuestionController@deleteTestQuestion');

     //      Video and Audio routes
     Route::get('video/{videoId}', 'App\Http\Controllers\web\director\VideoController@index');
     Route::post('saveVideo', 'App\Http\Controllers\web\director\VideoController@saveVideo');
     Route::get('deleteVideo/{videoId}', 'App\Http\Controllers\web\director\VideoController@deleteVideo');
     Route::post('uploadVideo', 'App\Http\Controllers\web\director\VideoController@uploadVideo');
     Route::get('audio/{audioId}', 'App\Http\Controllers\web\director\VideoController@audioIndex');
     Route::post('saveAudio', 'App\Http\Controllers\web\director\VideoController@saveAudio');
     Route::get('deleteAudio/{audioId}', 'App\Http\Controllers\web\director\VideoController@deleteAudio');
     Route::post('uploadAudio', 'App\Http\Controllers\web\director\VideoController@uploadAudio');



    });
