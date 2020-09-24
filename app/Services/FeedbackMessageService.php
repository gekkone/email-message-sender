<?php

namespace App\Services;

use App\Exceptions\ActionTimeoutException;
use App\FeedbackMessage;
use App\Http\Requests\FeedbackSendRequest;
use App\Jobs\SendFeedbackMessageJob;

class FeedbackMessageService
{
    /**
     * Допустимый интервал отправки сообщений в секундах
     * @var int
     */
    private $sendingInterval;

    /**
     * Создаёт новый экземпляр службы обработки сообщений обратной связи
     * @param int $sendingInterval - допустимый интервал отправки сообщений в секундах
     */
    public function __construct(int $sendingInterval = 60)
    {
        $this->sendingInterval = $sendingInterval;
    }

    public function handleRequest(FeedbackSendRequest $request)
    {
        $message = new FeedbackMessage($request->validated());
        $message->client_ip = $request->ip();

        $this->checkSendingInterval($message);

        $message->save();
        dispatch(new SendFeedbackMessageJob($message));
    }

    private function checkSendingInterval(FeedbackMessage $message)
    {
        $lastSend = FeedbackMessage::lastSendedMessage($message->client_ip);

        if (null !== $lastSend) {
            $interval = time() - $lastSend->create_at;

            if ($interval < $this->sendingInterval) {
                throw new ActionTimeoutException(
                    'отправить сообщение обратной связи',
                    $this->sendingInterval - $interval
                );
            }
        }
    }
}
