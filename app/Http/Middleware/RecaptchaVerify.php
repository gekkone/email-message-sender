<?php

namespace App\Http\Middleware;

use App\Exceptions\RecaptchaVerifyException;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RecaptchaVerify
{
    /**
     * Обрабатывает запрос, отклоняет его, если не пройдена верификация Recaptch'и
     *
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, float $passingScore = 0.5)
    {
        if (config('app.validate_recaptcha')) {
            $token = $request->input('g-recaptcha-response');
            if (!is_string($token) || $this->recaptchaScore($token) < $passingScore) {
                throw new RecaptchaVerifyException('Не пройдена анти-спам проверка');
            }
        }

        return $next($request);
    }

    /**
     * Возвращает оценку человечности пользователя
     * @param string $token - клиентский токен recaptcha
     * @return float оценка пользователя
     */
    private function recaptchaScore(string $token)
    {
        $secret = config('secrets.recaptcha.secret_key');

        $ch = curl_init('https://www.google.com/recaptcha/api/siteverify');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => "secret=$secret&response=$token",
            CURLOPT_CONNECTTIMEOUT => 15,
            CURLOPT_TIMEOUT => 15
        ]);

        /**
         * @var string
         */
        $result = curl_exec($ch);
        if (!$result) {
            Log::error("Не удалось проверить токен reacaptch'и: \n"
                . curl_errno($ch) . ' ' . curl_error($ch));

            throw new RecaptchaVerifyException('Не удалось произвести анти-спам проверку');
        }

        $jsonData = json_decode($result, true);
        if (is_array($jsonData) && ($jsonData['success'] ?? false)) {
            return (float)$jsonData['score'] ?? 0;
        } else {
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error("Не удалось привести ответ от Recaptch'a к json:\n"
                    . json_last_error_msg());
                Log::error($result);
            }

            Log::error($jsonData);
            throw new RecaptchaVerifyException('Не удалось произвести анти-спап проверку');
        }
    }
}
