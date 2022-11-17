<?php

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
// Home
Route::get('/', function () {
    return view('layouts/app');
});

// Static

Route::get('about', function () {
    return view('static/about');
});

Route::get('faq', function () {
    return view('static/faq');
});

Route::get('contacts', function () {
    return view('static/contacts');
});

Route::get('services', function () {
    return view('static/services');
});


// User
Route::get('user/{id}', function ($id){
    return view('layouts/user', ['id' => $id]);
})->where('id', '[0-9]+');

Route::get('user/{id}/edit', function ($id){
    return view('layouts/user_edit', ['id' => $id]);
})->where('id', '[0-9]+');


// Project 
Route::get('project/{id}', function ($id){
    return view('layouts/project', ['id' => $id]);
})->where('id', '[0-9]+');

Route::get('project/{id}/info', function ($id){
    return view('layouts/project_info', ['id' => $id]);
})->where('id', '[0-9]+');

Route::get('project/{id}/forum', function ($id){
    return view('layouts/project_forum', ['id' => $id]);
})->where('id', '[0-9]+');

Route::get('project/{id}/forum/{threadId}', function ($id, $thread_id){
    return response('Thread' . $thread_id); // placeholder
})->where(['id', '[0-9]+'], ['thread', '[0-9]+']);

Route::get('project/{id}/task/{taskId}', function ($id, $task_id){
    return response('Task' . $task_id);  // placeholder
})->where(['id', '[0-9]+'], ['task', '[0-9]+']);



// Search 
Route::get('search', function (Request $request){
    return view('layouts/search', ['q' => $request->q, 'limit' => $request->limit]);
});

// Authentication
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');