<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('index');
});

Route::get('/login', 'Auth\LoginController@Index')->name('login');
Route::post('/login', 'Auth\LoginController@LogIn');
Route::get('/register', 'Auth\RegisterController@Index')->name('reigster');
Route::post('/register', 'Auth\RegisterController@Register');
Route::get('/logout', 'Auth\LoginController@LogOut')->name('logout');
Route::get('/forgot', 'Auth\ForgotController@Index')->name('forgot');

Route::middleware(['auth', 'role'])->group(function(){
    // Admin page
    Route::get('/admin', 'Admin\DashboardController@Index')->name('admin');
    // Category
    Route::resource('/admin/category', 'Admin\SCategoryController');
    // Question
    Route::post('/admin/question/upload-attached', 'Admin\SQuestionController@uploadAttached');
    Route::resource('/admin/question', 'Admin\SQuestionController');
    
    // Auth::routes();
    Route::get('/select-category', 'CategoryController@Index')->name('select-category');
    Route::get('/ask-subject/{id?}', 'SubjectController@Index')->name('ask-subject');
    Route::get('/solution-subject/{id?}', 'SubjectController@SolutionSubject')->name('solution-subject');
    Route::get('/institution/{id?}', 'SimulateController@Institution')->name('institution');
    Route::get('/question', 'QuestionController@Index')->name('question');
    Route::get('/question-answerlist/{id?}', 'QuestionController@QuestionAnswerList');
    Route::get('/show-detail-answer/{id?}', 'QuestionController@GetAnswersList');
    Route::get('/alert-show-answer/{id?}', 'AnswerController@EachAnswerShow');

    Route::get('/question-post/{id?}', 'QuestionController@PostQuestion')->name('question-post');
    Route::post('/upload-question-file', 'QuestionController@UploadFile');
    Route::post('/question-upload', 'QuestionController@UploadQuestion');
    Route::get('/answers/{id?}', 'AnswerController@Index')->name('answers');
    Route::get('/allquestions', 'QuestionController@AllQuestions')->name('allquestions');
    Route::get('/totalquestions', 'QuestionController@TotalQuestions')->name('totalquestions');
    Route::get('/show-answers/{id?}','AnswerController@ShowAnswer');
    Route::post('/answer-readed', 'AnswerController@AnswerState');
    Route::get('/solution/{id?}', 'AnswerController@ReplyAnswers');
    Route::post('/upload-answers-file', 'AnswerController@UploadFile');
    Route::post('/reply-answer', 'AnswerController@ReplyAnswer');
    Route::post('/send-answer', 'AnswerController@SendAnswer');
    Route::post('/detail-answer', 'AnswerController@DetailAnswer');
    Route::post('/answerslist', 'AnswerController@AnswersList');
    Route::post('/remove-answers', 'AnswerController@RemoveAnswer');
    Route::post('/remove-question', 'QuestionController@RemoveQuestion');

    Route::get('/account-setting', 'Auth\RegisterController@AccountSetting')->name('account-setting');
    Route::post('/change-userinfo', 'Auth\RegisterController@ChangeInfo');

    // modified
    // simulate
    Route::get('/simulate', 'SimulateController@simulate')->name('simulate');
    Route::get('/simulate/category/{id}/questions', 'SimulateController@questions');
    Route::get('/simulate/category/{sc_id}/question/{id}', 'SimulateController@answers');
    Route::post('/simulate/category/question', 'SimulateController@answered');
});




