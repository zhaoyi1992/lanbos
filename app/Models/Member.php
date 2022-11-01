<?php

namespace App\Models;
use App\Extends\Libs\AdminBaseModel;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $connection = 'mysql';
    use AdminBaseModel;

    protected $table = 'lb_member';

    protected $primaryKey = 'id';

    public $incrementing = true;

    public $timestamps = true;

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    protected $fillable = ['id','name','appid','email','sex','token', 'phone', 'password', 'photo','created_at','updated_at'];
}

