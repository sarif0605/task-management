<?php

use App\Http\Controllers\DealProjectController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProspectController;
use App\Http\Controllers\SurveyController;
use App\Models\DealProject;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::controller(ProspectController::class)->prefix('prospects')->group(function () {
        Route::get('', 'index')->name('prospects');
        Route::get('create', 'create')->name('prospects.create');
        Route::post('store', 'store')->name('prospects.store');
        Route::get('show/{id}', 'show')->name('prospects.show');
        Route::get('edit/{id}', 'edit')->name('prospects.edit');
        Route::put('edit/{id}', 'update')->name('prospects.update');
        Route::delete('destroy/{id}', 'destroy')->name('prospects.destroy');
    });

    Route::controller(SurveyController::class)->prefix('surveys')->group(function () {
        Route::get('', 'index')->name('surveys');
        Route::get('create', 'create')->name('surveys.create');
        Route::post('store', 'store')->name('surveys.store');
        Route::get('show/{id}', 'show')->name('surveys.show');
        Route::get('edit/{id}', 'edit')->name('surveys.edit');
        Route::put('edit/{id}', 'update')->name('surveys.update');
        Route::delete('destroy/{id}', 'destroy')->name('surveys.destroy');
    });

    Route::controller(DealProjectController::class)->prefix('deal_projects')->group(function () {
        Route::get('', 'index')->name('deal_projects');
        Route::get('create', 'create')->name('deal_projects.create');
        Route::post('store', 'store')->name('deal_projects.store');
        Route::get('show/{id}', 'show')->name('deal_projects.show');
        Route::get('edit/{id}', 'edit')->name('deal_projects.edit');
        Route::put('edit/{id}', 'update')->name('deal_projects.update');
        Route::delete('destroy/{id}', 'destroy')->name('deal_projects.destroy');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/admin', function() {
    return '<h1>Hello Admin</h1>';
})->middleware(['auth', 'verified', 'role:admin']);

require __DIR__.'/auth.php';
