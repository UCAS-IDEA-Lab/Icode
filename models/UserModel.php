<?php
class UserModel extends Model{
    protected $_collection;
    protected $_table;

    public function __construct(){
        $this->_collection = "movie";
        $this->_table = "user";
        $this->connect();
    }
    public function selectByEmail($email){
        return $this->where(['email'=>$email],$this->_collection,$this->_table);
    }
    public function selectByName($name){
        return $this->where(['name'=>$name],$this->_collection,$this->_table);
    }
    public function isExistName($name){
        return count($this->selectByName($name))>0;
    }
    public function getPage($page,$filter,$pageSize,$pageTotal){
        if($page<1)$page=1;
        if(!$pageTotal){
            $pageTotal = ceil($this->getCount($filter,[],$this->_collection,$this->_table)/$pageSize);
        }
        if($pageTotal==0){
            return ['error'=>true];
        }
        if($page>$pageTotal)$page=$pageTotal;
        $option = ['skip'=>($page-1)*$pageSize,'limit'=>$pageSize];
        return ['users'=>$this->selectByOption($filter,$option,$this->_collection,$this->_table),'pageSize'=>$pageSize,'page'=>$page,'pageTotal'=>$pageTotal];
    }
    public function isExistEmail($email){
        return count($this->selectByEmail($email))>0;
    }

    public function updatePassword($email,$new_password){
        $filter=['email'=>$email];
        $data=['$set'=>['password'=>$new_password]];
        return $this->update($filter,$data,$this->_collection,$this->_table);
    }
    public function updateEmail($old_email,$new_email){
        $filter=['email'=>$old_email];
        $data=['$set'=>['email'=>$new_email]];
        return $this->update($filter,$data,$this->_collection,$this->_table);
    }
    public function addUser($name,$email,$password){
        $content = ['name'=>$name,'email'=>$email,'password'=>$password,'role'=>'member','signup_date'=>new MongoDB\BSON\UTCDateTime((time()+8*60*60)*1000)];
        return $this->insert($content,$this->_collection,$this->_table);
    }
}

?>