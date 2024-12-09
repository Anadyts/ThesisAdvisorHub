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

    if(isset($_POST['edit'])){
        header('location: /ThesisAdvisorHub/edit_profile');
    }

    if (isset($_POST['delete'])) {
        // รับ id จาก session
        $id = $_SESSION['id'];
    
        // 1. ค้นหาข้อมูลไฟล์รูปภาพจากฐานข้อมูลก่อนการลบ
        $sql = "SELECT img FROM advisor_profile WHERE user_id = '$id'";
        $result = $conn->query($sql);
    
        // ตรวจสอบว่าได้ผลลัพธ์หรือไม่
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $img = $row['img'];  // ไฟล์รูปภาพที่ต้องการลบ
    
            // 2. ลบไฟล์จากเซิร์ฟเวอร์
            if (file_exists($img)) {
                unlink($img);  // ลบไฟล์จากเซิร์ฟเวอร์
            }
        }
    
        // 3. ลบข้อมูลจากฐานข้อมูล
        $sql_delete = "DELETE FROM advisor_profile WHERE user_id = '$id'";
        if ($conn->query($sql_delete)) {
            header('location: /ThesisAdvisorHub/profile');
            exit;
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    }

    if (isset($_POST['submit'])) {
        // รับค่าจากฟอร์ม
        $id = $_SESSION['id'];
        $name = $_POST['name'];
        $department = $_POST['department'];
        $email = $_POST['email'];
        $tel = $_POST['tel'];
        $research_topic = $_POST['research_topic'];
        $research_info = $_POST['research_info'];
        $other_info = $_POST['other_info'];
        $student_amount = $_POST['student_amount'];

        // จัดการการอัพโหลดไฟล์
        $target_dir = "../uploads/";
        $imageFileType = strtolower(pathinfo($_FILES["img"]["name"], PATHINFO_EXTENSION));
    
        // สร้างชื่อไฟล์ใหม่โดยใช้ uniqid() และต่อท้ายด้วยนามสกุลไฟล์เดิม
        $new_file_name = uniqid() . "." . $imageFileType;
        $target_file = $target_dir . $new_file_name;
    
        $uploadOk = 1;
    
        // ตรวจสอบว่าเป็นไฟล์รูปภาพ
        $check = getimagesize($_FILES["img"]["tmp_name"]);
        if ($check === false) {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    
        // ตรวจสอบขนาดไฟล์ (สามารถปรับขนาดได้ตามที่ต้องการ)
        if ($_FILES["img"]["size"] > 5000000) {  // 5MB
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }
    
        // ตรวจสอบประเภทของไฟล์ (สามารถปรับประเภทไฟล์ได้ตามที่ต้องการ)
        $allowed_types = array("jpg", "jpeg", "png", "gif");
        if (!in_array($imageFileType, $allowed_types)) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }
    
        // หากทุกอย่างถูกต้องให้ทำการอัพโหลด
        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["img"]["tmp_name"], $target_file)) {
                $img = $target_file;
    
                // เชื่อมต่อฐานข้อมูลและเตรียมคำสั่ง SQL
                $sql = "INSERT INTO advisor_profile (name, department, email, tel, research_topic, research_info, other_info, img, user_id, student_amount) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
                // เตรียมคำสั่ง SQL
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssssssss", $name, $department, $email, $tel, $research_topic, $research_info, $other_info, $img, $id, $student_amount);
                
                // ดำเนินการคำสั่ง SQL
                if ($stmt->execute()) {
                    header('location: /ThesisAdvisorHub/profile');
                    exit;
                } else {
                    echo "Error: " . $stmt->error;
                }
                
                // ปิดการเชื่อมต่อ
                $stmt->close();
            }
        }
        // ปิดการเชื่อมต่อ
        $conn->close();
        
    }

    if(isset($_POST['addStudentProfile'])){
        $user_id = $_SESSION['id'];
        $name = $_POST['name'];
        $student_id = $_POST['student_id'];
        $department = $_POST['department'];
        $email = $_POST['email'];
        $tel = $_POST['tel'];
        $research_topic = $_POST['research_topic'];
        $other_info = $_POST['other_info'];

        $sql = "INSERT INTO student_profile (user_id, name, student_id, department, email, tel, research_topic, other_info)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        // เตรียม statement
        if ($stmt = $conn->prepare($sql)) {
            // ผูกค่าตัวแปรกับคำสั่ง SQL
            $stmt->bind_param("ssssssss", $user_id, $name, $student_id, $department, $email, $tel, $research_topic, $other_info);
            
            // Execute คำสั่ง SQL
            if ($stmt->execute()) {
                header('location: /ThesisAdvisorHub/profile');
            } else {
                echo "Error saving student profile: " . $stmt->error;
            }

            // ปิด statement
            $stmt->close();
        } else {
            echo "Error preparing statement: " . $conn->error;
        }
    }

    if(isset($_POST['deleteStudentProfile'])){
        $user_id = $_SESSION['id'];
        $sql = "DELETE FROM student_profile WHERE '$user_id'";

        if($conn->query($sql)){
            header('location: /ThesisAdvisorHub/profile');
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
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
            <li><a href="/ThesisAdvisorHub/inbox">Inbox</a></li>
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
                <div class='container'>
                    
                    <div class='profile-info'>
                    <img src= '$img' >
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
                        <h3>Research Topic</h3>
                        <p>$research_topic</p>
                        <p>" . nl2br($research_info) . "</p>
                        <h3>Other</h3>
                        <p>" . nl2br($other_info) . "</p>
                        <h3>Number of students available for advising: $student_amount</h3>
                    </div>
                    <form action='' method='post' class='editForm'>
                        <button name='edit' class='edit'>Edit</button>
                        <button name='delete' class='delete'>Delete</button>
                    </form>
                </div>
                ";
            }else{
                echo 
                "
                <form action='' method='post' class='profile-form' enctype='multipart/form-data'>
                    <div class='wrap'>
                        <h2>Advisor Profile</h2>
                        <div class='wrapInput'>
                            <input type='text' placeholder='Name' name='name' required>
                        </div>

                        <div class='wrapInput'>
                            <input type='text' placeholder='Department' name='department' required>
                        </div>
                        
                        <div class='wrapInput'>
                            <input type='email' placeholder='Contact Email' name='email' required>
                        </div>
                        
                        <div class='wrapInput'>
                            <input type='tel' placeholder='Telephone' name='tel' required>
                        </div>
                        
                        <div class='wrapInput'>
                            <input type='text' placeholder='Research Topic' name='research_topic' required>
                        </div>
                        
                        <div class='wrapInput'>
                            <textarea name='research_info' id='' placeholder='Research Information' required></textarea>
                        </div>
                        
                        
                        <div class='wrapInput'>
                            <textarea name='other_info' id='' placeholder='Other' required></textarea>
                        </div>
                        
                        <div class='wrapInput'>
                            <input type='text' placeholder='Number of students available for advising ex. 7/10, 4/6' name='student_amount' required>
                        </div>

                        <div class='wrapInput'>
                            <input type='file' id='fileInput' name='img'>
                            <label for='fileInput' class='file-upload-btn'>Choose Profile Image</label>
                            <p class='file-name' id='fileName'></p>
                        </div>

                        <div class='wrapInput'>
                            <button name='submit'>Add Profile</button>
                        </div>
                        
                    </div>
                </form>
                ";
            }
        }else{
            $sql = "SELECT * FROM student_profile WHERE user_id = '$id'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();

            if(isset($row['id'])){
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
                    <form action='' method='post' class='editForm'>
                        <button name='edit' class='edit'>Edit</button>
                        <button name='deleteStudentProfile' class='delete'>Delete</button>
                    </form>
                </div>
                ";
            }else{
                echo 
                "
                <form action='' method='post' class='profile-form' enctype='multipart/form-data'>
                    <div class='wrap'>
                        <h2>Student Profile</h2>
                        <div class='wrapInput'>
                            <input type='text' placeholder='Name' name='name' required>
                        </div>

                        <div class='wrapInput'>
                            <input type='text' placeholder='Student ID' name='student_id' required>
                        </div>

                        <div class='wrapInput'>
                            <input type='text' placeholder='Department' name='department' required>
                        </div>
                        
                        <div class='wrapInput'>
                            <input type='email' placeholder='Contact Email' name='email' required>
                        </div>
                        
                        <div class='wrapInput'>
                            <input type='tel' placeholder='Telephone' name='tel' required>
                        </div>
                        
                        <div class='wrapInput'>
                            <input type='text' placeholder='Interested research topics' name='research_topic' required>
                        </div>
                        
                        <div class='wrapInput'>
                            <textarea name='other_info' id='' placeholder='Other' required></textarea>
                        </div>
                        
                        <div class='wrapInput'>
                            <button name='addStudentProfile'>Add Profile</button>
                        </div>
                        
                    </div>
                </form>
                ";
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

