<?php
    include_once "database.php"
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    
        <center>
        <form method="post" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Name" class="login-input" required>
        <br>
        <br>
        <input type="text" name="email" placeholder="Email" class="login-input" required>
        <br>
        <br>
        <input type="text" name="password" placeholder="Password" class="login-input" required>
        <br>
        <br>
        <input type="file" name="Profile_Image" required>
        <br>
        <br>
        <input type="submit" class="register-button" name="register" value="Register">
        <form>
        <center>
        

        <?php
        if (isset($_POST['register'])) {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            $name_file = $_FILES['Profile_Image']['name'];
            $tmp_name = $_FILES['Profile_Image']['tmp_name'];
            $local_image = "profile-images/";
    
            move_uploaded_file($tmp_name, $local_image.$name_file);

            $sql = "INSERT INTO msg (name,email,profile_image_dir,password,role,banned) VALUES ('$name', '$email','$name_file', '$password', 'Member', 'NO')";
            $result = mysqli_query($conn,$sql);

            header("Location: index.php");
        }
    ?>
</body>
</html>