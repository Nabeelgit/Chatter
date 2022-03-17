<?php
$conn = mysqli_connect("host", "username", "password", "database");
if (!$conn){
  echo "Something went wrong while connecting please try again later";
  exit;
}
?>
