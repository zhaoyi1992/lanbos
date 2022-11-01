<?php
// +----------------------------------------------------------------------
// | B5LaravelCMF V2.0 [快捷通用基础管理开发平台]
// +----------------------------------------------------------------------
// | Author: z <834574377@qq.com>
// +----------------------------------------------------------------------
namespace App\Models\Professional;

use App\Extends\Libs\AdminBaseModel;
use Illuminate\Database\Eloquent\Model;
class Everyoneparttime extends Model
{
    use AdminBaseModel;

    protected $table = 'lb_everyoneparttime';

    protected $primaryKey = 'id';

    public $incrementing = true;

    public $timestamps = true;

    const CREATED_AT = 'create_time';

    const UPDATED_AT = 'update_time';

    protected $fillable = ['id','typeid','name','keyword','price','num','address','content','links','create_time','update_time'];

    public function collect()
    {
        return $this->hasOne(Collect::class,'goodsid','id')->select('id','goodsid','status');
    }
}
