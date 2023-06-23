<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\Password\NewPasswordController;
use App\Http\Controllers\Password\PasswordResetLinkController;

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


Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::get('/reset-password/{token}', function ($token, Request $request) {
    $spaUrl = env('FRONTEND_URL') ."/". 'reset-password/' . $token . '?email=' . $request->query('email');
    return redirect($spaUrl);
})->name('password.reset');


Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware(['guest:' . config('fortify.guard')])
    ->name('password.email');

Route::post('/update-password', [NewPasswordController::class, 'store'])
    ->middleware(['guest:' . config('fortify.guard')])
    ->name('password.update');


Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/whoiam', [AuthController::class, 'whoiam'])->name('whoiam');
});
