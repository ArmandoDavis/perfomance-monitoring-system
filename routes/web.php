<?php

use App\Http\Controllers\Admin\Access\StaffUserController;
use App\Http\Controllers\Admin\Department\DepartmentController;
use App\Http\Controllers\Admin\Documents\DocumentController;
use App\Http\Controllers\Admin\Task\CommentController;
use App\Http\Controllers\Admin\Task\TaskAssignmentController;
use App\Http\Controllers\Admin\Task\TaskController;
use App\Http\Controllers\Admin\Task\TaskExpenseController;
use App\Http\Controllers\Admin\Task\TaskPerformanceController;
use App\Http\Controllers\Admin\Task\TaskShareController;
use App\Http\Controllers\Frontend\MyTaskController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\NotificationController;
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

    Route::prefix('hod_panel')->name('hod_panel.')->group(function () {
        require __DIR__ . '/hod/hod_routes.php';
    });

    /** ADMIN ROUTES  */
    Route::prefix('admin_panel')->name('admin_panel.')->group(function () {
        require __DIR__ . '/admin/admin_routes.php';
    });

    Route::prefix('frontend')->name('frontend.')->group(function () {
        require __DIR__ . '/frontend/frontend_routes.php';
    });


    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/mark_all_read', [NotificationController::class, 'markAllRead'])->name('mark_all_read');
        Route::get('/read/{id}', [NotificationController::class, 'read'])->name('read');
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
