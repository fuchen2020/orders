<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:66:"F:\order\orders\public/../application/index\view\index\addCard.php";i:1525278528;}*/ ?>
<!DOCTYPE html>
<html>


<!-- Mirrored from www.zi-han.net/theme/hplus/form_validate.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 20 Jan 2016 14:19:15 GMT -->
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title>小雨云订单管理系统 - 卡密生成</title>
    <meta name="keywords" content="小雨云订单管理系统">
    <meta name="description" content="小雨云订单管理系统">

    <link rel="shortcut icon" href="favicon.ico"> <link href="/static/css/bootstrap.min14ed.css?v=3.3.6" rel="stylesheet">
    <link href="/static/css/font-awesome.min93e3.css?v=4.4.0" rel="stylesheet">
    <link href="/static/css/animate.min.css" rel="stylesheet">
    <link href="/static/css/style.min862f.css?v=4.1.0" rel="stylesheet">

</head>

<body class="gray-bg">
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>卡密生成</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                            <a class="dropdown-toggle" data-toggle="dropdown" href="form_basic.html#">
                                <i class="fa fa-wrench"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-user">
                                <li><a href="form_basic.html#">选项1</a>
                                </li>
                                <li><a href="form_basic.html#">选项2</a>
                                </li>
                            </ul>
                            <a class="close-link">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                        <div class="ibox-content">
                            <form action="/addCard" method="post"  class="form-horizontal m-t" id="commentForm">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">卡类型：</label>
                                    <div class="col-sm-8">
                                        <select name="type" style="width: 300px;height: 30px;color: #0a6aa1;font-size: 18px;text-align: center;">
                                            <option value="1">天卡(24点)</option>
                                            <option value="2">周卡(168点)</option>
                                            <option value="3">月卡(720点)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">生成数量：</label>
                                    <div class="col-sm-8">
                                        <input id="cemail" type="text" class="form-control" name="num" required="" aria-required="true" style="width: 300px;height: 30px;color: #0a6aa1;font-size: 18px;">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-4 col-sm-offset-3">
                                        <button class="btn btn-primary" type="submit">提交</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="/static/js/jquery.min.js?v=2.1.4"></script>
    <script src="/static/js/bootstrap.min.js?v=3.3.6"></script>
    <script src="/static/js/content.min.js?v=1.0.0"></script>
    <script src="/static/js/plugins/validate/jquery.validate.min.js"></script>
    <script src="/static/js/plugins/validate/messages_zh.min.js"></script>
    <script src="/static/js/demo/form-validate-demo.min.js"></script>

</body>


<!-- Mirrored from www.zi-han.net/theme/hplus/form_validate.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 20 Jan 2016 14:19:16 GMT -->
</html>
