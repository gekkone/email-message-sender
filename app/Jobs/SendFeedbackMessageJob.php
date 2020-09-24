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
     * @var FeedbackMessage
     */
    private $feedbackMessage;

    /**
     * Создаёт новый экзепляр задачи
     * @return void
     */
    public function __construct(FeedbackMessage $feedbackMeesage)
    {
        $this->feedbackMessage = $feedbackMeesage;
    }

    /**
     * Обрабатывает задачу
     * @return void
     */
    public function handle()
    {
        //TODO: Добавить обработку или хотя бы связать с фейковым сервисом
    }
}
