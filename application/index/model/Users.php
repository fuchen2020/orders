<?php

namespace app\index\model;

use think\Model;

class Users extends Model
{
    protected $autoWriteTimestamp = true;
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';


//    public function getaddtimeAttr($value){
//
//        return date('Y-m-d H:i:s',$value);
//    }


}
