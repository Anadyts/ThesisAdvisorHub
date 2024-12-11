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
    <title>Student Profile</title>
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
            <li><a href='/ThesisAdvisorHub/advisor'>Advisor</a></li>
            <li><a href='/ThesisAdvisorHub/inbox'>Inbox</a></li>
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
        if(isset($_SESSION['profileInbox'])){
            $student_id = $_SESSION['profileInbox'];
            $sql = "SELECT * FROM student_profile WHERE user_id = '$student_id'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();

            if(isset($row['id'])){
                $user_id = $row['user_id'];
                $name = $row['name'];
                $department = $row['department'];
                $email = $row['email'];
                $tel = $row['tel'];
                $research_topic = $row['research_topic'];
                $other_info = $row['other_info'];
                
                echo 
                "
                <div class='container'>
                    
                    <div class='profile-info'>
                    <h2>$name</h2>
                    <p>Department $department</p>
                    </div>

                    
                    <div class='contact-info'>
                        <h3>Contact</h3>
                        <p><strong>Email:</strong> $email</p>
                        <p><strong>Telephone Number:</strong> $tel</p>
                        </div>

                        <!-- ข้อมูลหัวข้อวิจัย -->
                        <div class='research-info'>
                        <h3>Interested research topics</h3>
                        <p>$research_topic</p>
                        <h3>Other</h3>
                        <p>" . nl2br($other_info) . "</p>
                    </div>
                </div>
                ";
            }else{
                echo "<h1>This user don't have profile</h1>";
            }
        }
        
    ?>
    <footer>
        <p>&copy; 2024 Naresuan University.</p>
    </footer>
</body>
</html>