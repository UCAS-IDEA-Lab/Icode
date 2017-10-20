<?php

class Sql
{
    protected $_con;

        // 连接数据库
//    $filter = ['name' => $name];
//    $query = new MongoDB\Driver\Query($filter);
//    $result = $con->executeQuery($db . ".user", $query);
//    $result = $result->toArray();
    public function connect(){
        try{
            $this->_con    = new MongoDB\Driver\Manager(DATABASE);
        }catch (Exception $e){
            die("Database Error.");
        }

    }

    // 查询条件
    public function where($where = array(),$collection,$table){
        $filter=array();
        if (isset($where)) {
            foreach($where as $key=>$value){
                $filter[$key]=$value;
            }
        }
        $query = new MongoDB\Driver\Query($filter);
        $result = $this->_con->executeQuery($collection .'.'. $table, $query);
        return $result->toArray();
    }
    // 根据条件 (id) 查询
    public function selectbyid($id,$collection,$table){
        $filter = array();
        $filter['_id']=$id;
        $query = new MongoDB\Driver\Query($filter);
        $result = $this->_con->executeQuery($collection .'.'. $table, $query);

        return $result->toArray();
    }

    public function getCount($filter,$option,$collection,$table){
        $query = new MongoDB\Driver\Query($filter,$option);
        $result = $this->_con->executeQuery($collection .'.'. $table, $query);
        return count($result->toArray());
    }
    public function insert($content=array(),$collection,$table){
        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->insert($content);
        return $this->_con->executeBulkWrite($collection .'.'. $table, $bulk);
    }
    public function selectAll($collection,$table){
        $query = new MongoDB\Driver\Query([]);
        $result = $this->_con->executeQuery($collection .'.'. $table, $query);
        return $result->toArray();
    }

    public function update($filter,$data,$collection,$table){
        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->update($filter,$data);
        return $this->_con->executeBulkWrite($collection .'.'. $table, $bulk);
    }

    public function selectByOption($filter,$option,$collection,$table){
        $query = new MongoDB\Driver\Query($filter,$option);
        $result = $this->_con->executeQuery($collection .'.'. $table, $query);
        return $result->toArray();
    }













    // 排序条件
    public function order($order = array())
    {
        if(isset($order)) {
            $this->filter .= ' ORDER BY ';
            $this->filter .= implode(',', $order);
        }

        return $this;
    }



    // 根据条件 (id) 删除
    public function delete($id)
    {
        $sql = sprintf("delete from `%s` where `id` = '%s'", $this->_table, $id);
        $sth = $this->_dbHandle->prepare($sql);
        $sth->execute();

        return $sth->rowCount();
    }

    // 自定义SQL查询，返回影响的行数
    public function query($sql)
    {
        $sth = $this->_dbHandle->prepare($sql);
        $sth->execute();

        return $sth->rowCount();
    }

    // 新增数据
    public function add($data)
    {
        $sql = sprintf("insert into `%s` %s", $this->_table, $this->formatInsert($data));

        return $this->query($sql);
    }



    // 将数组转换成插入格式的sql语句
    private function formatInsert($data)
    {
        $fields = array();
        $values = array();
        foreach ($data as $key => $value) {
            $fields[] = sprintf("`%s`", $key);
            $values[] = sprintf("'%s'", $value);
        }

        $field = implode(',', $fields);
        $value = implode(',', $values);

        return sprintf("(%s) values (%s)", $field, $value);
    }

    // 将数组转换成更新格式的sql语句
    private function formatUpdate($data)
    {
        $fields = array();
        foreach ($data as $key => $value) {
            $fields[] = sprintf("`%s` = '%s'", $key, $value);
        }

        return implode(',', $fields);
    }
}