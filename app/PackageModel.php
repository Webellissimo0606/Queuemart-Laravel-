<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PackageModel extends Model
{
    protected $fillable = ['package_name', 'package_des', 'package_unit', 'package_price', 'package_participants', 'service_id', 'credit_amount'];
}
