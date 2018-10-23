<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QueueScreenModel extends Model
{
    protected $fillable = ['user_id', 'qr_screen_video', 'qr_screen_news', 'display_client'];
}
