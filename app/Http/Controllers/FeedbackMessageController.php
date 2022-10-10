<?php

namespace App\Http\Controllers;

use App\Http\Requests\FeedbackSendRequest;
use App\Services\FeedbackMessageService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;

class FeedbackMessageController extends Controller
{
    private FeedbackMessageService $messageService;

    public function __construct(FeedbackMessageService $messageService)
    {
        $this->messageService = $messageService;
    }

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
     * @param FeedbackSendRequest $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function send(FeedbackSendRequest $request): \Symfony\Component\HttpFoundation\Response
    {
        if ($this->messageService->handleRequest($request)) {
            return Response::json(['message' => 'Сообщение принято к отправке']);
        } else {
            Log::warning('Не удалось принять сообщение к отправке', [$request]);
            return Response::json(['message' => 'Не удалось принять сообщение к отправке, пожалуйста повторите попытку позднее']);
        }
    }
}
