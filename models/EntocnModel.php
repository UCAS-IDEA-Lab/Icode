<?php
class EntocnModel extends Model{
    protected $_collection;
    protected $_table;

    public function __construct(){
        $this->_collection = "movie";
        $this->_table = "entocn";
        $this->connect();
    }
    public function getMap(){
        return $this->selectAll($this->_collection,$this->_table)[0];
    }

}

?>