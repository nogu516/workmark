<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\AdminAttendanceController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminApplicationController;
use App\Http\Controllers\Admin\RequestApplicationController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.store');

Route::middleware('auth')->group(function () {
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/clock-in', [AttendanceController::class, 'clockIn'])->name('attendance.clockIn');
    Route::post('/attendance/clock-out', [AttendanceController::class, 'clockOut'])->name('attendance.clockOut');
    Route::post('/attendance/break-start', [AttendanceController::class, 'breakStart'])->name('attendance.breakStart');
    Route::post('/attendance/break-end', [AttendanceController::class, 'breakEnd'])->name('attendance.breakEnd');

    Route::get('/attendance/list', [AttendanceController::class, 'list'])->name('attendance.list');

    Route::get('/attendances/{id}', [AttendanceController::class, 'show'])->name('attendance.show');
    Route::post('/attendance/{id}', [AttendanceController::class, 'update'])->name('attendance.update');

    // 修正内容確認画面（POSTでデータを受け取り表示）
    Route::post('/attendance/confirm/{id}', [AttendanceController::class, 'confirm'])->name('attendance.confirm');

    Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');

    Route::post('/attendance/store-application', [AttendanceController::class, 'storeApplication'])->name('attendance.storeApplication');

    // 申請一覧（スタッフ）
    Route::get('/request_applications', [RequestApplicationController::class, 'index'])->name('request_applications.index');

    // 承認処理
    Route::post('/request-applications/{id}/approve', [AdminApplicationController::class, 'approve'])->name('requests.approve');

    // 詳細確認
    Route::get('/request-applications/{id}/confirm', [AdminApplicationController::class, 'confirm'])->name('requests.confirm');

    Route::post('/request_applications', [RequestApplicationController::class, 'store'])->name('request_applications.store');

    Route::post('/attendance/request', [RequestApplicationController::class, 'store'])->name('attendance.request');

    Route::resource('request-applications', RequestApplicationController::class);

    // Route::delete('/request_applications/{id}', [RequestApplicationController::class, 'destroy'])->name('request_applications.destroy');


    // 申請詳細（任意）
    // Route::get('/request_applications/{id}', [RequestApplicationController::class, 'show'])->name('request_applications.show');

});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminLoginController::class, 'login'])->name('login');
});

Route::prefix('admin')->name('admin.')->middleware('auth:admin')->group(function () {

    Route::get('/attendances', [AdminAttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendances/{id}/{year}/{month}', [AdminAttendanceController::class, 'show'])->name('attendance.show');

    Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('users/{user}/attendances', [AdminUserController::class, 'show'])->name('users.attendances');
    Route::get('users/{user}/attendances/csv', [AdminUserController::class, 'exportCsv'])->name('users.attendances.csv');

    Route::get('requests', [AdminApplicationController::class, 'index'])->name('requests.index');
    Route::get('requests/{id}', [AdminApplicationController::class, 'show'])->name('requests.show');
    Route::post('requests/{id}/approve', [AdminApplicationController::class, 'approve'])->name('requests.approve');

    Route::post('logout', [AdminLoginController::class, 'logout'])->name('logout');

    // 勤怠修正申請の登録
    Route::post('/request_applications', [RequestApplicationController::class, 'store'])->name('request_applications.store');

    Route::get('/requests/{id}/confirm', [RequestApplicationController::class, 'confirm'])->name('requests.confirm');

    Route::resource('request-applications', RequestApplicationController::class);
});
