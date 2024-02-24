<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/test', 'HomeController@index')->name('test');

// Route::get('logout', function () {
//     Auth::logout();

//     return redirect('/login');

// });

// Auth::routes();
// Route::get('logout', 'auth\LoginController@logout ');


//Auth::routes();
// Route::get('/register', 'auth\RegisterController@index');

Route::group(['middleware' => ['role:Super-Admin']], function () {

// Admin Routes

Route::get('/dashboard', 'web\DashboardController@index')->name('home');
Route::get('/studentlist', 'web\DashboardController@studentlist')->name('studentlist');
Route::get('/stafflist', 'web\DashboardController@stafflist')->name('stafflist');
Route::get('/studentprofile', 'web\DashboardController@studentprofile')->name('studentprofile');
Route::get('/staff-form', 'web\DashboardController@getStaffForm')->name('adminLte.staffForm');
Route::get('/staff-form/{user_id}', 'web\DashboardController@getStaffForm');
Route::get('/save-staff', 'web\DashboardController@saveStaff')->name('adminLte.saveStaff');

Route::get('/program', 'web\ProgramController@index')->name('adminLte.program');
Route::get('/program-form', 'web\ProgramController@getForm')->name('adminLte.programForm');
Route::get('/program-form/{program_id}', 'web\ProgramController@getForm');
Route::post('/save-program', 'web\ProgramController@saveprogram')->name('adminLte.saveProgram');
Route::get('/delete-program/{program_id}', 'web\ProgramController@deleteprogram');



Route::get('/course', 'web\CourseController@index')->name('adminLte.course');
Route::get('/course-form', 'web\CourseController@getForm')->name('adminLte.courseForm');
Route::get('/course-form/{course_id}', 'web\CourseController@getForm');
Route::get('/course-form1/{course_id}', 'web\CourseController@getForm1');
Route::post('/save-course', 'web\CourseController@saveCourse')->name('adminLte.saveCourse');
Route::post('/save-courseManagement', 'web\CourseController@courseManagement')->name('adminLte.courseManagement');
Route::get('/delete-course/{course_id}', 'web\CourseController@deleteCourse');


Route::get('/school', 'web\SchoolController@index')->name('adminLte.school');
Route::get('/school-form', 'web\SchoolController@getForm')->name('adminLte.schoolForm');
Route::get('/school-form/{school_id}', 'web\SchoolController@getForm');
Route::post('/save-school', 'web\SchoolController@saveschool')->name('adminLte.saveSchool');
Route::get('/delete-school/{school_id}', 'web\SchoolController@deleteschool');


Route::get('/department', 'web\DepartmentController@index')->name('adminLte.department');
Route::get('/department-form', 'web\DepartmentController@getForm')->name('adminLte.departmentForm');
Route::get('/department-form/{department_id}', 'web\DepartmentController@getForm');
Route::post('/save-department', 'web\DepartmentController@savedepartment')->name('adminLte.saveDepartment');
Route::get('/delete-department/{department_id}', 'web\DepartmentController@deletedepartment');


Route::get('/review', 'web\ReviewbController@index')->name('adminLte.review');


Route::get('/faculty', 'web\FacultyController@index')->name('adminLte.faculty');
Route::get('/faculty-form', 'web\FacultyController@getForm')->name('adminLte.facultyForm');
Route::get('/faculty-form/{faculty_id}', 'web\FacultyController@getForm');
Route::post('/save-faculty', 'web\FacultyController@savefaculty')->name('adminLte.saveFaculty');
Route::get('/delete-faculty/{faculty_id}', 'web\FacultyController@deletefaculty');



Route::get('/subscriptionplan', 'web\SubscrbController@index')->name('adminLte.subscriptionplan');
Route::get('/subscriptionplan-form', 'web\SubscrbController@getForm')->name('adminLte.subscriptionplanForm');
Route::get('/subscriptionplan-form/{subscriptionplan_id}', 'web\SubscrbController@getForm')->name('adminLte.subscriptionEdit');
Route::post('/save-subscriptionplan', 'web\SubscrbController@saveSubscriptionplan')->name('adminLte.saveSubScriptionplan');
Route::get('/delete-subscriptionplan/{subscriptionplan_id}', 'web\SubscrbController@deleteSubscriptionplan');


});

