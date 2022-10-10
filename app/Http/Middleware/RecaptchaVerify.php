<?php

namespace App\Http\Middleware;

use App\Exceptions\RecaptchaVerifyException;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RecaptchaVerify
{
    /**
     * Обрабатывает запрос, отклоняет его, если не пройдена верификация Recaptch'и
     *
     * @param Request $request
     * @param Closure $next
     * @param null|float $passingScore - если null, использует значение из конфигурации
     * @return mixed
     * @throws RecaptchaVerifyException
     */
    public function handle(Request $request, Closure $next, ?float $passingScore = null): mixed
    {
        if (config('app.validate_recaptcha', false)) {
            $passingScore = $passingScore ?? config('app.recaptcha_passing_score', 0.5);
            $token = $request->input('g-recaptcha-response');

            if (empty($token) || $this->recaptchaScore($token) < $passingScore) {
                throw new RecaptchaVerifyException('Не пройдена анти-спам проверка');
            }
        }

        return $next($request);
    }

    /**
     * Возвращает оценку человечности пользователя
     * @param string $token - клиентский токен recaptcha
     * @return float - оценка пользователя
     * @throws RecaptchaVerifyException
     */
    private function recaptchaScore(string $token): float
    {
        try {
            $response = Http::connectTimeout(15)->timeout(15)
                ->asForm()->acceptJson()->post(
                    'https://www.google.com/recaptcha/api/siteverify',
                    [
                        'secret' => config('secrets.recaptcha.secret_key'),
                        'response' => $token
                    ]
                )->throw();
        } catch (\Throwable $e) {
            throw new RecaptchaVerifyException(
                'Не удалось произвести анти-спам проверку',
                422,
                $e
            );
        }

        if (!$response->json('success', false)) {
            Log::warning('Не удалось пройти анти-спам проверку', $response->json());
        }
        return $response->json('score', 0);
    }
}
