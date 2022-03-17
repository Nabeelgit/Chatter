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
function returnUsername($id) {
	global $conn;
	$sql = "SELECT * FROM users WHERE id = '$id'";
	$query = mysqli_query($conn, $sql);
	$res = mysqli_fetch_assoc($query);
	return $res['username'];
}
$result = ['username'=>''];
if (isset($_POST['userSearchSubmit'])){
	$search = htmlspecialchars($_POST['userSearch']);
	if (empty($search)){

	}
	else {
		$currentUser = returnUsername($info['id']);
		$sql = "SELECT username, id FROM users WHERE username LIKE '$search%'";
		$query = mysqli_query($conn, $sql);
		$result = mysqli_fetch_all($query, MYSQLI_ASSOC);
        if (mysqli_num_rows($query) <= 0){
            echo "No users found"."<br/>";
        }
	}
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
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Chatter <i class="fa-regular fa-comments"></i></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item active">
                <a class="nav-link">Search for users <i class="fa-solid fa-magnifying-glass"></i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="account.php?user_id=<?php echo $info['id']?>">View your account  <i class="fa-solid fa-user"></i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="recent.php?user_id=<?php echo $info['id']?>">Recent chats <i class="fa-regular fa-comment"></i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="login.php">Logout <i class="fa-solid fa-arrow-right-from-bracket"></i></a>
            </li>
        </ul>
    </div>
</nav>
    <br>
	<form action="users.php" method="POST">
		<label>Search for users</label>
		<input type="text" name="userSearch">
		<button name="userSearchSubmit" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
	</form>
	<div>
		<!-- Result of user search -->
		<?php foreach($result as $usernames): ?>
        <?php
            $currentUser = returnUsername($info['id']);
            $user = $usernames['username'] ?? $usernames;
            if ($user == $currentUser){
            echo  "";
            } else {?>
		<a href="account.php?user_id=<?php echo $usernames['id']?>"><?php $user = $usernames['username'] ?? $usernames;
            echo $user;
        ?></a>
        -
		<a href="chat.php?id=<?php echo $info['id'] ?>&reciever_id=<?php echo $usernames['id'] ?>"><i class="fa-solid fa-message"></i></a>
        <br>
        <?php }?>
		<?php endforeach; ?>

	</div>
</body>
</html>