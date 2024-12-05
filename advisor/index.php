<?php
    session_start();
    if(isset($_POST['logout'])){
        session_destroy();
        header('location: /ThesisAdvisorHub/login');
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
            <select name="faculty">
                <option value="102">บัณฑิตวิทยาลัย</option>
                <option value="120">วิทยาลัยพลังงานทดแทนและสมาร์ตกริดเทคโนโลยี</option>
                <option value="121">สถานการศึกษาต่อเนื่อง</option>
                <option value="123">คณะโลจิสติกส์และดิจิทัลซัพพลายเชน</option>
                <option value="124">วิทยาลัยเพื่อการค้นคว้าระดับรากฐาน</option>
                <option value="126">สถานพัฒนาวิชาการด้านภาษา</option>
                <option value="127">วิทยาลัยการจัดการระบบสุขภาพ</option>
                <option value="196">วิทยาลัยนานาชาติ</option>
                <option value="203">คณะเกษตรศาสตร์ ทรัพยากรธรรมชาติและสิ่งแวดล้อม</option>
                <option value="204">คณะเภสัชศาสตร์</option>
                <option value="206">คณะวิทยาศาสตร์</option>
                <option value="207">คณะวิศวกรรมศาสตร์</option>
                <option value="208">คณะศึกษาศาสตร์</option>
                <option value="209">คณะแพทยศาสตร์</option>
                <option value="210">คณะสาธารณสุขศาสตร์</option>
                <option value="211">คณะวิทยาศาสตร์การแพทย์</option>
                <option value="212">คณะพยาบาลศาสตร์</option>
                <option value="213">คณะทันตแพทยศาสตร์</option>
                <option value="214">คณะสหเวชศาสตร์</option>
                <option value="215">คณะสถาปัตยกรรมศาสตร์ ศิลปะและการออกแบบ</option>
                <option value="216">คณะนิติศาสตร์</option>
                <option value="217">คณะมนุษยศาสตร์</option>
                <option value="218">คณะบริหารธุรกิจ เศรษฐศาสตร์และการสื่อสาร</option>
                <option value="219">คณะสังคมศาสตร์</option>
                <option value="292">โรงเรียนสาธิตมหาวิทยาลัยนเรศวร</option>
                </select>
            <button><i class='bx bx-search'></i></button>
        </form>
    </div>

    <div class="advisorList">
        <div class="advisorCard">
            <img src="images/1.png" alt="">
            <div class="details">
                <p>Aj.Wuttipong Ruanthong</p>
                <p>อาจารย์วุฒิพงษ์ เรือนทอง</p>
                <p>Email : wuttipongr@nu.ac.th</p>
                <form action="" method="post">
                    <button name="info"><i class='bx bx-info-circle'></i></button>
                </form>
            </div>
        </div>

        <div class="advisorCard">
            <img src="images/2.png" alt="">
            <div class="details">
                <p>Asst.Prof.Dr.Janjira Payakpate</p>
                <p>ผู้ช่วยศาสตราจารย์ ดร.จันทร์จิรา พยัคฆ์เพศ</p>
                <p>Email : janjirap@nu.ac.th</p>
                <form action="" method="post">
                    <button name="info"><i class='bx bx-info-circle'></i></button>
                </form>
            </div>
        </div>

        <div class="advisorCard">
            <img src="images/3.png" alt="">
            <div class="details">
                <p>Asst.Prof.Dr.Thanathorn Phoka</p>
                <p>ผู้ช่วยศาสตราจารย์ ดร.ธนะธร พ่อค้า</p>
                <p>Email : thanathornp@nu.ac.th</p>
                <form action="" method="post">
                    <button name="info"><i class='bx bx-info-circle'></i></button>
                </form>
            </div>
        </div>

        <div class="advisorCard">
            <img src="images/4.png" alt="">
            <div class="details">
                <p>Assoc.Prof.Dr.Kraisak Kesorn</p>
                <p>รองศาสตราจารย์ ดร.ไกรศักดิ์ เกษร</p>
                <p>Email : kraisakk@nu.ac.th</p>
                <form action="" method="post">
                    <button name="info"><i class='bx bx-info-circle'></i></button>
                </form>
            </div>
        </div>

        <div class="advisorCard">
            <img src="images/5.png" alt="">
            <div class="details">
                <p>Asst.Prof.Dr.Kreangsak Tamee</p>
                <p>ผู้ช่วยศาสตราจารย์ ดร.เกรียงศักดิ์ เตมีย์</p>
                <p>Email : kreangsakt@nu.ac.th</p>
                <form action="" method="post">
                    <button name="info"><i class='bx bx-info-circle'></i></button>
                </form>
            </div>
        </div>

        <div class="advisorCard">
            <img src="images/6.png" alt="">
            <div class="details">
                <p>Aj.Phisetphong Suthaphan</p>
                <p>อาจารย์พิเศษพงศ์ สุธาพันธ์</p>
                <p>Email: phisetphongs@nu.ac.th</p>
                <form action="" method="post">
                    <button name="info"><i class='bx bx-info-circle'></i></button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>