<?php

namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\model\Device;
use app\index\model\ConsumeLog;
use app\index\model\Orders;
use app\index\model\Users;
use think\Controller;
use think\Db;
use think\Request;

class IndexController extends BaseController
{
    /**
     * 获取用户信息
     * @return \think\response\Json
     */
    public function getUser(){
        $user=$this->UserModel();
        if ($user) {
            $data=[
                'id'=>$user->id,
                'name'=>$user->name,
                'point'=>$user->point,
                'phone'=>$user->phone,
            ];
            return $this->zJson($data,200,true,'用户信息获取成功');
        }else{
            return $this->zJson('',400,false,'用户信息获取失败');
        }
    }

    /**
     * 获取用户订单数据
     * @param type 1=未完成  2=已完成
     * @param page 当前页数
     * @param  size 每页条数
     * @return \think\response\Json
     */
    public function getOrderList()
    {
        $user=$this->UserModel();
        $page=\request()->param('page')?:1;
        $size=\request()->param('size')?:5;
        $order=new Orders();
        switch (\request()->param('type')){
            case 1:
                $orderList=$order->where(['user_id'=>$user->id])
                    ->where('status','<',4)
                    ->paginate($size,true,[
                        'page'=>$page,
                    ]);
                if ($orderList->toArray()){
                    return $this->zJson($orderList->toArray(),200,true,'未完成订单获取成功');
                }else{
                    return $this->zJson('',200,false,'未完成订单获取失败');
                }
                break;
            case 2:
                $orderList=$order->where(['user_id'=>$user->id])
                    ->where(['status'=>4])
                    ->paginate($size,true,[
                        'page'=>$page,
                    ]);
                if ($orderList->toArray()){
                    return $this->zJson($orderList->toArray(),200,true,'已完成订单获取成功');
                }else{
                    return $this->zJson('',200,false,'已完成订单获取失败');
                }
                break;
        }

    }

    /**
     * 修改订单异常账户
     * @param order_no 订单编号
     * @param game_id 游戏账
     * @param game_pass 游戏密码
     * @return \think\response\Json
     */
    public function setGaemAccount()
    {
        $orderNo=\request()->param('order_no');
        $game_id=\request()->param('game_id');
        $game_pass=\request()->param('game_pass');
        if (empty($orderNo) || empty($game_id) || empty($game_pass)){
            return $this->zJson('',200,false,'缺少请求参数');
        }
        $re=(new Orders())->where('order_no',$orderNo)->find();
        if ($re) {
            $re->game_id=$game_id;
            $re->game_pass=$game_pass;
            if ($re->save()) {
                return $this->zJson('',200,true,'异常账号修改成功');
            }else{
                return $this->zJson('',200,false,'异常账号修改失败');
            }
        }
    }

    /**
     * 修改订单状态
     * @param type 1=暂停 2=继续 3=结束
     * @param order_no 订单编号
     * @return \think\response\Json
     */
    public function setOrderStatus()
    {
        $orderNo=\request()->param('order_no');
        $type=\request()->param('type');
        if (empty($orderNo) || empty($type)){
            return $this->zJson('',200,false,'缺少参数');
        }
        switch ($type){
            case 1: //暂停
                    $re=(new Device())->where('orderid',$orderNo)->find();
                    if ($re) {
                        $re->status=1;
                        if ($re->save()) {
                            if ((new Orders())->save(['status'=>4],['order_no',$orderNo])) {
                                return $this->zJson('',200,true,'订单暂停成功');
                            }
                        }else{
                            return $this->zJson('',200,false,'订单暂停失败');
                        }
                    }else{
                        return $this->zJson('',200,false,'设备中无此订单');
                    }
                break;
            case 2: //继续
                    $re=(new Device())->where('orderid',$orderNo)->find();
                    if ($re) {
                        $re->status=2;
                        if ($re->save()) {
                            if ((new Orders())->save(['status'=>2],['order_no',$orderNo])) {
                                return $this->zJson('',200,true,'订单已继续');
                            }
                        }else{
                            return $this->zJson('',200,false,'订单继续操作失败');
                        }
                    }else{
                        return $this->zJson('',200,false,'设备中无此订单');
                    }
                break;
            case 3: //结束
                $time=date('Y-m-d H:i:s');
                Db::startTrans();
                try{
                    $re=(new Device())->where('orderid',$orderNo)->find();
                    if ($re) {
                        $re->status=4;
                        $re->orderid=null;
                        if ($re->save()) {
                            //修改订单状态为已完成，到期时间修改为当前时间，
                            $order=(new Orders())->where('order_no',$orderNo)->find();
                            if ($order){
                                //到目前为止，使用时间（按小时算，不满一小时按一小时算）
                                $useTime=strtotime($time)-strtotime($order->created_at);
                                //转化为小时,保留两位小树
                                $h=sprintf("%.2f",$useTime/3600);
                                //个人定制需求1.6倍收取点数(向上取整)
                                if ($order->is_need){
                                    $point=ceil(ceil($h)*1.6);
                                    $returnPoint=$order->point-$point;//退还点数
                                }else{
                                    $point=ceil($h);
                                    $returnPoint=$order->point-$point;//退还点数
                                }
//                               dump($returnPoint);exit;
                                //更新订单点数和到期时间
                                $res=$order->save(['status'=>5,'end_time'=>$time,'point'=>$point]);
                                //更新用户账户余额
                                $user=(new Users())->where('id',$order->user_id)->find();
                                $user->point+=$returnPoint;
                                $res1=$user->save();
                                //存消费记录
                                $res2=(new ConsumeLog())->insert([
                                    'user_id'=>$order->user_id,
                                    'type'=>4,
                                    'point'=>$returnPoint,
                                    'current_point'=>$user->point,
                                    'reason'=>"订单编号为：.$orderNo.的订单提前结束，使用时间为：.$h.小时，退还点数为.$returnPoint.点",
                                    'created_at'=>$time,
                                ]);
//                                var_dump($res,$res1,$res2);exit;
                                if ($res && $res1 && $res2) {
                                    // 提交事务
                                    Db::commit();
                                    return $this->zJson('',200,true,'订单结束成功');
                                }else{
                                    throw new \Exception("订单结束失败");
                                }
                            }else{
                                throw new \Exception("订单不存在");
                            }

                        }else{
                            throw new \Exception("订单结束失败");
                        }
                    }else{
                        throw new \Exception("设备中无此订单");
                    }
                } catch (\Exception $e) {
                    // 回滚事务
                    Db::rollback();
                    return $this->zJson('',200,false,$e->getMessage());
                }
                break;
        }
    }


}

