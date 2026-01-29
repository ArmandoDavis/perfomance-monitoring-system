<?php
use App\Http\Controllers\Admin\Access\RoleController;
use App\Http\Controllers\Admin\Access\PermissionController;
use Illuminate\Support\Facades\Route;

Route::prefix('role')->name('role.')->group(function () {
    Route::get('/', [RoleController::class, 'index'])->name('index');
    Route::get('/users_preview/{role}', [RoleController::class, 'roleUsersPreview'])->name('users_preview');
    Route::get('/role_user/{role}', [RoleController::class, 'roleUser'])->name('role_user');
    Route::get('/get_all_for_dt', [RoleController::class, 'getAllForDt'])->name('get_all_for_dt');
    Route::get('/profile/{role}', [RoleController::class, 'profile'])->name('profile');

    Route::get('/edit/{role}', [RoleController::class, 'edit'])->name('edit');
    Route::put('/update/{role}', [RoleController::class, 'update'])->name('update');
    Route::delete('/delete/{role}', [RoleController::class, 'delete'])->name('delete');

    Route::get('/create', [RoleController::class, 'create'])->name('create');
    Route::post('/store', [RoleController::class, 'store'])->name('store');
});


Route::prefix('permissions')->name('permissions.')->group(function () {
    Route::get('/', [PermissionController::class, 'index'])->name('index');
    Route::get('/get_all_for_dt', [PermissionController::class, 'getAllForDt'])->name('get_all_for_dt');
});
