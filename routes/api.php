<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\RoutePath;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\Password\NewPasswordController;
use App\Http\Controllers\Password\PasswordResetLinkController;
use App\Http\Controllers\ProductController;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

$limiter = config('fortify.limiters.login');
// Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware(array_filter([
        'guest:' . config('fortify.guard'),
        $limiter ? 'throttle:' . $limiter : null,
    ]))->name('login');
Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

Route::get('/reset-password/{token}', function ($token, Request $request) {
    $spaUrl = env('FRONTEND_URL') . "/" . 'reset-password/' . $token . '?email=' . $request->query('email');
    return redirect($spaUrl);
})->name('password.reset');


Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware(['guest:' . config('fortify.guard')])
    ->name('password.email');

Route::post('/update-password', [NewPasswordController::class, 'store'])
    ->middleware(['guest:' . config('fortify.guard')])
    ->name('password.update');

Route::resource('/products', ProductController::class, ['except' => ['store', 'update', 'show']]);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::resource('/products', ProductController::class, ['only' => ['store']])->middleware('permission:create_product');
    Route::resource('/products', ProductController::class, ['only' => ['update']])->middleware('permission:update_product');
    Route::resource('/products', ProductController::class, ['only' => ['destroy']])->middleware('permission:delete_product');
    Route::get('/whoiam', [AuthController::class, 'whoiam'])->name('whoiam');
});
