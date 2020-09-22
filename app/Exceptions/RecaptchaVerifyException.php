<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class RecaptchaVerifyException extends Exception implements HttpExceptionInterface
{
    public function __construct(string $message, int $code = 422, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Возвращает код состояния HTTP ответа
     * @return int код состояния
     */
    public function getStatusCode(): int
    {
        return 422;
    }

    /**
     * Возвращает заголовки HTTP ответа
     * @return array<string, string> заголовки ответа
     */
    public function getHeaders(): array
    {
        return [];
    }
}
