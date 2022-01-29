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
    <title>Chatroom</title>
</head>
<body>
    <?php
            session_start();
            if (isset($_POST['login'])) {
                $email = $_POST["email"];
                $password = $_POST["password"];

                $sql = "select * from msg WHERE email = '$email'";

                $result = mysqli_query($conn, $sql);

                $row = mysqli_fetch_array($result);

                $name = $row['name'];
                $role = $row['role'];
                $img = $row['profile_image_dir'];
                $id = $row['id'];

                if ($row['email'] == "$email" and $row['password'] == "$password") {
                    $_SESSION['logged'] = 'CONNECTED';
                    $_SESSION['email'] = $email;
                    $_SESSION['password'] = $password;
                    $_SESSION['name'] = $name;
                    $_SESSION['role'] = $role;
                    $_SESSION['profile_image'] = $img;
                    $_SESSION['id'] = $id;

                    header("Location: #");
                } else {
                    echo 'Your credentials are invalid!';
                }
            }

            if (isset($_SESSION['logged'])) {
                header("Refresh:1");

                $currentMessageQuery = "SELECT current_message FROM msg WHERE id = '{$_SESSION['id']}'";

                $currentMessage = $conn->query($currentMessageQuery)->fetch_array()['current_message'];

                echo '
            <div class="profile_image_shape">
                <img style="width: 50px; height: 50px; border-radius:100px;" src="profile-images/'.$_SESSION['profile_image'].'">
            </div>
            <div style="width:50px; height:50px; background-color:#ff3729; border-radius:100px; position:absolute; right:10px; top:90px;"">
                <center>
                <a href="http://localhost/php/chatroom/?logoff"><img style="width:40px; height:45px;" src="https://www.seekpng.com/png/full/41-413813_shutdown-button-clipart-arrow-icon-logout-white-png.png"></a>
                </center>
            </div>
            <h3 style="margin:0;position:absolute;right:100px; margin-top:25px;">Name: '.$_SESSION['name']." ".  'Role: '.$_SESSION['role'].'</h3>
            <form method="post">
            <input type="text" name="msg-input" value="'. $currentMessage .'" class="message-input" autocomplete="off" required id="message-input">
            <input type="submit" name="submit-button" class="send-button" value="Send">
            </form>
            
            ';

                $all_messages = "select * from message";

                $result2 = $conn->query($all_messages);

            
                while ($rows = $result2->fetch_assoc()) {
                    $senderQuery = "select * from msg where id = '{$rows['user_id']}'";
                    $sender = $conn->query($senderQuery)->fetch_assoc();
                    #MESSAGES
                    echo '
                    <center>
                    <div><img style="width:50px; height:50px; border-radius:100px; margin:0; padding:0; position:relative; " src="profile-images/'.$sender['profile_image_dir'].'"></div>
                    <h3 style="margin:0; padding:0;">'.$sender['name'].'['.$sender['role'].']: '.$rows['msg'].'</h3>
                    <center>
                ';
                    if ($_SESSION['role'] == 'Admin') {
                        #DELETE MESSAGE BUTTON
                        echo " <a href='index.php?id=".$rows['id']."'><button class='delete-button'>Delete</button></a>";
                    }
                }
                if (isset($_POST['submit-button'])) {
                    $message = $_POST['msg-input'];
                    $send_message = "insert into message (msg, user_id) VALUES ('$message', '{$_SESSION['id']}')";
                    $results = mysqli_query($conn, $send_message);

                    $clearCurrentMessage = "UPDATE msg SET current_message = '' WHERE id = '{$_SESSION['id']}'";
                    $conn->query($clearCurrentMessage);

                    header("Refresh:0");
                }

                if (isset($_GET['logoff'])) {
                    unset($_SESSION['logged']);
                    header("Location: index.php");
                }
                if ($_SESSION['role'] == 'Admin') {
                    if (isset($_GET['id'])) {
                        $id = $_GET['id'];
                        $delete = "DELETE FROM message where id = '$id'";
                        $delete_ = mysqli_query($conn, $delete);
                        header("Location: index.php");
                    }
                }
            } else {
                echo '
            <center>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <h2>BINE AI VENIT!</h2>

            <form method="post">
                <input class="login-input" name="email" type="text" placeholder="Email">
                <br>
                <br>
                <input class="login-input" name="password" type="text" placeholder="Password">
                <br>
                <br>
                <input class="login-submit" name="login" type="submit">
            </form>
            <a href="register.php" style="color:black;"><p>If you don\'t have a account, click here!</p></a>
            </center>
            ';
            }

    ?>

    <script>
        const messageInput = document.getElementById('message-input');
        messageInput.focus()
        var tmpStr = messageInput.value
        messageInput.value = ''
        messageInput.value = tmpStr

        messageInput.addEventListener('keydown', function(event){
            const text = event.target.value

            let xhr = new XMLHttpRequest();
            let url = "persist_message.php";
       
            // open a connection
            xhr.open("POST", url, true);
 
            // Create a state change callback
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    console.log(this.responseText)
                }
            };
 
            // Converting JSON data to string
            let data = new FormData()
            data.append('current_message', text)
            // console.log(data.get('current_message'));
 
            // Sending data with the request
            xhr.send(data);
        })
    </script>
</body>
</html>
