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

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaticController;

Route::get('', 'HomeController@show')->name('home');

// Static
Route::get('{name}', 'StaticController@show')
    ->whereIn('name', StaticController::STATIC_PAGES);

// User
Route::prefix('user/')->name('user.')->controller('UserController')->group(function () {
    Route::prefix('{id}/')->group(function () {
        Route::get('', 'show')->name('profile');

        Route::prefix('/edit')->group(function () {
            Route::get('', 'showProfileEditPage')->name('edit');
            Route::post('', 'edit');
        });
    });
});

// Project 
Route::prefix('project')->name('project')->controller('ProjectController')->group(function () {
    Route::get('', 'listUserProjects')->name('.list');

    Route::get('new', 'showProjectCreationPage')->name('.new');
    Route::post('new', 'createProject');

    Route::prefix('{id}/')->group(function () {
        Route::redirect('', "/project/{id}/board")->name('');

        Route::get('info', 'showProjectByID')->name('.info');
        Route::get('board', 'showProjectByID')->name('.board');
        Route::get('timeline', 'showProjectByID')->name('.timeline');
        Route::get('forum', 'showProjectByID')->name('.forum');

        Route::prefix('task/')->name('.task.')->controller('TaskController')->group(function () {
            Route::post('new', 'createTask');

            Route::get('', 'search')->name('search');

            Route::prefix('{taskId}/')->where(['taskId', '[0-9]+'])->group(function () {
                Route::get('', 'show')->name('info');
                Route::post('', 'edit');
            });
        });

        Route::prefix('task-group/')->name('.task-group.')->controller('TaskGroupController')->group(function () {
            Route::post('new', 'createTaskGroup');
        });
    });
});

// Project Search
// due to naming collisions this was taken out of the Project router
// TODO: see if this is fixable (better name/route, etc...)
Route::get('/search', 'ProjectController@search')->name('project.search');

// Admin
Route::prefix('admin')->name('admin')->controller('AdminController')->group(function () {
    Route::redirect('', '/admin/users')->name('');
    Route::get('users', 'listUsers')->name('.users');
    Route::get('projects', 'listProjects')->name('.projects');
});

Route::get('dump', 'DebugController@dump');

// Authentication
Route::name('')->group(function () {
    Route::controller('Auth\LoginController')->group(function () {
        Route::get('login', 'showLoginForm')->name('login');
        Route::post('login', 'login');
        Route::get('logout', 'logout')->name('logout');
    });
    Route::controller('Auth\RegisterController')->group(function () {
        Route::get('register', 'showRegistrationForm')->name('register');
        Route::post('register', 'register');
    });
});

Route::prefix('/api')->name('api')->group(function () {

    Route::prefix('/project')->name('.project')->controller('ProjectController')->group(function() {
        Route::prefix('/{id}')->group(function () {
            Route::delete('', 'delete')->name('.delete');
        });
    });

    Route::prefix('/task')->name('task')->controller('TaskController')->group(function () {
        Route::prefix('/{id}')->group(function () {
            Route::put('/complete', 'complete')->name('complete');
        });
    });
});