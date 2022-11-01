<?php
// +----------------------------------------------------------------------
// | B5LaravelCMF V2.0 [快捷通用基础管理开发平台]
// +----------------------------------------------------------------------
// | Author: z <834574377@qq.com>
// +----------------------------------------------------------------------
namespace App\Models\Professional;

use App\Extends\Libs\AdminBaseModel;
use Illuminate\Database\Eloquent\Model;
class JoininvestmentBanner extends Model
{
    use AdminBaseModel;

    protected $table = 'lb_banner';

    protected $primaryKey = 'id';

    public $incrementing = true;

    public $timestamps = true;

    const CREATED_AT = 'create_at';

    const UPDATED_AT = 'updated_at';

    protected $fillable = ['id','appid','place','title','img','link','create_time','listsort','hot','updated_at','status'];

    public function collect()
    {
        return $this->hasOne(Collect::class,'goodsid','id')->select('id','goodsid','status');
    }
}
