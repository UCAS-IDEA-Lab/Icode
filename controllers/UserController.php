<?php

class UserController extends Controller{

    private function transInfo($user){
        $map = (array)(new EntocnModel())->getMap();
        $except = ['password','_id'];
        $result=array();
        $i=0;
        foreach ($user as $key=>$value){
            if(in_array($key,$except)){
                continue;
            }
            if($key=='signup_date'){
                $key = $map[$key];

                $value = $value->toDateTime();
                $result[$i]=array($key,$value->format('Y-m-d H:i:s'));
            }else if(array_key_exists($key,$map)){
                $key = $map[$key];
                $result[$i]=array($key,urldecode($value));
            }
            $i++;
        }
        return $result;
    }
    public function info($params=array()){
        $left = "baseinfo";
        $user = null;
        if(count($params)>0){
            $left=$params[0];
        }
        if($left == "baseinfo"){
            $user = (new UserModel())->selectByName(urlencode($_SESSION['name']))[0];
            $user = $this->transInfo($user);
        }
        $this->assign('user',$user);
        $this->assign('left',$left);
        $this->render();
    }

    public function update($params=array()){
        $userModel = new UserModel();
        $message = "";
        if($params[0]=="password"){
            $old_password =urlencode($_POST['old_password']);
            $new_password =urlencode($_POST['new_password']);
            $user = $userModel->selectByEmail(urlencode($_SESSION['email']));
            $user = $user[0];
            if($user->password==$old_password){
                $result = $userModel->updatePassword($user->email,$new_password);
                if($result->getModifiedCount()==1){
                    $message = '更新密码成功';
                }else{
                    $message = '未知错误，更新失败';
                }
            }else{
                $message="更新失败，原密码错误，请重试";
            }
        }else if($params[0]=="email"){
            $old_email =urlencode($_SESSION['email']);
            $new_email =urlencode($_POST['new_email']);
            if($userModel->isExistEmail($new_email)){
                $message="更新失败，新邮箱已被注册，请重试";
            }else{
                $result = $userModel->updateEmail($old_email,$new_email);
                if($result->getModifiedCount()==1){
                    $_SESSION['email']=urldecode($new_email);
                    $message = '更新邮箱成功';
                }else{
                    $message = '未知错误，更新失败';
                }
            }
        }
        echo $message;
    }
}