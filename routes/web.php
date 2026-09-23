<?php

use App\Http\Controllers\CheckoutController;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Auth\TeacherRegister;
use App\Livewire\Learner\Dashboard as LearnerDashboard;
use App\Livewire\Learner\ManageLearners;
use App\Livewire\Learner\PackageCatalog;
use App\Livewire\Learner\TeacherCatalog;
use App\Livewire\Teacher\AvailabilityCalendar;
use App\Livewire\Teacher\Dashboard as TeacherDashboard;
use App\Livewire\Teacher\ProfileSetup;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/locale/{lang}', function (string $lang) {
    if (in_array($lang, ['fr', 'en'])) {
        session(['locale' => $lang]);
    }

    return redirect()->back()->withInput();
})->name('locale.switch');

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Auth — guest only
Route::middleware('guest')->group(function () {
    Route::get('/register', Register::class)->name('register');
    Route::get('/login', Login::class)->name('login');
    Route::get('/become-a-teacher', TeacherRegister::class)->name('teacher.register');
    Route::get('/forgot-password', ForgotPassword::class)->name('password.request');
    Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset');
});

// Authenticated + verified
Route::middleware(['auth', 'verified'])->group(function () {
    // Espace apprenant
    Route::middleware('role:learner')->name('learner.')->group(function () {
        Route::get('/dashboard', LearnerDashboard::class)->name('dashboard');
        Route::get('/learners', ManageLearners::class)->name('learners');
        Route::get('/teachers', TeacherCatalog::class)->name('teachers');
        Route::get('/packages', PackageCatalog::class)->name('packages');
        Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
        Route::get('/checkout/{key}', [CheckoutController::class, 'create'])->name('checkout');
    });

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
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    $user = auth()->user();

    return $user->hasRole('teacher')
        ? redirect()->route('teacher.dashboard')
        : redirect()->route('learner.dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('status', 'verification-link-sent');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');
