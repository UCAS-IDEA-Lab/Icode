<?php

class SessionController{

    public function __construct(){
        if (!session_id()) session_start();
    }


    public function set($params=array()){
        foreach ($params as $key=>$value){
            $_SESSION[$key]=$value;
        }
    }

    public function delete($name){
        unset($_SESSION[$name]);
    }

    public function deleteAll($names=array()){
        foreach ($names as $name){
            $this->delete($name);
        }
    }

    public function destroy(){
        session_destroy();
    }

    public function isExpire(){
        if(!isset($_SESSION['last_access'])||!isset($_SESSION['remembertime'])||time()-$_SESSION['last_access']>$_SESSION['remembertime']){
            return true;
        }else{
            return false;
        }
    }

    public function isAdmin(){
        if(!$this->isExpire()&&$_SESSION['role']=='admin'){
            return true;
        }else{
            return false;
        }
    }

}