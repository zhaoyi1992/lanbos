<?php

namespace App\Models;
use App\Extends\Libs\AdminBaseModel;
use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    use AdminBaseModel;
    protected $connection = 'mysql';
    protected $table = 'lb_member';
    protected $fillable = ['billingid','userid','money_type','transaction','amount','description','status'];
    public $timestamps = false;
}

