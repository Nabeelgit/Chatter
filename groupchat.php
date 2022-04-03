<?php
session_start();
include "database.php";
$info = ['id'=>'', 'recievers'=>'', 'group_id'=>''];
if ((isset($_GET['id']) || isset($_SESSION['id'])) && isset($_GET['group_id'])){
$id = $_GET['id'] ?? $_SESSION['id'];
$id = mysqli_real_escape_string($conn, htmlspecialchars($id));
$info['id'] = $id;
$group_id = mysqli_real_escape_string($conn, htmlspecialchars($_GET['group_id']));
$info['group_id'] = $group_id;
} else {
    echo "Groupchat was not found It might have been deleted or changed...";
    echo "<a href='index.php'>Back</a>";
}
function getGroupChatName($id){
    global $conn;
    $sql = "SELECT name FROM groupchats WHERE unique_id = '$id'";
    $query = mysqli_query($conn, $sql);
    if (mysqli_num_rows($query) <= 0){
        return "No groupchat";
    } else {
    $result = mysqli_fetch_assoc($query);
    return $result['name'] ?? $result;
    }
}
function getGroupMessages($id){
    global $conn;
    $sql = "SELECT * FROM groupchat WHERE group_id = '$id'";
    $query = mysqli_query($conn, $sql);
    $result = mysqli_fetch_all($query, MYSQLI_ASSOC);
    return $result;
}
function getUsernameById($id){
    global $conn;
    $sql = "SELECT username FROM users WHERE id = '$id'";
    $query = mysqli_query($conn, $sql);
    $result = mysqli_fetch_assoc($query);
    return $result['username'] ?? $result;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css" integrity="sha512-10/jx2EXwxxWqCLX/hHth/vu2KY3jCF70dCQB8TSgNjbCVAC/8vai53GfMDrO2Emgwccf2pJqxct9ehpzG+MTw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title><?php echo getGroupChatName($info['group_id'])?></title>
</head>
<body>
<h1><?php echo getGroupChatName($info['group_id'])?></h1>
<input type="text" id="message">
<button id="send"><i class="fa-regular fa-paper-plane"></i></button>
<div id="error"></div>
    <div id="messagesDiv">
        <!-- MESSAGES -->

    </div>
<script>
    function getGroupMessages(){
        let group_id = "<?php echo $info['group_id']?>";
        $.post("./getGroupMessages.php", {group_id: group_id}, function(data) {
            $("#messagesDiv").html(data);
        }) 
    }
    $(document).ready(function(){
        getGroupMessages();
        setInterval(getGroupMessages, 3000)
    })

        $("#send").click(function(){
            let message = $("#message").val();
            let group_id = "<?php echo $info['group_id']?>";
            let sender = "<?php echo getUsernameById($info['id'])?>";
            let checkedMessage = message.replace(/\s/g, "");
            if (checkedMessage == ""){
                $("#error").html("Message can't be empty");
            } else {
                $.post("./handleGroupMessage.php", {message: message, group_id: group_id, sender: sender}, function(data){
                    if (data != "0"){
                        $("#message").val("");
                        getGroupMessages();
                    } else {
                        alert("An error occurred please try again later");
                        window.location.assign("index.php");
                    }
                })
            }
        })
</script>
</body>
</html>
