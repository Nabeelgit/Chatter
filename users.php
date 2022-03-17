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
		echo "Search is empty";
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
</head>
<body>
	<a href="recent.php?id=<?php echo $info['id'] ?>">Recent chats</a>
	<form action="users.php" method="POST">
		<label>Search for users</label>
		<input type="text" name="userSearch">
		<input type="submit" name="userSearchSubmit">
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