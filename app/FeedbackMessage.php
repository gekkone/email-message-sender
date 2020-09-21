<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FeedbackMessage extends Model
{
    public $timestamps = false;

    public $fillable = ['content'];
}
