<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\StepController;
use App\Http\Controllers\MotivationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Goal routes
    Route::resource('goals', GoalController::class);
    Route::patch('/goals/{goal}/complete', [GoalController::class, 'complete'])->name('goals.complete');
    Route::patch('/goals/{goal}/uncomplete', [GoalController::class, 'uncomplete'])->name('goals.uncomplete');
    
    // Motivation routes
    Route::post('/motivation', [MotivationController::class, 'store'])->name('motivation.store');
    Route::resource('motivation', MotivationController::class)->except(['create', 'store', 'show']);

    Route::resource('steps', StepController::class);
});

require __DIR__.'/auth.php';
