<?php

    $dbServername = "localhost:3306";
    $dbUsername = "catalinb";
    $dbPassword = "";
    $dbName = "messages";

    $conn = mysqli_connect($dbServername, $dbUsername, $dbPassword, $dbName);

    function sendMessage($message, $user_id)
    {
    }