Route::group(['middleware' => ['role:Director-of-Remedial-Studies |Director-of-PostUtme |Director-of-Undergraduate-Studies']], function () {

// Routes For Directors
 // Faculty routes
Route::get('/director/faculty', 'web\director\FacultyController@index')->name('director.faculty');
Route::get('/director/faculty-form', 'web\director\FacultyController@getForm')->name('director.facultyForm');
Route::get('/dfaculty-form/{faculty_id}', 'web\director\FacultyController@getForm');
Route::post('/director/save-faculty', 'web\director\FacultyController@savefaculty')->name('director.saveFaculty');
Route::get('/director/delete-faculty/{faculty_id}', 'web\director\FacultyController@deletefaculty');

//Department Routes

Route::get('/director/department', 'web\director\DepartmentController@index')->name('director.department');
Route::get('/director/department-form', 'web\director\DepartmentController@getForm')->name('director.departmentForm');
Route::get('/director/department-form/{department_id}', 'web\director\DepartmentController@getForm');
Route::post('/director/save-department', 'web\director\DepartmentController@savedepartment')->name('director.saveDepartment');
Route::get('/director/delete-department/{department_id}', 'web\director\DepartmentController@deletedepartment');

// Course Route

Route::get('/lead_course', 'web\director\CourseController@leadCourse')->name('director.lcourse');
Route::post('/director/save-course', 'web\director\CourseController@saveCourse')->name('director.saveCourse');
Route::get('/ddelete-course/{course_id}', 'web\director\CourseController@deleteCourse');
Route::get('/lead_chapter/{course_id},{course_tutor}', 'web\director\CourseController@leadChapter')->name('director.lchapter');
Route::get('/director/course-form', 'web\director\CourseController@getForm')->name('director.courseForm');
Route::get('/dcourse-form/{course_id}', 'web\director\CourseController@getForm');
Route::get('/dcourse-form1/{course_id}', 'web\director\CourseController@getForm1');
Route::post('/director/save-courseManagement', 'web\director\CourseController@courseManagement')->name('director.courseManagement');


// Route for chapters
Route::get('/dchapter/{course_id}', 'web\director\ChapterController@index')->name('director.chapter');
Route::post('/director/save-chapter', 'web\director\ChapterController@saveChapter')->name('director.saveChapter');
Route::get('/director/delete-chapter/{chapter_id}', 'web\director\ChapterController@deleteChapter');
Route::get('/dchapter-form/{course_id},{chapter_id}', 'web\director\ChapterController@getForm')->name('director.chapterForm');
Route::get('/dchapter-form/{course_id}', 'web\director\ChapterController@getForm');
Route::get('/director/chapter-form1/{course_id},{chapter_id}', 'web\director\ChapterController@getForm1');
Route::post('/director/save-chapterManagement', 'web\director\ChapterController@chapterManagement')->name('director.chapterManagement');

//Route for Videos
Route::get('/dvideo/{chapter_id}', 'web\director\VideoController@index')->name('director.video');
//Route for questions
Route::get('/question/{course_id}', 'web\director\QuestionController@index')->name('director.question');
Route::get('/question/{course_id},{chapter_id}', 'web\director\QuestionController@index')->name('director.question');
Route::get('/director/question-form', 'web\director\QuestionController@getForm')->name('director.questionForm');
Route::get('/question-form/{question_id}', 'web\director\QuestionController@getForm');
Route::get('/question-form1/{course_id},{chapter_id}', 'web\director\QuestionController@getForm1');
Route::post('/director/save-question', 'web\director\QuestionController@saveQuestion')->name('director.saveQuestion');
Route::post('/upload-question', 'web\director\QuestionController@uploadQuestion')->name('director.uploadQuestion');
Route::get('/upload-courseImage/{course_id}', 'web\director\QuestionController@uploadCourseImage')->name('director.courseImage');
Route::get('/upload-chapterImage/{chapter_id}', 'web\director\QuestionController@uploadChapterImage')->name('director.chapterImage');
Route::post('/save-courseImage', 'web\director\QuestionController@saveCourseImage')->name('director.savecourseImage');
Route::post('/save-chapterImage', 'web\director\QuestionController@saveChapterImage')->name('director.savechapterImage');
Route::post('/upload-ChapterVideo', 'web\director\QuestionController@uploadChapterVideo')->name('director.chapterVideo');
Route::post('/upload-VideoThumbnail', 'web\director\QuestionController@uploadVideoThumbNail')->name('director.VideoThumbnail');
Route::get('/director/delete-question/{question_id}', 'web\director\QuestionController@deleteQuestion');

// Director's Dashboard Route

Route::get('/director/dashboard', 'web\director\DashboardController@index')->name('home');
Route::get('/director/studentlist', 'web\director\DashboardController@studentlist')->name('studentlist');
Route::get('/director/stafflist', 'web\director\DashboardController@stafflist')->name('stafflist');
Route::get('/director/studentprofile', 'web\director\DashboardController@studentprofile')->name('studentprofile');
Route::get('/director/staff-form', 'web\director\DashboardController@getStaffForm')->name('director.staffForm');
Route::get('/director/staff-form/{user_id}', 'web\director\DashboardController@getStaffForm');
Route::get('/director/save-staff', 'web\director\DashboardController@saveStaff')->name('director.saveStaff');

});



