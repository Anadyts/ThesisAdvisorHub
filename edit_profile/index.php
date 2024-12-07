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

    if (isset($_POST['edit'])) {
        // รับค่าจากฟอร์ม
        $id = $_SESSION['id'];  // รับค่า ID จาก session
        $name = $_POST['name'];
        $department = $_POST['department'];
        $email = $_POST['email'];
        $tel = $_POST['tel'];
        $research_topic = $_POST['research_topic'];
        $research_info = $_POST['research_info'];
        $other_info = $_POST['other_info'];
        $student_amount = $_POST['student_amount'];
    
        // เชื่อมต่อฐานข้อมูลและดึงข้อมูลรูปโปรไฟล์เก่าจากฐานข้อมูล
        $sql = "SELECT img FROM advisor_profile WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($old_img);
        $stmt->fetch();
        $stmt->close();
    
        // จัดการการอัพโหลดไฟล์ (ถ้ามีการเลือกไฟล์ใหม่)
        $target_dir = "../uploads/";
        $uploadOk = 1;
        $new_file_name = null;
    
        if (isset($_FILES["img"]) && $_FILES["img"]["error"] == 0) {
            // ตรวจสอบว่าเป็นไฟล์รูปภาพ
            $imageFileType = strtolower(pathinfo($_FILES["img"]["name"], PATHINFO_EXTENSION));
            $new_file_name = uniqid() . "." . $imageFileType;
            $target_file = $target_dir . $new_file_name;
    
            $check = getimagesize($_FILES["img"]["tmp_name"]);
            if ($check === false) {
                echo "File is not an image.";
                $uploadOk = 0;
            }
    
            // ตรวจสอบขนาดไฟล์
            if ($_FILES["img"]["size"] > 5000000) {  // 5MB
                echo "Sorry, your file is too large.";
                $uploadOk = 0;
            }
    
            // ตรวจสอบประเภทไฟล์
            $allowed_types = array("jpg", "jpeg", "png", "gif");
            if (!in_array($imageFileType, $allowed_types)) {
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
            }
    
            // หากทุกอย่างถูกต้องให้ทำการอัพโหลด
            if ($uploadOk == 1) {
                if (move_uploaded_file($_FILES["img"]["tmp_name"], $target_file)) {
                    // ลบไฟล์เก่า (ถ้ามี)
                    if ($old_img && file_exists($old_img)) {
                        unlink($old_img);  // ลบไฟล์เก่า
                    }
                    $img = $target_file; // เก็บชื่อไฟล์ใหม่
                } else {
                    echo "Sorry, there was an error uploading your file.";
                    exit;
                }
            }
        } else {
            // ถ้าไม่มีการอัพโหลดไฟล์ใหม่ ให้ใช้ไฟล์เดิม
            $img = $old_img;
        }
    
        // อัพเดทข้อมูลในฐานข้อมูล
        $sql = "UPDATE advisor_profile SET 
                name = ?, 
                department = ?, 
                email = ?, 
                tel = ?, 
                research_topic = ?, 
                research_info = ?, 
                other_info = ?, 
                img = ?, 
                student_amount = ? 
                WHERE user_id = ?";
    
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssss", $name, $department, $email, $tel, $research_topic, $research_info, $other_info, $img, $student_amount, $id);
    
        if ($stmt->execute()) {
            header('location: /ThesisAdvisorHub/profile');  // รีไดเร็กต์ไปที่หน้าประวัติ
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }
    
        $stmt->close();
        $conn->close();
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
        $username = $_SESSION['username'];
        $role = $_SESSION['role'];
        $id = $_SESSION['id'];

        if($role == 'advisor'){
            $sql = "SELECT * FROM advisor_profile WHERE user_id = '$id'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();

            if(isset($row['id'])){
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
                <form action='' method='post' class='profile-form' enctype='multipart/form-data'>
                    <div class='wrap'>
                        <h2>Advisor Profile</h2>
                        <div class='wrapInput'>
                            <input type='text' placeholder='Name' name='name' value='$name'required>
                        </div>

                        <div class='wrapInput'>
                            <input type='text' placeholder='Department' name='department' value='$department' required>
                        </div>
                        
                        <div class='wrapInput'>
                            <input type='email' placeholder='Email Contact' name='email' value='$email' required>
                        </div>
                        
                        <div class='wrapInput'>
                            <input type='tel' placeholder='Telephone' name='tel' value='$tel' required>
                        </div>
                        
                        <div class='wrapInput'>
                            <input type='text' placeholder='Research Topic' name='research_topic' value='$research_topic' required>
                        </div>
                        
                        <div class='wrapInput'>
                            <textarea name='research_info' id='' placeholder='Research Information' required>$research_info</textarea>
                        </div>
                        
                        
                        <div class='wrapInput'>
                            <textarea name='other_info' id='' placeholder='Other' required>$other_info</textarea>
                        </div>
                        
                        <div class='wrapInput'>
                            <input type='text' placeholder='Number of students available for advising ex. 7/10, 4/6' name='student_amount' value='$student_amount' required>
                        </div>

                        <div class='wrapInput'>
                            <input type='file' id='fileInput' name='img' >
                            <label for='fileInput' class='file-upload-btn'>Choose Profile Image</label>
                            <p class='file-name' id='fileName'></p>
                        </div>

                        <div class='wrapInput'>
                            <button name='edit'>Edit Profile</button>
                        </div>
                        
                    </div>
                </form>
                ";
            }else{
                header('location: /ThesisAdvisorHub/profile');
            }
        }
    ?>

    <footer>
        <p>&copy; 2024 Naresuan University.</p>
    </footer>
    <script>
    // แสดงชื่อไฟล์เมื่อเลือก
        document.getElementById("fileInput").addEventListener("change", function(e) {
            const fileName = e.target.files[0] ? e.target.files[0].name : "ไม่มีไฟล์เลือก";
            document.getElementById("fileName").textContent = fileName;
        });
    </script>
</body>
</html>