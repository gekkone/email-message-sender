<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FeedbackMessage extends Model
{
    public $timestamps = false;

    public $fillable = ['content'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->create_at = time();
    }

    /**
     * Возвращает последнее отправленное с переданного ip соощение
     * @param string $ip - ip адрес клиента
     * @return FeedbackMessage|null
     */
    public static function lastSendedMessage(string $ip)
    {
        return self::where('client_ip', $ip)->orderBy('create_at', 'desc')->first();
    }
}
