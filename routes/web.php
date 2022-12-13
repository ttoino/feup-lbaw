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
Route::prefix('/user')->name('user.')->controller('UserController')->group(function () {
    Route::prefix('/{user}')->group(function () {
        Route::get('', 'show')->name('profile');

        Route::prefix('/edit')->group(function () {
            Route::get('', 'showProfileEditPage')->name('edit');
            Route::post('', 'edit');
        });
    });
});

// Project 
Route::prefix('/project')->middleware('auth')->name('project')->controller('ProjectController')->group(function () {
    Route::get('', 'listUserProjects')->name('.list');

    Route::get('/new', 'showProjectCreationPage')->name('.new');
    Route::post('/new', 'createProject')->name('.new-action');

    // Project Search
    Route::get('/search', 'search')->name('.search');

    Route::prefix('/{project}')->middleware('withOtherProjects')->group(function () {

        Route::redirect('', "/project/{project}/board")->name('');

        Route::get('/info', 'showProjectInfo')->name('.info');
        Route::get('/board', 'showProject')->name('.board');
        Route::get('/timeline', 'showProjectTimeline')->name('.timeline');
        Route::get('/forum', 'showProjectForum')->name('.forum');

        Route::post('/leave', 'leaveProject')->name('.leave');
        Route::post('/delete', 'delete')->name('.delete');

        Route::prefix('/task')->name('.task')->controller('TaskController')->group(function () {
            Route::post('/new', 'createTask')->name('.new');

            Route::get('search', 'search')->name('.search');

            Route::prefix('/{task}')->where(['task', '[0-9]+'])->group(function () {
                Route::get('', 'show')->name('.info');
                Route::post('', 'edit')->name('.edit');
            });
        });

        Route::prefix('/thread')->name('.thread')->controller('ThreadController')->group(function () {
            Route::prefix('/{thread}')->group(function () {
                Route::get('', 'show')->name('');

            });
        });

        Route::prefix('/task-group')->name('.task-group')->controller('TaskGroupController')->group(function () {
            Route::post('new', 'createTaskGroup')->name('.new');
        });

        Route::prefix('/add')->name('.user')->controller('ProjectController')->group(function () {
            // FIXME: this is a separate page to demonstrate functionality in the prototype and will be changed later
            Route::get('', 'showAddUserPage')->name('.add');
            Route::post('', 'addUser');
        });
    });
});

// Admin
Route::prefix('/admin')->middleware(['auth', 'isAdmin'])->name('admin')->controller('AdminController')->group(function () {
    Route::redirect('', '/admin/users')->name('');
    Route::get('/users', 'listUsers')->name('.users');
    Route::get('/projects', 'listProjects')->name('.projects');
    Route::prefix('/create')->name('.create.')->group(function () {
        Route::get('/user', 'showCreateUser')->name('user');
        Route::post('/user', 'createUser');
    });
    Route::prefix('/reports')->name('.reports.')->group(function () {
        Route::get('/user/{user}', 'showUserReports')->name('user');
        Route::get('/project/{project}', 'showProjectReports')->name('project');
    });
});

Route::get('/dump', 'DebugController@dump');

// Authentication
Route::name('')->group(function () {
    Route::controller('Auth\LoginController')->group(function () {
        Route::get('/login', 'showLoginForm')->name('login');
        Route::post('/login', 'login');
        Route::get('/logout', 'logout')->name('logout');
    });
    Route::controller('Auth\RegisterController')->group(function () {
        Route::get('/register', 'showRegistrationForm')->name('register');
        Route::post('/register', 'register');
    });
});

Route::prefix('/api')->name('api')->group(function () {

    Route::prefix('/project')->name('.project')->controller('ProjectController')->group(function() {
        Route::prefix('/{project}')->group(function () {
            Route::delete('', 'delete')->name('.delete');

            Route::prefix('/favorite')->name('.favorite')->group(function () {
                Route::post('/toggle', 'toggleFavorite')->name('.toggle');
            });
        });
    });

    Route::prefix('/user')->name('.user')->controller('UserController')->group(function () {
        Route::prefix('/{user}')->group(function () {
            Route::delete('', 'delete')->name('.delete');
        });
    });

    Route::prefix('/task')->name('.task')->controller('TaskController')->group(function () {
        Route::prefix('/{task}')->group(function () {
            Route::put('/complete', 'complete')->name('.complete');
            Route::post('/reposition', 'repositionTask')->name('.reposition');
        });
    });
    
    Route::prefix('/task-group')->name('.task-group')->controller('TaskGroupController')->group(function () {
        Route::prefix('/{taskGroup}')->group(function () {
            Route::post('/reposition', 'repositionTaskGroup')->name('.reposition');
        });
    });
});