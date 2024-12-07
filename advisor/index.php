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

    // ถ้ามีการค้นหา
    $search_query = "";
    if(isset($_POST['search'])){
        $search_query = $_POST['search'];  // รับค่าคำค้นจากฟอร์ม
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
            <input type="text" name="search" placeholder="Search Advisor..." value="<?php echo htmlspecialchars($search_query); ?>">
            <button><i class='bx bx-search'></i></button>
        </form>
    </div>

    <div class="advisorList">
        <?php
            // การค้นหาด้วยคำที่ผู้ใช้กรอก
            if($search_query != ""){
                // คำค้นหามีการใส่เข้ามา
                $sql = "SELECT * FROM advisor_profile WHERE name LIKE '%$search_query%' OR department LIKE '%$search_query%' OR research_topic LIKE '%$search_query%'";
            } else {
                // หากไม่มีคำค้นหาให้ดึงข้อมูลทั้งหมด
                $sql = "SELECT * FROM advisor_profile";
            }

            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()){
                    $id = $row['id'];
                    $name = $row['name'];
                    $department = $row['department'];
                    $email = $row['email'];
                    $tel = $row['tel'];
                    $research_topic = $row['research_topic'];
                    $research_info = $row['research_info'];
                    $other_info = $row['other_info'];
                    $student_amount = $row['student_amount'];
                    $img = $row['img'];

                    echo "
                    <div class='advisorCard'>
                        <img src='$img' alt=''>
                        <div class='details'>
                            <p>$name</p>
                            <p>$department</p>
                            <p>Email : $email</p>
                            <form action='../info/index.php' method='post'>
                                <button name='info' value='$id'><i class='bx bx-info-circle'></i></button>
                            </form>
                        </div>
                    </div>
                    ";
                }
            } else {
                echo "<p>No advisors found matching your search criteria.</p>";
            }
        ?>
    </div>
    <footer>
        <p>&copy; 2024 Naresuan University.</p>
    </footer>
</body>
</html>
