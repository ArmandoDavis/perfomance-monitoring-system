<?php

use App\Http\Controllers\Admin\Access\StaffUserController;
use App\Http\Controllers\Admin\Department\DepartmentController;
use App\Http\Controllers\Admin\Documents\DocumentController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\System\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\GlobalSearchController;

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

require __DIR__ . '/../admin/task.php';
require __DIR__ . '/../admin/roles.php';

Route::prefix('user_profile')->name('user_profile.')->group(function () {
    Route::get('/my_profile', [ProfileController::class, 'index'])->name('my_profile');
});


Route::prefix('global')->name('global.')->group(function () {
    Route::get('/search', [GlobalSearchController::class, 'search'])->name('search');
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
