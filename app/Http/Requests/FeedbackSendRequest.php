<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FeedbackSendRequest extends FormRequest
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
        return [
            'content' => 'required'
        ];
    }

    /**
     * Возвращает перечень сообщений об ошибках
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'content.required' => 'Поле "Текст сообщения" обязательно для заполнения'
        ];
    }
}
