

<br>
<div class="container">
    <?php if(isset($message)): ?>
    <div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong><?php echo $message; ?></strong></div>
    <?php endif; ?>
</div>

<div class="container-fluid">

    <div class="col-sm-8 col-sm-offset-2 panel panel-default" >
        <div class="panel-heading">
            <h1>推荐系统</h1>
        </div>
        <br/>
        <div class="form-group">
            <select class="form-control input-sm" id="select" placeholder="输选择题目" onchange="getstatics()">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
            </select>
        </div>
        <div class="col-sm-12 panel-body" style="height: 400px">
            <div class="col-sm-4 model-left" style="height:380px;">
                <div class="col-sm-12" >
                    <div class=" btn btn-primary drag"  style="position:absolute;cursor:move;" >
                        <span>A:</span>
                        <input  id="params1"  type="text" style="width: 20px;background-color: #ec971f"/>
                        <input  id="params2"  type="text" style="width: 20px;background-color: #ec971f"/>
                    </div>
                    <br/><br/>
                    <div class=" btn btn-primary drag"  style="position:absolute;cursor:move;" >
                        <span>B:</span>
                        <input  id="params1"  type="text" style="width: 20px;background-color: #ec971f"/>

                    </div>
                    <br/><br/>
                    <div class=" btn btn-primary drag"  style="position:absolute;cursor:move;" >
                        <span>B:</span>
                        <input  id="params1"  type="text" style="width: 20px;background-color: #ec971f"/>

                    </div><br/><br/>
                    <div class=" btn btn-primary drag"  style="position:absolute;cursor:move;" >
                        <span>A:</span>
                        <input  id="params1"  type="text" style="width: 20px;background-color: #ec971f"/>
                        <input  id="params2"  type="text" style="width: 20px;background-color: #ec971f"/>
                    </div>
                    <br/><br/>
                    <div class=" btn btn-primary drag"  style="position:absolute;cursor:move;" >
                        <span>C:</span>
                        <input  id="params1"  type="text" style="width: 20px;background-color: #ec971f"/>
                        <input  id="params2"  type="text" style="width: 20px;background-color: #ec971f"/>
                        <input  id="params3"  type="text" style="width: 20px;background-color: #ec971f"/>
                    </div>
                    <br/><br/>
                </div>

            </div>

        </div>

        <div class="col-sm-8">
            <input class="form-control " id="query" placeholder="输入您的答案" type="text" name="new_email"/>
        </div>
        <div class="col-sm-4">
            <a class="btn btn-primary" id="confirm" ><i class="fa fa-retweet"></i>确认提交</a>
        </div>

        <div class="col-sm-12  message" style="margin: 5px 0px">

        </div>
    </div>


</div>



<script>
    function makeModel(model_type,n_params,n){
        var div="";
    }
    function getstatics() {
        $.ajax({
            type: "POST",
            url: "/index/getstatics",
            data: {query:"query "+$('#select').val()},
            dataType: "html",
            success: function(data){
                $('.message').html('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>查询结果：'+data+'</strong></div>');
            }
        });

    }
    $(function(){
        $('#confirm').click(function(){

            $.ajax({
                type: "POST",
                url: "/index/getopinion",
                data: {query:"recommende "+$('#query').val()},
                dataType: "html",
                success: function(data){
                    $('.message').html('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>答案错误 推荐学习意见：'+data+'</strong></div>');
                }
            });

        });
    });
</script>





    <script type="text/javascript">
        // 模块拖拽
        $(function(){
            var _move=false;//移动标记
            var _x,_y;//鼠标离控件左上角的相对位置
            $(".drag").click(function(){
                //alert("click");//点击（松开后触发）
            }).mousedown(function(e){
                _move=true;
                _x=e.pageX-parseInt($(".drag").css("left"));
                _y=e.pageY-parseInt($(".drag").css("top"));
                $(".drag").fadeTo(20, 0.5);//点击后开始拖动并透明显示
            });
            $(document).mousemove(function(e){
                if(_move){
                    var x=e.pageX-_x;//移动时根据鼠标位置计算控件左上角的绝对位置
                    var y=e.pageY-_y;
                    $(".drag").css({top:y,left:x});//控件新位置
                }
            }).mouseup(function(){
                _move=false;
                $(".drag").fadeTo("fast", 1);//松开鼠标后停止移动并恢复成不透明
            });
        });
    </script>




<!--<div class="drag">这个可以拖动哦 ^_^</div>-->
<!---->
<!--<div class="drag">这个可以拖动哦 ^_^</div>-->


