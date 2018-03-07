<?php

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

Route::post('/login', function () {
	return response()->json([
		'token'	=> uniqid(),
		'id'	=> 1,
		'name'	=> 'John Doe',
		'status'=> true
	])->header('Access-Control-Allow-Origin', '*');
});