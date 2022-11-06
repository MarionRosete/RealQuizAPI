<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\QandAController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\VerifyEmailController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'login');
    Route::post('/register','register');
    Route::post('/forgot_password','forgot')->name('password.reset');
    Route::post('/change_forgotten_password/{token}','changeForgottenPassword');
});


Route::group(['verified','middleware' => ['auth:sanctum','verified','teacher']], function(){
    Route::post('/create-qanda', [QandAController::class, 'create']);
    Route::post('/create-quiz', [QuizController::class, 'create']);
    Route::get('/logout',[AuthController::class, 'logout']);
});


// Verify email
Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

// Resend link to verify email
Route::post('/email/verify/resend', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth:api', 'throttle:6,1'])->name('verification.send');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
