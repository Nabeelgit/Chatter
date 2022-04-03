<?php
include "database.php";
if (isset($_POST['group_id'])){
    $group_id = mysqli_real_escape_string($conn, htmlspecialchars($_POST['group_id']));
    $sql = "SELECT * FROM groupchat WHERE group_id = '$group_id'";
    $query = mysqli_query($conn, $sql);
    $result = mysqli_fetch_all($query, MYSQLI_ASSOC);
    foreach($result as $message){
        $msg = $message['message'] ?? $message;
        echo $msg."<br>";
    }
} else {
    echo "Something went wrong while connecting please try again later <a href='index.php'>Home</a>";
}
?>