<?php

use App\Http\Controllers;
use Illuminate\Support\Facades\Route;

Route::permanentRedirect('/', '/feedback');

Route::group(['prefix' => 'feedback'], function () {
    Route::get('/', [Controllers\FeedbackMessageController::class, 'index']);
    Route::post('/send', [Controllers\FeedbackMessageController::class, 'send'])
        ->middleware('recaptcha:0.5')
        ->name('feedbackSend');
});
