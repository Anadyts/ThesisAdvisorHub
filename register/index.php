<?php
    session_start();
    require('../server.php');
    if(isset($_SESSION['username'])){
        header('location: /ThesisAdvisorHub/home');
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advisor List</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

</head>
<body>
    <nav>
        <div class="logo">
            <img src="../Logo.jpg" alt="" width="90px">
        </div>
        <ul>
            <li><a href="/ThesisAdvisorHub/home">Home</a></li>
            <li><a href="/ThesisAdvisorHub/login">Login</a></li>
            <li><a href="/ThesisAdvisorHub/register">Register</a></li>
        </ul>
    </nav>

    <div class="wrap">
        <form action="" method="post">
            <i class='bx bxs-registered'></i>
            <div class="inputWrap">
                <input type="text" placeholder="Username" name="username" required> 
            </div>

            <div class="inputWrap">
                <input type="password" placeholder="Password" name="password" required> 
            </div>

            <div class="inputWrap">
                <input type="password" placeholder="Confirm Password" name="confirmPassword" required> 
            </div>

            <div class="inputWrap">
                <input type="email" placeholder="Naresuan University Email" name="email" required> 
            </div>

            <div class="buttonWrap">
                <button name="submit">Submit</button>
            </div>

            <div class="errorMessage">
                <?php
                    if(isset($_SESSION['error'])){
                        echo "<h3> {$_SESSION['error']}</h3>";
                        unset($_SESSION['error']);
                    }
                ?>
            </div>
        </form>
    </div>
</body>
</html>

<?php
    
    require('../server.php');
    require('../PHPMailer.php');
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    if(isset($_POST['submit'])){
        $username = filter_input(INPUT_POST,'username',FILTER_SANITIZE_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST,'password',FILTER_SANITIZE_SPECIAL_CHARS);
        $confirmPassword = filter_input(INPUT_POST,'confirmPassword',FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST,'email',FILTER_SANITIZE_SPECIAL_CHARS);
        $token = bin2hex(random_bytes(16));

        if(isset($_POST['submit'])){
            $role = '';

            list($localPart, $domain) = explode('@', $email);
            
            if ($domain === "nu.ac.th" && !preg_match('/\d/', $localPart)) {
                $role = 'advisor';
            }elseif($domain === "nu.ac.th" && preg_match('/\d/', $localPart)){
                echo 'student';
                $role = 'student';
            }

            if($role == 'advisor'){
                $sql = "SELECT username FROM advisor WHERE username = '$username' 
                        UNION SELECT username FROM student WHERE username = '$username'";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                
                if(isset($row['username'])){
                    $_SESSION['error'] = 'This username is already taken.'; 
                    header('location: /ThesisAdvisorHub/register');
                }elseif($password !== $confirmPassword){
                    $_SESSION['error'] = 'Passwords do not match.';
                    header('location: /ThesisAdvisorHub/register');
                }elseif($password === $confirmPassword ){
                    $hashPassword = password_hash($password, PASSWORD_DEFAULT);
                    $sql = "INSERT INTO advisor(username, password, email, verified, token, role) VALUES('$username', '$hashPassword', '$email', '0', '$token', 'advisor')";
                    $result = mysqli_query($conn, $sql);
                    sendEmail($token,$username);
                    header('location: /ThesisAdvisorHub/login');
                }

            }elseif($role == 'student'){
                $sql = "SELECT username FROM advisor WHERE username = '$username' 
                        UNION SELECT username FROM student WHERE username = '$username'";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                    
                if(isset($row['username'])){
                    $_SESSION['error'] = 'This username is already taken.'; 
                    header('location: /ThesisAdvisorHub/register');

                }elseif($password !== $confirmPassword){
                    $_SESSION['error'] = 'Passwords do not match.';
                    header('location: /ThesisAdvisorHub/register');

                }elseif($password === $confirmPassword ){
                    $hashPassword = password_hash($password, PASSWORD_DEFAULT);
                    $sql = "INSERT INTO student(username, password, email, verified, token, role) VALUES('$username', '$hashPassword', '$email', '0', '$token', 'student')";
                    $result = mysqli_query($conn, $sql);
                    sendEmail($token,$username);
                    echo 'student';
                    header('location: /ThesisAdvisorHub/login');
                }
            }else{
                $_SESSION['error'] = "Please use a Naresuan University email address.";
                header('location: /ThesisAdvisorHub/register');
            }
        }
    }
    
?>