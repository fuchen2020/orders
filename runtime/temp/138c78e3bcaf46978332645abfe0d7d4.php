<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:69:"F:\order\orders\public/../application/index\view\index\orderlists.php";i:1525347611;}*/ ?>
<!DOCTYPE html>
<html>


<!-- Mirrored from www.zi-han.net/theme/hplus/table_data_tables.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 20 Jan 2016 14:20:01 GMT -->
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title>小雨云订单管理系统 - 主页</title>
    <meta name="keywords" content="小雨云订单管理系统">
    <meta name="description" content="小雨云订单管理系统">
    <link rel="shortcut icon" href="favicon.ico"> <link href="/static/css/bootstrap.min14ed.css?v=3.3.6" rel="stylesheet">
    <link href="/static/css/font-awesome.min93e3.css?v=4.4.0" rel="stylesheet">

    <!-- Data Tables -->
    <link href="/static/css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">

    <link href="/static/css/animate.min.css" rel="stylesheet">
    <link href="/static/css/style.min862f.css?v=4.1.0" rel="stylesheet">

</head>

<body class="gray-bg">
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>订单管理 <small>分类，查找</small></h5>

                    </div>
                    <div class="ibox-content">
                        <table class="table table-striped table-bordered table-hover dataTables-example">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>订单编号</th>
                                    <th>用户ID</th>
                                    <th>机器号</th>
                                    <th>消耗点数</th>
                                    <th>刷墙</th>
                                    <th>升级建筑</th>
                                    <th>升级兵种</th>
                                    <th>人工需求</th>
                                    <th>需求内容</th>
                                    <th>下单时间</th>
                                    <th>结束时间</th>
                                    <th>订单状态</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$data): $mod = ($i % 2 );++$i;?>
                                <tr class="gradeC">
                                    <td><?php echo $data['id']; ?></td>
                                    <td><?php echo $data['order_no']; ?></td>
                                    <td><?php echo $data['user_id']; ?></td>
                                    <td ><?php echo $data['device']; ?></td>
                                    <td ><?php echo $data['point']; ?></td>
                                    <td ><?php echo $data['brush_wall']==1?'是':'否'; ?></td>
                                    <td ><?php echo $data['up_building']==1?'是':'否'; ?></td>
                                    <td ><?php echo $data['up_arms']==1?'是':'否'; ?></td>
                                    <td ><?php echo $data['is_need']==1?'是':'否'; ?></td>
                                    <td ><?php echo $data['need']; ?></td>
                                    <td ><?php echo $data['created_at']; ?></td>
                                    <td ><?php echo $data['end_time']; ?></td>
                                    <td > <?php echo $data['status']; ?> </td>
                                </tr>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="/static/js/jquery.min.js?v=2.1.4"></script>
    <script src="/static/js/bootstrap.min.js?v=3.3.6"></script>
    <script src="/static/js/plugins/jeditable/jquery.jeditable.js"></script>
    <script src="/static/js/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="/static/js/plugins/dataTables/dataTables.bootstrap.js"></script>
    <script src="/static/js/content.min.js?v=1.0.0"></script>
    <script src="/layer/layer.js"></script>
    <script>
        $(document).ready(function(){$(".dataTables-example").dataTable();var oTable=$("#editable").dataTable();oTable.$("td").editable("http://www.zi-han.net/theme/example_ajax.php",{"callback":function(sValue,y){var aPos=oTable.fnGetPosition(this);oTable.fnUpdate(sValue,aPos[0],aPos[1])},"submitdata":function(value,settings){return{"row_id":this.parentNode.getAttribute("id"),"column":oTable.fnGetPosition(this)[2]}},"width":"90%","height":"100%"})});function fnClickAddRow(){$("#editable").dataTable().fnAddData(["Custom row","New row","New row","New row","New row"])};
    </script>

</body>


<!-- Mirrored from www.zi-han.net/theme/hplus/table_data_tables.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 20 Jan 2016 14:20:02 GMT -->
</html>
