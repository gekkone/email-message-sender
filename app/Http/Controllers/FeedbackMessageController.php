<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FeedbackMessageController extends Controller
{
    public function index()
    {
        return response()->view('feedback');
    }

    public function send()
    {

    }
}
