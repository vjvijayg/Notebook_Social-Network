<?php
require 'config/config.php';

if(isset($_SESSION['username'])) {
  $userLogedIn = $_SESSION['username'];
}
else {
  header("Location: register.php");
}

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Social Network</title>
  </head>
  <body>
