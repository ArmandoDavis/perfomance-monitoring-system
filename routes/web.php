<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\SubtaskController;
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
    Route::get('/dashboard', [UserController::class, 'index'])->name('dashboard');

    /** ADMIN ROUTES  */
    Route::prefix('admin_panel')->name('admin_panel.')->group(function () {
        // Admin dashboard
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

        // Tasks
        Route::prefix('tasks')->name('tasks.')->group(function () {
            Route::get('/', [TaskController::class, 'index'])->name('index');
            Route::get('/create', [TaskController::class, 'create'])->name('create');
            Route::post('/', [TaskController::class, 'store'])->name('store');

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
    });

    /** MANAGER ROUTES */
    Route::middleware(['role:manager'])->prefix('manager')->name('manager.')->group(function () {
        Route::get('/dashboard', [ManagerController::class, 'index'])->name('dashboard');
    });

    /** STAFF ROUTES    */
    Route::middleware(['role:staff'])->prefix('staff')->name('staff.')->group(function () {
        Route::get('/dashboard', [StaffController::class, 'index'])->name('dashboard');
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
