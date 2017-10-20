<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>推荐系统</title>
    <script src="/static/javascript/jquery.min.js"></script>
    <script src="/static/javascript/bootstrap.min.js"></script>
    <link href="/static/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" media="all" href="/static/css/application.css" data-turbolinks-track="true"/>
    <link rel="stylesheet" href="/static/fontawesome/css/font-awesome.min.css">

</head>
<body>
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">

        <div class="navbar-header">
            <a class="navbar-brand" style="color:white" href="/"><i class="fa fa-leaf" aria-hidden="true"></i> 推荐系统</a>
        </div>

        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="dropdown">
                    <a class="dropbtn">菜单</a>
                    <div class="dropdown-content">
                        <a href="#" id="upload_panel"><i class="fa fa-angle-right fa-fw"></i>上传数据集</a>
                        <a href="/index/start_train" id="start_train"><i class="fa fa-angle-right fa-fw"></i> 训练模型</a>
                    </div>
                </li>
            </ul>
            <?php $logined = !(new SessionController())->isExpire(); ?>
            <?php if ($logined): ?>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a class="dropbtn"><?php echo $_SESSION['name']; ?><span class="glyphicon glyphicon-user"></span></a>
                    <div class="dropdown-content">
                        <a href="/user/info"><i class="fa fa-angle-right fa-fw"></i> 个人信息</a>
                        <?php if($_SESSION['role']=='admin'): ?>
                        <a href="/console/index"><i class="fa fa-angle-right fa-fw"></i> 控制台</a>
                        <?php endif; ?>
                    </div>
                </li>
                <li>
                    <a class="dropbtn" rel="nofollow" data-method="delete" href="/index/logout">退出 <span class="glyphicon glyphicon-log-out"></span></a>
                </li>
            </ul>
            <?php else: ?>
                <ul class="nav navbar-nav navbar-right">
                    <li id="login_panel"><a class="dropbtn" rel="nofollow" data-method="delete" href="#"> 登陆 <span class="glyphicon glyphicon-log-in"></span></a></li>
                    <li id="signin_panel"><a class="dropbtn" rel="nofollow" data-method="delete" href="#">注册 <span class="glyphicon glyphicon-pencil"></span></a></li>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</nav>

<?php if(!$logined): ?>
    <div class="login_content" hidden>
        <div class="modal-content">
            <div class="modal-header" style="padding:35px 50px;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4><span class="glyphicon glyphicon-log-in"></span> 登录</h4>
            </div>
            <div class="modal-body" style="padding:40px 50px;">

                <form role="form" action="/index/login" accept-charset="UTF-8" method="post">

                    <div class="form-group">
                        <label for="session_email">账号密码登陆</label>
                        <div class="input-group">
                            <div class="input-group-addon"><span class="glyphicon glyphicon-user"></span></div>
                            <input class="form-control" placeholder="输入名称" type="text" name="name"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></div>
                            <input class="form-control" placeholder="输入密码" type="password" name="password"/>
                        </div>
                    </div>

                    <div class="checkbox">
                        <label><input type="checkbox" value="0" name="remember_me"/>记住我</label>
                    </div>
                    <input hidden name="log_in" value="1"/>
                    <button name="login" type="submit" class="btn btn-primary btn-block">
                        <span class="glyphicon glyphicon-off"></span> 登陆
                    </button>
                </form>
            </div>

<!--            <div class="modal-footer">-->
<!--                <p class="text-center">没有账号? 请<a href="#" onclick="swich(0);">注册</a></p>-->
<!--            </div>-->

        </div>
    </div>

    <div class="signin_content" hidden>
        <div class="modal-content">
            <div class="modal-header" style="padding:35px 50px;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4><span class="glyphicon glyphicon-lock"></span> 注册</h4>
            </div>
            <div class="modal-body" style="padding:40px 50px;">

                <form role="form" action="/index/signup" accept-charset="UTF-8" method="post">

                    <div class="form-group">
                        <label for="session_email">用户注册</label>
                        <div class="input-group">
                            <div class="input-group-addon"><span class="glyphicon glyphicon-user"></span></div>
                            <input class="form-control" placeholder="输入名称" type="text" name="name"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></div>
                            <input class="form-control" placeholder="输入邮箱" type="text" name="email"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></div>
                            <input class="form-control" placeholder="输入密码" type="password" name="password"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></div>
                            <input class="form-control" placeholder="确认密码" type="password" name="password_confirm"/>
                        </div>
                    </div>

                    <input hidden name="sign_in" value="1"/>
                    <button name="signin" type="submit" class="btn btn-primary btn-block" onclick="return check(this.form)">
                        <span class="glyphicon glyphicon-off"></span> 注册
                    </button>

                    <div class="signup_error_message">

                    </div>
                </form>
            </div>

<!--            <div class="modal-footer">-->
<!--                <p class="text-center">已有账号? 请<a href="#" onclick="swich(1);">登陆</a></p>-->
<!--            </div>-->

        </div>
    </div>

    <div class="upload_file_content" hidden>
        <div class="modal-content">
            <div class="modal-header" style="padding:35px 50px;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4><span class="glyphicon glyphicon-lock"></span> 上传数据集</h4>
            </div>
            <div class="modal-body" style="padding:40px 50px;">

                <form role="form" action="/index/upload" enctype="multipart/form-data" method="post">

                    <input type="file" name="file" id="file" />

                    <input hidden name="upload_file" value="1"/>
                    <button name="upload_file" type="submit" class="btn btn-primary btn-block" >
                        <span class="glyphicon glyphicon-off"></span> 上传
                    </button>

                    <div class="upload_file_message">

                    </div>
                </form>
            </div>

            <!--            <div class="modal-footer">-->
            <!--                <p class="text-center">已有账号? 请<a href="#" onclick="swich(1);">登陆</a></p>-->
            <!--            </div>-->

        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="panel_modal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->


        </div>

    </div>



    <script>
        $(document).ready(function () {
            $("#login_panel").click(function (e) {
                e.preventDefault();
                $(".modal-dialog").html($(".login_content").html());
                $("#panel_modal").modal();
            });
            $("#signin_panel").click(function (e) {
                e.preventDefault();
                $(".modal-dialog").html($(".signin_content").html());
                $("#panel_modal").modal();
            });
            $("#upload_panel").click(function (e) {
                e.preventDefault();
                $(".modal-dialog").html($(".upload_file_content").html());
                $("#panel_modal").modal();
            });
        });
        function check(form) {
            var obj = $(".signup_error_message");
            if (form.name.value == '') {
                form.userId.focus();
                obj.html('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>请输入用户名</strong></div>');
                return false;
            }
            if (form.password.value.length<6) {
                form.password.focus();
                obj.html('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>密码至少6位</strong></div>');
                return false;
            }
            if (form.password.value != form.password_confirm.value) {
                form.password_confirm.focus();
                obj.html('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>密码不一致，请重新输入</strong></div>');
                return false;
            }
            return true;
        }

    </script>
<?php endif; ?>
