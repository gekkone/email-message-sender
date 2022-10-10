<?php

namespace App\Jobs;

use App\FeedbackMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendFeedbackMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Отправляемое сообщение обратной связи
     */
    private FeedbackMessage $feedbackMessage;

    /**
     * Создаёт новый экземпляр задачи
     */
    public function __construct(FeedbackMessage $feedbackMessage)
    {
        $this->feedbackMessage = $feedbackMessage;
    }

    /**
     * Обрабатывает задачу
     */
    public function handle(): void
    {
        //TODO: Добавить обработку или хотя бы связать с фейковым сервисом
    }
}
