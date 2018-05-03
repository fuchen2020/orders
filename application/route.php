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
//获取游戏版本列表
Route::any("api/:v/getGameVersion","api/:v.index/getGameVersion");
//获取订单页信息
Route::any("api/:v/getExplain","api/:v.index/getExplain");
//提交订单
Route::post("api/:v/setOrder","api/:v.index/setOrder");
//用户充值
Route::post("api/:v/recharge","api/:v.index/recharge");
//获取用户消费记录
Route::any("api/:v/getConsumeLog","api/:v.index/getConsumeLog");
//获取购卡地址
Route::any("api/:v/getToUrl","api/:v.index/getToUrl");

Route::any("api/:v/test","api/:v.reg/test");


Route::any("logout","index/index/logout");
Route::any("userLists","index/index/userLists");
Route::any("orderLists","index/index/orderLists");
Route::any("configLists","index/index/configLists");
Route::any("cardLists","index/index/cardLists");
Route::any("adminLogin","index/index/adminLogin");
Route::any("adminOrder","index/index/adminOrder");
Route::any("addCard","index/index/addCard");
//前台入口路由
Route::any("index","index/index/index");
