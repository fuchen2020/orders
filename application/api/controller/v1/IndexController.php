<?php

namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\model\Device;
use app\index\model\Cards;
use app\index\model\ConsumeLog;
use app\index\model\GameVersion;
use app\index\model\Orders;
use app\index\model\Users;
use think\Controller;
use think\Db;
use think\Request;
use think\Validate;

class IndexController extends BaseController
{
    /**
     * 获取用户信息
     * @return \think\response\Json
     */
    public function getUser(){
        if($this->UserModel()){
            $user=$this->UserModel();
        }else{
            return zJson('',400,false,'签名无效');
        }

        if ($user) {
            $total=(new ConsumeLog())->where(['user_id'=>$user->id,'type'=>3])->sum('point');
            $data=[
                'id'=>$user->id,
                'name'=>$user->name,
                'point'=>$user->point,
                'phone'=>$user->phone,
                'totalPoint'=>sprintf("%.2f",$total),
            ];
            return $this->zJson($data,200,true,'用户信息获取成功');
        }else{
            return $this->zJson('',200,false,'用户信息获取失败');
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
        if($this->UserModel()){
            $user=$this->UserModel();
        }else{
            return zJson('',400,false,'签名无效');
        }
        $page=\request()->param('page')?:1;
        $size=\request()->param('size')?:10;
        $order=new Orders();
        switch (\request()->param('type')){
            case 1:
                $orderList=$order->where(['user_id'=>$user->id])
                    ->where('status','<',4)
                    ->paginate($size,true,[
                        'page'=>$page,
                    ])->toArray();
                if ($orderList['data']){
                    return $this->zJson($orderList,200,true,'未完成订单获取成功');
                }else{
                    return $this->zJson('',200,false,'未完成订单获取失败');
                }
                break;
            case 2:
                $orderList=$order->where(['user_id'=>$user->id])
                    ->where(['status'=>4])
                    ->paginate($size,true,[
                        'page'=>$page,
                    ])->toArray();
                if ($orderList['data']){
                    return $this->zJson($orderList,200,true,'已完成订单获取成功');
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
        if($this->UserModel()){
            $user=$this->UserModel();
        }else{
            return zJson('',400,false,'签名无效');
        }
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
        if($this->UserModel()){
            $user=$this->UserModel();
        }else{
            return zJson('',400,false,'签名无效');
        }
        date_default_timezone_set('PRC'); //设置中国时区
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
                                    $point=ceil(ceil($h)*$this->getConfig('REN_XUQIU'));
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
                                    'order_no'=>$orderNo,
                                    'current_point'=>$user->point,
                                    'reason'=>"订单编号为： $orderNo 的订单提前结束，使用时间为： $h 小时，退还点数为 $returnPoint 点",
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

    /**
     * 获取所有游戏版本列表
     * @return \think\response\Json
     */
    public function getGameVersion()
    {
        $gameV=(new GameVersion())->column('name','name');
        if ($gameV){
            return $this->zJson($gameV,200,true,'游戏版本列表获取成功');
        }else{
            return $this->zJson('',200,false,'游戏版本列表为空');
        }
    }

    /**
     * 获取订单页说明
     * @return \think\response\Json
     */
    public function getExplain()
    {
        $sm=$this->getConfig('EXPLAIN');
        $banner=$this->getConfig('BANNER');
        if ($sm || $banner){
            $data=[
                'explain'=>$sm,
                'banner'=>$banner,
            ];
            return $this->zJson($data,200,true,'获取订单页信息成功');
        }else{
            return $this->zJson('',200,false,'获取订单页信息失败');
        }
    }

    /**
     * @param game_version 游戏版本
     * @param game_id 游戏账号
     * @param game_pass 游戏密码
     * @param brush_wall  刷墙（0=否   1=是）
     * @param up_building 升级建筑（0=否 1=是）
     * @param up_arms  升级兵种（0=否 1=是）
     * @param is_need  额外需求（0=否 1=是）
     * @param need  需求内容
     * @param time  订单使用时间
     * @return \think\response\Json
     */
    public function setOrder()
    {
        if($this->UserModel()){
            $user=$this->UserModel();
        }else{
            return zJson('',400,false,'签名无效');
        }
        date_default_timezone_set('PRC'); //设置中国时区
        $time=time();
        $validate = new Validate([
            'game_version'  => 'require',
            'game_id' => 'require',
            'game_pass' => 'require',
            'time'=>'require|integer'
        ]);
        if (!$validate->check(\request()->post())) {
            return $this->zJson('',400,false,$validate->getError());
        }
        $endTime=date('Y-m-d H:i:s',$time+\request()->post('time')*3600);
//        dump($endTime);exit;
        $data=\request()->post();

        //匹配订单对应机器
        $query=(new Device())->where('status',4)
            ->where('gameversion',$data['game_version']);
        //额外人工需求
        if(\request()->post('is_need')==1){
            $validate = new Validate([
                'need'  => 'require',
            ]);
            if (!$validate->check($data)) {
                return $this->zJson('',400,false,$validate->getError());
            }
            $data['is_need']=\request()->post('is_need');
            $data['need']=\request()->post('need');
            $data['point']=ceil(\request()->post('time')*$this->getConfig('REN_XUQIU'));
            $query->where('xuqiu',1);
        }else{
            $data['point']=\request()->post('time');
        }
        //适配机器
        $free=$query->find();
        if($free){
            $data['device']=$free->xinxi;
        }else{
            return $this->zJson('',200,false,'暂未匹配到适合您订单需求的机器！');
        }
        //刷强
        if(\request()->post('brush_wall')==1){
            $data['brush_wall']=\request()->post('brush_wall');
        }
        //升级建筑
        if(\request()->post('up_building')==1){
            $data['up_building']=\request()->post('up_building');
        }
        //升级兵种
        if(\request()->post('up_arms')==1){
            $data['up_arms']=\request()->post('up_arms');
        }
        $data['user_id']=$user->id;
        $data['created_at']=date('Y-m-d H:i:s',$time);
        $data['updated_at']=date('Y-m-d H:i:s',$time);
        $data['end_time']=$endTime;
        $data['order_no']=date('YmdHis').uniqid().$data['point'];
//        dump($data);exit;
        // 启动事务
        Db::startTrans();
        try{
            //添加订单
            $res=(new Orders())->insert($data);
            if (empty($res)){
                throw new \Exception("提交订单参数出错");
            }
            //修改机器状态，存入订单编号
            $res1=$free->save(['status'=>3,'orderid'=>$data['order_no']]);
            //扣除余额点数
            if($user->point>=$data['point']){
                $po=$user->point-$data['point'];
                $res2=(new Users())->save(['point'=>$po,'updated_at'=>date('Y-m-d H:i:s',$time)],['id'=>$user->id]);
                //存消费记录
                $orderNo=$data['order_no'];
                $h=\request()->post('time');
                $returnPoint=$data['point'];
                $res3=(new ConsumeLog())->insert([
                    'user_id'=>$user->id,
                    'type'=>2,
                    'point'=>$data['point'],
                    'order_no'=>$orderNo,
                    'current_point'=>$po,
                    'reason'=>"订单编号为： $orderNo 的订单创建成功，订单时间为： $h 小时，共消耗点数为 $returnPoint 点",
                    'created_at'=>date('Y-m-d H:i:s',$time),
                ]);
            }else{
                return $this->zJson('',200,true,'您的币不够了哦，请先充值！');
            }
            // 提交事务
            if ($res && $res1 && $res2 && $res3) {
                Db::commit();
                return $this->zJson('',200,true,'您的订单创建成功');
            }else{
//                throw new \Exception($res.'--'.$res1.'--'.$res2.'--'.$res3);
                throw new \Exception("提交订单出错了");
            }
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return $this->zJson('',200,false,$e->getMessage());
        }

    }

    /**
     * 用户充值
     * @param cam 卡密号
     * @return \think\response\Json
     */
    public function recharge()
    {
        if($this->UserModel()){
            $user=$this->UserModel();
        }else{
            return zJson('',400,false,'签名无效');
        }
        date_default_timezone_set('PRC'); //设置中国时区
        $time=date('Y-m-d H:i:s');
        $validate = new Validate([
            'cam'  => 'require',
        ]);
        if (!$validate->check(\request()->post())) {
            return $this->zJson('',400,false,$validate->getError());
        }
        $cam=\request()->post('cam');
        $card=new Cards();
        $re=$card->where('card_no',$cam)->find();
        if ($re) {
            if ($re->status==1){
                // 启动事务
                Db::startTrans();
                try{
                    $userModel=new Users();
                    $cpoint=$user->point+$re->point;
                    $res=$re->save(['status'=>2,'user_id'=>$user->id,'updated_at'=>$time]);
                    $res1=$userModel->save(['point'=>$cpoint,'updated_at'=>$time],['id'=>$user->id]);
                    $res2=(new ConsumeLog())->insert([
                        'user_id'=>$user->id,
                        'type'=>1,
                        'point'=>$re->point,
                        'order_no'=>$cam,
                        'current_point'=>$cpoint,
                        'reason'=>"充值卡号为：$cam 充值成功，充值点数为：$re->point 点，当前账户总点数为 $cpoint 点",
                        'created_at'=>$time,
                    ]);
                    //如果用户消费月卡，有推荐人则赠送推荐人点数(平台设置赠送点数)
                    if ($re->type==3 && $user->referee_id){
                        $zuser=$userModel->where('id',$user->referee_id)->find();
                        if (!empty($zuser)){
                            $p=$this->getConfig('ZENSONG');
                            $zpo=$zuser->point+$p;
                            $res4=$zuser->save(['point'=>$zpo,'updated_at'=>$time]);
                            $res5=(new ConsumeLog())->insert([
                                'user_id'=>$zuser->id,
                                'type'=>3,
                                'point'=>$p,
                                'current_point'=>$zpo,
                                'reason'=>"您推荐的会员：$user->name 成功消费月卡，平台赠送给您 $p 点 ，当前账户总点数为 $zpo 点",
                                'created_at'=>$time,
                            ]);
                            $res3=$userModel->save(['point'=>$zpo,'updated_at'=>$time],['id'=>$zuser->id]);
                            if($res && $res1 && $res2 && $res3 && $res4 && $res5){
                                // 提交事务
                                Db::commit();
                                return $this->zJson('',200,true,'充值成功');
                            }else{
//                                throw new \Exception($res .'--'. $res1 .'--'. $res2 .'--'. $res3 .'--'. $res4 .'--'. $res5);
                                throw new \Exception("充值失败");
                            }
                        }

                    }else{
                        if($res && $res1 && $res2){
                            // 提交事务
                            Db::commit();
                            return $this->zJson('',200,true,'充值成功');
                        }else{
                            throw new \Exception("充值失败");
                        }
                    }
                } catch (\Exception $e) {
                    // 回滚事务
                    Db::rollback();
                    return $this->zJson('',200,false,$e->getMessage());
                }

            }else{
                return $this->zJson('',200,false,'充值卡已失效');
            }
        }else{
            return $this->zJson('',200,false,'充值卡无效');
        }
    }

    /**
     * 获取用户消费记录
     * @param  type 1充值 2消费 3赠送 退还
     * @param page 当前页数
     * @param  size 每页条数
     * @return \think\response\Json
     */
    public function getConsumeLog()
    {
        if($this->UserModel()){
            $user=$this->UserModel();
        }else{
            return zJson('',400,false,'签名无效');
        }
        $validate = new Validate([
            'type'  => 'require',
        ]);
        if (!$validate->check(\request()->param())) {
            return $this->zJson('',400,false,$validate->getError());
        }
        $page=\request()->param('page')?:1;
        $size=\request()->param('size')?:10;
        $user_id=$user->id;
        switch (\request()->param('type')){
            case 1: //充值
                $list=(new ConsumeLog())->where(['user_id'=>$user_id,'type'=>1])
                    ->order('created_at', 'desc')
                    ->paginate($size,true,[
                    'page'=>$page,
                ])->toArray();
                break;
            case 2:  //消费
                $list=(new ConsumeLog())->where(['user_id'=>$user_id,'type'=>2])
                    ->order('created_at', 'desc')
                    ->paginate($size,true,[
                        'page'=>$page,
                    ])->toArray();
                break;
            case 3:  //赠送
                $list=(new ConsumeLog())->where(['user_id'=>$user_id,'type'=>3])
                    ->order('created_at', 'desc')
                    ->paginate($size,true,[
                        'page'=>$page,
                    ])->toArray();
                break;
            case 4:  //退还
                $list=(new ConsumeLog())->where(['user_id'=>$user_id,'type'=>4])
                    ->order('created_at', 'desc')
                    ->paginate($size,true,[
                        'page'=>$page,
                    ])->toArray();
                break;
        }
        if ($list['data']){
            return $this->zJson($list,200,true,'消费记录获取成功');
        }else{
            return $this->zJson('',200,false,'暂无该类型消费记录哦');
        }

    }


}

