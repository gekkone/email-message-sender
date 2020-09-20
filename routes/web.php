<?php

Route::permanentRedirect('/', '/feedback');

Route::group(['prefix' => 'feedback'], function () {
    Route::get('/', 'FeedbackMessageController@index');
    Route::post('/send', 'FeedbackMessageController@send')->name('feedbackSend');
});
