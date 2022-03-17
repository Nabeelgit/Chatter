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
             <li class="nav-item">
                 <a class="nav-link" href="users.php?id=<?php echo $info['id']?>">Search for users <i class="fa-solid fa-magnifying-glass"></i></a>
             </li>
             <li class="nav-item">
                 <a class="nav-link" href="account.php?user_id=<?php echo $info['id']?>">View your account  <i class="fa-solid fa-user"></i></a>
             </li>
             <li class="nav-item active">
                 <a class="nav-link" href="recent.php?id=<?php echo $info['id']?>">Recent chats <i class="fa-regular fa-comment"></i></a>
             </li>
             <li class="nav-item">
                 <a class="nav-link" href="login.php">Logout <i class="fa-solid fa-arrow-right-from-bracket"></i></a>
             </li>
         </ul>
     </div>
 </nav>

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