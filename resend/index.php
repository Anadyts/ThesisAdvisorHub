<?php
    session_start();
    require ('../server.php');
    require('../PHPMailer.php');
    if(isset($_POST['logout'])){
        session_destroy();
        header('location: /ThesisAdvisorHub/login');
    }

    if(isset($_SESSION['username'])){
        header('location: /ThesisAdvisorHub/home');
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resend</title>
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
            <li><a href="/ThesisAdvisorHub/login">Login</a></li>
            <li><a href="/ThesisAdvisorHub/register">Register</a></li>
        </ul>

        <div class="userProfile">
            <?php
                if(isset($_SESSION['username'])){
                    echo '<h2>'.$_SESSION['username'].'<h2/>';
                    echo "<i class='bx bxs-user-circle' ></i>";
                    echo "<div class='dropdown'>
                            <form action='' method='post'>
                                <button name='profile'>Profile</button>
                                <button name='history'>History</button>
                                <button name='logout'>Logout</button>
                            </form>
                        </div>";
                }
            ?>
        </div>
    </nav>

    <div class="wrap">
        <form action="" method="post">
            <div class="inputWrap">
                <input type="text" placeholder="Username" name="username" required> 
            </div>

            <div class="inputWrap">
                <input type="email" placeholder="Email" name="email" required> 
            </div>

            
            <div class="buttonWrap">
                <button name="resend">Resend</button>
            </div>

            <div class="errorMessage">
                <?php
                    if(isset($_SESSION['error'])){
                        echo "<h3> {$_SESSION['error']}</h3>";
                        unset($_SESSION['error']);
                    }
                ?>
            </div>
            <div class="success">
            <?php
                    if(isset($_SESSION['success'])){
                        echo "<h3> {$_SESSION['success']}</h3>";
                        unset($_SESSION['success']);
                    }
                ?>
            </div>
        </form>
    </div>    
    <footer>
        <p>&copy; 2024 Naresuan University.</p>
    </footer>
</body>
</html>

<?php
    $username = filter_input(INPUT_POST,'username',FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST,'email',FILTER_SANITIZE_SPECIAL_CHARS);

    if(isset($_POST['resend'])){
        $sql = "SELECT * FROM advisor WHERE username = '$username' AND email = '$email'
                UNION SELECT * FROM student WHERE username = '$username' AND email = '$email'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        
        if(isset($row['username'])){
            sendEmail($row['token'],$row['username']);
            $_SESSION['success'] = 'A verification link has been sent to your email.';
            header('location: /ThesisAdvisorHub/resend');
        }else{
            header('location: /ThesisAdvisorHub/resend');
            $_SESSION['error'] = 'Do not have this email or username';
        }
    }
?>