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
                        <h5>卡密管理 <small>分类，查找</small></h5>
                    </div>
                    <div class="ibox-content">
                        <table id="ts" class="table table-striped table-bordered table-hover dataTables-example">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>卡号</th>
                                    <th>类型</th>
                                    <th>点数</th>
                                    <th>状态</th>
                                    <th>创建时间</th>
                                    <th>使用用户ID</th>
                                    <th>被使用时间</th>
                                </tr>
                            </thead>
                            <tbody>
                            {volist name="list" id="data"}
                                <tr class="gradeC">
                                    <td>{$data.id}</td>
                                    <td >{$data.card_no}</td>
                                    <td>{$types[$data.type]}</td>
                                    <td>{$data.point}</td>
                                    <td>{$data.status==1?'未使用':'已使用'}</td>
                                    <td>{$data.created_at}</td>
                                    <td>{$data.user_id}</td>
                                    <td>{$data.updated_at}</td>
                                </tr>
                            {/volist}
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
    <script>
        function dataToTxt(tableid)
        {
            var file_name=window.prompt("请指定输出文件名称(.txt)","C://ExportTxt.txt");
            if(file_name!=null)
            {
                var curTbl = document.getElementById(tableid);
                file_name=file_name.split("//").join("////");
                alert(file_name);
                var FSO=new ActiveXObject("Scripting.FileSystemObject");
                var f1 = FSO.CreateTextFile(file_name, true);
                var Lenr = curTbl.rows.length; //取得表格行数
                for (i = 0; i < Lenr; i++){
                    var Lenc = curTbl.rows(i).cells.length; //取得每行的列数
                    for (j = 0; j < Lenc; j++){
                        f1.write(curTbl.rows(i).cells(j).innerText+" "); //赋值
                    }
                    f1.write("\r\n");
                }
                f1.close();
            }
        }
    </script>

</body>


<!-- Mirrored from www.zi-han.net/theme/hplus/table_data_tables.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 20 Jan 2016 14:20:02 GMT -->
</html>
