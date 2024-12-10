<?php
    session_start();
    require ('../server.php');

    if(isset($_POST['logout'])){
        session_destroy();
        header('location: /ThesisAdvisorHub/login');
    }

    if(empty($_SESSION['username'])){
        header('location: /ThesisAdvisorHub/login');
    }

    if(isset($_POST['profile'])){
        header('location: /ThesisAdvisorHub/profile');
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="../Logo.jpg">
</head>
<body>
    <nav>
        <div class="logo">
            <img src="../Logo.jpg" alt="" width="90px">
        </div>
        <ul>
            <li><a href="/ThesisAdvisorHub/home">Home</a></li>
            <li><a href="/ThesisAdvisorHub/advisor">Advisor</a></li>
            <li><a href="/ThesisAdvisorHub/inbox">Inbox</a></li>
        </ul>

        <div class="userProfile">
            <?php
                if(isset($_SESSION['username'])){
                    echo '<h2>'.$_SESSION['username'].'<h2/>';
                    echo "<i class='bx bxs-user-circle' ></i>";
                    echo "<div class='dropdown'>
                            <form action='' method='post'>
                                <button name='profile'>Profile</button>
                                <button name='logout'>Logout</button>
                            </form>
                        </div>";
                }
            ?>
        </div>
    </nav>
    <?php
        if(isset($_SESSION['receiver_id'])){
            $receiver_id = $_SESSION['receiver_id'];
            $sender_id = $_SESSION['id'];

            $sql = "SELECT * FROM advisor_profile WHERE user_id = '$receiver_id'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();

            $advisorUsername = $row['name'];
            if(isset($_POST['send'])){
                $message = $_POST['message'];
                $sql = "INSERT INTO messages(sender_id, receiver_id, message) VALUES('$sender_id', '$receiver_id', '$message')";
                $result = $conn->query($sql);
            }

            
            echo 
            "
                <div class='chat-container'>
                    <div class='chat-header'>
                        <h2>$advisorUsername</h2>
                    </div>
                    <div class='chat-box'>
                        <div class='message-container'>
            ";
            
            $sql = "SELECT * FROM messages WHERE receiver_id = '$receiver_id' AND sender_id = '$sender_id' UNION
                    SELECT * FROM messages WHERE receiver_id = '$sender_id' AND sender_id = '$receiver_id'
                    ORDER BY timestamp ASC
                    ";
            $result = $conn->query($sql);

            while($row = $result->fetch_assoc()){
                $message = $row['message'];
                $time = $row['timestamp'];
                if($sender_id == $row['sender_id']){
                    echo 
                    "

                    <div class='message message-sent'>
                        <div class='message-content'>". nl2br($message) ."</div>
                        <div class='message-time'>$time</div>
                    </div>


                    ";
                }else{
                    echo 
                    "
                    <div class='message message-received'>
                        <div class='message-content'>". nl2br($message) ."</div>
                        <div class='message-time'>$time</div>
                    </div>
                    ";
                }
            }

            echo 
            "
                        </div>
                    </div>
                    <form action='' method='post'>
                        <div class='chat-input'>
                            <input type='text' class='input-message' name='message' placeholder='Type a message...' />
                            <button class='send-button' name='send'>Send</button>
                        </div>
                    </form>
            ";

        }
    
    ?>
    <button class="scroll-to-bottom" ><i class='bx bx-down-arrow-alt'></i></button>

    

    </div>
    <footer>
        <p>&copy; 2024 Naresuan University.</p>
    </footer>
    <script src="script.js"></script>
</body>
</html>