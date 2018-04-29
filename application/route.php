<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Route;
Route::rule('user/index','index/user/index');
Route::rule('user/add','index/user/add');
Route::rule('index/ws','index/index/ws');


//api接口路由

//发送短信验证码
Route::post("api/:v/sendSms","api/:v.reg/sendSms");
//注册账号
Route::post("api/:v/regist","api/:v.reg/regist");
//用户登陆
Route::post("api/:v/login","api/:v.reg/login");
//获取用户信息 getUser
Route::any("api/:v/getUser","api/:v.index/getUser");
//获取用户订单信息
Route::any("api/:v/getOrderList","api/:v.index/getOrderList");
//修改订单异常账户
Route::any("api/:v/setGaemAccount","api/:v.index/setGaemAccount");
//暂停|继续|结束订单
Route::any("api/:v/setOrderStatus","api/:v.index/setOrderStatus");
