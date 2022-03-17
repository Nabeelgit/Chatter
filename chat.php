<?php
include("database.php");
session_start();

// Function to get username with id

function getUsernameById($id){
global $conn;
$sql = "SELECT * FROM users WHERE id = '$id'";
$query = mysqli_query($conn, $sql);
$res = mysqli_fetch_assoc($query);
return $res['username'];
}


// Getting sender and reciever id

$info = ['sender_username'=>'','reciever_username'=>'', 'sender_id'=>'','reciever_id'=>'']; 
if(isset($_GET['id']) && isset($_GET['reciever_id'])){
	$sender_id = mysqli_real_escape_string($conn, $_GET['id']);
	$reciever_id = mysqli_real_escape_string($conn, $_GET['reciever_id']);
	$info['sender_username'] = getUsernameById($sender_id);
	$info['reciever_username'] = getUsernameById($reciever_id);
	$info['sender_id'] = $sender_id;
	$info['reciever_id'] = $reciever_id;
	$_SESSION['sender_id'] = $info['sender_id'];
	$_SESSION['reciever_id'] = $info['reciever_id'];
}
else {
	echo "Something went wrong";
	echo "<a href='index.php'>Back</a>";
}


// Checking if form is submitted
if (isset($_POST['messageSubmit'])){
    $sender_id = $info['sender_id'];
    $reciever_id = $info['reciever_id'];
	$message = htmlspecialchars($_POST['message']);
    if ($_FILES['image']['size'] != 0 && $_FILES['image']['error'] == 0)
    {
        $image = addslashes(file_get_contents($_FILES['image']['tmp_name']));
        $sql = "INSERT INTO messages(sender_id, reciever_id, image) VALUES('$sender_id', '$reciever_id','$image')";
        if (mysqli_query($conn, $sql)){

        }
        else {
            echo "There was an error connecting to the "."<a href='https://en.wikipedia.org/wiki/Server_(computing)'>server</a>"." please try again later";
        }
    }
	if(empty($message && $_FILES['image']['size'] == 0 && $_FILES['image']['error'] == 0)){
		echo "Message cannot be empty";
	}
	else {
	$sql = "INSERT INTO messages(sender_id, reciever_id, message) VALUES('$sender_id', '$reciever_id','$message')";
	if (mysqli_query($conn, $sql)){
		// success
	}
	else {
	echo "Something went wrong";
	echo "<a href='index.php'>Back</a>";
	}

}
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
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Chatter test</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css" integrity="sha512-10/jx2EXwxxWqCLX/hHth/vu2KY3jCF70dCQB8TSgNjbCVAC/8vai53GfMDrO2Emgwccf2pJqxct9ehpzG+MTw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
	<h1><?php echo $info['sender_username'] ?></h1>
	<a href="users.php?id=<?php echo $info['sender_id'] ?>">Back</a>

        <!--FORM-->

<form action="chat.php?id=<?php echo $_SESSION['sender_id']?>&reciever_id=<?php echo $_SESSION['reciever_id']?>" method="POST" id="messagingForm" enctype="multipart/form-data">
		<label>Message <a href="account.php?user_id=<?php echo $info['reciever_id']?>"><?php echo $info['reciever_username'] ?></a></label>
		<br>
		<input type="text" name="message" placeholder="Type a message" id="message">
        <input type="hidden" name="image" id="image">
    <button name="messageSubmit" type="submit"><i class="fa-regular fa-paper-plane"></i></button>
    <button id="imageUpload">Send an image</button>
</form>
<div id="root">
	<!-- Messages -->
	<?php
	$result = ['message'=>'']; 
	$sender_id = intval($info['sender_id']);
	$reciever_id = intval($info['reciever_id']);
	$sql = "SELECT message, image FROM messages WHERE reciever_id = '$reciever_id' AND sender_id = '$sender_id'";
	$query = mysqli_query($conn, $sql);
	$result = mysqli_fetch_all($query, MYSQLI_ASSOC);
	if (mysqli_num_rows($query) > 0){
		$sql = "SELECT message, image FROM messages WHERE reciever_id = '$reciever_id' AND sender_id = '$sender_id'";
		$query = mysqli_query($conn, $sql);
		$result = mysqli_fetch_all($query, MYSQLI_ASSOC);
	}
	else {
		$sql = "SELECT message, image FROM messages WHERE reciever_id = '$sender_id' AND sender_id = '$reciever_id'";
		$query = mysqli_query($conn, $sql);
		$result = mysqli_fetch_all($query, MYSQLI_ASSOC);
	}
	?>
	<?php foreach($result as $messages): ?>
		<?php $msg = $messages['message'] ?? $messages;
                 $img = $messages['image'];
                 if (empty($msg)){
                     echo '<img src="data:image/jpeg;base64,'.base64_encode($img).'"height="100px" width="100px">'."<br>";
                 }
                 else {
                     echo $msg."<br>";
                 }
            ?>

	<?php endforeach; ?>	
</div>
<script>
$(document).ready(function(){
setInterval(function(){
      $("#root").load(window.location.href + " #root" );
}, 3000);
});
let image = document.getElementById("image");
let imageUpload = document.getElementById("imageUpload");
let message = document.getElementById("message");
let icon = document.getElementById("icon");
isImage = false;
imageUpload.onclick = function (e){
    e.preventDefault()
    isImage = !isImage;
    if (isImage){
        image.type = "file";
        message.type = "hidden";
        imageUpload.innerText = "Send a message"
    }
    else {
        image.type = "hidden";
        message.type = "text";
        imageUpload.innerText = "Send an image";
    }
}
</script>
</body>
</html>