<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BranchModel extends Model
{
    protected $fillable = ['user_id', 'branch_image', 'branch_name', 'branch_label', 'branch_tel_num', 'branch_des', 'branch_address', 'longitude', 'latitude'];
}
