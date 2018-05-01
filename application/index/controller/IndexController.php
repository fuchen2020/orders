<?php

namespace app\index\controller;

use app\index\model\Cards;
use app\index\model\Config;
use app\index\model\Orders;
use app\index\model\Users;
use think\Controller;
use think\Request;

class IndexController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        return $this->fetch();
    }

    /**
     * 入口首页
     * @return mixed
     */
    public function rukou(){

        return $this->fetch();
    }

    /**
     * 用户列表
     * @return mixed
     */
    public function userLists()
    {
        $data=(new Users())->all();
        $this->assign('list',$data);
        return $this->fetch();
    }

    /**
     * 订单列表
     * @return mixed
     */
    public function orderLists()
    {
        $data=(new Orders())->all();
        $this->assign('list',$data);
        return $this->fetch();
    }

    /**
     * 配置列表
     * @return mixed
     */
    public function configLists()
    {
        $data=(new Config())->all();
        $this->assign('list',$data);
        return $this->fetch();
    }

    /**
     * 卡密列表
     * @return mixed
     */
    public function cardLists()
    {
        $data=(new Cards())->all();
        $datas=[
            1=>'天卡',
            2=>'周卡',
            3=>'月卡',
        ];
        $this->assign('list',$data);
        $this->assign('types',$datas);
        return $this->fetch();
    }



}
