<?php
/**
 * 视图基类
 */
class View
{
    protected $variables = array();
    protected $_controller;
    protected $_action;

    function __construct($controller, $action){
        $this->_controller = strtolower($controller);
        $this->_action = strtolower($action);
    }
    public function assignArray($arr){
        foreach ($arr as $key=>$value){
            $this->variables[$key] = $value;
        }
    }
    public function assign($name, $value){
        $this->variables[$name] = $value;
    }

    public function change($controller, $action){
        $this->_controller = strtolower($controller);
        $this->_action = strtolower($action);
    }

    public function render(){
        extract($this->variables);
        $defaultHeader = SERVER_ROOT . '/views/header.php';
        $defaultFooter = SERVER_ROOT . '/views/footer.php';

        $controllerHeader = SERVER_ROOT . '/views/' . $this->_controller . '/header.php';
        $controllerFooter = SERVER_ROOT . '/views/' . $this->_controller . '/footer.php';
        $controllerLayout = SERVER_ROOT . '/views/' . $this->_controller . '/' . $this->_action . '.php';

        // 页头文件
        if (file_exists($controllerHeader)) {
            include ($controllerHeader);
        } else {
            include ($defaultHeader);
        }

        include ($controllerLayout);

        // 页脚文件
        if (file_exists($controllerFooter)) {
            include ($controllerFooter);
        } else {
            include ($defaultFooter);
        }
    }
}