<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CutoffController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DtrController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DTRUploadController;
use App\Http\Controllers\PayslipController;

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
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/cutoff', [CutoffController::class, 'index'])->name('cutoff.index');
    Route::post('/cutoff/set', [CutoffController::class, 'set'])->name('cutoff.set');
    Route::get('/dtr', [DtrController::class, 'index'])->name('dtr.index');
    Route::post('/dtr/update', [DtrController::class, 'update'])->name('dtr.update');
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
    Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
    Route::put('/employees/{employee}', [EmployeeController::class, 'update']);
    Route::post('/excel-upload', [DTRUploadController::class, 'upload'])
    ->name('excel.upload');
    Route::get('/employee-suggestions', [PayslipController::class, 'suggestions'])
    ->name('employees.suggestions');
    Route::get('/payslip', [PayslipController::class, 'index'])->name('payslip.index');
});


require __DIR__.'/auth.php';
