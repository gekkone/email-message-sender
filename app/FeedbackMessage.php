<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string content
 * @property string client_ip
 * @property string create_at
 */
class FeedbackMessage extends Model
{
    public $timestamps = false;

    public $fillable = ['content'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->create_at = time();
    }
}
