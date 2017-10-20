<div class="panel panel-default">
    <div class="panel-heading">
        <i class="fa fa-users fa-fw"></i> <?php echo $_SESSION['name'] ?>的基本信息
    </div>
    <br/>
    <div class="panel-body">
        <div class="col-sm-4" id="key" style="visibility: hidden">
            <div class="form-group">
                <select class="form-control input-sm" id="select">
                    <option value="password">密码</option>
                    <option value="email">邮箱</option>
                </select>
            </div>
        </div>


        <div class="col-sm-2 col-sm-offset-6" id="changeInfo">
            <a class="btn btn-primary"><i class="fa fa-pencil-square-o"></i> 修改信息</a>
        </div>
        <div class="col-sm-2 col-sm-offset-6" id="confirm" hidden>
            <a class="btn btn-primary" ><i class="fa fa-retweet"></i>确认修改</a>
        </div>
    </div>
    <div class="panel-body" style="min-height: 450px" id="content">
        <table class="table table-responsive table-condensed table-hover" id="showInfo">
            <tbody style="font-size: 14px">
            <?php foreach ($user as $info): ?>
            <tr>
                <td><?php echo $info[0]; ?></td>
                <td><?php echo $info[1]; ?></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <div id="updatePassword" hidden>

            <div class="panel-body col-sm-6" >
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></div>
                        <input class="form-control" placeholder="输入原密码" type="password" name="old_password"/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></div>
                        <input class="form-control" placeholder="输入新密码" type="password" name="new_password"/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></div>
                        <input class="form-control" placeholder="再次输入新密码" type="password" name="new_password_confirm"/>
                    </div>
                </div>
                <br/>
                <div class="password_message">

                </div>
            </div>

        </div>

        <div id="updateEmail" hidden>
            <div class="panel-body col-sm-6" >
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></div>
                        <input class="form-control" placeholder="输入新邮箱" type="text" name="new_email"/>
                    </div>
                </div>
                <div class="email_message">

                </div>
            </div>
        </div>
    </div>

</div>

<script>
    $(function(){
        $("#changeInfo").click(function () {
            $("#changeInfo").hide();
            $("#confirm").show();
            $("#key").css("visibility","visible");
            $("#value").css("visibility","visible");
            $("#updatePassword").show();
            $("#showInfo").hide();
        });
        $("#select").change(function () {
             var obj=$("#select");
             var obj1=$("#updatePassword");
             var obj2=$("#showInfo");
             var obj3=$("#updateEmail");
             if(obj.val()=="password"){
                 obj1.show();
                 obj2.hide();
                 obj3.hide();
             }else if(obj.val()=="email"){
                 obj1.hide();
                 obj2.hide();
                 obj3.show();
             }
        });
        function checkPassword() {
            var new_password = $("input[name='new_password']");
            var new_password_confirm=$("input[name='new_password_confirm']");
            var obj = $(".password_message");
            if (new_password.val().length<6) {
                new_password.focus();
                obj.html('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>密码至少6位</strong></div>');
                return false;
            }
            if (new_password_confirm.val() != new_password.val()) {
                new_password_confirm.focus();
                obj.html('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>密码不一致，请重新输入</strong></div>');
                return false;
            }
            return true;
        }
        function checkEmail() {
            var new_email = $("input[name='new_email']");
            var obj = $(".email_message");
            if (new_email.val().length<6) {
                new_email.focus();
                obj.html('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>邮箱不合法，请重试</strong></div>');
                return false;
            }
            return true;
        }
        $('#confirm').click(function(){
            var form = $("#select").val();
            if(form=="password"){
                if(checkPassword()){
                    $.ajax({
                        type: "POST",
                        url: "/user/update/password",
                        data: {old_password:$("input[name='old_password']").val(), new_password:$("input[name='new_password']").val()},
                        dataType: "html",
                        success: function(data){
                            $('.password_message').html('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>'+data+'</strong></div>');
                        }
                    });
                }else{
                    return false;
                }
            }else if(form=="email"){
                if(checkEmail()){
                    $.ajax({
                        type: "POST",
                        url: "/user/update/email",
                        data: {new_email:$("input[name='new_email']").val()},
                        dataType: "html",
                        success: function(data){
                            $('.email_message').html('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>'+data+'</strong></div>');
                        }
                    });
                }else{
                    return false;
                }
            }

        });
    });
</script>