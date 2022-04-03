<?php
include("database.php");
session_start();
$info = ['id'=>'']; 
if (isset($_GET['id']) || isset($_SESSION['id'])){
	$getId = $_GET['id'] ?? $_SESSION['id'];
	$id = mysqli_real_escape_string($conn, $getId);
	$info['id'] = $id;
}
else {
	header("Location: index.php");
}
if (!isset($_SESSION['id'])){
    header("Location: index.php");
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Chatter test</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css" integrity="sha512-10/jx2EXwxxWqCLX/hHth/vu2KY3jCF70dCQB8TSgNjbCVAC/8vai53GfMDrO2Emgwccf2pJqxct9ehpzG+MTw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand">Chatter <i class="fa-regular fa-comments"></i></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item active">
                <a class="nav-link">Search for users <i class="fa-solid fa-magnifying-glass"></i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="account.php?user_id=<?php echo $info['id']?>">View your account  <i class="fa-solid fa-circle-user"></i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="recent.php?user_id=<?php echo $info['id']?>">Recent chats <i class="fa-regular fa-comment"></i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="groupchats.php?id=<?php echo $info['id']?>">Groupchat(beta) <i class="fa-solid fa-users"></i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="login.php">Logout <i class="fa-solid fa-arrow-right-from-bracket"></i></a>
            </li>
        </ul>
    </div>
</nav>
    <br>
		<label>Search for users</label>
		<input type="text" name="userSearch" id="userInput">
		<button name="userSearchSubmit" id="userSearch"><i class="fa-solid fa-magnifying-glass"></i></button>
	<div id="userResult">


	</div>
    <script>
        let userResult = $("#userResult");
        let userId = "<?php echo $info['id']?>";
        $("#userInput").keyup(function(){
            let search = $("#userInput").val();
            if (search.length >= 2){
                $.post("./handleUserSearch.php", {search: search, id: userId}, function(data){
                    userResult.html(data);
                })
            } else {
                userResult.html("");
            }
        })
        $("#userSearch").click(function(){
            let search = $("#userInput").val();
            let checked = search.replace(/\s/g, search);
            if (checked == ""){
                userResult.html("");
            } else {
                $.post("./handleUserSearch.php", {clickSearch: search, user_id: userId}, function(data){
                    userResult.html(data);
                })
            }
        })
    </script>
</body>
</html>