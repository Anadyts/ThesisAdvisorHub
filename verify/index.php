<?php
    require('../server.php');
    if (isset($_GET['token'])) {
        $token = $_GET['token'];

        // ใช้ UNION ค้นหาในทั้งสองตาราง
        $sql = "SELECT * FROM advisor WHERE token = ? AND verified = 0 
                UNION 
                SELECT * FROM student WHERE token = ? AND verified = 0";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $token, $token); // ผูกโทเค็นกับ ? ทั้งสองจุด
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // ยืนยันอีเมล
            $row = $result->fetch_assoc();

            if ($row['role'] === 'advisor') {
                $updateSql = "UPDATE advisor SET verified = 1, token = NULL WHERE token = ?";
            } else {
                $updateSql = "UPDATE student SET verified = 1, token = NULL WHERE token = ?";
            }

            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param('s', $token);
            $updateStmt->execute();

            $_SESSION['success'] = "Your email has been verified! You can now log in.";
            header('location: /ThesisAdvisorHub/login');
            exit();
        } else {
            $_SESSION['error'] = "Invalid or expired token.";
            header('location: /ThesisAdvisorHub/register');
            exit();
        }
    }

?>
