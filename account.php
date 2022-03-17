<?php
session_start();
include("database.php");
$info = ['username'=>'','name'=>'', 'id'=>''];
function getDefaultPhoto() {
    global $conn;
    $sql = "SELECT * FROM users WHERE id = 1";
    $query = mysqli_query($conn, $sql);
    $result = mysqli_fetch_assoc($query);
    return $result['profile_photo'];
}
function getUsernameById($id){
    global $conn;
    $sql = "SELECT * FROM users WHERE id = '$id'";
    $query = mysqli_query($conn, $sql);
    $result = mysqli_fetch_assoc($query);
    return $result['username'];
}
function getNameById($id){
    global $conn;
    $sql = "SELECT * FROM users WHERE id = '$id'";
    $query = mysqli_query($conn, $sql);
    $result = mysqli_fetch_assoc($query);
    return $result['name'];
}
function getPhotoById($id){
    global $conn;
    $sql = "SELECT * FROM users WHERE id = '$id'";
    $query = mysqli_query($conn, $sql);
    $result = mysqli_fetch_assoc($query);
    return $result['profile_photo'];
}
if (isset($_GET['user_id'])){
    $id = mysqli_real_escape_string($conn, $_GET['user_id']);
    $info['username'] = getUsernameById($id);
    $info['name'] = getNameById($id);
    $info['id'] = $id;
}
else {
    echo "User was not found...";
}
$id = $info['id'];
$username = $info['username'];
function isUserAccount(){
    global $id;
    global $username;
 if (!isset($_SESSION['username'])){
     return false;
 }
 else {
     $Userusername = $_SESSION['username'];
     if ($username == $Userusername){
         return true;
     }
     else {
         return false;
     }
 }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $info['username']."'s account"?></title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css" integrity="sha512-10/jx2EXwxxWqCLX/hHth/vu2KY3jCF70dCQB8TSgNjbCVAC/8vai53GfMDrO2Emgwccf2pJqxct9ehpzG+MTw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="users.php?id=<?php echo $info['id']?>">Chatter <i class="fa-regular fa-comments"></i></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="users.php?id=<?php echo $info['id']?>">Search for users <i class="fa-solid fa-magnifying-glass"></i></a>
            </li>
            <li class="nav-item active">
                <a class="nav-link">View your account  <i class="fa-solid fa-user"></i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="recent.php?id=<?php echo $info['id']?>">Recent chats <i class="fa-regular fa-comment"></i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="login.php">Logout <i class="fa-solid fa-arrow-right-from-bracket"></i></a>
            </li>
        </ul>
    </div>
</nav>
<div class="user-data">
<?php
    echo '<img id="accountImage" src="data:image/jpeg;base64,' . base64_encode(getPhotoById($info['id'])) . '"height="200px" width="200px" style="border-radius: "50%">' . "<br>";
?>
<h1>User: <?php echo $info['username']?></h1>
<h3>Name: <?php echo $info['name']?></h3>
    <button type="submit" name="editSubmit" class=<?php echo isUserAccount() ? "btn" : "display-none"?>><a class="editA" href="edit.php?id=<?php echo $info['id']?>"><?php echo isUserAccount() ? "Edit your account" : ""?></a></button>
</div>
<script>
    let img = document.getElementById("accountImage")
    img.style.borderRadius = "50%"
    img.addEventListener("error", function (){
        img.src = "https://t4.ftcdn.net/jpg/00/64/67/63/360_F_64676383_LdbmhiNM6Ypzb3FM4PPuFP9rHe7ri8Ju.jpg"
    })
</script>
</body>
</html>
