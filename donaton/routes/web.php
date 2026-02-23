
<?php
/*
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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
});

require __DIR__.'/auth.php';
*/


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\CauseController;
use App\Http\Controllers\DonationController;

Route::get('/', [PageController::class, 'landing'])->name('landing');

Route::get('/admin/dashboard', function () {
    abort_unless(auth()->user()?->role === 'admin', 403);
    return view('admin.dashboard');
})->middleware(['auth'])->name('admin.dashboard');

Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user && $user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('causas.index');
})->middleware(['auth'])->name('dashboard');

Route::get('/contacto', [PageController::class, 'contactoForm'])->name('contacto.form');
Route::post('/contacto', [PageController::class, 'contactoSend'])->name('contacto.send');

Route::middleware(['auth'])->group(function () {
    Route::get('/causas', [CauseController::class, 'index'])->name('causas.index');
    Route::get('/causas/{slug}', [CauseController::class, 'show'])->name('causas.show');

    Route::get('/donar/{slug}', [DonationController::class, 'create'])->name('donaciones.create');
});

require __DIR__.'/auth.php';
