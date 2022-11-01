<?php
// +----------------------------------------------------------------------
// | B5LaravelCMF V2.0 [快捷通用基础管理开发平台]
// +----------------------------------------------------------------------
// | Author: z <834574377@qq.com>
// +----------------------------------------------------------------------
namespace App\Models\Member;

use App\Extends\Libs\AdminBaseModel;

class Lbmember
{
    use AdminBaseModel;

    protected $table = 'lb_member';

    protected $primaryKey = 'id';

    public $incrementing = true;

    public $timestamps = true;

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    protected $fillable = ['id','name','appid','email','sex','token', 'phone', 'password', 'photo','created_at','updated_at'];
}
