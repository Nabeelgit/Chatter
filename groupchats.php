<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Recent groupchats</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css" integrity="sha512-10/jx2EXwxxWqCLX/hHth/vu2KY3jCF70dCQB8TSgNjbCVAC/8vai53GfMDrO2Emgwccf2pJqxct9ehpzG+MTw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
<?php
session_start();
include("database.php");
$info = ['id'=>'', 'username'=>''];
if (isset($_GET['id']) || isset($_SESSION['id'])){
    $rawid = $_GET['id'] ?? $_SESSION['id'];
    $id = mysqli_real_escape_string($conn, $rawid);
    $sql = "SELECT * FROM users WHERE id = '$id'";
    $query = mysqli_query($conn, $sql);
    $result = mysqli_fetch_assoc($query);
    $info['id'] = $result['id'];
    $info['username'] = $result['username'];
} else {
    echo "An error occurred";
}
function isUserReciever($id){
    global $conn;
    global $info;
    $sql = "SELECT id FROM groupchat WHERE recievers_id LIKE '%$id%'";
    $query = mysqli_query($conn, $sql);
    $result = mysqli_fetch_all($query, MYSQLI_ASSOC);
	if (mysqli_num_rows($query) > 0){
		return $result;
	}
	else {
		return false;
	}
}
function getUsernameById($id){
    global $conn;
    $sql = "SELECT * FROM users WHERE id = '$id'";
    $query = mysqli_query($conn, $sql);
    $result = mysqli_fetch_assoc($query);
    return $result['username'];
}
function getGroupChatName($id){
    global $conn;
    $sql = "SELECT * FROM groupchat WHERE id = '$id'";
    $query = mysqli_query($conn, $sql);
    $result = mysqli_fetch_assoc($query);
    return $result['name'];
}
function getAllGroupChats(){
    global $conn;
    global $info;
    $id = $info['id'];
    $sql = "SELECT * FROM groupchat WHERE sender_id = '$id'";
    $query = mysqli_query($conn, $sql);
    $result = mysqli_fetch_all($query, MYSQLI_ASSOC);
    return $result;
}
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand">Chatter <i class="fa-regular fa-comments"></i></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="users.php?id=<?php echo $info['id']?>">Search for users <i class="fa-solid fa-magnifying-glass"></i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="account.php?user_id=<?php echo $info['id']?>">View your account  <i class="fa-solid fa-circle-user"></i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="recent.php?id=<?php echo $info['id']?>">Recent chats <i class="fa-regular fa-comment"></i></a>
            </li>
            <li class="nav-item active">
                <a class="nav-link">Groupchat(beta) <i class="fa-solid fa-users"></i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="login.php">Logout <i class="fa-solid fa-arrow-right-from-bracket"></i></a>
            </li>
        </ul>
    </div>
</nav>
<br>
<a class="btn" href="create-groupchat.php?id=<?php echo $info['id']?>">Create a groupchat</a>
<h1>Recent groupchats for <?php echo $info['username']?></h1>
<?php
$id = $info['id'];
$rec = getAllGroupChats();
foreach($rec as $allRecievers):
    $groupid = $allRecievers['id']." ";
?>
    <a class="group-link" href="groupchat.php?id=<?php echo $info['id']?>&recievers_id=<?php echo $allRecievers['recievers_id']?>"><?php echo getGroupChatName($groupid)?></a>
    <!--GIVE ID AND RECIEVERS ID-->
    <br>
<?php endforeach; ?>
</body>
</html>