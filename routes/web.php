<?php
require __DIR__.'/auth.php';
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\staff\SupervisorApplicationController;
use App\Http\Controllers\Admin\AdminSupervisorController;

Route::get('/', function () {
    return view('welcome');
});

// Admin Routes
Route::middleware(['auth','role:admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class,'adminDashboard'])->name('admin.dashboard');
    Route::get('/admin/work-queue', [DashboardController::class,'adminWorkQueue'])->name('admin.work-queue');
    
    // User Management
    Route::get('/admin/users', [UserManagementController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/create', [UserManagementController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [UserManagementController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/{user}/edit', [UserManagementController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{user}', [UserManagementController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [UserManagementController::class, 'destroy'])->name('admin.users.destroy');
    Route::get('/admin/supervisors',[AdminSupervisorController::class, 'index'])->name('admin.supervisors.index');
    Route::post('/admin/supervisors/{application}/approve',[AdminSupervisorController::class, 'approve'])->name('admin.supervisors.approve');
    Route::post('/admin/supervisors/{application}/reject',[AdminSupervisorController::class, 'reject'])->name('admin.supervisors.reject');
    Route::post('/admin/supervisors/{application}/revoke',[AdminSupervisorController::class, 'revoke'])->name('admin.supervisors.revoke');

    Route::get('/admin/assignments', [\App\Http\Controllers\AdminAssignmentController::class,'index'])->name('admin.assignments.index');
    Route::post('/admin/assignments/{request}/approve', [\App\Http\Controllers\AdminAssignmentController::class,'approve'])->name('admin.assignments.approve');
    Route::post('/admin/assignments/{request}/reject', [\App\Http\Controllers\AdminAssignmentController::class,'reject'])->name('admin.assignments.reject');

    // Manual supervision management (assign/remove)
    Route::get('/admin/supervision/manage', [\App\Http\Controllers\AdminSupervisionManagementController::class, 'index'])->name('admin.supervision.manage.index');
    Route::post('/admin/supervision/manage', [\App\Http\Controllers\AdminSupervisionManagementController::class, 'store'])->name('admin.supervision.manage.store');
    Route::delete('/admin/supervision/manage/{assignment}', [\App\Http\Controllers\AdminSupervisionManagementController::class, 'destroy'])->name('admin.supervision.manage.destroy');
    Route::post('/admin/supervision/manage/{assignment}/transfer', [\App\Http\Controllers\AdminSupervisionManagementController::class, 'transfer'])->name('admin.supervision.manage.transfer');

    Route::get('/admin/tracking', [\App\Http\Controllers\AdminTrackingController::class,'index'])->name('admin.tracking.index');
    Route::get('/admin/tracking/{student}', [\App\Http\Controllers\AdminTrackingController::class,'show'])->name('admin.tracking.show');
});

// Staff Routes
Route::middleware(['auth','role:staff'])->group(function () {
    Route::get('/staff/dashboard', [DashboardController::class,'staffDashboard'])->name('staff.dashboard');
    Route::get('/staff/supervisor/apply',[SupervisorApplicationController::class, 'create'])->name('staff.supervisor.apply');
    Route::post('/staff/supervisor/apply',[SupervisorApplicationController::class, 'store'])->name('staff.supervisor.apply.store');

    Route::get('/staff/tasks', [\App\Http\Controllers\SupervisorTaskController::class,'index'])->name('staff.tasks.index');
    Route::get('/staff/tasks/create', [\App\Http\Controllers\SupervisorTaskController::class,'create'])->name('staff.tasks.create');
    Route::post('/staff/tasks', [\App\Http\Controllers\SupervisorTaskController::class,'store'])->name('staff.tasks.store');
    Route::get('/staff/tasks/{task}/edit', [\App\Http\Controllers\SupervisorTaskController::class,'edit'])->name('staff.tasks.edit');
    Route::put('/staff/tasks/{task}', [\App\Http\Controllers\SupervisorTaskController::class,'update'])->name('staff.tasks.update');
    Route::delete('/staff/tasks/{task}', [\App\Http\Controllers\SupervisorTaskController::class,'destroy'])->name('staff.tasks.destroy');
    Route::post('/staff/tasks/{task}/restore', [\App\Http\Controllers\SupervisorTaskController::class,'restore'])->name('staff.tasks.restore');

    Route::post('/staff/submissions/{submission}/grade', [\App\Http\Controllers\SupervisorTaskController::class,'grade'])->name('staff.submissions.grade');
});

// User/Student Routes
Route::middleware(['auth','role:user'])->group(function () {
    Route::get('/user/dashboard', [DashboardController::class,'userDashboard'])->name('user.dashboard');
    Route::get('/user/supervision/request', [\App\Http\Controllers\StudentSupervisionController::class,'create'])->name('user.supervision.request');
    Route::post('/user/supervision/request', [\App\Http\Controllers\StudentSupervisionController::class,'store'])->name('user.supervision.request.store');
    Route::get('/user/tasks', [\App\Http\Controllers\StudentTaskController::class,'index'])->name('user.tasks.index');
    Route::post('/user/tasks/{task}/submit', [\App\Http\Controllers\StudentSubmissionController::class,'store'])->name('user.tasks.submit');
});

// Message Routes (Available to all authenticated users)
Route::middleware(['auth'])->group(function () {
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/create', [MessageController::class, 'create'])->name('messages.create');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/sent', [MessageController::class, 'sent'])->name('messages.sent');
    Route::get('/messages/{message}', [MessageController::class, 'show'])->name('messages.show');
    Route::delete('/messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');
    Route::delete('/messages', [MessageController::class, 'bulkDestroy'])->name('messages.bulkDestroy');
    Route::get('/api/online-users', [MessageController::class, 'onlineUsers'])->name('messages.online');
});

// Password Update Route and Personal Data
Route::middleware(['auth'])->group(function () {
    Route::put('/password', [App\Http\Controllers\Auth\PasswordController::class, 'update'])->name('password.update');
    Route::get('/personal-data', [App\Http\Controllers\ProfileController::class, 'personalData'])->name('profile.personal');
    Route::put('/personal-data', [App\Http\Controllers\ProfileController::class, 'updatePersonalData'])->name('profile.personal.update');

    // Required profile info gate (used before supervision request/application)
    Route::get('/profile/required-info', [App\Http\Controllers\ProfileController::class, 'requiredInfo'])->name('profile.required');
    Route::put('/profile/required-info', [App\Http\Controllers\ProfileController::class, 'updateRequiredInfo'])->name('profile.required.update');
});

