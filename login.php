<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css" integrity="sha512-10/jx2EXwxxWqCLX/hHth/vu2KY3jCF70dCQB8TSgNjbCVAC/8vai53GfMDrO2Emgwccf2pJqxct9ehpzG+MTw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="style.css">
    <title>Login</title>
</head>
<body>
<?php
include("database.php");
$errors = ['username'=>'','password'=>'', 'incorrect'=>''];
if (isset($_POST['loginSubmit'])){
    $username = htmlspecialchars($_POST['loginUsername']);
    $password = htmlspecialchars($_POST['loginPassword']);
    if (empty($username)){
        $errors['username'] = "Username is missing";
    }
    if (empty($password)){
        $errors['password'] = "Password is missing";
    }
    if (!array_filter($errors)){
        $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $query = mysqli_query($conn, $sql);
        if (mysqli_num_rows($query) <= 0){
            $errors['incorrect'] = "Incorrect credientials please try again";
        }
        else {
            session_start();
            $result = mysqli_fetch_assoc($query);
            $_SESSION['id'] = $result['id'];
            header("Location: users.php?id=".$_SESSION['id']);
        }
    }
}
?>
<div class="header">
<h1>Chatter <i class="fa-regular fa-comments"></i></h1>
<h2>Login</h2>
</div>
<div class="form">
<form action="login.php" method="POST">
    <label>Username</label>
    <input type="text" name="loginUsername" placeholder="Enter your username">
    <br>
    <div class="red-text"><?php echo  $errors['username']?></div>
    <br><br>
    <label>Password</label>
    <input type="text" name="loginPassword" placeholder="Enter your password">
    <br>
    <div class="red-text"><?php echo $errors['password'] ?></div>
    <br><br>
    <button type="submit" name="loginSubmit" class="btn">
    Login
    </button>
    <br><br>
    <div><?php echo  $errors['incorrect']?></div>
    <p>Don't have an account? <a href="index.php">Sign up here</a></p>
</form>
</div>
</body>
</html>