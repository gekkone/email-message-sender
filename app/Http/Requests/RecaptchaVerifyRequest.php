<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecaptchaVerifyRequest extends FormRequest
{
    /**
     * Определяет авторизован ли пользователь для выполнения этого запроса
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Возвращает набор правил для проверки запроса
     * @return array<string, string>
     */
    public function rules()
    {
        if (config('app.validate_recaptcha')) {
            return [
                'g-recaptcha-response' => 'required'
            ];
        }

        return [];
    }
}
