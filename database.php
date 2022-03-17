<?php
$conn = mysqli_connect("hostname", "username", "password", "database");
if (!$conn){
  echo "Something went wrong while connecting please try again later";
  exit;
}
?>
