<?php
    session_start();
    
    require('../server.php');
    if(isset($_POST['logout'])){
        session_destroy();
        header('location: /ThesisAdvisorHub/login');
    }

    if(isset($_POST['profile'])){
        header('location: /ThesisAdvisorHub/profile');
    }

    if(empty($_SESSION['username'])){
        header('location: /ThesisAdvisorHub/login');
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
            
            <?php
                if(empty($_SESSION['username'])){
                    echo "
                        <li><a href='/ThesisAdvisorHub/login'>Login</a></li>
                        <li><a href='/ThesisAdvisorHub/register'>Register</a></li>
                    ";
                }else{
                    echo "
                        <li><a href='/ThesisAdvisorHub/advisor'>Advisor</a></li>
                    ";
                }
            ?>
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
        if(isset($_POST['info'])){
            $id = $_POST['info'];
            $sql = "SELECT * FROM advisor_profile WHERE id = '$id'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();

            $name = $row['name'];
            $department = $row['department'];
            $email = $row['email'];
            $tel = $row['tel'];
            $research_topic = $row['research_topic'];
            $research_info = $row['research_info'];
            $other_info = $row['other_info'];
            $img = $row['img'];
            $student_amount = $row['student_amount'];

            echo 
            "
            <div class='container'>
                    
                    <div class='profile-info'>
                    <img src= '$img' >
                    <h2>$name</h2>
                    <p>Department $department</p>
                    </div>

                    
                    <div class='contact-info'>
                        <h3>ข้อมูลการติดต่อ</h3>
                        <p><strong>Email:</strong> $email</p>
                        <p><strong>เบอร์โทร:</strong> $tel</p>
                        </div>

                        <!-- ข้อมูลหัวข้อวิจัย -->
                        <div class='research-info'>
                        <h3>หัวข้อวิจัย</h3>
                        <p>$research_topic</p>
                        <p>" . nl2br($research_info) . "</p>
                        <h3>อื่นๆ</h3>
                        <p>" . nl2br($other_info) . "</p>
                        <h3>Number of students available for advising: $student_amount</h3>
                    </div>
                    
                </div>
            ";

        }
    ?>
    <footer>
        <p>&copy; 2024 Naresuan University.</p>
    </footer>
</body>
</html>