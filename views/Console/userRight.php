<div class="panel panel-default">
    <div class="panel-heading">
        <i class="fa fa-users fa-fw"></i> 用户信息管理
    </div>
    <div class="panel-body " id="search_bar">
        <form role="form" action="/console/user_query" accept-charset="UTF-8" method="post">
            <div class="col-sm-2 col-sm-offset-1">
                <select class="form-control" name="key">
                    <option value="name" <?php if (isset($key) && $key == 'name') echo 'selected="selected"'; ?>>用户名
                    </option>
                    <option value="email" <?php if (isset($key) && $key == 'email') echo 'selected="selected"'; ?>>邮箱
                    </option>
                </select>
            </div>
            <div class="col-sm-3">
                <input class="form-control" placeholder="输入查询值" type="text"
                       name="value" <?php if (isset($value)) echo 'value="' . $value . '"'; ?>/>
            </div>
            <div class="col-sm-1">
                <button name="query" type="submit" class="btn btn-primary btn-block">提交</button>
            </div>
        </form>
    </div>
    <div class="panel-body" id="table">
        <?php echo $show_user; ?>
    </div>

    <div class="panel-body" id="pages">
        <?php if (isset($show_page)): ?>
            <div class="col-sm-2 col-sm-offset-1">
                共 <span id="pageTotal"><?php echo $pageTotal; ?></span> 页
            </div>
            <div class="col-sm-4 col-sm-offset-1" id="pagination">
                <?php echo $show_page; ?>
            </div>
            <div class="col-sm-2">
                跳转到 <input class="form-control input-sm" type="text" id="skip" style="width:30%;display:inline"/> 页
            </div>
        <?php endif; ?>
    </div>


</div>

<script>
    $('#skip').keydown(function(e){
        if(e.keyCode==13){
            var cur_page = parseInt($('#skip').val());

            var page_total = parseInt($('#pageTotal').html());
            var key = $("select[name='key']").val();
            var value = $("input[name='value']").val();

            $.ajax({
                type: "POST",
                url: "/console/user_page",
                data: {key:key, value:value,page:cur_page,pageTotal:page_total},
                dataType: "json",
                success: function(data){
                    $('#table').html(data.show_user);
                    $('#pagination').html(data.show_page);
                }
            });
        }
    });
    $('#pagination').on('click','.pagination>li',function () {
        var cur_page = parseInt($('.pagination .active>span').html());

        var obj = $(this).children("span");
        if (obj.html().length == 0) {
            if(obj.attr('id')=='previous'){
                cur_page = cur_page-1;
            }else{
                cur_page = cur_page+1;
            }
        } else {
            cur_page = parseInt(obj.html());
        }

        var page_total = parseInt($('#pageTotal').html());
        var key = $("select[name='key']").val();
        var value = $("input[name='value']").val();

        $.ajax({
            type: "POST",
            url: "/console/user_page",
            data: {key:key, value:value,page:cur_page,pageTotal:page_total},
            dataType: "json",
            success: function(data){
                $('#table').html(data.show_user);
                $('#pagination').html(data.show_page);
            }
        });

    });
</script>
<style type="text/css">
    th {
        text-align: center !important;
    }
</style>