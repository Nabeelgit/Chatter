<?php 
include("database.php");
function getUsernameById($id){
	global $conn;
	$sql = "SELECT * FROM users WHERE id = '$id'";
	$query = mysqli_query($conn, $sql);
	$result = mysqli_fetch_assoc($query);
	return $result['username'];
}
$info = ['username'=>'', 'id'=>''];
session_start();
if (isset($_GET['id']) || isset($_SESSION['id'])){
	$rawid = $_GET['id'] ?? $_SESSION['id'];
	$id = mysqli_real_escape_string($conn, $rawid);
	$info['username'] = getUsernameById($id);
	$info['id'] = $id;
}


 ?>
 <!DOCTYPE html>
 <html>
 <head>
 	<meta charset="utf-8">
 	<meta name="viewport" content="width=device-width, initial-scale=1">
 	<title>Recent chats</title>
 </head>
 <body>
 <h1>Recent chats for <?php echo $info['username'] ?></h1>
 <div>
 	<!-- Recent chat(s) -->
 	<?php 
 	$sql = "SELECT reciever_id FROM messages WHERE reciever_id = '$id' OR sender_id = '$id'";
 	$query = mysqli_query($conn, $sql);
 	$result = mysqli_fetch_all($query, MYSQLI_ASSOC);
 	$id = $info['id'];
 	if (mysqli_num_rows($query) <= 0){
 		echo "No recent chats";
 	}
 	else {
 		foreach ($result as $recents) {
 			$reciever_id = $recents['reciever_id'];
            $username = getUsernameById($recents['reciever_id']);
 			echo "<a href=account.php?user_id=$reciever_id>$username</a>"."-"."<a href=chat.php?id=$id&reciever_id=$reciever_id>Message</a>";
 		}
 	}



 	 ?>
 	
 </div>
 </body>
 </html>