<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

// Establish database connection
$db_host = "localhost";
$db_user = "root";
$db_pass = ""; // Replace with your database password
$db_name = "myapp";

// Create connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle password update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_SESSION["username"];
    $new_password = $_POST["new_password"];

    // Validate the new password (example: at least 8 characters)
    if (strlen($new_password) < 8) {
        echo "Password must be at least 8 characters long.";
    } else {
        
        // Update password and last_password_update date using prepared statements
        $current_date = date("Y-m-d");
        $stmt = $conn->prepare("UPDATE users SET password = ?, last_password_update = ? WHERE emp_name = ?");
        $stmt->bind_param("sss", $new_password, $current_date, $username);

        if ($stmt->execute()) {
            echo "Password updated successfully. Redirecting to home page...";
            header("refresh:2;url=user/home.php");
            exit();
        } else {
            echo "Error updating password: " . $stmt->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Password</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css?family=Raleway:400,700');
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Raleway, sans-serif;
        }
        body {
            background: linear-gradient(90deg, #a63995, #776BCC);
        }
        .container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .screen {
            background: linear-gradient(90deg, #a63995, #16222A);
            position: relative;
            height: 600px;
            width: 380px;
            box-shadow: 0px 0px 24px #5C5696;
        }
        .screen__content {
            z-index: 1;
            position: relative;
            height: 100%;
        }
        .screen__background {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 0;
            -webkit-clip-path: inset(0 0 0 0);
            clip-path: inset(0 0 0 0);
        }
        .screen__background__shape {
            transform: rotate(45deg);
            position: absolute;
        }
        .screen__background__shape1 {
            height: 520px;
            width: 520px;
            background: #FFF;
            top: -50px;
            right: 120px;
            border-radius: 0 72px 0 0;
        }
        .screen__background__shape2 {
            height: 220px;
            width: 220px;
            background: linear-gradient(270deg, #6C63AC, #a63995);
            top: -172px;
            right: 0;
            border-radius: 32px;
        }
        .screen__background__shape3 {
            height: 540px;
            width: 190px;
            background: linear-gradient(270deg, #776BCC, #a63995);
            top: -24px;
            right: 0;
            border-radius: 32px;
        }
        .screen__background__shape4 {
            height: 400px;
            width: 200px;
            background: linear-gradient(270deg, #7E7BB9, #a63995);
            top: 420px;
            right: 50px;
            border-radius: 60px;
        }
        .login {
            width: 320px;
            padding: 30px;
            padding-top: 156px;
        }
        .login__field {
            padding: 20px 0px;
            position: relative;
        }
        .login__icon {
            position: absolute;
            top: 30px;
            color: #7875B5;
        }
        .login__input {
            border: none;
            border-bottom: 2px solid #D1D1D4;
            background: none;
            padding: 10px;
            padding-left: 24px;
            font-weight: 700;
            width: 75%;
            transition: .2s;
        }
        .login__input:active,
        .login__input:focus,
        .login__input:hover {
            outline: none;
            border-bottom-color: #6A679E;
        }
        .login__submit {
            background: #a63995;
            font-size: 18px;
            margin-top: 30px;
            padding: 10px 16px;
            border-radius: 26px;
            border: 1px solid #D4D3E8;
            text-transform: uppercase;
            font-weight: 700;
            display: flex;
            align-items: center;
            width: 100%;
            color: black;
            box-shadow: 0px 2px 2px #5C5696;
            cursor: pointer;
            transition: .2s;
        }
        .login__submit:active,
        .login__submit:focus,
        .login__submit:hover {
            border-color: #6A679E;
            outline: none;
        }
        .button__icon {
            font-size: 24px;
            margin-left: auto;
            color: #7875B5;
        }
        .social-login {
            position: absolute;
            height: 140px;
            width: 160px;
            text-align: center;
            bottom: 0px;
            right: 0px;
            color: #fff;
        }
        .social-icons {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .social-login__icon {
            padding: 20px 10px;
            color: #fff;
            text-decoration: none;
            text-shadow: 0px 0px 8px #7875B5;
        }
        .social-login__icon:hover {
            transform: scale(1.5);
        }
        .container {
            width: 50%;
            margin: auto;
            padding: 20px;
        }
        h3 {
            font-family: cursive;
            font-size: 30px;
            color: #333;
            margin-top: -800px;
            margin-left: -10px;
            justify-content: center;
        }
        .image-container {
            justify-content: center;
            align-items: center;
            max-width: 600px;
            margin: 0 auto;
            position: absolute;
        }
        .image-container img {
            width: 400px;
            height: 60px; /* added to maintain aspect ratio */
            margin-top: -650px;
            margin-left: -10px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="screen">
        <div class="screen__content">
            <div class="container image-container">
                <img src="https://cdn.animaapp.com/projects/65cb38660ad29ada46aa26c5/releases/65cd9c125e94c95439019ca9/img/thumbnail-final-logo-removebg-preview--1--1.png" alt="Lock" class="lock">
            </div>
            <form class="login" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <h4 style="text-transform: uppercase; font-size:27px; font-family:Abolition; margin-top: 10px; color:black;">Update Password</h4>
                <div class="login__field">
                    <i class="login__icon fas fa-user"></i>
                    <input type="password" class="login__input" id="new_password" placeholder="New Password" name="new_password" required>
                </div>
                <div class="login__field">
                    <button type="submit" class="button login__submit">Update Password
                        <i class="button__icon fas fa-chevron-right"></i>
                    </button>
                </div>
            </form>
            <div class="social-login">
                <div class="social-icons">
                    <a href="#" class="social-login__icon fab fa-instagram"></a>
                    <a href="#" class="social-login__icon fab fa-facebook"></a>
                    <a href="#" class="social-login__icon fab fa-twitter"></a>
                </div>
            </div>
        </div>
        <div class="screen__background">
            <span class="screen__background__shape screen__background__shape4"></span>
            <span class="screen__background__shape screen__background__shape3"></span>
            <span class="screen__background__shape screen__background__shape2"></span>
            <span class="screen__background__shape screen__background__shape1"></span>
        </div>
    </div>
</div>
</body>
</html>
