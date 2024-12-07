<?php
    session_start();
    
    require('../server.php');
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
            <li><a href="/ThesisAdvisorHub/advisor">Advisor</a></li>
            <?php
                if(empty($_SESSION['username'])){
                    echo "
                        <li><a href='/ThesisAdvisorHub/login'>Login</a></li>
                        <li><a href='/ThesisAdvisorHub/register'>Register</a></li>
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
    <div class="search">
        <form action="" method="post">
            <input type="text" name="search" placeholder="Search Advisor...">
            <button><i class='bx bx-search'></i></button>
        </form>
    </div>

    <div class="advisorList">
        <?php
            $sql = "SELECT * FROM advisor_profile";
            $result = $conn->query($sql);
            while($row = $result->fetch_assoc()){
                $name = $row['name'];
                $department = $row['department'];
                $email = $row['email'];
                $tel = $row['tel'];
                $research_topic = $row['research_topic'];
                $research_info = $row['research_info'];
                $other_info = $row['other_info'];
                $student_amount = $row['student_amount'];
                $img = $row['img'];

                echo 
                "
                <div class='advisorCard'>
                    <img src='$img' alt=''>
                    <div class='details'>
                        <p>$name</p>
                        <p>$department</p>
                        <p>Email : $email</p>
                        <form action='' method='post'>
                            <button name='info'><i class='bx bx-info-circle'></i></button>
                        </form>
                    </div>
                </div>
                ";
            }
        ?>
    </div>
    <footer>
        <p>&copy; 2024 Naresuan University.</p>
    </footer>
</body>
</html>