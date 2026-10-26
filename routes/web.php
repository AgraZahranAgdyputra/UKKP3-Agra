<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'permission:view dashboard'])->name('dashboard');

Route::resource('users', UserController::class)
    ->middleware(['auth', 'permission:manage users'])
    ->except('show');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('categories', CategoryController::class)->middleware(['auth', 'permission:manage categories'])->except('show');

Route::get('/pengaduans', [PengaduanController::class,'index'])
    ->middleware(['auth', 'permission:view pengaduan'])
    ->name('pengaduans.index');

Route::get('/pengaduans/create', [PengaduanController::class,'create'])
    ->middleware(['auth', 'permission:create pengaduan'])
    ->name('pengaduans.create');

Route::post('/pengaduans', [PengaduanController::class,'store'])
    ->middleware(['auth', 'permission:create pengaduan'])
    ->name('pengaduans.store');

Route::get('/pengaduans/{pengaduan}/edit', [PengaduanController::class,'edit'])
    ->middleware(['auth', 'permission:edit pengaduan'])
    ->name('pengaduans.edit');

Route::put('/pengaduans/{pengaduan}', [PengaduanController::class,'update'])
    ->middleware(['auth', 'permission:edit pengaduan'])
    ->name('pengaduans.update');

Route::delete('/pengaduans/{pengaduan}', [PengaduanController::class,'destroy'])
    ->middleware(['auth', 'permission:delete pengaduan'])
    ->name('pengaduans.destroy');

Route::get('/pengaduans/{pengaduan}/files', [PengaduanController::class, 'files'])
    ->middleware(['auth', 'permission:view pengaduan'])
    ->name('pengaduans.files');

require __DIR__.'/auth.php';
