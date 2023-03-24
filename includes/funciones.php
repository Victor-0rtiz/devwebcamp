<?php

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}
function pagina_actual($path){
    return str_contains($_SERVER["PATH_INFO"], $path)? true : false;
}
function is_auth(){
session_start();
return isset($_SESSION["nombre"]) && !empty($_SESSION);
}

function is_admin(){
    session_start();
    return isset($_SESSION["admin"]) && !empty($_SESSION["admin"]);
}