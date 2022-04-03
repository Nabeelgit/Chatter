<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css" integrity="sha512-10/jx2EXwxxWqCLX/hHth/vu2KY3jCF70dCQB8TSgNjbCVAC/8vai53GfMDrO2Emgwccf2pJqxct9ehpzG+MTw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <title>Login</title>
</head>
<body>
<?php
// include("database.php");
// $errors = ['username'=>'','password'=>'', 'incorrect'=>''];
// if (isset($_POST['loginSubmit'])){
//     $username = htmlspecialchars($_POST['loginUsername']);
//     $password = htmlspecialchars($_POST['loginPassword']);
//     if (empty($username)){
//         $errors['username'] = "Username is missing";
//     }
//     if (empty($password)){
//         $errors['password'] = "Password is missing";
//     }
//     if (!array_filter($errors)){
//         $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
//         $query = mysqli_query($conn, $sql);
//         if (mysqli_num_rows($query) <= 0){
//             $errors['incorrect'] = "Incorrect credientials please try again";
//         }
//         else {
//             session_start();
//             $result = mysqli_fetch_assoc($query);
//             $_SESSION['id'] = $result['id'];
//             $_SESSION['username'] = $result['username'];
//             header("Location: users.php?id=".$_SESSION['id']);
//         }
//     }
// }
?>
<div class="header">
<h1>Chatter <i class="fa-regular fa-comments"></i></h1>
<h2>Login</h2>
</div>
<div class="form">
    <div class="formReplica">
    <label>Username</label>
    <input type="text" name="loginUsername" placeholder="Enter your username" id="username">
    <br>
    <div class="red-text" id="usernameError"></div>
    <br><br>
    <label>Password</label>
    <input type="password" name="loginPassword" placeholder="Enter your password" id="password">
    <br>
    <div class="red-text" id="passwordError"></div>
    <br><br>
    <button type="submit" name="loginSubmit" class="btn" id="loginSubmit">
    Login
    </button>
    <br><br>
    <div id="incorrect" class="red-text"></div>
    <p>Don't have an account? <a href="index.php">Sign up here</a></p>
    </div>
    </div>
<script>
    $("#loginSubmit").click(function(){
        let username = $("#username").val();
        let password = $("#password").val();
        let checkUsername = username.replace(/\s/g, "");
        let checkPass = password.replace(/\s/g, "");
        let errors = [false, false]
        if (checkUsername == ""){
            $("#usernameError").html("Username is required!");
            errors[0] = true;
        }
        else {
            $("#usernameError").html("");
            errors[0] = false;
        }
        if (checkPass == ""){
            $("#passwordError").html("Password is required")
            errors[1] = true;
        }
        else {
            $("#passwordError").html("")
            errors[1] = false;
        }
        if (errors[0] == false && errors[1] == false){
            $.post("./handleLogin.php", {username: username, password: window.btoa(password)}, function(data){
                if (data == "Incorrect credentials"){
                    $("#incorrect").html("Incorrect credentials!");
                } else {
                    window.location.assign("users.php?id="+data);
                }
            })
        }
    })
    $(document).keypress(function(event){
    var keycode = (event.keyCode ? event.keyCode : event.which);
	if(keycode == '13'){
        let username = $("#username").val();
        let password = $("#password").val();
        let checkUsername = username.replace(/\s/g, "");
        let checkPass = password.replace(/\s/g, "");
        let errors = [false, false]
        if (checkUsername == ""){
            $("#usernameError").html("Username is required!");
            errors[0] = true;
        }
        else {
            $("#usernameError").html("");
            errors[0] = false;
        }
        if (checkPass == ""){
            $("#passwordError").html("Password is required")
            errors[1] = true;
        }
        else {
            $("#passwordError").html("")
            errors[1] = false;
        }
        if (errors[0] == false && errors[1] == false){
            $.post("./handleLogin.php", {username: username, password: window.btoa(password)}, function(data){
                if (data == "Incorrect credentials"){
                    $("#incorrect").html("Incorrect credentials!");
                } else {
                    window.location.assign("users.php?id="+data);
                }
            })
        }
    }
	});
</script>
</body>
</html>