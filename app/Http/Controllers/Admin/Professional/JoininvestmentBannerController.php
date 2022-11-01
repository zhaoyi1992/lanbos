<?php
// +----------------------------------------------------------------------
// | B5LaravelCMF V2.0 [快捷通用基础管理开发平台]
// +----------------------------------------------------------------------
// | Author: z <834574377@qq.com>
// +----------------------------------------------------------------------
declare (strict_types=1);

namespace App\Http\Controllers\Admin\Professional;

use App\Extends\Helpers\Result;
use App\Extends\Libs\AdminCommonAction;
use App\Extends\Services\System\StructService;
use App\Models\Professional\JoininvestmentBanner;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class JoininvestmentBannerController extends Professional
{
    use AdminCommonAction;

    protected string $model = JoininvestmentBanner::class;





}
