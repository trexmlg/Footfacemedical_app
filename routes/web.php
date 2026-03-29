<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FullCalendarController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PodologController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/locale/{locale}', function (string $locale) {
    if (! in_array($locale, ['lv', 'en', 'ru'], true)) {
        abort(404);
    }

    session(['locale' => $locale]);

    return back();
})->name('locale.switch');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:6,1')->name('login.submit');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:6,1')->name('register.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/fullcalender', [FullCalendarController::class, 'index'])
    ->middleware('auth')
    ->name('calendar.events');

Route::post('/fullcalenderAjax', [FullCalendarController::class, 'ajax'])
    ->middleware('auth')
    ->name('calendar.events.ajax');

Route::get('/calendar', [FullCalendarController::class, 'index'])
    ->middleware('auth')
    ->name('calendar.view');

Route::get('/info', function () {
    return view('info');
})->middleware('auth')->name('info.view');

Route::middleware('auth')->group(function () {
    Route::get('/profile/card', [ProfileController::class, 'edit'])->name('profile.card');
    Route::put('/profile/card', [ProfileController::class, 'update'])->name('profile.card.update');
});

Route::middleware(['auth', 'role:podolog,admin'])->group(function () {
    Route::get('/podolog/dashboard', [PodologController::class, 'dashboard'])->name('podolog.dashboard');
    Route::put('/podolog/reservations/{event}', [PodologController::class, 'updateReservation'])->name('podolog.reservations.update');
    Route::get('/patients/{user}', [ProfileController::class, 'showForManager'])->name('patients.show');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::put('/admin/users/{user}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
    Route::post('/admin/users/{userId}/restore', [AdminController::class, 'restoreUser'])->name('admin.users.restore');
    Route::put('/admin/users/{user}/role', [AdminController::class, 'updateUserRole'])->name('admin.users.role.update');
    Route::put('/admin/reservations/{event}', [AdminController::class, 'updateReservation'])->name('admin.reservations.update');
    Route::delete('/admin/reservations/{event}', [AdminController::class, 'deleteReservation'])->name('admin.reservations.delete');
    Route::post('/admin/reservations/{eventId}/restore', [AdminController::class, 'restoreReservation'])->name('admin.reservations.restore');
    Route::post('/admin/promotions', [AdminController::class, 'createPromotion'])->name('admin.promotions.create');
    Route::put('/admin/promotions/{promotion}', [AdminController::class, 'updatePromotion'])->name('admin.promotions.update');
    Route::delete('/admin/promotions/{promotion}', [AdminController::class, 'deletePromotion'])->name('admin.promotions.delete');
    Route::post('/admin/promotions/{promotionId}/restore', [AdminController::class, 'restorePromotion'])->name('admin.promotions.restore');
});

Route::get('/', [HomeController::class, 'index'])->name('home.view');