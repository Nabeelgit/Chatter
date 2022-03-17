<?php
$info = ['id'=>'', 'name'=>'', 'about'=>'', 'username'=>'', 'password'=>''];
session_start();
include("database.php");
if (isset($_GET['edit_id']) || isset($_SESSION['id'])){
    $rawid = $_GET['edit_id'] ?? $_SESSION['id'];
    $id = mysqli_real_escape_string($conn, $rawid);
    $sql = "SELECT * FROM users WHERE id = '$id'";
    $query = mysqli_query($conn, $sql);
    $result = mysqli_fetch_assoc($query);
    $info['id'] = $id;
    $info['name'] = $result['name'];
    $info['about'] = $result['about'];
    $info['username'] = $result['username'];
    $info['password'] = $result['password'];
}
else {
    echo "Something went wrong while trying to find your account please try again later";
}
function getPhotoById($id){
    global $conn;
    $sql = "SELECT * FROM users WHERE id = '$id'";
    $query = mysqli_query($conn, $sql);
    $result = mysqli_fetch_assoc($query);
    return $result['profile_photo'];
}
$form = ['submitted'=>'', 'success'=>'', 'error'=>''];
if (isset($_POST['editSubmit'])){
    $form['submitted'] = true;
    $password = htmlspecialchars($_POST['password']);
    $about = htmlspecialchars($_POST['about']);
    $id = $info['id'];
    $sql = "UPDATE users SET password = '$password', about = '$about' WHERE id = '$id'";
    if (mysqli_query($conn, $sql)){
        $form['success'] = "Edits succesfully changed";
        $sql = "SELECT * FROM users WHERE id = '$id'";
        $query = mysqli_query($conn, $sql);
        $result = mysqli_fetch_assoc($query);
        $info['about'] = $result['about'];
        $info['password'] = $result['password'];
    }
    else {
        $form['error'] = "There was an error changing your form this could be due to poor connection please try again later";
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
    <title>Edit <?php echo $info['username']."'s account"?></title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css" integrity="sha512-10/jx2EXwxxWqCLX/hHth/vu2KY3jCF70dCQB8TSgNjbCVAC/8vai53GfMDrO2Emgwccf2pJqxct9ehpzG+MTw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
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
                <a class="nav-link" href="account.php?user_id=<?php echo $info['id']?>">View your account  <i class="fa-solid fa-user"></i></a>
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
<br>
<body>
<!--USER-DATA-->
<div class="user-data">
    <form action="edit.php?id=<?php echo $info['id']?>" method="POST">
    <?php echo '<img id="accountImage" src="data:image/jpeg;base64,' . base64_encode(getPhotoById($info['id'])) . '"height="200px" width="200px" style="border-radius: "50%">' . "<br>";?>
    <h1>Username: <?php echo $info['username']?></h1>
    <h2>Name: <?php echo $info['name']?></h2>
    <label>Password</label>
    <br>
    <input type="password" name="password" id="passwordInput" value=<?php echo $info['password']?>><i class="fa-solid fa-eye" id="password"></i>
    <br><br>
    <label>About</label>
    <br>
        <textarea type="text" name="about" class="user-about"><?php echo $info['about']?></textarea>
    <br><br>
    <input type="submit" name="editSubmit" class="btn" value="Save">
    </form>
    <div><?php echo $form['success']?></div>
    <div><?php echo $form['error']?></div>
</div>
<script>
    let passwordInput = document.querySelector("#passwordInput");
    let password = document.querySelector("#password");
    isChecked = false;
    password.addEventListener("click", function (){
       isChecked = !isChecked;
       if (isChecked){
           passwordInput.type = "text";
           password.classList.remove("fa-eye");
           password.classList.add("fa-eye-slash");
       }
       else {
           passwordInput.type = "password";
           password.classList.remove("fa-eye-slash");
           password.classList.add("fa-eye");
       }
    });
    function unloadPage(){
        let formSubmitted = <?php echo $form['submitted']?>;
        if (formSubmitted){

        }
        else {
            return "Have you made any unsaved changes?";
        }
    }
    window.onbeforeunload = unloadPage;
</script>
</body>
</html>
