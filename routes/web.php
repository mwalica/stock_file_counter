<?php

use Illuminate\Support\Facades\Route;

Route::livewire('login', 'pages::auth.login')->middleware('guest')->name('login');

Route::middleware(['auth'])->group(function() {
    Route::livewire('/', 'pages::photo.index')->name('photo.index');
    Route::livewire('summary', 'pages::summary.index')->name('summary.index');
    Route::post('logout', function() {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect()->route('login');
    })->name('logout');
});
