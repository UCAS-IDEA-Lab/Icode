<?php
$dispatch = new $controller($controllerName, $actionName);
call_user_func_array(array($dispatch, $actionName), array($params));
?>