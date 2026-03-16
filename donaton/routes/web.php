
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
use App\Http\Controllers\MercadoPagoController;
use App\Http\Controllers\PayPalController;
use App\Http\Controllers\Admin\AdminDashboardController;

//use App\Http\Controllers\MercadoPagoWebhookController;


Route::get('/', [PageController::class, 'landing'])->name('landing');

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth', 'admin'])->name('admin.dashboard');



Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
    ->middleware(['auth', 'admin'])
    ->name('admin.dashboard');

Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user && $user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('causas.index');
})->middleware(['auth'])->name('dashboard');

Route::post('/contacto', [PageController::class, 'contactoSend'])
    ->middleware('throttle:10,1')
    ->name('contacto.send');

Route::get('/contacto', [PageController::class, 'contactoForm'])->name('contacto.form');


Route::middleware(['auth'])->group(function () {

    Route::post('/donar/{slug}/paypal', [PayPalController::class, 'start'])->name('paypal.start');
    Route::get('/paypal/return', [PayPalController::class, 'return'])->name('paypal.return');
    Route::get('/paypal/cancel', [PayPalController::class, 'cancel'])->name('paypal.cancel');

    Route::post('/mercadopago/sync/{donation}', [MercadoPagoController::class, 'sync'])
        ->name('mp.sync');

    Route::post('/donar/{slug}/mercadopago', [MercadoPagoController::class, 'start'])->name('mp.start');
    Route::get('/mercadopago/return/{result}', [MercadoPagoController::class, 'returnPage'])
    ->whereIn('result', ['success', 'pending', 'failure'])
    ->name('mp.return');

    Route::get('/mis-donativos', [DonationController::class, 'mine'])->name('donaciones.mine');
    Route::get('/donar/{slug}', [DonationController::class, 'create'])->name('donaciones.create');
    Route::post('/donar/{slug}', [DonationController::class, 'store'])->name('donaciones.store');

    Route::get('/causas', [CauseController::class, 'index'])->name('causas.index');
    Route::get('/causas/{slug}', [CauseController::class, 'show'])->name('causas.show');

});
/*
Route::post('/webhooks/mercadopago', [MercadoPagoWebhookController::class, 'handle'])
    ->middleware('throttle:60,1')
    ->name('mp.webhook');
*/
require __DIR__.'/auth.php';
