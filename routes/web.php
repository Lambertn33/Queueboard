<?php

use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthenticationController::class, 'create'])->name('login');
    Route::post('login', [AuthenticationController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::get('/home', function () {
        return Inertia::render('Welcome', [
            'message' => 'Laravel sent this page to Vue through Inertia.',
        ]);
    })->name('home');

    Route::get('/teams', [TeamController::class, 'index'])->name('teams.index');

    Route::post('logout', [AuthenticationController::class, 'destroy'])->name('logout');
});
