<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Panel_news extends Model
{
	protected $fillable = ['user_id', 'news_image', 'news_des', 'news_duration'];
}
