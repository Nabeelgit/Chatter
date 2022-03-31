<?php
session_start();
include "database.php";
function uniqueIdTaken($id){
    global $conn;
    $sql = "SELECT * FROM groupchats WHERE unique_id = '$id'";
    $query = mysqli_query($conn, $sql);
    if (mysqli_num_rows($query) > 0){
        return true;
    }
    else {
        return false;
    }
}
$styles = ['created'=>false];
$info = ['id'=>'', 'username'=>'', 'token'=>''];
if ((isset($_GET['id']) || isset($_SESSION['id'])) && isset($_GET['token'])){
$rawid = $_GET['id'] ?? $_SESSION['id'];
$id = mysqli_real_escape_string($conn, htmlspecialchars($rawid));
$info['id'] = $id;
$info['username'] = getUsernameById($id);
$username = $info['username'];
$token = mysqli_real_escape_string($conn, htmlspecialchars($_GET['token']));
$info['token'] = $token;
if (!uniqueIdTaken($token)){
    $sql = "INSERT INTO groupchats(founder, unique_id) VALUES('$username', '$token')";
    if (mysqli_query($conn, $sql)){
        $sel = "SELECT * FROM groupchats WHERE unique_id = '$token'";
        $query = mysqli_query($conn, $sel);
        $result = mysqli_fetch_assoc($query);
        $setMembers = [$result['members']];
        if ($setMembers[0] == "None"){
            $upd = "UPDATE groupchats SET members = '' WHERE unique_id = '$token'";
            if (mysqli_query($conn, $upd)){
    
            }
            else {
                connectionError();
            }
        }
    }
    else {
        echo "There was an error connecting to the server... Please try again later";
        exit;
    }
}
}
else {
    echo "Something went wrong please try again later";
    exit;
}
function getUsernameById($id){
    global $conn;
    $sql = "SELECT * FROM users WHERE id = '$id'";
    $query = mysqli_query($conn, $sql);
    $result = mysqli_fetch_assoc($query);
    return $result['username'];
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
function connectionError(){
    echo "There was an error connecting to the server... Please try again later";
    exit;
}
?>
<!-- '/^([a-zA-Z\s]+)(,\s*[a-zA-Z\s]*)*$/'-->
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
    <title>Create a groupchat</title>
</head>
<body>
<form action="<?php echo $_SERVER['REQUEST_URI']?>" method="POST">
    <fieldset>
        <legend>Search for users</legend>
        <input type="text" name="search">
        <input type="submit" name="searchSubmit" title="Search" id="search">
    </fieldset>

</form>
        <div>
<!--           Search results-->
<?php
$result = [];
if (isset($_POST['searchSubmit'])){
    $search = htmlspecialchars($_POST['search']) ?? "";
    if (empty($search)){
        echo "Search is empty!";
    }
    else {
        $sql = "SELECT username FROM users WHERE username LIKE '$search%'";
        $query = mysqli_query($conn, $sql);
        $result = mysqli_fetch_all($query, MYSQLI_ASSOC);
    }
}
foreach ($result as $user):
$usernames = $user['username'] ?? $user;
if ($usernames == getUsernameById($info['id'])){
    echo "";
}
else {
?>
<form action="<?php echo $_SERVER['REQUEST_URI']?>" method="POST">
<input type="text" name="member"  value="<?php echo $user['username'] ?? $user?>" readonly>
<button type="submit" name="userAdd" title="Add user" id="userAdd"><i class="fa-solid fa-user-plus"></i></button>
</form>
<?php
}
endforeach;?>
<?php
if (isset($_POST['userAdd'])) {
    $token = $info['token'];
    $founder = $info['username'];
    $sel = "SELECT * FROM groupchats WHERE unique_id = '$token'";
    $query = mysqli_query($conn, $sel);
    $result = mysqli_fetch_assoc($query);
    $member = htmlspecialchars($_POST['member']);
    $new = $result['members'].$member.",";
    $sql = "UPDATE groupchats SET members  = '$new' WHERE unique_id = '$token'";
    if (mysqli_query($conn, $sql)){
        echo "Added";
    }
    else {
        connectionError();
    }
}
?>
<button id="createBtn" class="btn">Create groupchat</button>

</div>
<div class="display-none" id="modal">
    <h4 class="modal-nav">Final step</h4>
    <p class="modal-text">Add a name for your groupchat</p>
        <button id="closeFinal">X</button>
        <br><br>
        <input type="text" placeholder="Enter a name for your groupchat" class="modal-name" id="groupname">
        <button id="finalCreate">Create</button>
        <div id="success">
            
        </div>
    <p>Members: <span id="members"></span></p>
</div>
<script>
    var token = "<?php echo $info['token']?>";
    function changeAllBtn(bool){
        $("#createBtn").prop("disabled", bool);
        $("#userAdd").prop("disabled", bool);
        $("#search").prop("disabled", bool);
    }
    var modal = $("#modal");
    $("#createBtn").click(function(){
        modal.removeClass("display-none");
        modal.addClass("modal");
        changeAllBtn(true)
        $.post("./handleGroupCreate.php", {token: token}, function(data){
            $("#members").html(data);
        })
    });
    $("#closeFinal").click(function(){
        modal.removeClass("modal");
        modal.addClass("display-none");
        changeAllBtn(false)
    })
    $("#finalCreate").click(function(){
        var groupname = $("#groupname").val();
        let newStr = groupname.replace(/\s+/g, '');
        if (newStr == ""){
            $("#success").html("Name can't be blank")
        }
        else {
            $("#success").html("");
            $.post("./handleGroupCreate.php", {token: token, groupname: groupname}, function(data){
                $("#success").html(data)
                modal.removeClass("modal");
                modal.addClass("display-none");
                $.post("./handleGroupCreate.php", {created: "true", user_id: <?php echo $info['id']?>}, function(data){
                    if (data == "done"){
                        window.location.assign("groupchats.php?id=<?php echo $info['id']?>")
                    }
                })
            })
        }
    })
</script>
</body>
</html>
