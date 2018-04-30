<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

//返回json对象函数
 function zJson($result,$code,$success=true,$msg=''){
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