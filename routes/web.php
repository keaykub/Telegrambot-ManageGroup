<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TelegramController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ManageUserController;
use App\Http\Controllers\ManageAdminController;

Route::get('/', function () {
    return view('login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Telegram
Route::post('/telegram/webhook', [TelegramController::class, 'webhook']);
Route::get('/telegram/set-webhook', [TelegramController::class, 'setWebhook']);         //testing
Route::post('/set-webhook', [TelegramController::class, 'setWebhooksub']);               //testing
Route::post('/delete-webhook', action: [TelegramController::class, 'deleteWebhook']);            //testing

//Login
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');

//Admin && Tester
Route::middleware(['auth:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/manageusers',[ManageUserController::class, 'index'])->name('admin.manageusers');
    Route::post('/admin/reducedaysall', [AdminController::class, 'reduceDaysAll'])->name('admin.reducedaysall');
    Route::post('/admin/plusdaysall', [AdminController::class, 'plusDaysAll'])->name('admin.plusdaysall');
    //Buttons for Admin
    Route::post('/admin/deleteuser', [ManageUserController::class, 'deleteuser'])->name('admin.deleteuser');
    Route::post('admin/kickuser', [AdminController::class, 'kickUser'])->name('admin.kickuser');
    Route::post('/admin/reducedays/', [AdminController::class, 'reduceDays'])->name('admin.reducedays');
    Route::post('/admin/plusdays/', [AdminController::class, 'plusDays'])->name('admin.plusdays');
    //Admin Manage
    Route::get('/admin/manageadmins', [ManageAdminController::class, 'index'])->name('admin.manageadmins');
    Route::post('/admins/editadmin', [ManageAdminController::class, 'edit'])->name('admin.editadmin');
});

//Get data from API
Route::get('/api/users', [ManageUserController::class, 'getUsers']);
Route::get('/api/admins', [ManageAdminController::class, 'getAdmins']);

