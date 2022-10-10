<?php

namespace App\Services;

use App\Exceptions\ActionTimeoutException;
use App\FeedbackMessage;
use App\Http\Requests\FeedbackSendRequest;
use App\Jobs\SendFeedbackMessageJob;

class FeedbackMessageService
{
    public function handleRequest(FeedbackSendRequest $request): bool
    {
        $message = new FeedbackMessage($request->validated());
        $message->client_ip = $request->ip();

        if ($message->save()) {
            dispatch(new SendFeedbackMessageJob($message));
            return true;
        }

        return false;
    }
}
