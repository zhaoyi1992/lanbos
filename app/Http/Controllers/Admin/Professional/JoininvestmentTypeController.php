<?php
// +----------------------------------------------------------------------
// | B5LaravelCMF V2.0 [快捷通用基础管理开发平台]
// +----------------------------------------------------------------------
// | Author: z <834574377@qq.com>
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace App\Http\Controllers\Admin\Professional;

use App\Extends\Helpers\Result;
use App\Extends\Libs\AdminCommonAction;
use App\Models\Professional\JoininvestmentType;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class JoininvestmentTypeController extends Professional
{
    use AdminCommonAction;
    protected string $model = JoininvestmentType::class;



}
