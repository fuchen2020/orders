<?php
/**
 * Created by PhpStorm.
 * Author: Floating dust
 * Date: 2018-04-28
 * Time: 19:33
 */

namespace app\api\controller\v1;


use app\api\controller\CurlController;
use app\api\model\SmsCode;
use app\index\model\Orders;
use app\index\model\Users;

class RegController extends CurlController
{
    //您的注册验证码：8585，如非本人操作，请忽略本短信【小雨云】
    public function sendSms($phone){
        if($phone){
            $codeModel=new SmsCode();
            $codes=$codeModel->where('phone',$phone)->find();
            if (!empty($codes)){
                $content="您的注册验证码：{$codes->sms_code}，如非本人操作，请忽略本短信【小雨云】";
                $res=$this->sendCode($phone,$content);
                if($res['msg']=="ok"){
                    return $this->zJson('',200,true,'验证码发送成功');
                }else{
                    return $this->zJson('',400,false,'验证码发送失败');
                }
            }else{
                $code=rand(999,99999);
                $content="您的注册验证码：{$code}，如非本人操作，请忽略本短信【小雨云】";
                $re=$this->sendCode($phone,$content);
                if($re['msg']=="ok"){
                    $codeModel->insert([
                        'phone'=>$phone,
                        'sms_code'=>$code,
                        'created_at'=>date('Y-m-d H:i:s'),
                    ]);
                    return $this->zJson('',200,true,'验证码发送成功');
                }else{
                    return $this->zJson('',400,false,'验证码发送失败');
                }
            }
        }
    }
    /**
     * 会员注册
     * @param code 验证码
     * @param phone 手机号
     * @param name 昵称
     * @param pass 密码
     * @param referee_id 推荐人ID
     * @return \think\response\Json
     */
    public function regist()
    {
        if(\request()->post('code')){
            $codeModel=new SmsCode();
            $code1=$codeModel->where('phone',request()->post('phone'))->find();
            if ($code1){
                if($code1['sms_code']!=\request()->post('code')){
                    return $this->zJson('',200,false,'短信验证码错误！');
                }
            }else{
                return $this->zJson('',200,false,'短信验证码错误！');
            }
        }
            $data=[
                'name' => request()->post('name'),
                'phone'=>request()->post('phone'),
                'pass' => password_hash(request()->post('pass'), PASSWORD_DEFAULT),
                'created_at' =>date('Y-m-d H:i:s'),
                'updated_at' =>date('Y-m-d H:i:s'),
            ];
        if(\request()->post('referee_id')){
            $data[ 'referee_id']=\request()->post('referee_id');
        }

        $result = (new Users())->insertGetId($data);
        if ($result) {
            $code1->delete();
            return $this->zJson('',200,true,'注册成功');
        } else {
            return $this->zJson('', 200,false, '注册失败');
        }
    }

    /**
     * 会员登陆
     * @param $phone 手机号
     * @param $pass 密码
     * @return \think\response\Json
     */
    public function login()
    {
        $user=new Users();
        $user =$user->where(['phone' => request()->post('phone')])->find();
        if ($user) {
            if (password_verify(\request()->post('pass'), $user['pass'])) {
                //登陆成功生成token------------------------------------------------
                $str=$user['phone'].$user['name'].time().'kami';
                $token=md5($str);
                $strictToken=md5($token.'kami2');
                $user->token=$strictToken;
                $user->updated_at=date('Y-m-d H:i:s');
                $user->save();
                //----------------------------------------------------------------
                $data = [
                    "id" => $user['id'],
                    "name" => $user['name'],
                    "point" => $user['point'],
                    'sign'=>$token,
                ];
                return $this->zJson($data, 200,true, '登陆成功');
            } else {
                return $this->zJson('', 200,false, '用户密码错误');
            }

        } else {
            return $this->zJson('', 200,false, '用户名不正确');
        }

    }

}