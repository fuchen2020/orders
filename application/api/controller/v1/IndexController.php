<?php

namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\index\model\Orders;
use app\index\model\Users;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use think\Controller;
use think\Db;
use think\Request;

class IndexController extends BaseController
{
    /**
     * 会员登陆
     * @param $username
     * @param $pwd
     * @return \think\response\Json
     */
    public function login()
    {
//        $pass=password_hash(\request()->post('pass'),PASSWORD_DEFAULT);
//        dump($pass);exit;
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
                //统计当前用户待付款订单数
                $orderlist = (new Orders())->where('user_id',$user->id)->select();
                $data = [
                    "success" => true,
                    "errorMsg" => "",
                    "result" => [
                        "id" => $user['id'],
                        "name" => $user['name'],
                        "point" => $user['point'],
                        "orderlist" => $orderlist ? $orderlist : [],
                        'sign'=>$token,
                    ],
                ];
                return $this->zJson($data);
            } else {
                return $this->zJson(null, 200,false, '用户密码错误');
            }

        } else {

            return $this->zJson(null, 200,false, '用户名不正确');
        }

    }




}

