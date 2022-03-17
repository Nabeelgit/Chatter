<?php
session_start();
include("database.php");
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
$info = ['username'=>'','name'=>'', 'id'=>''];
if (isset($_GET['user_id'])){
    $id = mysqli_real_escape_string($conn, $_GET['user_id']);
    $info['username'] = getUsernameById($id);
    $info['name'] = getNameById($id);
    $info['id'] = $id;
}
else {
    echo "User was not found...";
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title></title>
</head>
<body>
<?php
    echo '<img id="accountImage" src="data:image/jpeg;base64,' . base64_encode(getPhotoById($info['id'])) . '"height="200px" width="200px" style="border-radius: "50%">' . "<br>";
?>
<h1>User: <?php echo $info['username']?></h1>
<br>
<h3>Name: <?php echo $info['name']?></h3>
<script>
    let img = document.getElementById("accountImage")
    img.style.borderRadius = "50%"
    img.addEventListener("error", function (){
        img.src = "https://t4.ftcdn.net/jpg/00/64/67/63/360_F_64676383_LdbmhiNM6Ypzb3FM4PPuFP9rHe7ri8Ju.jpg"
    })
</script>
</body>
</html>
