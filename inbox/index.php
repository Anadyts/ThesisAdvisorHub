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
    <title>Inbox</title>
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
            <li><a href="/ThesisAdvisorHub/advisor">Advisor</a></li>
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

    <div class="inbox-container">
        <div class="sidebar">
            <div class="sidebar-item active">Inbox</div>
            <div class="sidebar-item">Sent</div>
            <div class="sidebar-item">Drafts</div>
            <div class="sidebar-item">Spam</div>
            <div class="sidebar-item">Trash</div>
        </div>

        <div class="content">
            <div class="header">
                <input type="text" class="search-input" placeholder="Search messages...">
                <button class="btn new-message">New Message</button>
            </div>
            
            <div class="message-list">
                <div class="message">
                    <div class="message-info">
                        <div class="message-sender">John Doe</div>
                        <div class="message-subject">Meeting Tomorrow</div>
                    </div>
                    <div class="message-time">12:30 PM</div>
                </div>
                
                <div class="message">
                    <div class="message-info">
                        <div class="message-sender">Jane Smith</div>
                        <div class="message-subject">Project Update</div>
                    </div>
                    <div class="message-time">2:00 PM</div>
                </div>

                <div class="message">
                    <div class="message-info">
                        <div class="message-sender">Tommy Lee</div>
                        <div class="message-subject">Weekend Plans</div>
                    </div>
                    <div class="message-time">3:45 PM</div>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Naresuan University.</p>
    </footer>
</body>
</html>