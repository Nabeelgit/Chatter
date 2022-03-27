<?php
session_start();
include "database.php";
function uniqueIdTaken($id){
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
$info = ['id'=>'', 'username'=>'', 'token'=>''];
if ((isset($_GET['id']) || isset($_SESSION['id'])) && isset($_GET['token'])){
$rawid = $_GET['id'] ?? $_SESSION['id'];
$id = mysqli_real_escape_string($conn, htmlspecialchars($rawid));
$info['id'] = $id;
$info['username'] = getUsernameById($id);
$username = $info['username'];
$token = mysqli_real_escape_string($conn, htmlspecialchars($_GET['token']));
$info['token'] = $token;
if (!uniqueIdTaken($token)){
    $sql = "INSERT INTO groupchats(founder, unique_id) VALUES('$username', '$token')";
    if (mysqli_query($conn, $sql[0])){

    }
    else {
        echo "There was an error connecting to the server... Please try again later";
        exit;
    }
}
}
else {
    echo "Something went wrong please try again later";
    exit;
}
function getUsernameById($id){
    global $conn;
    $sql = "SELECT * FROM users WHERE id = '$id'";
    $query = mysqli_query($conn, $sql);
    $result = mysqli_fetch_assoc($query);
    return $result['username'];
}
if (!isset($_SESSION)) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['postdata'] = $_POST;
    unset($_POST);
    header("Location: ".$_SERVER['REQUEST_URI']);
    exit;
}

if (@$_SESSION['postdata']){
    $_POST=$_SESSION['postdata'];
    unset($_SESSION['postdata']);
}
function connectionError(){
    echo "There was an error connecting to the server... Please try again later";
    exit;
}
?>
<!-- '/^([a-zA-Z\s]+)(,\s*[a-zA-Z\s]*)*$/'-->
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css" integrity="sha512-10/jx2EXwxxWqCLX/hHth/vu2KY3jCF70dCQB8TSgNjbCVAC/8vai53GfMDrO2Emgwccf2pJqxct9ehpzG+MTw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Create a groupchat</title>
</head>
<body>
<form action="<?php echo $_SERVER['REQUEST_URI']?>" method="POST">
    <fieldset>
        <legend>Search for users</legend>
        <input type="text" name="search">
        <input type="submit" name="searchSubmit" title="Search">
    </fieldset>

</form>
        <div>
<!--           Search results-->
<?php
$result = [];
if (isset($_POST['searchSubmit'])){
    $search = htmlspecialchars($_POST['search']) ?? "";
    if (empty($search)){
        echo "Search is empty!";
    }
    else {
        $sql = "SELECT username FROM users WHERE username LIKE '$search%'";
        $query = mysqli_query($conn, $sql);
        $result = mysqli_fetch_all($query, MYSQLI_ASSOC);
    }
}
foreach ($result as $user):
?>
<form action="<?php echo $_SERVER['REQUEST_URI']?>" method="POST">
<input type="text" name="member"  value="<?php echo $user['username'] ?? $user?>" readonly>
<button type="submit" name="userAdd" title="Add user"><i class="fa-solid fa-user-plus"></i></button>
</form>
<?php endforeach;?>
<?php
if (isset($_POST['userAdd'])) {
    $token = $info['token'];
    $founder = $info['username'];
    $member = htmlspecialchars($_POST['member']);
    $sel = "SELECT * FROM groupchats WHERE unique_id = '$token'";
    $query = mysqli_query($conn, $sel);
    $result = mysqli_fetch_assoc($query);
    $setMembers = [$result['members']];
    if ($setMembers[0] == "None"){
        $upd = "UPDATE groupchats SET members = '' WHERE unique_id = '$token'";
        if (mysqli_query($conn, $upd)){

        }
        else {
            connectionError();
        }
    }
    $new = $result['members'].$member.",";
    $sql = "UPDATE groupchats SET members  = '$new' WHERE unique_id = '$token'";
    if (mysqli_query($conn, $sql)){
        echo "Added";
    }
    else {
        connectionError();
    }
}
?>
</div>
</body>
</html>
