<?php
    session_start();
    require('../server.php');

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
            <img src="Logo.jpg" alt="" width="90px">
        </div>
        <ul>
            <li><a href="/ThesisAdvisorHub/home">Home</a></li>
            <li><a href="/ThesisAdvisorHub/advisor">Advisor</a></li>
            <li><a href="/ThesisAdvisorHub/login">Login</a></li>
            <li><a href="/ThesisAdvisorHub/register">Register</a></li>
        </ul>
    </nav>

    <div class="wrap">
        <form action="" method="post">
            <i class='bx bxs-user-circle' ></i>
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
                <input type="email" placeholder="Email" name="email" required> 
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
            }else{
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
                    header('location: /ThesisAdvisorHub/login');
                }

            }else{
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
                    header('location: /ThesisAdvisorHub/login');
                }
            }
        }
        

        require '../vendor/autoload.php';

        $mail = new PHPMailer(true);

        try {
            

            // ตั้งค่าการเชื่อมต่อ SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'harry.sgy24@gmail.com'; // ใส่อีเมลของคุณ
            $mail->Password = 'flue swzk aqjt hhgg';   // ใส่ App Password
            $mail->SMTPSecure = "tls";
            $mail->Port = 587;

            // ตั้งค่าผู้ส่งและผู้รับ
            $mail->setFrom('harry.sgy24@gmail.com', 'Sender Name');
            $mail->addAddress('jakkritu65@nu.ac.th', 'Receive');

            // ตั้งค่าอีเมล
            $mail->isHTML(true);
            $mail->Subject = 'Verify Your Email';
            $verificationLink = "http://localhost/ThesisAdvisorHub/verify?token=$token";
            $mail->Body = "<h1>Thank you for registering!</h1>
                        <p>Please click the link below to verify your email:</p>
                        <a href='$verificationLink'>Verify Email</a>";
            $mail->AltBody = "Please visit the following link to verify your email: $verificationLink";

            // ส่งอีเมล
            $mail->send();
            echo 'Email has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
    
    
?>