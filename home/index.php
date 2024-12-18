<?php
    session_start();
    
    if(isset($_POST['logout'])){
        session_destroy();
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
    <title>Home</title>
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
            
            <?php
                if(empty($_SESSION['username'])){
                    echo "
                        <li><a href='/ThesisAdvisorHub/login'>Login</a></li>
                        <li><a href='/ThesisAdvisorHub/register'>Register</a></li>
                    ";
                }else{
                    echo "
                        <li><a href='/ThesisAdvisorHub/advisor'>Advisor</a></li>
                        <li><a href='/ThesisAdvisorHub/inbox'>Inbox</a></li>
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
    <img src="nu.jpg" alt="" width="100%">

    <footer>
        <div class="footer-container">
            <div class="footer-content">
                <div class="footer-about">
                    <h3>เกี่ยวกับเรา</h3>
                    <p>ThesisAdvisorHub เป็นเว็บไซต์ที่ช่วยให้นักศึกษาและผู้ที่ต้องการคำปรึกษาในการวิจัยสามารถค้นหาและติดต่ออาจารย์ที่ปรึกษาได้สะดวกมากขึ้น</p>
                </div>
                
                <div class="footer-contact">
                    <h3>ติดต่อเรา</h3>
                    <ul>
                        <li> <a href="mailto:contact@advisorhub.com">ThesisAdvisorHub@gmail.com</a></li>
                        <li>055-123-4561</li>
                        <li> มหาวิทยาลัยนเรศวร, ตำบลบางกระทุ่ม, อำเภอเมือง, จังหวัดพิษณุโลก</li>
                    </ul>
                </div>
                
                <div class="footer-social">
                    <h3>ติดตามเรา</h3>
                    <ul>
                        <li><a href="https://www.facebook.com/nu.university"><i class='bx bxl-facebook-circle'></i></a></li>
                        <li><a href="#"><i class='bx bxl-twitter'></i></a></li>
                        <li><a href="#"><i class='bx bxl-instagram-alt' ></i></a></li>
                    </ul>
                </div>
            </div>


        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 ThesisAdvisorHub | All Rights Reserved</p>
        </div>
    </footer>
</body>
</html>