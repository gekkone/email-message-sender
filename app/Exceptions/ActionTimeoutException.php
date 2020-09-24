<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class ActionTimeoutException extends Exception implements HttpExceptionInterface
{
    private $secondTimeout;

    /**
     * Создаёт новый экземпляр исключения
     * @param string $action - описание действия, отвечает на вопрос "что сделать?"
     * @param integer $secondTimeout - допустимый интервал повторения действия
     * @param integer $code - код
     * @param Throwable $previous - предшествующее исключение
     */
    public function __construct(string $action, int $secondTimeout, $code = 429, Throwable $previous = null)
    {
        $this->secondTimeout = $secondTimeout;
        $message = "Вы сможете $action через $secondTimeout секунд";

        parent::__construct($message, $code, $previous);
    }

    /**
     * Возвращает код состояния HTTP ответа
     * @return int код состояния
     */
    public function getStatusCode(): int
    {
        return 429;
    }

    /**
     * Возвращает заголовки HTTP ответа
     * @return array<string, string> заголовки ответа
     */
    public function getHeaders(): array
    {
        return [
            'Retry-After' => $this->secondTimeout
        ];
    }
}
