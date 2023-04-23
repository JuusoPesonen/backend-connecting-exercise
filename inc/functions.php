<?php

function createDbConnection(){
    $ini = parse_ini_file("./config.ini", true);

    $host = $ini["host"];
    $db = $ini["db"];
    $username = $ini["username"];
    $pw = $ini["pw"];

    try{
        $dbcon = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $username, $pw);
        return $dbcon;
    }catch( PDOException $e){
        echo "Connection failed: " . $e->getMessage();
    }

    return null;
}

function returnError(PDOException $pdoex) {
    header('HTTP/1.1 500 Internal Server Error');
    $error = array('error' => $pdoex->getMessage());
    print json_encode($error);
}