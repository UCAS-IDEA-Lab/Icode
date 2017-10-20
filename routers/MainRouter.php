<?php


$url = $_SERVER['REQUEST_URI'];
$position = strpos($url, '?');
$url = $position === false ? $url : substr($url, 0, $position);
$url = trim($url, '/');
$controllerName = $defaultController;
$actionName = $defaultAction;
$params = array();

if ($url) {
    $urlArray = explode('/', $url);
    $urlArray = array_filter($urlArray);
    $controllerName = $urlArray ? ucfirst($urlArray[0]) : $controllerName;
    array_shift($urlArray);
    $actionName = $urlArray ? $urlArray[0] : $actionName;
    array_shift($urlArray);
    $params = $urlArray ? $urlArray : $params;
}

$controller = $controllerName . 'Controller';
if (!class_exists($controller)) {
    exit($controller . '控制器不存在');
}
if (!method_exists($controller, $actionName)) {
    exit($actionName . '方法不存在');
}

include ($controllerName . 'Router.php');


?>