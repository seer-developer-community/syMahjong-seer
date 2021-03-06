<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:90:"E:\PHPTutorial\WWW\seer_gm\public/../application/admin\view\gamemodule\horseset\index.html";i:1535699920;s:69:"E:\PHPTutorial\WWW\seer_gm\application\admin\view\layout\default.html";i:1535699920;s:66:"E:\PHPTutorial\WWW\seer_gm\application\admin\view\common\meta.html";i:1535699920;s:68:"E:\PHPTutorial\WWW\seer_gm\application\admin\view\common\script.html";i:1535699920;}*/ ?>
<!DOCTYPE html>
<html lang="<?php echo $config['language']; ?>">
    <head>
        <meta charset="utf-8">
<title><?php echo (isset($title) && ($title !== '')?$title:''); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<meta name="renderer" content="webkit">

<link rel="shortcut icon" href="/seer_gm/public/assets/img/favicon.ico" />
<!-- Loading Bootstrap -->
<link href="/seer_gm/public/assets/css/backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.css?v=<?php echo \think\Config::get('site.version'); ?>" rel="stylesheet">

<!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
<!--[if lt IE 9]>
  <script src="/seer_gm/public/assets/js/html5shiv.js"></script>
  <script src="/seer_gm/public/assets/js/respond.min.js"></script>
<![endif]-->
<script type="text/javascript">
    var require = {
        config:  <?php echo json_encode($config); ?>
    };
</script>
    </head>

    <body class="inside-header inside-aside <?php echo defined('IS_DIALOG') && IS_DIALOG ? 'is-dialog' : ''; ?>">
        <div id="main" role="main">
            <div class="tab-content tab-addtabs">
                <div id="content">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <section class="content-header hide">
                                <h1>
                                    <?php echo __('Dashboard'); ?>
                                    <small><?php echo __('Control panel'); ?></small>
                                </h1>
                            </section>
                            <?php if(!IS_DIALOG && !$config['fastadmin']['multiplenav']): ?>
                            <!-- RIBBON -->
                            <div id="ribbon">
                                <ol class="breadcrumb pull-left">
                                    <li><a href="dashboard" class="addtabsit"><i class="fa fa-dashboard"></i> <?php echo __('Dashboard'); ?></a></li>
                                </ol>
                                <ol class="breadcrumb pull-right">
                                    <?php foreach($breadcrumb as $vo): ?>
                                    <li><a href="javascript:;" data-url="<?php echo $vo['url']; ?>"><?php echo $vo['title']; ?></a></li>
                                    <?php endforeach; ?>
                                </ol>
                            </div>
                            <!-- END RIBBON -->
                            <?php endif; ?>
                            <div class="content">
                                <div class="panel panel-default panel-intro">
    <!--<?php echo build_heading(); ?>-->

    <div class="panel-body">
        <div id="myTabContent" class="tab-content">
            <div class="tab-pane fade active in" id="one">
                <div class="widget-body no-padding">
                    <div id="toolbar" class="toolbar">
                        <?php echo build_toolbar('refresh'); ?>
                        <div class="dropdown btn-group <?php echo $auth->check('gamemodule/horseset/add')?'':'hide'; ?>" style="background-color: #18BC9C;color: white;width: 70px;height: 30px;text-align: center;line-height: 30px;">
                            <a -class="btn btn-success btn-edit" title="新增条目" id="add" style="color: white;cursor: pointer;">
                                <i class="fa fa-plus"></i>
                                新增条目
                            </a>
                        </div>
                        <div class="dropdown btn-group <?php echo $auth->check('gamemodule/horseset/timeSet')?'':'hide'; ?>" style="background-color: #18BC9C;color: white;width: 70px;height: 30px;text-align: center;line-height: 30px;" onclick="timeSet()">
                            <a -class="btn btn-success btn-edit" title="时间设置" id="timeSet" style="color: white;cursor: pointer;">
                                <i class="fa fa-plus"></i>
                                时间设置
                            </a>
                        </div>
                        <div class="dropdown btn-group <?php echo $auth->check('gamemodule/horseset/temporaryInserting')?'':'hide'; ?>" style="background-color: #18BC9C;color: white;width: 70px;height: 30px;text-align: center;line-height: 30px;" onclick="temporaryInserting()">
                            <a -class="btn btn-success btn-edit" title="临时插播" id="temporaryInserting" style="color: white;cursor: pointer;">
                                <i class="fa fa-plus"></i>
                                临时插播
                            </a>
                        </div>
                        <div class="dropdown btn-group <?php echo $auth->check('temporaryrecording/index')?'':'hide'; ?>" style="background-color: #18BC9C;color: white;width: 70px;height: 30px;text-align: center;line-height: 30px;" onclick="temporaryRecording()">
                            <a -class="btn btn-success btn-edit" title="插播记录" id="edit3" style="color: white;cursor: pointer;">
                                插播记录
                            </a>
                        </div>
                    </div>
                    <table id="table" class="table table-striped table-bordered table-hover"
                           data-operate-edit="<?php echo $auth->check('user/user/edit'); ?>"
                           data-operate-del="<?php echo $auth->check('user/user/del'); ?>"
                           width="100%">
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
<script type="text/javascript" src="/seer_gm/public//assets/libs/jcrop/js/jquery.min.js"></script>
<script type="text/javascript">
    $(function () {
        $("#ribbon").css("display","none");
    })
