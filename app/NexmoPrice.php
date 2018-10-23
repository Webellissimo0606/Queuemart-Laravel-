<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NexmoPrice extends Model
{
    protected $fillable = ['balance', 'price', 'status'];
}
