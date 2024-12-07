<?php
session_start();
include 'conn.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';

if (isset($_SESSION['user_id'])) {
    header("Location: .\page\index.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $_SESSION['email']=$email;

    $sql = "SELECT * FROM users WHERE email='$email'";
    $query = mysqli_query($conn, $sql);
    $data = mysqli_fetch_array($query);

    if ($data && password_verify($password, $data['password'])) {
        $otp = rand(100000, 999999);
        $otp_expiry = date("Y-m-d H:i:s", strtotime("+3 minute"));
        $subject= "Your OTP for Login";
        $message="Your OTP is: $otp";

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'anikmondal340@gmail.com'; //host email 
        $mail->Password = 'llcztliolgphxjej'; // app password of your host email
        $mail->Port = 465;
        $mail->SMTPSecure = 'ssl';
        $mail->isHTML(true);
        $mail->setFrom('anikmondal340@gmail.com', 'Aerionix System');//Sender's Email & Name
        $mail->addAddress($email,$name); //Receiver's Email and Name
        $mail->Subject = ("$subject");
        $mail->Body = $message;
        $mail->send();

        $sql1 = "UPDATE users SET otp='$otp', otp_expiry='$otp_expiry' WHERE id=".$data['id'];
        $query1 = mysqli_query($conn, $sql1);

        $_SESSION['temp_user'] = ['id' => $data['id'], 'otp' => $otp];
        header("Location: otp_verification.php");
        exit();
    } else {
        ?>
        <script>
           alert("Invalid Email or Password. Please try again.");
                function navigateToPage() {
                    window.location.href = 'index.php';
                }
                window.onload = function() {
                    navigateToPage();
                }
        </script>
        <?php 
    
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aerionix Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            
            background-repeat: no-repeat; 
            font-family: 'Poppins', sans-serif;
            color: #fff;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100vh;
        }
        .video-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
        }

        header {
            width: 100%;
            padding: 20px 0;
            background: linear-gradient(90deg, #00ffcc, #007bff);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
        }

        header h1 {
            margin: 0;
            font-size: 36px;
            color: #fff;
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.8);
        }

        #container {
            background: rgba(0, 0, 0, 0.8);
            box-shadow: 0 0 30px rgba(0, 255, 255, 0.8), 0 0 60px rgba(0, 255, 255, 0.5);
            border-radius: 15px;
            padding: 40px;
            width: 400px;
            text-align: center;
            margin-top: 100px;
            transition: transform 0.3s ease-in-out;
        }
        #container:hover {
            transform: scale(1.05);
        }

        label, input[type=text], input[type=password], input[type=submit], a, p {
            display: block;
            margin-bottom: 20px;
        }

        input[type=text], input[type=password] {
            width: 100%;
            height: 40px;
            padding: 10px;
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            border: none;
            border-radius: 5px;
            outline: none;
            box-shadow: 0 0 10px rgba(0, 255, 255, 0.3);
        }

        input[type=submit] {
            background: linear-gradient(90deg, #00ffcc, #007bff);
            color: #fff;
            font-weight: bold;
            cursor: pointer;
            border-radius: 5px;
            padding: 10px;
            border: none;
        }

        input[type=submit]:hover {
            background: linear-gradient(90deg, #007bff, #00ffcc);
        }

        a {
            color: #00ffff;
            font-size: 16px;
            text-decoration: none;
        }

        a:hover {
            color: #00ffcc;
            text-shadow: 0 0 5px #00ffcc;
        }
    </style>
</head>
<body>
    <!-- Background Video -->
<video autoplay muted loop class="video-background">
    <source src="9512135-hd_1920_1080_25fps.mp4" type="video/mp4">
    Your browser does not support the video tag.
</video>

    <header>
        <h1>Aerionix International</h1>
    </header>
    <div id="container">
        <form method="post" action="index.php">
            <label for="email">Email</label>
            <input type="text" id="email" name="email" placeholder="Enter Your Email" required>
            
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter Your Password" required>
            
            <input type="submit" name="login" value="Login">
            
            <p>Don't have an account? <a href="registration.php">Sign Up</a></p>
        </form>
    </div>
</body>
</html>
