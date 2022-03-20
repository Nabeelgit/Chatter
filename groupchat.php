<?php
session_start();
include "database.php";
$info = ['id'=>'', 'recievers'=>''];
if (isset($_GET['id']) && isset($_GET['recievers_id'])){
    $rawid = $_GET['id'] ?? $_SESSION['id'];
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $recievers = mysqli_real_escape_string($conn, $_GET['recievers_id']);
    $recieversArr = explode(",", $recievers);
    $sql = [];
    $info['id'] = $id;
    $info['recievers'] = $recievers;
    for ($i = 0; $i < count($recieversArr); $i++){
        $reciever = $recieversArr[$i];
        $sql[0] = "SELECT message, name FROM groupchat WHERE sender_id = '$id' OR recievers_id LIKE '%$id%'";
        $query = mysqli_query($conn, $sql[0]);
        if (mysqli_num_rows($query) <= 0){
            break;
        }
        else {
            $sql[0] = "SELECT message, name FROM groupchat WHERE recievers_id LIKE '%$reciever%' OR sender_id = '%$id%'";
            $query = mysqli_query($conn, $sql[0]);
            if (mysqli_num_rows($query) <= 0){
                break;
            }
            else {
                $sql[0] = "SELECT message, name FROM groupchat WHERE sender_id = '$id' AND recievers_id LIKE '%$reciever%'";
                $query = mysqli_query($conn, $sql[0]);
                if (mysqli_num_rows($query) <= 0){
                    $sql[0] = "SELECT message, name FROM groupchat WHERE recievers_id = '%$id%' AND sender_id = '$reciever'";
                    $query = mysqli_query($conn, $sql[0]);
                    if (mysqli_num_rows($query) <= 0){
                        break;
                    }
                }
            }

        }
    }
}
else {
    echo "Groupchat was not found... It might have been deleted or changed.";
    echo "<a href=groupchats.php?id=".$_SESSION['id'].">Click to go back</a>";
}
function getGroupchatName(){
    global $conn;
    global $sql;
    $query = mysqli_query($conn, $sql[0]);
    $result = mysqli_fetch_assoc($query);
    return $result['name'];
}
if (isset($_POST['groupchat-submit'])){
    $message = mysqli_real_escape_string($conn, htmlspecialchars($_POST['group-message']));
    $recievers = $info['recievers'];
    $id = $info['id'];
    $name = getGroupChatName();
    $insert = "INSERT INTO groupchat(sender_id, recievers_id, message, name) VALUES('$id', '$recievers', '$message','$name')";
    if (mysqli_query($conn, $insert)){

    }
    else {
        echo "There was an error submitting your message this could be due to connection issues please try again later";
        echo "<a href=groupchats.php?id=".$_SESSION['id'].">Click to go back</a>";
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
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <title><?php echo getGroupChatName()?></title>
</head>
<body>
<h1><?php echo getGroupChatName()?></h1>
<form action="<?php echo $_SERVER['REQUEST_URI']?>" method="POST">
    <input type="text" name="group-message" placeholder="Send a message">
    <input type="submit" name="groupchat-submit">
</form>
<br>
<div id="root">
<?php
$query = mysqli_query($conn, $sql[0]);
$result = mysqli_fetch_all($query, MYSQLI_ASSOC);
foreach ($result as $message){
    echo $message['message']."<br>";
}
?>
</div>
<script>
    $(document).ready(function(){
        setInterval(function(){
            $("#root").load(window.location.href + " #root" );
        }, 3000);
    });
</script>
</body>
</html>