</script>

<!--跑马灯新增条目-->
<script type="text/javascript">
    $(function () {
        $("#add").click(function () {
            var modurl = "<?php echo url('add'); ?>";
            layer.open({
                type: 2,
                closeBtn: 1,
                skin: 'layui-layer-demo',
                title: '跑马灯设置',
                maxmin: false,
                shadeClose: true,
                area : ['800px', '550px'],
                offset : ['20px', ''],
                content: modurl
            })
        })
    })
</script>

<script type="text/javascript">
    function timeSet() {
        //获取所有被选中的记录
        var rows = $("#table").bootstrapTable('getSelections');
        if (rows.length==0) {
            alert("请至少选择一条跑马灯记录来进行设置时间!");
            return;
        }
        else {
            if(confirm("是否确定要对选中的跑马灯进行设置时间?")){
                var ids = '';
                for (var i = 0; i < rows.length; i++) {
                    ids += rows[i]['id'] + ",";
                }
                ids = ids.substring(0, ids.length - 1);
                //console.log(ids);
                //跳转弹窗
                var modurl = "<?php echo url('timeSet'); ?>?ids="+ids;
                layer.open({
                    type: 2,
                    closeBtn: 1,
                    skin: 'layui-layer-demo',
                    title: '时间设置',
                    maxmin: false,
                    shadeClose: true,
                    area : ['800px', '550px'],
                    offset : ['20px', ''],
                    content: modurl
                })
            }
        }
    }
</script>

<!--临时插播-->
<script type="text/javascript">
    function temporaryInserting() {
        //获取所有被选中的记录
        if(confirm("是否确定要临时插播一条消息?")){
                //跳转弹窗
                var modurl = "<?php echo url('temporaryInserting'); ?>";
                layer.open({
                    type: 2,
                    closeBtn: 1,
                    skin: 'layui-layer-demo',
                    title: '临时插播',
                    maxmin: false,
                    shadeClose: true,
                    area : ['800px', '550px'],
                    offset : ['20px', ''],
                    content: modurl
                })
        }

    }
</script>

<!--插播记录-->
<script type="text/javascript">
    function temporaryRecording() {
        var modurl = "<?php echo url('temporaryrecording/index'); ?>";
        layer.open({
            type: 2,
            closeBtn: 1,
            skin: 'layui-layer-demo',
            title: '临时插播记录信息',
            maxmin: true,
            shadeClose: true,
            area : ['1000px', '650px'],
            offset : ['20px', ''],
            content: modurl
        })
    }
</script> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="/seer_gm/public/assets/js/require<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js" data-main="/seer_gm/public/assets/js/require-backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js?v=<?php echo $site['version']; ?>"></script>
    </body>
</html>