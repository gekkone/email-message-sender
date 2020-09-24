<?php

namespace App\Providers;

use App\Services\FeedbackMessageService;
use Illuminate\Support\ServiceProvider;

class FeedbackMessageServiceProvider extends ServiceProvider
{
    /**
     * Регистрирует сервис
     * @return void
     */
    public function register()
    {
        $this->app->singleton(FeedbackMessageService::class, function () {
            return new FeedbackMessageService(60);
        });
    }
}
