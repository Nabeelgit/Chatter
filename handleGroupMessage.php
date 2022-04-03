<?php
include "database.php";
if (isset($_POST['message']) && isset($_POST['group_id']) && isset($_POST['sender'])){
    $message = mysqli_real_escape_string($conn, htmlspecialchars($_POST['message']));
    $group_id = mysqli_real_escape_string($conn, htmlspecialchars($_POST['group_id']));
    $sender = mysqli_real_escape_string($conn, htmlspecialchars($_POST['sender']));
    $sql = "INSERT INTO groupchat(sender, group_id, message) VALUES('$sender', '$group_id', '$message')";
    if (mysqli_query($conn, $sql)){
        $sql = "SELECT * FROM groupchat WHERE group_id = '$group_id'";
        $query = mysqli_query($conn, $sql);
        $result = mysqli_fetch_all($query, MYSQLI_ASSOC);
        foreach ($result as $message){
            $all = $message['message'] ?? $message;
            echo $all."<br>";
        }
    } else {
        echo "0";
    }
} else {
    echo "Something went wrong please try again later";
}
?>