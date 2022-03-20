<?php
if (isset($_POST['submit'])){
    $image = addslashes(file_get_contents($_FILES['image']['tmp_name']));
    echo $image;
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<form action="test.php" method="POST" enctype="multipart/form-data">
    <input type="file" name="image">
    <input type="submit" name="submit">
</form>
</body>
</html>
