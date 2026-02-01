<?php

use App\Http\Controllers\Admin\Task\CommentController;
use App\Http\Controllers\Admin\Task\TaskExpenseController;
use App\Http\Controllers\Admin\Task\TaskShareController;
use App\Http\Controllers\Frontend\MyTaskController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\System\DashboardController;
use Illuminate\Support\Facades\Route;


Route::get('/dashboard', [DashboardController::class, 'staffDashboard'])->name('dashboard');

/** user tasks */
Route::prefix('tasks')->name('tasks.')->group(function () {
    Route::get('/', [MyTaskController::class, 'index'])->name('index');
    Route::get('/create', [MyTaskController::class, 'create'])->name('create');
    Route::post('/store', [MyTaskController::class, 'store'])->name('store');
    Route::get('/get_all_for_dt', [MyTaskController::class, 'getAllForDt'])->name('get_all_for_dt');

    Route::get('/profile/{task}', [MyTaskController::class, 'profile'])->name('profile');
    Route::put('/change_status/{task}', [MyTaskController::class, 'changeTaskStatus'])->name('change_status');

    Route::get('/shared', [MyTaskController::class, 'shared'])->name('shared');
    Route::get('/transferred', [MyTaskController::class, 'transferred'])->name('transferred');
    Route::get('/next_actions', [MyTaskController::class, 'nextActions'])->name('next_actions');

    Route::prefix('share')->name('share.')->group(function () {
        Route::post('/store/{task}', [TaskShareController::class, 'store'])->name('store');
        Route::delete('/delete/{share}', [TaskShareController::class, 'delete'])->name('delete');
    });

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


Route::prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/', [NotificationController::class, 'staffNotifications'])->name('index');
});
