<?php

use App\Http\Controllers\Admin\Access\StaffUserController;
use App\Http\Controllers\Admin\Department\DepartmentController;
use App\Http\Controllers\Admin\Documents\DocumentController;
use App\Http\Controllers\Admin\Task\CommentController;
use App\Http\Controllers\Admin\Task\TaskAssignmentController;
use App\Http\Controllers\Admin\Task\TaskController;
use App\Http\Controllers\Admin\Task\TaskExpenseController;
use App\Http\Controllers\Admin\Task\TaskPerformanceController;
use App\Http\Controllers\Frontend\MyTaskController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\System\DashboardController;
use Illuminate\Support\Facades\Route;

/**
 * @description remember to arrange code when you apply any changes.
 * @author Yohana Samile <yohanasamile@gmail.com>
 */

Route::get('/__probe', function () {
    return 'PROBE OK';
});

/** Public Routes */
Route::get('/', [HomeController::class, 'index'])->name('home');

/** Authenticated Routes */
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /** ADMIN ROUTES  */
    Route::prefix('admin_panel')->name('admin_panel.')->group(function () {
        // Admin dashboard
        Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');

        /** departments */
        Route::prefix('departments')->name('departments.')->middleware('permission:department.manage')->group(function () {
            Route::get('/', [DepartmentController::class, 'index'])->name('index');
            Route::get('/profile/{department}', [DepartmentController::class, 'profile'])->name('profile');
            Route::get('/get_all_for_dt', [DepartmentController::class, 'getAllForDt'])->name('get_all_for_dt');
            Route::get('/get_active_departments', [DepartmentController::class, 'getActiveDepartments'])->name('get_active_departments');

            Route::get('/create', [DepartmentController::class, 'create'])->name('create');
            Route::post('/store', [DepartmentController::class, 'store'])->name('store');

            Route::get('/edit/{department}', [DepartmentController::class, 'edit'])->name('edit');
            Route::put('/update/{department}', [DepartmentController::class, 'update'])->name('update');
            Route::put('/change_status/{department}', [DepartmentController::class, 'changeDepartmentStatus'])->name('change_status');

            Route::delete('/delete/{department}', [DepartmentController::class, 'delete'])->name('delete');
        });

        /** task */
        Route::prefix('tasks')->name('tasks.')->group(function () {
            Route::get('/', [TaskController::class, 'index'])->name('index');
            Route::get('/create', [TaskController::class, 'create'])->name('create');
            Route::post('/store', [TaskController::class, 'store'])->name('store');
            Route::get('/get_all_for_dt', [TaskController::class, 'getAllForDt'])->name('get_all_for_dt');

            Route::get('/edit/{task}', [TaskController::class, 'edit'])->name('edit');
            Route::put('/update/{task}', [TaskController::class, 'update'])->name('update');
            Route::put('/change_status/{task}', [TaskController::class, 'changeTaskStatus'])->name('change_status');
            Route::get('/profile/{task}', [TaskController::class, 'profile'])->name('profile');
            Route::delete('/delete/{task}', [TaskController::class, 'delete'])->name('delete');


            Route::prefix('comment')->name('comment.')->group(function () {
                Route::post('/store/{task}', [CommentController::class, 'store'])->name('store');
            });

            Route::prefix('assignment')->name('assignment.')->group(function () {
                Route::get('/edit/{assignment}', [TaskAssignmentController::class, 'edit'])->name('edit');
                Route::put('/update/{assignment}', [TaskAssignmentController::class, 'update'])->name('update');
                Route::delete('/delete/{assignment}', [TaskAssignmentController::class, 'delete'])->name('delete');
                Route::post('/store/{task}', [TaskAssignmentController::class, 'store'])->name('store');
            });

            Route::prefix('progress')->name('progress.')->group(function () {
                Route::get('/edit/{assignment}', [TaskAssignmentController::class, 'edit'])->name('edit');
                Route::put('/update/{assignment}', [TaskAssignmentController::class, 'update'])->name('update');
                Route::delete('/delete/{assignment}', [TaskAssignmentController::class, 'delete'])->name('delete');
                Route::post('/store/{task}', [TaskAssignmentController::class, 'store'])->name('store');
            });

            Route::prefix('performance')->name('performance.')->group(function () {
                Route::post('/store/{task}', [TaskPerformanceController::class, 'store'])->name('store');
            });

            Route::prefix('expenses')->name('expenses.')->group(function () {
                Route::post('/store/{task}', [TaskExpenseController::class, 'store'])->name('store');
                Route::put('/approve/{expense}', [TaskExpenseController::class, 'approve'])->name('approve');
            });



            //Subtasks
            Route::prefix('{task}/subtasks')->name('subtasks.')->group(function () {
                Route::get('/', [SubtaskController::class, 'index'])->name('index');
                Route::get('/create', [SubtaskController::class, 'create'])->name('create');
                Route::post('/', [SubtaskController::class, 'store'])->name('store');
                Route::get('/profile', [SubtaskController::class, 'profile'])->name('profile');
            });
        });

        //Subtask standalone actions
        Route::prefix('subtasks')->name('subtasks.')->group(function () {
            Route::get('{subtask}/edit', [SubtaskController::class, 'edit'])->name('edit');
            Route::put('{subtask}', [SubtaskController::class, 'update'])->name('update');
            Route::delete('{subtask}', [SubtaskController::class, 'destroy'])->name('destroy');
        });

        //Task Budgets
        Route::prefix('tasks/{task}/budget')->name('tasks.budget.')->group(function () {
            Route::get('/create', [TaskController::class, 'createBudget'])->name('create');
            Route::post('/', [TaskController::class, 'storeBudget'])->name('store');
            Route::get('{budget}/edit', [TaskController::class, 'editBudget'])->name('edit');
            Route::put('{budget}', [TaskController::class, 'updateBudget'])->name('update');
            Route::delete('{budget}', [TaskController::class, 'destroyBudget'])->name('destroy');
        });

        Route::prefix('user_profile')->name('user_profile.')->group(function () {
            Route::get('/my_profile', [App\Http\Controllers\Admin\ProfileController::class, 'index'])->name('my_profile');
        });


        Route::prefix('global')->name('global.')->group(function () {
            Route::get('/search', [\App\Http\Controllers\Admin\GlobalSearchController::class, 'search'])->name('search');
        });


        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [StaffUserController::class, 'index'])->name('index');
            Route::get('/create', [StaffUserController::class, 'create'])->name('create');
            Route::get('/get_staff_user_for_dt', [StaffUserController::class, 'getAllForDt'])->name('get_staff_user_for_dt');
            Route::get('/profile/{user}', [StaffUserController::class, 'profile'])->name('profile');

            Route::get('/edit/{user}', [StaffUserController::class, 'edit'])->name('edit');
            Route::put('/update/{user}', [StaffUserController::class, 'update'])->name('update');
            Route::post('/store', [StaffUserController::class, 'store'])->name('store');

            Route::post('/resend_resend_temp_password/{user}', [StaffUserController::class, 'resendPassowrd'])->name('resend_resend_temp_password');
            Route::delete('/delete/{user}', [StaffUserController::class, 'delete'])->name('delete');
        });
    });



    /** frontend routes */
    Route::prefix('frontend')->name('frontend.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'staffDashboard'])->name('dashboard');

        /** user tasks */
        Route::prefix('tasks')->name('tasks.')->group(function () {
            Route::get('/', [MyTaskController::class, 'index'])->name('index');
            Route::get('/create', [MyTaskController::class, 'create'])->name('create');
            Route::post('/store', [MyTaskController::class, 'store'])->name('store');
            Route::get('/get_all_for_dt', [MyTaskController::class, 'getAllForDt'])->name('get_all_for_dt');

            Route::get('/edit/{task}', [MyTaskController::class, 'edit'])->name('edit');
            Route::put('/update/{task}', [MyTaskController::class, 'update'])->name('update');
            Route::put('/update_status/{task}/{status}', [MyTaskController::class, 'updateStatus'])->name('update_status');
            Route::get('/profile/{task}', [MyTaskController::class, 'profile'])->name('profile');
            Route::delete('/delete/{task}', [MyTaskController::class, 'delete'])->name('delete');


            Route::prefix('comment')->name('comment.')->group(function () {
                Route::post('/store/{task}', [CommentController::class, 'store'])->name('store');
            });

            Route::prefix('expenses')->name('expenses.')->group(function () {
                Route::post('/store/{task}', [TaskExpenseController::class, 'store'])->name('store');
                Route::put('/approve/{expense}', [TaskExpenseController::class, 'approve'])->name('approve');
            });
        });
        /** end of user tasks */
        Route::prefix('user_profile')->name('user_profile.')->group(function () {
            Route::get('/my_profile', [ProfileController::class, 'index'])->name('my_profile');
            Route::put('/change_password', [ProfileController::class, 'changePassword'])->name('change_password');
            Route::put('/update', [ProfileController::class, 'update'])->name('update');
            Route::get('/security', [ProfileController::class, 'security'])->name('security');
        });
    });




    /** MANAGER ROUTES */
    Route::middleware(['role:manager'])->prefix('manager')->name('manager.')->group(function () {
        Route::get('/dashboard', [ManagerController::class, 'index'])->name('dashboard');
    });

    /** STAFF ROUTES */
    Route::middleware(['role:staff'])->prefix('staff')->name('staff.')->group(function () {
        Route::get('/dashboard', [StaffController::class, 'index'])->name('dashboard');
    });

    /** attachments */
    Route::prefix('attachments')->name('attachments.')->group(function () {
        Route::get('/download/{attachment}', [DocumentController::class, 'download'])->name('download');
        Route::patch('/profile/{attachment}', [DocumentController::class, 'profile'])->name('profile');
        Route::patch('/update/{attachment}', [DocumentController::class, 'update'])->name('update');
        Route::delete('/delete', [DocumentController::class, 'delete'])->name('delete');
    });

    /** PROFILE */
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });
});

/** Auth Routes */
require __DIR__ . '/auth.php';
