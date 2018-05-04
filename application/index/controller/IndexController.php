<?php

namespace app\index\controller;

use app\index\model\Cards;
use app\index\model\Config;
use app\index\model\Orders;
use app\index\model\Users;
use function PHPSTORM_META\type;
use think\Controller;
use think\Request;
use think\Session;

class IndexController extends Controller
{
    //前台页面入口
    public function index(){

        $this->redirect(url('/web/login'));
    }

    /**
     * 后台首页
     *
     * @return \think\Response
     */
    public function adminOrder()
    {
        if (empty(session('user'))) {
            return redirect(url('/adminLogin'));
        }
        return $this->fetch('index');
    }
    /**
     * 后台登陆
     * @return $this|\think\response\Redirect|\think\response\View
     */
    public function adminLogin(){
        //检查cookie
        if (!empty(session('user'))) {
            return redirect(url('/adminOrder'));
        }
        if (request()->isPost()) {
//            dump(request()->post('phone'));exit;
            if (request()->post('phone')) {
                $user=(new Users())->where(['phone'=>request()->post('phone'),'is_gl'=>1])->find();
//                dump($user);exit;
                if ($user) {
                    if(password_verify(\request()->post('pass'), $user['pass'])){
                        $info=[
                            'id'=>$user->id,
                            'phone'=>$user->phone,
                        ];
                        if($user->is_gl==1){
                            $info['is_gl']= $user->is_gl;
                        }
                        session('user',$info);
                        $this->success('登陆成功', url('/adminOrder'));
                    }else{
                        $this->error('手机号或密码错误!',url('/adminLogin'));
                    }
                }else{
                    $this->error('手机号或无此权限!',url('/adminLogin'));
                }
            }
        }
        return $this->fetch('login');

    }

    /**
     * 退出登录
     *
     * @return 跳转登录页面
     */
    public function logout(){

        Session::clear('think');

        return redirect('/adminLogin');

    }



    /**
     * 入口首页
     * @return mixed
     */
    public function ruKou(){
        if (empty(session('user'))) {
            return redirect(url('/adminLogin'));
        }
        return $this->fetch('rokou');
    }

    /**
     * 用户列表
     * @return mixed
     */
    public function userLists()
    {
        if (empty(session('user'))) {
            return redirect(url('/adminLogin'));
        }
        $data=(new Users())->order('created_at','desc')->select();
        $this->assign('list',$data);
        return $this->fetch('userlists');
    }

    /**
     * 订单列表
     * @return mixed
     */
    public function orderLists()
    {
        if (empty(session('user'))) {
            return redirect(url('/adminLogin'));
        }
        $data=(new Orders())->order('created_at','desc')->select();
        $this->assign('list',$data);
        return $this->fetch('orderlists');
    }

    /**
     * 配置列表
     * @return mixed
     */
    public function configLists()
    {
        if (empty(session('user'))) {
            return redirect(url('/adminLogin'));
        }
        $data=(new Config())->all();
        $this->assign('list',$data);
        return $this->fetch('configlists');
    }

    /**
     * 卡密列表
     * @return mixed
     */
    public function cardLists()
    {
        if (empty(session('user'))) {
            return redirect(url('/adminLogin'));
        }
        $data=(new Cards())->order('created_at','desc')->select();
        $datas=[
            1=>'天卡',
            2=>'周卡',
            3=>'月卡',
        ];
        $this->assign('list',$data);
        $this->assign('types',$datas);
        return $this->fetch('cardlists');
    }

    public function addCard(){
        if (empty(session('user'))) {
            return redirect(url('/adminLogin'));
        }
        if (\request()->isPost()){
            $data=[
                'type'=>request()->post('type'),
                'status'=>1,
                'created_at' =>date('Y-m-d H:i:s'),
            ];
            if(request()->post('type')==1){
                $data['point']=24;
            }
            if(request()->post('type')==2){
                $data['point']=168;
            }
            if(request()->post('type')==3){
                $data['point']=720;
            }

            $datas=[];
            $num=\request()->post('num');
            for ($i=1;$i<=$num;$i++){
                $str=time().uniqid().rand(111111,9999999);
                $data['card_no']=md5($str);
                $datas[$i]=$data;
                $result = (new Cards())->insert($data);
            }

            if ($result) {
                $this->success('生成成功', url('/cardLists'));
            } else {
                $this->error('生成失败');
            }


        }

        return $this->fetch('addCard');
    }

    public function userFrozen(){
        if (empty(session('user'))) {
            return redirect(url('/adminLogin'));
        }
        switch (\request()->param('type')){
            case 1:
                $re=(new Users())->where('id',\request()->param('id'))->update(['status'=>2]);
                if ($re){
                    $this->success('冻结成功', url('/userlists'));
                }else{
                    $this->error('冻结失败');
                }
                break;
            case 2:
                $re=(new Users())->where('id',\request()->param('id'))->update(['status'=>1]);
                if ($re){
                    $this->success('解冻成功', url('/userlists'));
                }else{
                    $this->error('解冻失败');
                }
                break;
        }

    }

    public function editConfig(){
        if (empty(session('user'))) {
            return redirect(url('/adminLogin'));
        }
        if(\request()->param('value')){
            $re=(new Config())->where('id',\request()->param('id'))->update(['value'=>\request()->param('value')]);
            if ($re){
                return zJson('',200,true,'修改成功');
            }else{
                return zJson('',200,true,'修改失败');
            }
        }else{
            return zJson('',200,true,'参数错误');

        }


    }

}
