<?php

namespace app\api\controller;
use app\index\model\Config;
use app\index\model\Users;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\ValidationData;
use think\Controller;
use Lcobucci\JWT\Parser;
use think\Db;

class BaseController extends Controller
{
    public $user=null;
    //token验证------------------------------------------------------------
    protected function _initialize()
    {
        if(!empty(request()->action())){
            $token =request()->header('token');
            if (!$token) {
                return zJson('',400,false,'签名无效');
            }

            $token = request()->header('token').'kami2';
            $data=(new Users())->where('token',md5($token))->find();
            if (empty($data)) {
                return zJson('',400,false,'签名无效');
            }
             $this->user=$data;
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

    //获取用户模型
    protected function UserModel(){
        return $this->user;
    }

    //获取配置value
    protected function getConfig($name){
        $re=(new Config())->where('name',$name)->find();
        return $re->value;
    }

    //ascii加密
    protected function enascii($str){
        $len=strlen($str);
        $int='';
        for ($x=0; $x<$len; $x++) {
            $int.=chr(ord($str[$x])+3);
        }
        return $int;
    }

}
