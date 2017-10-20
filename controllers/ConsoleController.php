<?php

class ConsoleController extends Controller{
    protected $itemPerPage=20;

    public function index($params=array()){
        $this->assign('left',"");
        $this->render();
    }

    public function user($params=array()){
        $this->assign('left',"user");
        $page = isset($_POST['page'])?$_POST['page']:1;
        $pageTotal = isset($_POST['pageTotal'])?$_POST['pageTotal']:null;
        $pageSize = isset($_POST['pageSize'])?$_POST['pageSize']:$this->itemPerPage;
        $filter = isset($_POST['filter'])?$_POST['filter']:[];
        $results = (new UserModel())->getPage($page,$filter,$pageSize,$pageTotal);
        $this->assign('show_user',$this->showUsers(null,$results['users']));
        $this->assign('show_page',$this->showPagination($results['page'],$results['pageTotal']));
        $this->assign('users',$results['users']);
        $this->assign('pageSize',$results['pageSize']);
        $this->assign('page',$results['page']);
        $this->assign('pageTotal',$results['pageTotal']);
        $this->render();
    }

    public function user_query(){
        $key = $_POST['key'];
        $value = null;
        if(isset($_POST['value'])&&strlen($_POST['value'])!=0){
            $value = urlencode($_POST['value']);
        }
        $filter = $value?[$key=>['$regex'=>$value,'$options'=>'$i']]:[];
        $results = (new UserModel())->getPage(1,$filter,$this->itemPerPage,null);
        $this->assign('left',"user");
        if($value){
            $this->assign('key',$key);
            $this->assign('value',urldecode($value));
        }
        if(isset($results['error'])){
            $this->assign('show_user',$this->showUsers(true,null));
        }else{
            $this->assign('show_user',$this->showUsers(null,$results['users']));
            $this->assign('show_page',$this->showPagination($results['page'],$results['pageTotal']));
            $this->assign('pageSize',$results['pageSize']);
            $this->assign('page',$results['page']);
            $this->assign('pageTotal',$results['pageTotal']);
        }
        $this->change("Console","user");
        $this->render();
    }

    public function user_page(){
        $page = isset($_POST['page'])?$_POST['page']:1;
        $pageTotal = isset($_POST['pageTotal'])?$_POST['pageTotal']:null;
        $pageSize = isset($_POST['pageSize'])?$_POST['pageSize']:$this->itemPerPage;
        $key = $_POST['key'];
        $value = null;
        if(isset($_POST['value'])&&strlen($_POST['value'])!=0){
            $value = urlencode($_POST['value']);
        }
        $filter = $value?[$key=>['$regex'=>$value,'$options'=>'$i']]:[];
        $results = (new UserModel())->getPage($page,$filter,$pageSize,$pageTotal);
        $show_user = $this->showUsers(null,$results['users']);
        $show_page = $this->showPagination($results['page'],$results['pageTotal']);
        $data = ['show_user'=>$show_user,'show_page'=>$show_page];
        echo json_encode($data);
    }
    public function showUsers($error=null,$users){
        $content = '<table class="table table-responsive table-condensed table-hover text-center" id="showInfo">';
        $content = $content.'<tbody style="font-size: 14px">';
        $content = $content.'<tr><th>用户名</th><th>邮箱</th><th>角色</th><th>注册时间</th><th>操作</th></tr>';
        if($error){
            $content = $content.'<tr><td colspan="4">无结果</td></tr>';
        }else{
            foreach ($users as $user){
                $content = $content.'<tr>';
                $content = $content.'<td>'.urldecode($user->name).'</td>';
                $content = $content.'<td>'.urldecode($user->email).'</td>';
                $content = $content.'<td>'.$user->role.'</td>';
                $content = $content.'<td>'.$user->signup_date->toDateTime()->format('Y-m-d H:i:s').'</td>';
                $content = $content.'<td>'.'<i class="fa fa-angle-right fa-fw" style="cursor:pointer"></i>'.'</td>';
            }
        }
        $content = $content.'</tbody></table>';
        return $content;
    }
    public function showPagination($cur_page,$total_page){
        $content = '<ul class="pagination" style="cursor:pointer">';
        $content = $content.'<li><span class="fa fa-chevron-circle-left" aria-hidden="true" id="previous"></span></li>';
        $start = ($cur_page<4)?1:$cur_page-2;
        $end = $start+4;
        $end = ($end>$total_page)?$total_page:$end;
        $start=$end-4;
        $start = ($start>0)?$start:1;
        for($i=$start;$i<=$end;$i++){
            $content = $content.'<li ';
            if($i==$cur_page){
                $content = $content.'class="active"';
            }
            $content = $content.'><span>'.$i.'</span></li>';
        }
        $content = $content.'<li><span class="fa fa-chevron-circle-right" aria-hidden="true" id="next"></span></li></ul>';
        return $content;
    }
}