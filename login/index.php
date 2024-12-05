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
            <img src="Logo.jpg" alt="" width="90px">
        </div>
        <ul>
            <li><a href="/ThesisAdvisorHub/home">Home</a></li>
            <li><a href="/ThesisAdvisorHub/advisor">Advisor</a></li>
            <li><a href="/ThesisAdvisorHub/login">Login</a></li>
            <li><a href="/ThesisAdvisorHub/register">Register</a></li>
        </ul>
    </nav>
    
    <div class="wrap">
        <form action="" method="post">
            <i class='bx bxs-user-circle' ></i>
            <div class="inputWrap">
                <input type="text" placeholder="Username" name="username" required> 
            </div>

            <div class="inputWrap">
                <input type="password" placeholder="Password" name="password" required> 
            </div>

            <div class="buttonWrap">
                <button name="login">Login</button>
            </div>

            <div class="errorMessage">
                <?php
                    if(isset($_SESSION['error'])){
                        echo "<h3> {$_SESSION['error']}</h3>";
                        unset($_SESSION['error']);
                    }
                ?>
            </div>
        </form>
    </div>
</body>
</html>