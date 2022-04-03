<?php
include "database.php";
function credentialCorrect($username, $password){
    global $conn;
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $query = mysqli_query($conn, $sql);
    if (mysqli_num_rows($query) > 0){
        return true;
    }
    else {
        return false;
    }
}
if (isset($_POST['username']) && isset($_POST['password'])){
    $username = $_POST['username'];
    $password = base64_decode($_POST['password']);
    if (credentialCorrect($username, $password)){
        $sql = "SELECT id FROM users WHERE username = '$username' AND password = '$password'";
        $query = mysqli_query($conn, $sql);
        $result = mysqli_fetch_assoc($query);
        $id = $result['id'] ?? $result;
        session_start();
        $_SESSION['id'] = $id;
        echo $id;
    } else {
        echo "Incorrect credentials";
    }
}
?>