<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\StudyPetController;
use App\Http\Controllers\TKAController;
use App\Http\Controllers\TodoTaskController;
use App\Http\Controllers\UTBKController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Authentication Routes (Guest Only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Logout Route (Auth Only)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Routes (Auth Only)
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    // 1. Dashboard Route
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 2. Goal / Tujuan Routes
    Route::get('/tujuan', [GoalController::class, 'index'])->name('goal.index');
    Route::post('/tujuan', [GoalController::class, 'store'])->name('goal.store');

    // 3. Materi Routes
    Route::get('/materi', [MateriController::class, 'index'])->name('materi.index');
    Route::post('/materi', [MateriController::class, 'store'])->name('materi.store');
    Route::get('/materi/{materi}/edit', [MateriController::class, 'edit'])->name('materi.edit');
    Route::put('/materi/{materi}', [MateriController::class, 'update'])->name('materi.update');
    Route::delete('/materi/{materi}', [MateriController::class, 'destroy'])->name('materi.destroy');

    // 4. To-Do List Routes
    Route::get('/todolist', [TodoTaskController::class, 'index'])->name('todolist.index');
    Route::post('/todolist', [TodoTaskController::class, 'store'])->name('todolist.store');
    Route::patch('/todolist/{todoTask}/complete', [TodoTaskController::class, 'complete'])->name('todolist.complete');
    Route::get('/todolist/{todoTask}/edit', [TodoTaskController::class, 'edit'])->name('todolist.edit');
    Route::put('/todolist/{todoTask}', [TodoTaskController::class, 'update'])->name('todolist.update');
    Route::delete('/todolist/{todoTask}', [TodoTaskController::class, 'destroy'])->name('todolist.destroy');

    // 5. UTBK Score Tracker Routes
    Route::get('/utbk', [UTBKController::class, 'index'])->name('utbk.index');
    Route::get('/utbk/tryouts/create', [UTBKController::class, 'create'])->name('utbk.create');
    Route::post('/utbk/tryouts', [UTBKController::class, 'store'])->name('utbk.store');
    Route::get('/utbk/tryouts/{utbkTryout}', [UTBKController::class, 'show'])->name('utbk.show');
    Route::get('/utbk/tryouts/{utbkTryout}/edit', [UTBKController::class, 'edit'])->name('utbk.edit');
    Route::put('/utbk/tryouts/{utbkTryout}', [UTBKController::class, 'update'])->name('utbk.update');
    Route::delete('/utbk/tryouts/{utbkTryout}', [UTBKController::class, 'destroy'])->name('utbk.destroy');

    // 6. TKA Analisis Routes
    Route::get('/tka', [TKAController::class, 'index'])->name('tka.index');
    Route::get('/tka/tryouts/create', [TKAController::class, 'create'])->name('tka.create');
    Route::post('/tka/tryouts', [TKAController::class, 'store'])->name('tka.store');
    Route::get('/tka/tryouts/{tkaTryout}', [TKAController::class, 'show'])->name('tka.show');
    Route::get('/tka/tryouts/{tkaTryout}/edit', [TKAController::class, 'edit'])->name('tka.edit');
    Route::put('/tka/tryouts/{tkaTryout}', [TKAController::class, 'update'])->name('tka.update');
    Route::delete('/tka/tryouts/{tkaTryout}', [TKAController::class, 'destroy'])->name('tka.destroy');

    // 7. Study Pet Routes
    Route::get('/study-pet', [StudyPetController::class, 'index'])->name('study-pet.index');

    // 8. Profile Routes
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/info', [\App\Http\Controllers\ProfileController::class, 'updateInfo'])->name('profile.update-info');
    Route::post('/profile/avatar', [\App\Http\Controllers\ProfileController::class, 'updateAvatar'])->name('profile.update-avatar');
    Route::put('/profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.update-password');

    // 9. Admin Routes (Protected by admin.pin middleware)
    Route::middleware('admin.pin')->group(function () {
        Route::get('/admin/pin', [\App\Http\Controllers\AdminController::class, 'showPinForm'])->name('admin.pin.show');
        Route::post('/admin/pin', [\App\Http\Controllers\AdminController::class, 'verifyPin'])->name('admin.pin.verify');
        Route::get('/admin/dashboard', [\App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/admin/activities', [\App\Http\Controllers\AdminController::class, 'activities'])->name('admin.activities');
        Route::post('/admin/maintenance/toggle', [\App\Http\Controllers\AdminController::class, 'toggleMaintenance'])->name('admin.maintenance.toggle');
        Route::get('/admin/backup/download', [\App\Http\Controllers\AdminController::class, 'downloadBackup'])->name('admin.backup.download');

        // 10. User Management Routes
        Route::resource('/admin/users', \App\Http\Controllers\Admin\UserController::class)->names('admin.users');
    });
});
