<?php
use App\Http\Controllers\Admin\Task\CommentController;
use App\Http\Controllers\Admin\Task\TaskAssignmentController;
use App\Http\Controllers\Admin\Task\TaskController;
use App\Http\Controllers\Admin\Task\TaskExpenseController;
use App\Http\Controllers\Admin\Task\TaskPerformanceController;
use App\Http\Controllers\Admin\Task\TaskShareController;
use Illuminate\Support\Facades\Route;

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
    Route::post('/transfer', [TaskShareController::class, 'transferTask'])->name('transfer');

    Route::prefix('share')->name('share.')->group(function () {
        Route::post('/store/{task}', [TaskShareController::class, 'store'])->name('store');
        Route::delete('/delete/{task}', [TaskShareController::class, 'delete'])->name('delete');
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

//Task Budgets
Route::prefix('tasks/{task}/budget')->name('tasks.budget.')->group(function () {
    Route::get('/create', [TaskController::class, 'createBudget'])->name('create');
    Route::post('/', [TaskController::class, 'storeBudget'])->name('store');
    Route::get('{budget}/edit', [TaskController::class, 'editBudget'])->name('edit');
    Route::put('{budget}', [TaskController::class, 'updateBudget'])->name('update');
    Route::delete('{budget}', [TaskController::class, 'destroyBudget'])->name('destroy');
});
