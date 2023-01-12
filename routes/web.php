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
use App\Enums\ProviderType;

Route::get('', 'HomeController@show')->name('home');

// Static
Route::get('{name}', 'StaticController@show')
    ->whereIn('name', StaticController::STATIC_PAGES)->name('static');

// User
Route::prefix('/user')->middleware(['auth', 'verified'])->name('user.')->controller('UserController')->group(function () {
    Route::prefix('/{user}')->where(['user', '[0-9]+'])->group(function () {
        Route::get('', 'show')->name('profile');

        Route::prefix('/edit')->group(function () {
            Route::get('', 'edit')->name('edit');
            Route::post('', 'update')->name('edit-action');
        });

        Route::prefix('/report')->group(function () {
            Route::get('', 'showReportForm')->name('report');
            Route::post('', 'report')->name('report-action');
        });
    });
});

Route::get('/notifications', 'UserController@showNotifications')->middleware(['auth', 'verified'])->name('notifications');

// Project 
Route::prefix('/project')->middleware(['auth', 'verified'])->name('project')->controller('ProjectController')->group(function () {
    Route::get('', 'index')->name('.list');

    Route::prefix('/new')->group(function () {
        Route::get('', 'create')->name('.new');
        Route::post('', 'store')->name('.new-action');
    });

    Route::prefix('/{project}')->where(['project', '[0-9]+'])->middleware('withOtherProjects')->group(function () {

        // Report project
        Route::prefix('/report')->group(function () {
            Route::get('', 'showReportForm')->name('.report');
            Route::post('', 'report')->name('.report-action');
        });

        Route::redirect('', "/project/{project}/board")->name('');

        Route::get('/info', 'showProjectInfo')->name('.info');
        Route::get('/members', 'getProjectMembers')->name('.members');
        Route::get('/tags', 'getProjectTags')->name('.tags');
        Route::get('/board', 'showProjectBoard')->name('.board');
        Route::get('/tasks', 'getProjectTasks')->name('.tasks');
        Route::get('/timeline', 'showProjectTimeline')->name('.timeline');
        Route::get('/forum', 'showProjectForum')->name('.forum');

        // This breaks the HTTP standard since a GET request is changing server state (a project's members). However this should only be changed if this application scales
        Route::get('/join', 'joinProject')->name('.join')->middleware('signed');
        
        Route::post('/delete', 'destroy')->name('.delete');

        Route::prefix('/task')->name('.task')->controller('TaskController')->group(function () {
            Route::prefix('/{task}')->where(['task', '[0-9]+'])->scopeBindings()->group(function () {
                Route::get('', 'show')->name('.info');
            });
        });

        Route::prefix('/thread')->name('.thread')->controller('ThreadController')->group(function () {
            Route::prefix('/{thread}')->where(['thread', '[0-9]+'])->scopeBindings()->group(function () {
                Route::get('', 'show')->name('');
            });
        });
    });
});

