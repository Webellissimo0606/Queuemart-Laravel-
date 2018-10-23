<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyModel extends Model
{
    protected $fillable = ['user_id', 'company_url', 'company_image', 'company_name', 'company_tel_num', 'company_des', 'permission_status', 'support_id', 'questionnaire_link'];
}
