<?php

use App\Http\Controllers\Constructor\ConstraintController;
use App\Http\Controllers\Constructor\DashboardConstructorController;
use App\Http\Controllers\Constructor\DealProjectController;
use App\Http\Controllers\Constructor\MaterialController;
use App\Http\Controllers\Constructor\OpnamController;
use App\Http\Controllers\Constructor\OpnamMaterialConstraintController;
use App\Http\Controllers\Constructor\ProspectController;
use App\Http\Controllers\Constructor\SurveyController;
use App\Http\Controllers\ProfileController;
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
    Route::get('/dashboard', [DashboardConstructorController::class, 'index'])->name('dashboard');
    Route::post('/store_project_data', [OpnamMaterialConstraintController::class, 'storeProjectData'])->name('store_project_data');
    Route::get('/create_project/{deal_project_id}', [OpnamMaterialConstraintController::class, 'createProject'])->name('create_project');

    Route::controller(ProspectController::class)->prefix('prospects')->group(function () {
        Route::get('', 'index')->name('prospects');
        Route::get('/export', 'export')->name('prospects.export');
        Route::get('create', 'create')->name('prospects.create');
        Route::post('store', 'store')->name('prospects.store');
        Route::get('show/{id}', 'show')->name('prospects.show');
        Route::get('edit/{id}', 'edit')->name('prospects.edit');
        Route::put('update/{id}', 'update')->name('prospects.update');
        Route::delete('destroy/{id}', 'destroy')->name('prospects.destroy');
    });

    Route::controller(MaterialController::class)->prefix('materials')->group(function () {
        Route::get('', 'index')->name('materials');
        Route::get('create', 'create')->name('materials.create');
        Route::post('store', 'store')->name('materials.store');
        Route::get('show/{id}', 'show')->name('materials.show');
        Route::get('edit/{id}', 'edit')->name('materials.edit');
        Route::put('update/{id}', 'update')->name('materials.update');
        Route::delete('destroy/{id}', 'destroy')->name('materials.destroy');
    });

    Route::controller(OpnamController::class)->prefix('opnams')->group(function () {
        Route::get('', 'index')->name('opnams');
        Route::get('create', 'create')->name('opnams.create');
        Route::post('store', 'store')->name('opnams.store');
        Route::get('show/{id}', 'show')->name('opnams.show');
        Route::get('edit/{id}', 'edit')->name('opnams.edit');
        Route::put('update/{id}', 'update')->name('opnams.update');
        Route::delete('destroy/{id}', 'destroy')->name('opnams.destroy');
    });

    Route::controller(ConstraintController::class)->prefix('constraints')->group(function () {
        Route::get('', 'index')->name('constraints');
        Route::get('create', 'create')->name('constraints.create');
        Route::post('store', 'store')->name('constraints.store');
        Route::get('show/{id}', 'show')->name('constraints.show');
        Route::get('edit/{id}', 'edit')->name('constraints.edit');
        Route::put('update/{id}', 'update')->name('constraints.update');
        Route::delete('destroy/{id}', 'destroy')->name('constraints.destroy');
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
