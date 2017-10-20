<?php
function __autoload($class)
{
    $controllers = SERVER_ROOT . '/controllers/' . $class . '.php';
    $models = SERVER_ROOT . '/models/' . $class . '.php';
    $views = SERVER_ROOT . '/views/' . $class . '.php';
    if (file_exists($controllers)) {
        include $controllers;
    } elseif (file_exists($models)) {
        include $models;
    } elseif (file_exists($views)) {
        include $views;
    }else{
        die("File '$class' not found.");
    }
}

function getToken(){
    $token="";
    for($i=0;$i<64;$i++){
        $x=rand(0,36);
        if($x<=9){
            $token=$token.chr($x+48);
        }else{
            $token=$token.chr($x+87);
        }
    }
    return $token;
}


?>