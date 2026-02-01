<?php

use App\Http\Controllers\Admin\Access\ProfileController;
use App\Http\Controllers\Admin\Access\StaffUserController;
use App\Http\Controllers\Admin\Task\CommentController;
use App\Http\Controllers\Admin\Task\TaskAssignmentController;
use App\Http\Controllers\Admin\Task\TaskExpenseController;
use App\Http\Controllers\Admin\Task\TaskShareController;
use App\Http\Controllers\Hod\audits\AuditController;
use App\Http\Controllers\Hod\Expense\ExpenseController;
use App\Http\Controllers\Hod\Task\TaskController;
use App\Http\Controllers\Hod\Task\TaskPerformanceController;
use App\Http\Controllers\System\DashboardController;
use App\Http\Controllers\System\NotificationController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [DashboardController::class, 'hodDashboard'])->name('dashboard');

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


    Route::get('/my_tasks', [TaskController::class, 'myTasks'])->name('my_tasks');
    Route::get('/shared', [TaskController::class, 'shared'])->name('shared');
    Route::get('/next_actions', [TaskController::class, 'nextActions'])->name('next_actions');
    Route::get('/transferred', [TaskController::class, 'transferred'])->name('transferred');

    Route::prefix('share')->name('share.')->group(function () {
        Route::post('/store/{task}', [TaskShareController::class, 'store'])->name('store');
        Route::post('/transfer/{task}', [TaskShareController::class, 'transfer'])->name('transfer');
        Route::delete('/delete/{share}', [TaskShareController::class, 'delete'])->name('delete');
        Route::put('/modify_access/{share}', [TaskShareController::class, 'modifyAccess'])->name('modify_access');
    });


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


/** Task Budgets */
Route::prefix('tasks/{task}/budget')->name('tasks.budget.')->group(function () {
    Route::get('/create', [TaskController::class, 'createBudget'])->name('create');
    Route::post('/', [TaskController::class, 'storeBudget'])->name('store');
    Route::get('{budget}/edit', [TaskController::class, 'editBudget'])->name('edit');
    Route::put('{budget}', [TaskController::class, 'updateBudget'])->name('update');
    Route::delete('{budget}', [TaskController::class, 'destroyBudget'])->name('destroy');
});

/** expenses ROUTES */
Route::prefix('expenses')->name('expenses.')->group(function () {
    Route::get('/', [ExpenseController::class, 'index'])->name('index');
    Route::get('/get_all_for_dt', [ExpenseController::class, 'getAllForDt'])->name('get_all_for_dt');

    Route::get('/create', [ExpenseController::class, 'create'])->name('create');
    Route::post('/store', [ExpenseController::class, 'store'])->name('store');
    Route::put('/approve/{expense}', [TaskExpenseController::class, 'approve'])->name('approve');

    Route::get('/profile/{expense}', [ExpenseController::class, 'profile'])->name('profile');
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
    Route::put('/change_status/{user}', [StaffUserController::class, 'toggleStatus'])->name('change_status');
    Route::put('/update_password/{user}', [StaffUserController::class, 'updatePassowrd'])->name('update_password');
    Route::delete('/delete/{user}', [StaffUserController::class, 'delete'])->name('delete');
});

Route::prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/', [NotificationController::class, 'hodNotifications'])->name('index');
});

Route::prefix('user_profile')->name('user_profile.')->group(function () {
    Route::get('/my_profile', [ProfileController::class, 'index'])->name('my_profile');
});


Route::prefix('audits')->name('audits.')->group(function () {
    Route::get('/get_all_for_dt', [AuditController::class, 'getAllForDt'])->name('get_all_for_dt');
    Route::get('/my_logs', [AuditController::class, 'myLogs'])->name('my_logs');
});
