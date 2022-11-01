<?php

namespace App\Models;
use App\Extends\Libs\AdminBaseModel;
use Illuminate\Database\Eloquent\Model;

class Behaviour extends Model
{
    use AdminBaseModel;
    protected $connection = 'mysql';
    protected $table = 'lb_behaviour';
    public $timestamps = false;
    protected $primaryKey = 'id';

    public $incrementing = true;


    protected $fillable = ['id','appid','uid','ip','source','country', 'city', 'region', 'urlname','clicks','add_time'];
}

