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
    Route::post('/change_forgotten_password','changeForgottenPassword');
    Route::get('/check-email-reset-password/{token}/{email}','checkEmailResetPassword');
});


Route::group(['verified','middleware' => ['auth:sanctum','verified','teacher']], function(){
    Route::get('/logout',[AuthController::class, 'logout']);
    Route::get('/get-auth-user',[AuthController::class, 'user']);
    Route::post('/create-qanda', [QandAController::class, 'create']);
    Route::get('/get-qanda/{code}', [QandAController::class, 'get']);
    Route::post('/create-quiz', [QuizController::class, 'create']);
    Route::get('/get-all-quiz',[QuizController::class, 'show']);
   
});


// Verify email
Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

// Resend link to verify email
Route::get('/email/verify/resend', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return ['message'=> 'Verification link sent!'];
})->middleware(['auth:sanctum', 'throttle:6,1'])->name('verification.send');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
