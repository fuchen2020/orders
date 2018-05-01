<?php
/**
 * Created by PhpStorm.
 * Author: Floating dust
 * Date: 2018-04-30
 * Time: 22:57
 */

namespace app\index\model;


use think\Model;

class Cards extends Model
{
    protected $autoWriteTimestamp = false;

//    //获取订单状态
//    public function getTypeAttr($value){
//        $data=[
//            1=>'天卡',
//            2=>'周卡',
//            3=>'月卡',
//        ];
//        $this->types=$data[$value];
//    }

}