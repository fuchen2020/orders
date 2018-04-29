<?php

namespace app\api\controller;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\ValidationData;
use think\Controller;
use Lcobucci\JWT\Parser;
use think\Db;

class BaseController extends Controller
{
    protected $user=[];
    //token验证------------------------------------------------------------
    protected function _initialize()
    {
        if(request()->action()!='login'){
            $token =request()->param('sign');
            $re=[
                "code"=>200,
                "success" => false,
                "errorMsg" =>'签名无效',
                "result" =>null,
            ];
            if (!$token) {
                exit(json_encode($re));
            }

            $token = request()->header('token').'kami2';
            $data=Db::table('users')->where('token',md5($token))->find()->toArray();

            if (empty($data)) {
                exit(json_encode($re));
            }else{
                $this->user=$data;
            }

        }

        parent::_initialize();
    }
//-------------------------------------------------------------------------

    //返回json对象函数
    protected function zJson($result,$code,$success=true,$msg=''){
        $data=[
            "code"=>$code,
//            "false代表服务器返回数据失败，true代表成功",
            "success" => $success,
//            在success为false的时候返回对应的失败信息",
            "msg" =>$msg,
//            "此处返回为实际的数据，根据返回的数据类型确定result的最终类型"
            "result" =>$result,
        ];

        return json($data);
    }

    
}
