<?php

use App\Http\Controllers\LabelController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskStatusController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
})->name('home');

Route::middleware('auth')->group(function () {
    Route::resource('task_statuses', TaskStatusController::class)
        ->only(['create', 'store', 'edit', 'update', 'destroy']);

    // create/edit must be registered BEFORE the public {task} wildcard below
    Route::resource('tasks', TaskController::class)
        ->only(['create', 'store', 'edit', 'update', 'destroy']);

    Route::resource('labels', LabelController::class)
        ->only(['create', 'store', 'edit', 'update', 'destroy']);
});

// Public viewing
Route::resource('tasks', TaskController::class)->only(['index', 'show']);
Route::resource('task_statuses', TaskStatusController::class)->only(['index']);
Route::resource('labels', LabelController::class)->only(['index']);

require __DIR__.'/auth.php';
