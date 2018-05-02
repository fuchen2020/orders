<?php
/**
 * Created by PhpStorm.
 * Author: Floating dust
 * Date: 2018-04-28
 * Time: 18:25
 */

namespace app\index\model;


use think\Model;

class Orders extends Model
{
    protected $autoWriteTimestamp = false;

    //获取订单状态
    public function getStatusAttr($value){
        $data=[
            1=>'登陆中',
            2=>'进行中',
            3=>'账号异常',
            4=>'暂停中',
            5=>'已完成',
        ];
        return $data[$value];
    }

}