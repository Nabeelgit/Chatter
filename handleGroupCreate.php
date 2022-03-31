<?php
include "database.php";
if (isset($_POST['token']) && isset($_POST['groupname'])){
    $token = htmlspecialchars($_POST['token']);
    $groupname = htmlspecialchars($_POST['groupname']);
    $sql = "UPDATE groupchats SET name = '$groupname' WHERE unique_id = '$token'";
    if (mysqli_query($conn, $sql)){

    } else {
        echo "There was an error creating the group please try again later";
    }
}
if (isset($_POST['token']) && !isset($_POST['groupname'])){
    $token = mysqli_real_escape_string($conn, $_POST['token']);
    $sql = "SELECT members FROM groupchats WHERE unique_id = '$token'";
    $query = mysqli_query($conn, $sql);
    $result = mysqli_fetch_assoc($query);
    echo $result['members'] ?? $result;
}
if (isset($_POST['user_id']) && isset($_POST['created'])){
    $created = htmlspecialchars($_POST['created']);
    $id = htmlspecialchars($_POST['user_id']);
    echo "done";
}
?>