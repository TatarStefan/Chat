<?php

include_once "database.php";
session_start();

$currentMessage = $_POST['current_message'];

$updateCurrentMessageQuery = "update msg set current_message = '$currentMessage' where id = '{$_SESSION['id']}'";

$conn->query($updateCurrentMessageQuery);

echo $_SESSION['id'];
