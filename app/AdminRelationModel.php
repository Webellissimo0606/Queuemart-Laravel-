<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminRelationModel extends Model
{
    protected $fillable = ['user_id', 'company_id', 'branch_id', 'service_id'];
}
