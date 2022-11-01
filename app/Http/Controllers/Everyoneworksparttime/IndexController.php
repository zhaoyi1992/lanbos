<?php

namespace App\Http\Controllers\Everyoneworksparttime;

use App\Imports\ContentImport;
use App\Models\Professional\Everyoneparttime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class IndexController extends Controller
{

    /**
     * @OA\Post(
     *     path="/upload",
     *     tags={"其他模块"},
     *     summary="图片上传",
     *     @OA\Parameter(name="token", in="header",description="token", required=true, @OA\Schema(type="string")),
     *     @OA\RequestBody(
     *       @OA\MediaType(mediaType="multipart/form-data",@OA\Schema(type="object",@OA\Property(property="file",type="file",description="上传图片",),
     *           ),
     *       )
     *     ),
     *     @OA\Response(response=200, description="{code: int, msg:string, data:[]}  "
     *     )
     * )
     **/

    public function upload(Request $request)
    {

        //pDSMiQPlI4UfDkiecPumfouxaPklsOJ0Ric1t0wEKZ3wVGlpywYvWHvUIqNfhfTotoUZDnAct7V2y7xDiP1X1lWTUf63kfs3ZMEe
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:jpeg,jpg,png',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->fail(0, $errors->first('*'), '');
        }
        try {
            $path = $request->file('file')->store('/images', 'admin');
            //  return $this->success('/uploads/' . $path);
            return $this->success(config('app.url') . '/uploads/' . $path);
        } catch (Exception $e) {
            return $this->fail('0', $e, '');
        }
    }

    /**
     * @OA\Post(
     *     path="/contentpload",
     *     tags={"其他模块"},
     *     summary="内容导入",
     *     @OA\Parameter(name="typeid",in="query",description="分类1今日推荐，2宅家做，3线上兼职，4翻译接单，5聊天赚钱，6本地兼职", required=true, @OA\Schema(type="string")),
     *     @OA\RequestBody(
     *       @OA\MediaType(mediaType="multipart/form-data",@OA\Schema(type="object",@OA\Property(property="file",type="file",description="上传excel",),
     *           ),
     *       )
     *     ),
     *     @OA\Response(response=200, description="{code: int, msg:string, data:[]}  "
     *     )
     * )
     **/

    public function contentpload(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:csv,excel,xls',
            'typeid' => 'required|in:1,2,3,4,5,6',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->fail(0, $errors->first('*'), '');
        }

        try {
            $path = $request->file('file')->store('/images', 'admin');
            $data = Excel::toArray(new ContentImport, './uploads/' . $path);
            foreach ($data[0] as $key => $value) {
                if ($key !== 0) {
                    Everyoneparttime::Insert(['typeid' => $request['typeid'], 'name' => $value[0], 'keyword' => 'ประสบการณ์ไม่จำกัด', 'price' => $value[1], 'num' => $value[2]
                        , 'address' => $value[3], 'content' => $value[4],'create_time'=>date('Y-m-d h:i:s',time())]);
                }

            }
            return $this->success(config('app.url') . '/uploads/' . $path);
        } catch (Exception $e) {
            return $this->fail('0', $e, '');
        }
    }


    /**
     * @OA\Get(
     *      path="/emailmessage",
     *      tags={"其他模块"},
     *      summary="邮件模板",
     *      @OA\Parameter(name="email",in="query",description="邮箱", required=true, @OA\Schema(type="string")),
     *      @OA\Parameter(name="message",in="query",description="邮件信息", required=true, @OA\Schema(type="string")),
     *      @OA\Parameter(name="name",in="query",description="发送人", required=true, @OA\Schema(type="string")),
     *      @OA\Parameter(name="title",in="query",description="标题", required=true, @OA\Schema(type="string")),
     *      @OA\Response(response=200,description="{code: int, msg:string, data:[]}",
     *        ),
     *     @OA\PathItem (
     *
     *     ),
     * )
     */
    public function emailmessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'message' => 'required',
            'name' => 'required',
            'title' => 'required',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->fail(0, $errors->first('*'), '');
        }
        return $this->postemailtemplate($request['email'], $request['message'], $request['name'], $request['title']);

    }

}

