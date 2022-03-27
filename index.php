<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style.css">
    <title>Chatter test</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css" integrity="sha512-10/jx2EXwxxWqCLX/hHth/vu2KY3jCF70dCQB8TSgNjbCVAC/8vai53GfMDrO2Emgwccf2pJqxct9ehpzG+MTw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  </head>
  <body>
    <?php
    include("database.php");
    function getDefaultPhoto() {
        global $conn;
        $sql = "SELECT * FROM users WHERE id = 1";
        $query = mysqli_query($conn, $sql);
        $result = mysqli_fetch_assoc($query);
        return $result['profile_photo'];
    }
    function checkFor($value){
      global $conn;
      $sql = "SELECT * FROM users WHERE username = '$value'";
      $result = mysqli_query($conn, $sql);
      if (mysqli_num_rows($result) > 0){
        return true;
      }
      else {
        return false;
      }
    }
    function removeSpace($string){
        return preg_replace('/\s+/', '', $string);
    }
    $errors = ['name'=>'', 'username'=>'', 'password'=>'','image'=>''];
    if (isset($_POST['SignUpSubmit'])) {
      $spacename = htmlspecialchars($_POST['name']);
      $name = removeSpace($spacename);
      $spaceusername = htmlspecialchars($_POST['username']);
      $username = removeSpace($spaceusername);
      $password = htmlspecialchars($_POST['password']);
      $photo =[];
      if($_FILES['profile_photo']['size'] != 0 && $_FILES['profile_photo']['error'] == 0){
          $photo[0] = addslashes(file_get_contents($_FILES['profile_photo']['tmp_name']));
      }
      else {
          $errors['image'] = "Profile photo is required!";
      }
      if (empty($name)){
        $errors['name'] = "Name is required";
      }
      if (empty($username)){
        $errors['username'] = "Username is required";
      }
      if (empty($password)){
        $errors['password'] = "Password is required";
      }
      if (array_filter($errors)){
        echo 'Please try again';
      }
      else {
        if (checkFor($username)){
          echo "<h3>Username is taken</h3>";
        }
        else {
        echo "Success";
        session_start();
        $_SESSION['name'] = $name;
        $_SESSION['username'] = $username;
        $sql = "INSERT INTO users(name, username, password, profile_photo) VALUES('$spacename', '$spaceusername', '$password', '$photo[0]')";
        if (mysqli_query($conn, $sql)){
          // success
          $sql = "SELECT * FROM users WHERE username = '$spaceusername'";
          $query = mysqli_query($conn, $sql);
          $result = mysqli_fetch_assoc($query);
          $_SESSION['id'] = $result['id'];
          $_SESSION['username'] = $result['username'];
          header("Location: users.php?id=".$_SESSION['id']);
          
        }
        else {
          echo "Something went wrong while connecting please try again later";
          }
        }
      }
    }
     ?>
    <div class="header">
     <div class="brand">
        <h1>Chatter <i class="fa-regular fa-comments"></i></h1>
     </div>
        <h2>Sign up</h2>
    </div>
    <div class="form">
    <form action="index.php" method="POST" enctype="multipart/form-data">
      <label>Name</label>
      <input type="text" name="name" placeholder="Enter your name">
      <?php echo $errors['name'] ?>
      <br><br>
      <label>Username</label>
      <input type="text" name="username" placeholder="Enter your username">
      <?php echo $errors['username'] ?>
      <br><br>
      <label>Password</label>
      <input type="password" name="password" id="passwordInput" placeholder="Enter a password">
      <?php echo $errors['password'] ?>
      <br><br>
      <label>Profile photo</label>
      <input type="file" name="profile_photo" accept="image/*" required>
      <?php echo $errors['image']?>
      <br><br>
<!--      <input type="checkbox" id="password">See password-->
      <i class="fa-solid fa-eye" id="password"></i> See password
      <br><br>
      <input type="submit" name="SignUpSubmit" class="btn">
        <p>Already have an account? <a href="login.php">Login in here</a></p>
    </form>
    </div>
    <script>
    let password = document.querySelector("#password");
    let passwordInput = document.querySelector("#passwordInput")
    isChecked = false;
    // password.addEventListener("change", ()=>{
    //   if (password.checked){
    //     passwordInput.type = "text"
    //   }
    //   else {
    //     passwordInput.type = "password";
    //   }
    // })
    password.addEventListener("click", function (){
        isChecked = !isChecked;

        if (isChecked){
            password.classList.remove("fa-eye");
            password.classList.add("fa-eye-slash");
            passwordInput.type = "text"
        }
        else {
            password.classList.remove("fa-eye-slash");
            password.classList.add("fa-eye");
            passwordInput.type = "password"
        }
    })
    </script>
  </body>
</html>
