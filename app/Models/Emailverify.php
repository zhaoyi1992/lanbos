<?php

namespace App\Models;
use App\Extends\Libs\AdminBaseModel;
use Illuminate\Database\Eloquent\Model;

class Emailverify extends Model
{
    use AdminBaseModel;
    protected $connection = 'mysql';
    protected $table = 'lb_emailverify';
    public $timestamps = false;
    protected $primaryKey = 'id';

    public $incrementing = true;


    protected $fillable = ['id','email','type','code','created_at'];
}

