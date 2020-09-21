<?php

namespace App\Http\Controllers;

use App\Http\Requests\FeedbackSendRequest;

class FeedbackMessageController extends Controller
{
    public function index()
    {
        return view('feedback');
    }

    public function send(FeedbackSendRequest $request)
    {
       return response()->json(['message' => 'Сообщение принято к отправке']);
    }
}
