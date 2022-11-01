<?php
// +----------------------------------------------------------------------
// | B5LaravelCMF V2.0 [快捷通用基础管理开发平台]
// +----------------------------------------------------------------------
// | Author: z <834574377@qq.com>
// +----------------------------------------------------------------------
namespace App\Models\Professional;

use App\Extends\Libs\AdminBaseModel;
use Illuminate\Database\Eloquent\Model;
class Collect extends Model
{
    use AdminBaseModel;

    protected $table = 'lb_collect';

    protected $primaryKey = 'id';

    public $incrementing = true;

    public $timestamps = true;

    const CREATED_AT = 'create_time';

    const UPDATED_AT = 'update_time';

    protected $fillable = ['id','appid','userid','goodsid','status','create_time','update_time'];

    public function everyoneparttime()
    {
        return $this->hasOne(Everyoneparttime::class,'id','goodsid')->select('id','name','keyword','price','num','address','content');
    }
}
