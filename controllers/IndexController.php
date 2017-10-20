<?php

class IndexController extends Controller{
    public function sessionStart(){
        if (!session_id()) session_start();
    }
    public function index(){
        $this->sessionStart();
        if(isset($_SESSION['message'])){
            $this->assign('message', $_SESSION['message']);
            unset($_SESSION['message']);
        }
        $this->render();
    }
    public function login(){
        $this->sessionStart();
        $message = "";
        if (isset($_POST['name'])  && isset($_POST['password']) ) {
            $name = urlencode($_POST['name']);
            $password = urlencode($_POST['password']);
            $remember = isset($_POST['remember_me'])?1:0;
            $result = (new UserModel())->selectByName($name);
            if (count($result) == 1) {
                $result = $result[0];
                if ($result->password == $password) {
                    $_SESSION['name'] = urldecode($name);
                    $_SESSION['email'] = urldecode($result->email);
                    $_SESSION['role'] = $result->role;
                    $_SESSION['id'] = $result->_id;
                    $_SESSION['last_access'] = time();
                    if ($remember == 1) {
                        $_SESSION['remembertime'] = 60 * 24 * 60;
                    } else {
                        $_SESSION['remembertime'] = 600;
                    }
                    $message = "欢迎回来，".urldecode($name);
                } else {
                    $message = "密码错误";
                }
            } else {
                $message = "用户不存在";
            }
        }
        if($message!=""){
            $this->assign('message', $message);
        }
        $this->change("Index","index");
        $this->index();
    }

    public function logout(){
        $this->sessionStart();
        session_destroy();
        $this->change("Index","index");
        $this->index();
    }

    public function signup(){
        $this->sessionStart();
        $message = "";
        if (isset($_POST['name'])  && isset($_POST['password']) && isset($_POST['email'])) {
            $name = urlencode($_POST['name']);
            $password = urlencode($_POST['password']);
            $email = urlencode($_POST['email']);
            $usermodel = new UserModel();
            if ($usermodel->isExistName($name)) {
                $message = "用户名已被注册，请重新选择";
            } else if($usermodel->isExistEmail($email)) {
                $message = "该邮箱已注册，请重新选择";
            }else{
                $result= $usermodel->addUser($name,$email,$password);
                if($result->getInsertedCount()==1){
                    $message = '注册成功，请使用用户名 '.urldecode($name).' 登录';
                }else{
                    $message = '未知错误，注册失败';
                }
            }
        }
        if($message!=""){
            $this->assign('message', $message);
        }
        $this->change("Index","index");
        $this->index();
    }

    public function getopinion(){
        // 创建socket
        if(isset($_POST['query'])&&$_POST['query']!=""){
            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            // 连接服务器
            $connection = socket_connect($socket, '192.168.95.130', '3434');
            // 接收返回数据
            $in=$_POST['query'];
            socket_write($socket, $in, strlen($in));
            $buffer = socket_read($socket, 1024);
            echo $buffer;
            // $socket = fsockopen("127.0.0.1",3434,$errno,$errster,1);
            // echo fread($socket,128);
        }else{
            echo "";
        }


    }
    public function getstatics(){
        // 创建socket
        if(isset($_POST['query'])&&$_POST['query']!=""){
            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            // 连接服务器
            $connection = socket_connect($socket, '192.168.95.130', '3434');
            // 接收返回数据
            $in=$_POST['query'];
            socket_write($socket, $in, strlen($in));
            $buffer = socket_read($socket, 1024);

            echo $buffer;
            // $socket = fsockopen("127.0.0.1",3434,$errno,$errster,1);
            // echo fread($socket,128);
        }else{
            echo "";
        }

    }

    public function upload(){
        $message = "";
        if ($_FILES["file"]["type"] == "text/plain") {
            if ($_FILES["file"]["error"] > 0) {
                $message= "未知错误";
            }
            else {
                move_uploaded_file($_FILES["file"]["tmp_name"], "data/data.txt");
                $message = "上传成功";
            }
        }
        else {
            $message = "文件不合法，必须是txt文件";
        }
        if($message!=""){
            $this->assign('message', $message);
        }
        $this->change("Index","index");
        $this->index();
    }

    public function start_train(){
        $message = "";
        // 创建socket

        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        // 连接服务器
        $connection = socket_connect($socket, '192.168.95.130', '3434');
        // 接收返回数据
        $in="start_train";
        socket_write($socket, $in, strlen($in));
        $buffer = socket_read($socket, 1024);

        $message = $buffer;
        // $socket = fsockopen("127.0.0.1",3434,$errno,$errster,1);
        // echo fread($socket,128);

        if($message!=""){
            $this->assign('message', $message);
        }
        $this->change("Index","index");
        $this->index();
    }
}