<?php
session_start();
include 'conn.php';

if (isset($_SESSION['user_id'])) {
    header("Location: ..\page\index.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash the password

    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";
    $query = mysqli_query($conn, $sql);

    if ($query) {
        ?>
            <script>
    alert("Registration Successful.");
    function navigateToPage() {
        window.location.href = 'index.php';
    }
    window.onload = function() {
        navigateToPage();
    }
</script>
        <?php 
    } else {
       echo "<script> alert('Registration Failed. Try Again');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aerionix Registration</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background-image: url('aircraft-4053232.jpg'); 
            background-size:cover ;
            background-position: center;
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

        header {
            width: 100%;
            background: linear-gradient(90deg, #00ffcc, #007bff);
            padding: 20px 0;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
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
            background: rgba(0, 0, 0, 0.85);
            box-shadow: 0 0 30px rgba(0, 255, 255, 0.8), 0 0 60px rgba(0, 255, 255, 0.5);
            border-radius: 15px;
            padding: 40px;
            width: 400px;
            text-align: center;
            margin-top: 120px;
            transition: transform 0.3s ease-in-out;
        }

        #container:hover {
            transform: scale(1.05);
        }

        label {
            font-size: 18px;
            color: #00ffff;
            display: block;
            margin-bottom: 10px;
        }

        input[type=text], input[type=password] {
            width: 100%;
            height: 40px;
            padding: 10px;
            margin-bottom: 20px;
            border: none;
            border-radius: 5px;
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            font-size: 16px;
            outline: none;
            box-shadow: 0 0 10px rgba(0, 255, 255, 0.3);
        }

        input[type=submit] {
            width: 100%;
            background: linear-gradient(90deg, #00ffcc, #007bff);
            border: none;
            color: white;
            font-weight: bold;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease-in-out;
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

        p {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Aerionix International</h1>
    </header>
    <div id="container">
        <form method="post" action="registration.php">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" placeholder="Enter Username" required>

            <label for="email">Email:</label>
            <input type="text" id="email" name="email" placeholder="Enter Your Email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter Password" required>

            <input type="submit" name="register" value="Register">
            
            <p>Already have an account? <a href="index.php">Login</a></p>
        </form>
    </div>
</body>
</html>