// Admin
Route::prefix('/admin')->middleware(['auth', 'isAdmin', 'verified'])->name('admin')->controller('AdminController')->group(function () {
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

// Authentication
Route::name('')->middleware('guest')->namespace('Auth')->group(function () {
    Route::controller('LoginController')->group(function () {
        Route::get('/login', 'showLoginForm')->name('login');
        Route::post('/login', 'login');
        Route::get('/logout', 'logout')->withoutMiddleware('guest')->middleware('auth')->name('logout');
    });
    Route::controller('RegisterController')->group(function () {
        Route::get('/register', 'showRegistrationForm')->name('register');
        Route::post('/register', 'register');
    });
    Route::controller('PasswordRecoveryController')->name('password')->group(function () {
        Route::get('/recover-password', 'showPasswordRecoveryForm')->name('.request');
        Route::post('/recover-password', 'sendPasswordRecoveryLink')->name('.request-action');
        Route::get('/reset-password/{token}', 'showPasswordResetForm')->name('.reset');
        Route::post('/reset-password', 'resetPassword')->name('.reset-action');
    });

    Route::controller('OAuthController')->prefix('/oauth/{provider}')->whereIn('provider', ProviderType::values())->name('oauth')->group(function () {
        Route::get('/redirect', 'redirectOAuth')->name('.redirect');
        Route::get('/callback', 'handleOAuthCallback')->name('.callback');
    });

    Route::controller('EmailVerificationController')->prefix('/email')->withoutMiddleware('guest')->middleware('auth')->name('verification')->group(function () {
        Route::get('/verify', 'showEmailVerificationNotice')->name('.notice');
        Route::get('/verify/{id}/{hash}', 'verifyEmail')->middleware('signed')->name('.verify');
        Route::post('/verification-notice', 'sendNewVerificationEmail')->middleware('throttle:6,1')->name('.send');
    });
});

Route::prefix('/api')->name('api')->middleware('throttle')->group(function () {

    Route::prefix('/project')->name('.project')->middleware('verified')->controller('ProjectController')->group(function () {

        Route::post('', 'store')->name('.new');

        Route::prefix('/{project}')->where(['project', '[0-9]+'])->group(function () {
            Route::delete('', 'destroy')->name('.delete');
            Route::get('', 'show')->name('');

            Route::put('', 'update')->name('.update');

            Route::prefix('/archive')->group(function () {
                Route::put('', 'archive')->name('.archive');
                Route::delete('', 'unarchive')->name('.unarchive');
            });

            Route::post('/leave', 'leaveProject')->name('.leave');

            Route::prefix('/members')->name('.members')->group(function () {
                Route::get('', 'getProjectMembers')->name('');
                Route::delete('/{user}', 'removeUser')->name('.remove');
                // Route::post('', 'addProjectMember')->name('.add');
            });

            Route::prefix('tags')->name('.tags')->group(function () {
                Route::get('', 'getProjectTags');
            });

            Route::prefix('/favorite')->name('.favorite')->group(function () {
                Route::post('/toggle', 'toggleFavorite')->name('.toggle');
            });

            Route::post('/invite', 'inviteUser')->name('.invite-user');

            Route::prefix('/coordinator')->name('.coordinator')->group(function () {
                Route::put('', 'setCoordinator');
            });
        });
    });

    Route::prefix('/user')->name('.user')->middleware('verified')->controller('UserController')->group(function () {
        
        Route::post('', 'store')->name('.new');      

        Route::prefix('/{user}')->where(['user', '[0-9]+'])->group(function () {
            Route::delete('', 'destroy')->name('.delete');

            Route::put('', 'update')->name('.update');

            Route::post('/block', 'block')->name('.block');

            Route::post('/unblock', 'unblock')->name('.unblock');

            Route::get('', 'show')->name('');
        });
    });

    Route::prefix('/task')->name('.task')->middleware('verified')->controller('TaskController')->group(function () {

        Route::post('/new', 'store')->name('.new');

        Route::prefix('/{task}')->where(['task', '[0-9]+'])->group(function () {

            // this needs to be a separate function since this won't be wrapped in a project route group
            Route::get('', 'show')->name('');
            Route::put('', 'update')->name('update');
            Route::delete('', 'destroy')->name('.delete');

            Route::put('/complete', 'complete')->name('.complete');
            Route::delete('/complete', 'incomplete')->name('.incomplete');
            Route::post('/reposition', 'update')->name('.reposition');
        });
    });

    Route::prefix('/task-comment')->name('.task-comment')->middleware('verified')->controller('TaskCommentController')->group(function () {

        Route::post('/new', 'store')->name('.new');
        Route::get('', 'index')->name('list');
        
        Route::prefix('/{taskComment}')->where(['taskComment', '[0-9]+'])->group(function () {

            Route::get('', 'show')->name('');
            Route::put('', 'update')->name('.update');
            Route::delete('', 'destroy')->name('.delete');
        });
    });

    Route::prefix('/task-group')->name('.task-group')->middleware('verified')->controller('TaskGroupController')->group(function () {
        
        Route::post('/new', 'store')->name('.new');
        
        Route::prefix('/{taskGroup}')->where(['taskGroup', '[0-9]+'])->group(function () {

            Route::get('', 'show')->name('');
            Route::put('', 'update')->name('.update');
            Route::delete('', 'destroy')->name('.delete');

            Route::post('/reposition', 'update')->name('.reposition');
        });
    });

    Route::prefix('/thread')->name('.thread')->middleware('verified')->controller('ThreadController')->group(function () {

        Route::post('/new', 'store')->name('.new');
        
        Route::prefix('/{thread}')->where(['thread', '[0-9]+'])->group(function () {

            Route::get('', 'show')->name('');
            Route::put('', 'update')->name('.update');
            Route::delete('', 'destroy')->name('.delete');
        });
    });

    Route::prefix('/thread-comment')->name('.thread-comment')->middleware('verified')->controller('ThreadCommentController')->group(function () {

        Route::post('/new', 'store')->name('.new');
        Route::get('', 'index')->name('list');
        
        Route::prefix('/{threadComment}')->where(['threadComment', '[0-9]+'])->group(function () {

            Route::get('', 'show')->name('');
            Route::put('', 'update')->name('.update');
            Route::delete('', 'destroy')->name('.delete');
        });
    });

    Route::prefix('/tag')->name('.tag')->middleware('verified')->controller('TagController')->group(function () {

        Route::post('/new', 'store')->name('.new');
        
        Route::prefix('/{tag}')->where(['tag', '[0-9]+'])->group(function () {

            Route::get('', 'show')->name('');
            Route::put('', 'update')->name('.update');
            Route::delete('', 'destroy')->name('.delete');
        });
    });

    Route::prefix('/notifications')->name('.notification')->middleware('verified')->controller('NotificationController')->group(function () {
        Route::prefix('/{notification}')->group(function () {

            Route::get('', 'show')->name('');

            Route::put('/read', 'markAsRead')->name('.mark-read');
            // Route::put('/unread', 'markAsUnread')->name('.mark-unread');
        });
    });
});
