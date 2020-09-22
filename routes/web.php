<?php

Route::permanentRedirect('/', '/feedback');

Route::group(['prefix' => 'feedback'], function () {
    Route::get('/', 'FeedbackMessageController@index');
    Route::post('/send', 'FeedbackMessageController@send')
        ->middleware('recaptcha:0.5')
        ->name('feedbackSend');
});
