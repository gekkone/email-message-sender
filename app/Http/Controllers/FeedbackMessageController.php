<?php

namespace App\Http\Controllers;

use App\Http\Requests\FeedbackSendRequest;
use App\Services\FeedbackMessageService;
use Illuminate\View\View;

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
        $feedbackService = resolve(FeedbackMessageService::class);
        $feedbackService->handleRequest($request);

        return response()->json(['message' => 'Сообщение принято к отправке']);
    }
}
