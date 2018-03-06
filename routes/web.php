<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Auth::routes();
Route::get('/logout', 'Auth\LoginController@logout');
Route::get('/', 'HomeController@index');

Route::get('/announcements', 'SubjectController@index');

Route::group(['prefix' => "quiz"], function() {
	Route::get('/', [
		'as'	=> 'quiz.index',
		'uses'	=> 'QuizController@index'
	]);

	Route::get('/{quiz_id}/items', [
		'as'	=> 'quiz.items.create',
		'uses'	=> 'QuizItemController@create'
	]);

	Route::post('/{quiz_id}/items', [
		'as'	=> 'quiz.items.save',
		'uses'	=> 'QuizItemController@save'
	]);

	Route::get('/create', [
		'as'	=> 'quiz.create',
		'uses'	=> 'QuizController@create'
	]);

	Route::post('/create', [
		'as'	=> 'quiz.save',
		'uses'	=> 'QuizController@save'
	]);
});

Route::group(['prefix' => 'attendance'], function () {

	Route::get('/{attendance_id}', [
		'as'	=> 'attendance.index',
		'uses'	=> 'AttendanceController@show'
	]);
});

Route::group(['prefix' => "subjects"], function () {
	Route::get('/', [
		'as'	=> 'subject.index',
		'uses'	=> 'SubjectController@index'
	]);

	Route::get('/{subject_id}/students', [
		'as'	=> 'subject.students',
		'uses'	=> 'SubjectStudentController@index'
	]);

	Route::get('/{subject_id}/students/attendance', [
		'as'	=> 'subject.students.attendance.index',
		'uses'	=> 'AttendanceController@index'
	]);

	Route::get('/{subject_id}/students/attendance/create', [
		'as'	=> 'subject.students.attendance.create',
		'uses'	=> 'AttendanceController@create'
	]);

	Route::post('/{subject_id}/students/attendance/create', [
		'as'	=> 'subject.students.attendance.save',
		'uses'	=> 'AttendanceController@save'
	]);

	Route::get('/create', [
		'as'	=> 'subject.create',
		'uses'	=> 'SubjectController@create'
	]);

	Route::post('/create', [
		'as'	=> 'subject.save',
		'uses'	=> 'SubjectController@save'
	]);
});
