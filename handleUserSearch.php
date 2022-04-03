<?php
include "database.php";
session_start();
function getUsernameById($id){
    global $conn;
    $sql = "SELECT * FROM users WHERE id = '$id'";
    $query = mysqli_query($conn, $sql);
    $result = mysqli_fetch_assoc($query);
    return $result['username'] ?? $result;
}
if (isset($_POST['search']) && (isset($_POST['id']) || isset($_SESSION['id']))){
    $search = mysqli_real_escape_string($conn, htmlspecialchars($_POST['search']));
    $id = $_POST['id'] ?? $_SESSION['id'];
    $id = mysqli_real_escape_string($conn, htmlspecialchars($id));
    $sql = "SELECT username, id FROM users WHERE username LIKE '$search%' LIMIT 10";
    $query = mysqli_query($conn, $sql);
    $result = mysqli_fetch_all($query, MYSQLI_ASSOC);
    foreach($result as $user){
        $username = $user['username'] ?? $user;
        $user_id = $user['id'] ?? $user;
        if ($username == getUsernameById($id)){
            echo "";
        } else {
            ?>
            <a href="account.php?user_id=<?php echo $user_id?>"><?php echo $username?></a>
            <a href="chat.php?id=<?php echo $id?>&reciever_id=<?php echo $user_id?>"><i class="fa-solid fa-message"></i></a>
            <br>
<?php            
        }
    }
}
if (isset($_POST['clickSearch']) && (isset($_POST['user_id']) || isset($_SESSION['id']))){
    $search = mysqli_real_escape_string($conn, $_POST['clickSearch']);
    $id = $_POST['id'] ?? $_SESSION['id'];
    $id = mysqli_real_escape_string($conn, htmlspecialchars($id));
    $sql = "SELECT username, id FROM users WHERE username LIKE '$search%' LIMIT 10";
    $query = mysqli_query($conn, $sql);
    $result = mysqli_fetch_all($query, MYSQLI_ASSOC);
    foreach($result as $user){
        $username = $user['username'] ?? $user;
        $user_id = $user['id'] ?? $user;
        if ($username == getUsernameById($id)){
            echo "";
        } else {
            
            ?>
            <a href="account.php?user_id=<?php echo $user_id?>"><?php echo $username?></a>
            <a href="chat.php?id=<?php echo $id?>&reciever_id=<?php echo $user_id?>"><i class="fa-solid fa-message"></i></a>
            <br>
            <?php
        }
    }
}

?>