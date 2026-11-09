<?php

use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\AnnouncementController as AdminAnnouncementController;
use App\Http\Controllers\Admin\LoadSheddingScheduleController as AdminScheduleController;
use App\Http\Controllers\Admin\OutageController as AdminOutageController;
use App\Http\Controllers\Admin\OutageReasonController as AdminOutageReasonController;
use App\Http\Controllers\Admin\ZoneController as AdminZoneController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\PublicAnnouncementController;
use App\Http\Controllers\PublicOutageController;
use App\Http\Controllers\PublicScheduleController;
use App\Http\Controllers\PublicZoneController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/language', LanguageController::class)->name('language.update');

Route::get('/zones', [PublicZoneController::class, 'index'])->name('zones.index');
Route::get('/zones/{zone:slug}', [PublicZoneController::class, 'show'])->name('zones.show');
Route::get('/schedules', [PublicScheduleController::class, 'index'])->name('schedules.index');
Route::get('/outages', [PublicOutageController::class, 'index'])->name('outages.index');
Route::get('/outages/history', [PublicOutageController::class, 'history'])->name('outages.history');
Route::get('/outages/{outage}', [PublicOutageController::class, 'show'])
    ->whereNumber('outage')
    ->name('outages.show');
Route::get('/announcements', [PublicAnnouncementController::class, 'index'])->name('announcements.index');
Route::get('/announcements/{announcement}', [PublicAnnouncementController::class, 'show'])
    ->whereNumber('announcement')
    ->name('announcements.show');

Route::prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])
        ->middleware('throttle:admin-login')
        ->name('login.store');

    Route::middleware(['auth', 'admin'])->group(function (): void {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.password.update');

        Route::resource('zones', AdminZoneController::class)->except('show');
        Route::resource('reasons', AdminOutageReasonController::class)->except('show');
        Route::patch('/reasons/{reason}/activation', [AdminOutageReasonController::class, 'updateActivation'])
            ->name('reasons.activation.update');
        Route::resource('schedules', AdminScheduleController::class)->except('show');
        Route::patch('/schedules/{schedule}/publish', [AdminScheduleController::class, 'publish'])
            ->name('schedules.publish');
        Route::patch('/schedules/{schedule}/cancel', [AdminScheduleController::class, 'cancel'])
            ->name('schedules.cancel');
        Route::patch('/outages/{outage}/restore', [AdminOutageController::class, 'restore'])
            ->name('outages.restore');
        Route::get('/outages/history', [AdminOutageController::class, 'history'])
            ->name('outages.history');
        Route::resource('outages', AdminOutageController::class)->except('destroy');
        Route::resource('announcements', AdminAnnouncementController::class)->except('show');
        Route::patch('/announcements/{announcement}/publish', [AdminAnnouncementController::class, 'publish'])
            ->name('announcements.publish');
        Route::patch('/announcements/{announcement}/unpublish', [AdminAnnouncementController::class, 'unpublish'])
            ->name('announcements.unpublish');
    });
});
