<?php
require __DIR__.'/auth.php';
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\MessageController;

Route::get('/', function () {
    return view('welcome');
});

// Admin Routes
Route::middleware(['auth','role:admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class,'adminDashboard'])->name('admin.dashboard');
    
    // User Management
    Route::get('/admin/users', [UserManagementController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/create', [UserManagementController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [UserManagementController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/{user}/edit', [UserManagementController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{user}', [UserManagementController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [UserManagementController::class, 'destroy'])->name('admin.users.destroy');
});

// Staff Routes
Route::middleware(['auth','role:staff'])->group(function () {
    Route::get('/staff/dashboard', [DashboardController::class,'staffDashboard'])->name('staff.dashboard');
});

// User/Student Routes
Route::middleware(['auth','role:user'])->group(function () {
    Route::get('/user/dashboard', [DashboardController::class,'userDashboard'])->name('user.dashboard');
});

// Message Routes (Available to all authenticated users)
Route::middleware(['auth'])->group(function () {
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/create', [MessageController::class, 'create'])->name('messages.create');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/sent', [MessageController::class, 'sent'])->name('messages.sent');
    Route::get('/messages/{message}', [MessageController::class, 'show'])->name('messages.show');
    Route::get('/api/online-users', [MessageController::class, 'onlineUsers'])->name('messages.online');
});

// Password Update Route
Route::middleware(['auth'])->group(function () {
    Route::put('/password', [App\Http\Controllers\Auth\PasswordController::class, 'update'])->name('password.update');
});

