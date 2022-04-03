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
    echo "An error occurred please try again later";
    exit;
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
function getIdByUsername($username){
    global $conn;
    $sql = "SELECT id FROM users WHERE username = '$username'";
    $query = mysqli_query($conn, $sql);
    $result = mysqli_fetch_assoc($query);
    return $result['id'] ?? $result;
}
function getRecieverIdFromName($recieversArr){
    $arr = [];
    for ($k = 0; $k < count($recieversArr); $k++){
        array_push($arr, getIdByUsername($recieversArr[$k]));
        if ($arr[$k] == NULL){
            array_pop($arr);
        }
    }
    return $arr;
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
    $username = getUsernameById($id);
    $sql = "SELECT * FROM groupchats WHERE founder = '$username' OR members LIKE '%$username%'";
    $query = mysqli_query($conn, $sql);
    $result = mysqli_fetch_all($query, MYSQLI_ASSOC);
    $arr = [];
    for ($c = 0; $c < count($result); $c++){
        array_push($arr, $result[$c]);
        if ($result[$c]['members'] == "None" || $result[$c]['members'] == "" || $result[$c]['name'] == "None"){
            array_pop($arr);
        }
    }
    return $arr;
}
function UniqueIdTaken($id){
    global $conn;
    $sql = "SELECT * FROM groupchats WHERE unique_id = '$id'";
    $query = mysqli_query($conn, $sql);
    if (mysqli_num_rows($query) > 0){
        return true;
    }
    else {
        return false;
    }
}
function getUniqueId(){
    $stack = [0];
    $alphabet = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
    $symbols = ['-', '_'];
    for ($i = 0; $i < 5; $i++){
        $int = rand(0, 64);
        $index = rand(0, (count($alphabet) - 1));
        $sym = rand(0, 1);
        $id = [];
        if ($int < 32){
            if ($index > 13){
                $id[0] = $symbols[$sym].strtolower($alphabet[$index]).$int.uniqid();
            }
            else {
                $id[0] = strtolower($alphabet[$index]).$int.uniqid();
            }
        }
        else {
            if ($index > 45){
                $id[0] = $symbols[$sym].$alphabet[$index].$int.uniqid();
            }
            else {
                $id[0] = $alphabet[$index].$int.uniqid();
            }
        }
    }
    if (UniqueIdTaken($id[0])){
        $stack[0] += 1;
        if ($stack[0] < 15) {
            getUniqueId();
        } else {
            echo "There was an error while generating an Id please try again or contact an admin";
            exit;
        }
    }
    else {
        return $id[0];
    }
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
<a class="btn" href="create-groupchat.php?id=<?php echo $info['id']?>&token=<?php echo getUniqueId()?>">Create a groupchat</a>
<!--PASS USER ID AND UNIQUE ID-->
<h1>Recent groupchats for <?php echo $info['username']?></h1>
<?php
$id = $info['id'];
$allGroups = getAllGroupChats();
for($i = 0; $i < count($allGroups); $i++):
    $each = explode(",", $allGroups[$i]['members']);
    // var_dump($each);
    $recievers = getIdByUsername($each[$i]);
    // var_dump($recievers);
    $recieversId = getRecieverIdFromName($each);
    // var_dump($recieversId)
?>
<a href="groupchat.php?id=<?php echo $id?>&recievers_id=<?php echo implode(",", $recieversId)?>&group_id=<?php echo $allGroups[$i]['unique_id']?>" style="font-size: 20px; text-decoration: underline;"><?php echo $allGroups[$i]['name']?></a>
<?php endfor;?>
</body>
</html>