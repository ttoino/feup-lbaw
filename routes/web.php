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
Route::get('/', fn() => Auth::check()
    ? app()->make(\App\Http\Controllers\ProjectController::class)->listUserProjects()
    : view('pages.home'))->name('home');

// Static
Route::get('{name}', 'StaticController@show')
    ->whereIn('name', ['about', 'contacts', 'faq', 'services']);

// User
Route::prefix('user/')->name('user.')->controller('UserController')->group(function () {
    Route::prefix('{id}/')->group(function () {
        Route::get('', 'show')->name('profile');
        /* Route::get('/edit', function ($id){
        return view('layouts/user_edit', ['id' => $id]);
        }); */
    }
    );
});

// Project 
Route::prefix('project/')->name('project.')->controller('ProjectController')->group(function () {
    Route::get('new', 'showProjectCreationPage')->name('new');
    Route::post('new', 'createProject');

    Route::prefix('{id}/')->group(function () {
        Route::get('', 'showProjectByID')->name('home');

        Route::prefix('task/')->name('task.')->controller('TaskController')->group(function () {
            Route::post('new', 'createTask');

            Route::prefix('{taskId}/')->where(['taskId', '[0-9]+'])->group(function () {
                Route::get('', 'show')->name('info');
            });

            // Route::get('', 'search')->name('search');
        });
    }
    );
});
/*
Route::get('project/{id}/info', function ($id){
return view('layouts/project_info', ['id' => $id]);
});
Route::get('project/{id}/forum', function ($id){
return view('layouts/project_forum', ['id' => $id]);
});
Route::get('project/{id}/forum/{threadId}', function ($id, $thread_id){
    return response('Thread' . $thread_id); // placeholder
})->where(['id', '[0-9]+'], ['thread', '[0-9]+']);
*/

/*
// Search 
Route::get('search', function (Request $request){
return view('layouts/search', ['q' => $request->q, 'limit' => $request->limit]);
});
*/

Route::get('dump', 'DebugController@dump');

// Authentication
Route::controller('Auth\LoginController')->group(function () {
    Route::get('login', 'showLoginForm')->name('login');
    Route::post('login', 'login');
    Route::get('logout', 'logout')->name('logout');
});

Route::controller('Auth\RegisterController')->group(function () {
    Route::get('register', 'showRegistrationForm')->name('register');
    Route::post('register', 'register');
});