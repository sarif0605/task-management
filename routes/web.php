<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Constructor\AttendanceController;
use App\Http\Controllers\Constructor\ConstraintController;
use App\Http\Controllers\Constructor\DashboardConstructorController;
use App\Http\Controllers\Constructor\DealProjectController;
use App\Http\Controllers\Constructor\MaterialController;
use App\Http\Controllers\Constructor\OpnamController;
use App\Http\Controllers\Constructor\OpnamMaterialConstraintController;
use App\Http\Controllers\Constructor\PenawaranProjectController;
use App\Http\Controllers\Constructor\ProspectController;
use App\Http\Controllers\Constructor\ReportProjectController;
use App\Http\Controllers\Constructor\SurveyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
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

Route::get('/register', function () {
    return view('auth.register');
});

Route::get('/verifikasi-view', [AuthController::class, 'verifikasiView'])->name('verifikasi-view');
Route::post('/verifikasi-otp', [AuthController::class, 'verifikasi'])->name('verifikasi');
Route::post('/generate', [AuthController::class, 'generateOtpCode'])->name('generate');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardConstructorController::class, 'index'])
    ->name('dashboard');
    Route::post('/store_project_data', [OpnamMaterialConstraintController::class, 'storeProjectData'])->name('store_project_data');
    Route::get('/create_project/{deal_project_id}', [OpnamMaterialConstraintController::class, 'createProject'])->name('create_project');

    Route::controller(UserController::class)->prefix('users')->group(function () {
        Route::get('', 'index')->name('users');
        Route::get('show/{id}', 'show')->name('users.show');
        Route::get('edit/{id}', 'edit')->name('users.edit');
        Route::put('update/{id}', 'update')->name('users.update');
        Route::delete('destroy/{id}', 'destroy')->name('users.destroy');
    });

    Route::controller(AttendanceController::class)->prefix('attendance')->group(function () {
        Route::get('', 'index')->name('attendance');
        Route::get('create', 'create')->name('attendance.create');
        Route::post('store', 'store')->name('attendance.store'); // This handles the POST request
        Route::get('edit', 'edit')->name('attendance.edit');
        Route::put('update', 'update')->name('attendance.update');
    });

    Route::controller(ProspectController::class)->prefix('prospects')->group(function () {
        Route::get('', 'index')->name('prospects');
        Route::post('export', 'export')->name('prospects.export');
        Route::get('create', 'create')->name('prospects.create');
        Route::post('store', 'store')->name('prospects.store');
        Route::get('show/{id}', 'show')->name('prospects.show');
        Route::get('edit/{id}', 'edit')->name('prospects.edit');
        Route::put('update/{id}', 'update')->name('prospects.update');
        Route::delete('destroy/{id}', 'destroy')->name('prospects.destroy');
    });

    Route::controller(MaterialController::class)->prefix('materials')->group(function () {
        Route::get('', 'index')->name('materials');
        Route::get('create/{report_project_id}', 'create')->name('materials.create');
        Route::post('store', 'store')->name('materials.store');
        Route::get('show/{id}', 'show')->name('materials.show');
        Route::get('edit/{id}', 'edit')->name('materials.edit');
        Route::put('update/{id}', 'update')->name('materials.update');
        Route::delete('destroy/{id}', 'destroy')->name('materials.destroy');
    });

    Route::controller(OpnamController::class)->prefix('opnams')->group(function () {
        Route::get('', 'index')->name('opnams');
        Route::get('create/{report_project_id}', 'create')->name('opnams.create');
        Route::post('store', 'store')->name('opnams.store');
        Route::get('show/{id}', 'show')->name('opnams.show');
        Route::get('edit/{id}', 'edit')->name('opnams.edit');
        Route::put('update/{id}', 'update')->name('opnams.update');
        Route::delete('destroy/{id}', 'destroy')->name('opnams.destroy');
    });

    Route::controller(ConstraintController::class)->prefix('constraints')->group(function () {
        Route::get('', 'index')->name('constraints');
        Route::get('create/{report_project_id}', 'create')->name('constraints.create');
        Route::post('store', 'store')->name('constraints.store');
        Route::get('show/{id}', 'show')->name('constraints.show');
        Route::get('edit/{id}', 'edit')->name('constraints.edit');
        Route::put('update/{id}', 'update')->name('constraints.update');
        Route::delete('destroy/{id}', 'destroy')->name('constraints.destroy');
    });

    Route::controller(PenawaranProjectController::class)->prefix('penawaran_projects')->group(function () {
        Route::get('', 'index')->name('penawaran_projects');
        Route::post('store', 'store')->name('penawaran_projects.store');
        Route::get('show/{id}', 'show')->name('penawaran_projects.show');
        Route::get('edit/{id}', 'edit')->name('penawaran_projects.edit');
        Route::put('update/{id}', 'update')->name('penawaran_projects.update');
        Route::delete('destroy/{id}', 'destroy')->name('penawaran_projects.destroy');
        Route::post('download/{id}/{type}', 'downloadFile')->name('penawaran_projects.download');
    });

    Route::controller(SurveyController::class)->prefix('surveys')->group(function () {
        Route::get('', 'index')->name('surveys');
        Route::post('store', 'store')->name('surveys.store');
        Route::get('show/{id}', 'show')->name('surveys.show');
        Route::get('edit/{id}', 'edit')->name('surveys.edit');
        Route::put('update/{id}', 'update')->name('surveys.update');
        Route::delete('destroy/{id}', 'destroy')->name('surveys.destroy');
    });

    Route::controller(DealProjectController::class)->prefix('deal_projects')->group(function () {
        Route::get('', 'index')->name('deal_projects');
        Route::post('store', 'store')->name('deal_projects.store');
        Route::get('show/{id}', 'show')->name('deal_projects.show');
        Route::get('edit/{id}', 'edit')->name('deal_projects.edit');
        Route::put('update/{id}', 'update')->name('deal_projects.update');
        Route::delete('destroy/{id}', 'destroy')->name('deal_projects.destroy');
    });

    Route::controller(ReportProjectController::class)->prefix('report_projects')->group(function () {
        Route::get('', 'index')->name('report_projects');
        Route::get('create/{deal_project_id}', 'create')->name('report_projects.create');
        Route::post('store', 'store')->name('report_projects.store');
        Route::get('show/{id}', 'show')->name('report_projects.show');
        Route::post('export/{deal_project_id}', 'import')->name('report_projects.export');
        Route::get('edit/{id}', 'edit')->name('report_projects.edit');
        Route::put('edit/{id}', 'update')->name('report_projects.update');
        Route::delete('destroy/{id}', 'destroy')->name('report_projects.destroy');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
