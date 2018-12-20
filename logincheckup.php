<?php

    $posted_data = $_POST['json'];
    $data = json_decode($posted_data,true);
    $username = $data[0];
    $password = $data[1];
    $password = (string)$password;
    $login = exec("perl ciscoAuth.pl $username '$password'");

    if($login){
        session_start();
        $_SESSION["username"] = $username;
        echo "true";
    }else{
        echo "false";
    }

?>
