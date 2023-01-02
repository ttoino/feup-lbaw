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
    ->whereIn('name', StaticController::STATIC_PAGES)->name('static');

// User
Route::prefix('/user')->middleware('auth')->name('user.')->controller('UserController')->group(function () {
    Route::prefix('/{user}')->where(['user', '[0-9]+'])->group(function () {
        Route::get('', 'show')->name('profile');

        Route::prefix('/edit')->group(function () {
            Route::get('', 'edit')->name('edit');
            Route::post('', 'update')->name('edit-action');
        });
    });
});

Route::get('/notifications', 'UserController@showNotifications')->name('notifications');

// Project 
Route::prefix('/project')->middleware('auth')->name('project')->controller('ProjectController')->group(function () {
    Route::get('', 'index')->name('.list');

    Route::prefix('/new')->group(function () {
        Route::get('', 'create')->name('.new');
        Route::post('', 'store')->name('.new-action');
    });

    // Project Search
    Route::get('/search', 'search')->name('.search');

    Route::prefix('/{project}')->where(['project', '[0-9]+'])->middleware('withOtherProjects')->group(function () {

        Route::redirect('', "/project/{project}/board")->name('');

        Route::get('/info', 'showProjectInfo')->name('.info');
        Route::get('/board', 'showProjectBoard')->name('.board');
        Route::get('/timeline', 'showProjectTimeline')->name('.timeline');
        Route::get('/forum', 'showProjectForum')->name('.forum');

        // This breaks the HTTP standard since a GET request is changing server state (a project's members). However this should only be changed if this application scales
        Route::get('/join', 'joinProject')->name('.join')->middleware('signed');
        Route::post('/leave', 'leaveProject')->name('.leave');
        
        Route::post('/delete', 'destroy')->name('.delete');
        
        Route::post('/archive', 'archive')->name('.archive');
        Route::post('/unarchive', 'unarchive')->name('.unarchive');

        Route::prefix('/task')->name('.task')->controller('TaskController')->group(function () {
            Route::post('/new', 'store')->name('.new');

            Route::get('search', 'search')->name('.search');

            Route::prefix('/{task}')->where(['task', '[0-9]+'])->scopeBindings()->group(function () {
                Route::get('', 'show')->name('.info');
                Route::post('', 'update')->name('.edit');

                Route::post('/comment', 'createComment')->name('.comment');
            });
        });

        Route::prefix('/thread')->name('.thread')->controller('ThreadController')->group(function () {

            Route::prefix('/new')->group(function () {
                Route::get('', 'create')->name('.new');
                Route::post('', 'store')->name('.new-action');
            });

            Route::prefix('/{thread}')->where(['thread', '[0-9]+'])->scopeBindings()->group(function () {
                Route::get('', 'show')->name('');
            });
        });

        Route::prefix('/task-group')->name('.task-group')->controller('TaskGroupController')->group(function () {
            Route::post('new', 'store')->name('.new');
        });

        Route::prefix('/invite')->name('.user')->controller('ProjectController')->group(function () {
            Route::get('', 'showInviteUserPage')->name('.invite');
            Route::post('', 'inviteUser')->name('.invite-action');
        });
    });
});

// Admin
Route::prefix('/admin')->middleware(['auth', 'isAdmin'])->name('admin')->controller('AdminController')->group(function () {
    Route::redirect('', '/admin/users')->name('');
    Route::get('/users', 'listUsers')->name('.users');
    Route::get('/projects', 'listProjects')->name('.projects');
    Route::prefix('/create')->name('.create')->group(function () {
        Route::get('/user', 'showCreateUser')->name('.user');
        Route::post('/user', 'createUser')->name('.user-action');
    });
    Route::prefix('/reports')->name('.reports')->group(function () {
        Route::get('/user/{user}', 'showUserReports')->name('.user');
        Route::get('/project/{project}', 'showProjectReports')->name('.project');
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

Route::prefix('/api')->name('api')->middleware('throttle')->group(function () {

    Route::prefix('/project')->name('.project')->controller('ProjectController')->group(function () {

        Route::post('', 'store')->name('.new');

        Route::prefix('/{project}')->where(['project', '[0-9]+'])->group(function () {
            Route::delete('', 'destroy')->name('.delete');
            Route::get('', 'show')->name('');

            Route::put('', 'update')->name('.update');

            Route::prefix('/members')->name('.members')->group(function () {
                Route::get('', 'getProjectMembers')->name('');
                // Route::post('', 'addProjectMember')->name('.add');
            });

            Route::prefix('tags')->name('.tags')->group(function () {
                Route::get('', 'getProjectTags');
            });

            Route::prefix('/favorite')->name('.favorite')->group(function () {
                Route::post('/toggle', 'toggleFavorite')->name('.toggle');
            });

            Route::prefix('/remove')->name('.remove')->group(function () {
                Route::post('/{user}', 'removeUser')->name('.user');
            });
        });
    });

    Route::prefix('/user')->name('.user')->controller('UserController')->group(function () {
        
        Route::post('', 'store')->name('.new');      

        Route::prefix('/{user}')->where(['user', '[0-9]+'])->group(function () {
            Route::delete('', 'destroy')->name('.delete');

            Route::put('', 'update')->name('.update');

            Route::get('', 'show')->name('');
        });
    });

    Route::prefix('/task')->name('.task')->controller('TaskController')->group(function () {

        Route::post('/new', 'store')->name('.new');

        Route::prefix('/{task}')->where(['task', '[0-9]+'])->group(function () {

            // this needs to be a separate function since this won't be wrapped in a project route group
            Route::get('', 'show')->name('');
            Route::put('', 'update')->name('update');
            Route::delete('', 'destroy')->name('.delete');

            Route::put('/complete', 'complete')->name('.complete');
            Route::post('/reposition', 'update')->name('.reposition');
        });
    });

    Route::prefix('/task-comment')->name('.task-comment')->controller('TaskCommentController')->group(function () {

        Route::post('/new', 'store')->name('.new');
        
        Route::prefix('/{taskComment}')->where(['taskComment', '[0-9]+'])->group(function () {

            Route::get('', 'show')->name('');
            Route::put('', 'update')->name('.update');
            Route::delete('', 'destroy')->name('.delete');
        });
    });

    Route::prefix('/task-group')->name('.task-group')->controller('TaskGroupController')->group(function () {
        
        Route::post('/new', 'store')->name('.new');
        
        Route::prefix('/{taskGroup}')->where(['taskGroup', '[0-9]+'])->group(function () {

            Route::get('', 'show')->name('');
            Route::put('', 'update')->name('.update');
            Route::delete('', 'destroy')->name('.delete');

            Route::post('/reposition', 'update')->name('.reposition');
        });
    });

    Route::prefix('/thread')->name('.thread')->controller('ThreadController')->group(function () {

        Route::post('/new', 'store')->name('.new');
        
        Route::prefix('/{thread}')->where(['thread', '[0-9]+'])->group(function () {

            Route::get('', 'show')->name('');
            Route::put('', 'update')->name('.update');
            Route::delete('', 'destroy')->name('.delete');
        });
    });

    Route::prefix('/thread-comment')->name('.thread-comment')->controller('ThreadCommentController')->group(function () {

        Route::post('/new', 'store')->name('.new');
        
        Route::prefix('/{threadComment}')->where(['threadComment', '[0-9]+'])->group(function () {

            Route::get('', 'show')->name('');
            Route::put('', 'update')->name('.update');
            Route::delete('', 'destroy')->name('.delete');
        });
    });
});
