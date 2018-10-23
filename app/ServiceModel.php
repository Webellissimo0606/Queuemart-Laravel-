<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceModel extends Model
{
    protected $fillable = ['user_id', 'service_image', 'service_name', 'service_des', 'service_participants', 'show_sequence', 'service_type'];
}
