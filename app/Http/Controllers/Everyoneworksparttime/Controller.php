<?php

namespace App\Http\Controllers\Everyoneworksparttime;

use App\Models\Billing;
use App\Models\Emailverify;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use PHPMailer\PHPMailer\PHPMailer;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    const API_SEND_URL = 'http://intapi.253.com/send/json'; //创蓝发送短信接口URL


    const API_ACCOUNT = 'I8233656'; // 创蓝API账号

    const API_PASSWORD = 'Ai204aWtkN01ea';// 创蓝API密码


    public function success($data)
    {
        return response()->json([
            'code' => 200,
            'msg' => 'ความสำเร็จ',
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

    //可用 美元
    public function myusd($uid)
    {
        $where = array();
        $where[] = ['userid', $uid];
        $where[] = ['money_type', 0];
        $sum_amount_0 = Billing::where($where)->where('transaction', '0')->where('status', 1)->sum('amount') ?: 0;
        $sum_amount_1 = Billing::where($where)->where('transaction', '1')->whereIn('status', [0, 1])->sum('amount') ?: 0;
        $sum_amount_2 = Billing::where($where)->where('transaction', '2')->whereIn('status', [0, 1])->sum('amount') ?: 0;
        $sum_amount = $sum_amount_0 - $sum_amount_1 - $sum_amount_2;
        return $sum_amount;
    }

    //广告花费
    public function myad($uid)
    {
        $where = array();
        $where[] = ['userid', $uid];
        $where[] = ['money_type', 0];
        $sum_amount_2 = Billing::where($where)->where('transaction', '2')->whereIn('status', [0, 1])->sum('amount') ?: 0;
        $sum_amount = $sum_amount_2;
        return $sum_amount;
    }

    //验证码
    public function postemail($email, $type)
    {

        $mail = new PHPMailer(true);
        $code = rand(100000, 999999);
        $mail->CharSet = 'UTF-8';
        $mail->isSMTP();
        $mail->Host = 'ssl://smtpdm.aliyun.com';                     //邮件推送服务器
        $mail->Port = 465;                                    //端口
        $mail->SMTPAuth = true;                                   //授权
        $mail->Username = 'juliangtec@email.juhech.com';                     //发信地址
        $mail->Password = 'ABC123456efg';                               //SMTP 密码
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //启用加密
        //Recipients
        $mail->setFrom('juliangtec@email.juhech.com', '蓝博DSP');   //显示发信地址,可设置代发。
        $mail->addAddress($email);     //收件人和昵称
        $mail->addReplyTo('834574377@qq.com', '蓝博DSP');//回信地址
        $mail->isHTML(true);                                  //HTML 格式
        $mail->Subject = '您的验证码:';
        $mail->Body = '<!DOCTYPE html>
                <html lang="en">
                    <head>
                        <meta charset="UTF-8">

                    </head>
                    <body>
                        <h2>您好！</h2>
                        <h4>请验证您的电子邮箱。</h4>
                        <div>
                            <span>您的验证码为：</span>
                            <strong>' . $code . '</strong>
                        </div>
                    </body>
                </html>';
        $mail->Sender = 'juliangtec@email.juhech.com';
        $res = $mail->send();
        if ($res) {
            Emailverify::insert(['email' => $email, 'code' => $code, 'type' => $type, 'created_at' => time()]);
            return $this->success((object)[]);
        } else {
            return $this->fail(0, $mail->setError, []);
        }
    }

    //邮件模板
    public function postemailtemplate($email, $message, $name, $title)
    {

        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8';
        $mail->isSMTP();
        $mail->Host = 'ssl://smtpdm.aliyun.com';                     //邮件推送服务器
        $mail->Port = 465;                                    //端口
        $mail->SMTPAuth = true;                                   //授权
        $mail->Username = 'tuikernetbot@email.juhech.com';                     //发信地址
        $mail->Password = 'ABC123456efgh';                               //SMTP 密码
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //启用加密
        //Recipients
        $mail->setFrom('tuikernetbot@email.juhech.com', $name);   //显示发信地址,可设置代发。
        $mail->addAddress($email);     //收件人和昵称
        $mail->addReplyTo('834574377@qq.com', $name);//回信地址
        $mail->isHTML(true);                                  //HTML 格式
        $mail->Subject = $title;
        $mail->Body = '<!DOCTYPE html>
                <html lang="en">
                    <head>
                        <meta charset="UTF-8">

                    </head>
                    <body>
                        <h2>' . $name . '提醒您：</h2>

                        <div>
                            <span>' . $message . '</span>

                        </div>
                    </body>
                </html>';
        $mail->Sender = 'tuikernetbot@email.juhech.com';
        //  $mail->SMTPDebug=true ;
        $res = $mail->send();
        if ($res) {
            return $this->success((object)[]);
        } else {
            return $this->fail(0, $mail->setError, []);
        }
    }

    public function postapi($url, $method, $headers, $data)
    {//curl

        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); // 从证书中检查SSL加密算法是否存在
        //    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        if ($method == 'POST') {
            curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
            //   curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data)); // Post提交的数据包
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data)); // Post提交的数据包
        }
        curl_setopt($curl, CURLOPT_TIMEOUT, 60); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回


        $tmpInfo = curl_exec($curl); // 执行操作
        $curlErrNo = curl_errno($curl);
        if ($tmpInfo === false) {
            echo $curlErrNo;//输出错误信息
            exit();
        }
        curl_close($curl); // 关闭CURL会话
        return ($tmpInfo); // 返回数据
    }


    public function sendMsg($email, $type)
    {
        $str = '0123456789';
        $code = '';
        for ($i = 1; $i <= 4; $i++) {
            $code .= (substr($str, mt_rand(0, 9), 1)) . '';
        }
        // $content = '【聚合出海】您的验证码是' . $code . '，5分钟内有效.若非本人操作请忽略此消息';
        $content = '【ทุกคนเป็นพาร์ทไทม์】รหัสยืนยันของคุณคือ' . $code . '，ใช้ได้ภายใน 5 นาที โปรดอย่าสนใจข้อความนี้หากไม่ใช่การดำเนินการของคุณ';
        //创蓝接口参数
        $postArr = array(
            'account' => self::API_ACCOUNT,
            'password' => self::API_PASSWORD,
            'msg' => $content,
            'mobile' => $email,
            'senderId'=>""
        );
        $result = json_decode(self::curlPost(self::API_SEND_URL, $postArr), 'true');
        if ($result['code'] == 0) {
            Emailverify::insert(['email' => $email, 'code' => $code, 'type' => $type, 'created_at' => time()]);
            return $this->Success('');
        } else {
            return $this->fail('0', $result['error']);
        }


    }

    public function phonesendMsg($email, $type)
    {
        $str = '0123456789';
        $code = '';
        for ($i = 1; $i <= 4; $i++) {
            $code .= (substr($str, mt_rand(0, 9), 1)) . '';
        }
        $content = '【聚合出海】您的验证码是' . $code . '，5分钟内有效.若非本人操作请忽略此消息';
        //创蓝接口参数
        $postArr = array(
            'account' => 'YZM9110950',
            'password' => 'QM8OA3K8TBb04d',
            'msg' => urlencode($content),
            'phone' => $email,
            'report' => false,
        );

        $result = json_decode($this->curlPost('http://smssh1.253.com/msg/send/json', $postArr), 'true');
        if(isset($result['code'])){
            if ($result['code'] == 0) {
                Emailverify::insert(['email' => $email, 'code' => $code, 'type' => $type, 'created_at' => time()]);
                return $this->Success('');

            } else {
                return $this->fail('0', $result['error']);

            }

        }else{

            return $this->fail('0', '请求失败，重试');
        }



    }



    private function curlPost($url, $postFields)
    {
        $postFields = json_encode($postFields);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json; charset=utf-8'   //json版本需要填写  Content-Type: application/json;
            )
        );
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4); //若果报错 name lookup timed out 报错时添加这一行代码
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $ret = curl_exec($ch);
        if (false == $ret) {
            $result = curl_error($ch);
        } else {
            $rsp = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if (200 != $rsp) {
                $result = "请求状态 " . $rsp . " " . curl_error($ch);
            } else {
                $result = $ret;
            }
        }
        curl_close($ch);
        return $result;
    }
}
