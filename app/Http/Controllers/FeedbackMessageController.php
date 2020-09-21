<?php

namespace App\Http\Controllers;

use App\FeedbackMessage;
use App\Http\Requests\FeedbackSendRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Exception;

class FeedbackMessageController extends Controller
{
    /**
     * Возвращает форму обратной связи
     * @return View
     */
    public function index(): View
    {
        return view('feedback');
    }

    /**
     * Обрабатывает запрос на отправку сообщения обратной связи
     * @param \App\Http\Requests\FeedbackSendRequest $request
     */
    public function send(FeedbackSendRequest $request)
    {
        try {
            $message = new FeedbackMessage($request->toArray());
            $message->save();

            return response()->json(['message' => 'Сообщение принято к отправке']);
        } catch (Exception $e) {
            Log::error($e);

            return response()->json(['message' => 'Возникла ошибка, сервис временно недоступен'], 500);
        }
    }
}