Route::group(['middleware' => ['role:Tutor|Director-of-Remedial-Studies |Director-of-PostUtme |Director-of-Undergraduate-Studies']], function () {


Route::get('/tutor/account', 'AccountDetailController@index')->name('account');
Route::post('/saveAccount', 'AccountDetailController@saveAccountDetail')->name('save.account');
// Tutor Routes
Route::get('/tutor/dashboard', 'web\tutor\DashboardController@index')->name('home');
Route::get('/tchapter/{course_id}', 'web\tutor\ChapterController@index')->name('tutor.chapter');
Route::get('/tquestion/{course_id}', 'web\tutor\QuestionController@index')->name('director.question');
Route::get('/tquestion/{course_id},{chapter_id}', 'web\tutor\QuestionController@index')->name('director.question');
Route::get('/tutor/question-form', 'web\tutor\QuestionController@getForm')->name('director.questionForm');
Route::get('/tquestion-form/{question_id}', 'web\tutor\QuestionController@getForm');
Route::get('/tquestion-form1/{course_id},{chapter_id}', 'web\tutor\QuestionController@getForm1');
Route::post('/tutor/save-question', 'web\tutor\QuestionController@saveQuestion')->name('director.saveQuestion');
Route::get('/tutor/delete-question/{question_id}', 'web\tutor\QuestionController@deleteQuestion');
Route::get('/video/{chapter_id}', 'web\tutor\VideoController@index')->name('tutor.video');
Route::get('/video-edit/{id}', 'web\tutor\VideoController@updateVideo')->name('tutor.videoUpdate');
Route::get('/tutor/video-form/{chapter_id}', 'web\tutor\VideoController@getForm')->name('tutor.videoForm');
Route::post('/tutor/save-video', 'web\tutor\VideoController@uploadVideo')->name('video.saveVideo');
Route::get('/tutor/delete-video/{id}', 'web\tutor\VideoController@deleteVideo');

});



Route::group(['middleware' => ['role:Media']], function () {

    Route::get('/live', 'web\LiveController@index')->name('media.live');
    Route::get('/live-form', 'web\LiveController@getForm')->name('media.liveForm');
    Route::get('/live-form/{live_id}', 'web\LiveController@getForm');
    Route::post('/save-live', 'web\LiveController@saveLiveEvent')->name('media.saveLive');
    Route::get('/delete-live/{live_id}', 'web\LiveController@deleteliveEvent');
});
