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
}
