<?php

namespace App\Http\Controllers\Joininvestment;

use App\Models\Billing;
use App\Models\Emailverify;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function success($data)
    {
        return response()->json([
            'code' => 200,
            'msg' => '成功',
            'data' => $data,
        ]);
    }
    public function fail($code, $msg )
    {
        return response()->json([
            'code' => $code,
            'msg' => $msg,
            'data' => (object)[],
        ]);
    }

}
