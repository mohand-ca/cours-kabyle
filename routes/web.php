<?php

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\TeacherRegister;
use App\Livewire\Teacher\AvailabilityCalendar;
use App\Livewire\Teacher\Dashboard as TeacherDashboard;
use App\Livewire\Teacher\ProfileSetup;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Auth — guest only
Route::middleware('guest')->group(function () {
    Route::get('/register', Register::class)->name('register');
    Route::get('/login', Login::class)->name('login');
    Route::get('/become-a-teacher', TeacherRegister::class)->name('teacher.register');
});

// Authenticated + verified
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return 'Dashboard apprenant — à venir (Phase 3)';
    })->middleware('role:learner')->name('dashboard');

    Route::post('/logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/');
    })->name('logout');

    // Espace enseignant
    Route::middleware('role:teacher')->prefix('teacher')->name('teacher.')->group(function () {
        Route::get('/dashboard', TeacherDashboard::class)->name('dashboard');
        Route::get('/profile', ProfileSetup::class)->name('profile');
        Route::get('/availability', AvailabilityCalendar::class)->name('availability');
    });
});

// Email verification
Route::get('/email/verify', function () {
    return 'Vérification email — vérifie ta boîte mail.';
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    $user = auth()->user();

    return $user->hasRole('teacher')
        ? redirect()->route('teacher.dashboard')
        : redirect()->route('dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('status', 'verification-link-sent');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');
