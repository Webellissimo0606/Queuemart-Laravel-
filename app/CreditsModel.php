<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CreditsModel extends Model
{
    protected $fillable = ['booking_id', 'user_id', 'other_id', 'branch_id', 'service_id', 'package_id', 'credit_unit', 'credit_amount'];
}
