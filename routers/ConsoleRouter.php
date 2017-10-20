<?php

if(!(new SessionController())->isAdmin()){
    header("Location: ".SITE_ROOT);
    exit;
}
$dispatch = new $controller($controllerName, $actionName);
call_user_func_array(array($dispatch, $actionName), array($params));
?>