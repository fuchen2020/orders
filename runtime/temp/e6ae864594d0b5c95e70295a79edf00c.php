<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:64:"F:\order\orders\public/../application/index\view\index\login.php";i:1525277322;}*/ ?>
<!DOCTYPE html>
<html>


<!-- Mirrored from www.zi-han.net/theme/hplus/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 20 Jan 2016 14:18:23 GMT -->
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title>小雨云订单管理系统 - 登录</title>
    <meta name="keywords" content="小雨云订单管理系统">
    <meta name="description" content="小雨云订单管理系统">
    <link rel="shortcut icon" href="favicon.ico"> <link href="/static/css/bootstrap.min14ed.css?v=3.3.6" rel="stylesheet">
    <link href="/static/css/font-awesome.min93e3.css?v=4.4.0" rel="stylesheet">

    <link href="/static/css/animate.min.css" rel="stylesheet">
    <link href="/static/css/style.min862f.css?v=4.1.0" rel="stylesheet">
    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html" />
    <![endif]-->
    <script>if(window.top !== window.self){ window.top.location = window.location;}</script>
</head>

<body class="gray-bg">

    <div class="middle-box text-center loginscreen  animated fadeInDown">
        <div>
            <div>

                <h1 class="logo-name">JN</h1>

            </div>
            <h3>小雨云订单管理系统</h3>

            <form class="m-t" role="form" action="/adminLogin" METHOD="post">
                <div class="form-group">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <input type="text" id="name" name="phone" class="form-control" placeholder="手机号" required="">
                </div>
                <div class="form-group">
                    <input type="password" id="pass" name="pass" class="form-control" placeholder="密码" required="">
                </div>
                <button type="submit" id="login" class="btn btn-primary block full-width m-b">登 录</button>
                </p>

            </form>
        </div>
    </div>
    <script src="/static/js/jquery.min.js?v=2.1.4"></script>
    <script src="/static/js/bootstrap.min.js?v=3.3.6"></script>
    <script src="/layer/layer.js"></script>
    <script>
        $(function () {
            $('#login').click(function () {
                var name=$('#name').val();
                var pass=$('#pass').val();
                if (name==='') {
                    return layer.tips('用户名不能为空', '#name');
                }
                if (pass==='') {
                   return  layer.tips('密码不能为空', '#pass',{
                        tipsMore: true
                    });
                }
            })


        })


    </script>

</body>


<!-- Mirrored from www.zi-han.net/theme/hplus/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 20 Jan 2016 14:18:23 GMT -->
</html>
