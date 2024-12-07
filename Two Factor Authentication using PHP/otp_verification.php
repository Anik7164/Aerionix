<?php
session_start();

include 'conn.php';
if (!isset($_SESSION['temp_user'])) {
    header("Location: index.php");
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_otp = $_POST['otp'];
    $stored_otp = $_SESSION['temp_user']['otp'];
    $user_id = $_SESSION['temp_user']['id'];

    $sql = "SELECT * FROM users WHERE id='$user_id' AND otp='$user_otp'";
    $query = mysqli_query($conn, $sql);
    $data = mysqli_fetch_array($query);

    if ($data) {
        $otp_expiry = strtotime($data['otp_expiry']);
        if ($otp_expiry >= time()) {
            $_SESSION['user_id'] = $data['id'];
            unset($_SESSION['temp_user']);
            header("Location: ..\page\index.html");
            exit();
        } else {
            ?>
                <script>
    alert("OTP has expired. Please try again.");
    function navigateToPage() {
        window.location.href = 'index.php';
    }
    window.onload = function() {
        navigateToPage();
    }
</script>
            <?php 
        }
    } else {
        echo "<script> alert('Invalid OTP. Please try again.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Two-Step Verification</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background-image: url('Untitled design.png'); 
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
            justify-content: center;
            height: 100vh;
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
            background: rgba(0, 0, 0, 0.85);
            box-shadow: 0 0 30px rgba(0, 255, 255, 0.8), 0 0 60px rgba(0, 255, 255, 0.5);
            border-radius: 15px;
            padding: 40px;
            width: 400px;
            text-align: center;
            margin-top: 80px;
        }
        #container:hover {
            transform: scale(1.05);
        }

        h1 {
            font-size: 24px;
            color: #00ffff;
            text-shadow: 0 0 5px #00ffff;
        }

        p {
            font-size: 16px;
            color: #fff;
            margin-bottom: 20px;
        }

        input[type=number] {
            width: 100%;
            padding: 15px;
            margin-top: 10px;
            border: none;
            border-radius: 5px;
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            font-size: 18px;
            outline: none;
            box-shadow: 0 0 10px rgba(0, 255, 255, 0.3);
        }

        button {
            background: linear-gradient(90deg, #00ffcc, #007bff);
            border: none;
            width: 120px;
            padding: 12px;
            color: white;
            font-weight: bold;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease-in-out;
        }
        
        button:hover {
            background: linear-gradient(90deg, #007bff, #00ffcc);
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <header>
        <h1>Two-Step Verification</h1>
    </header>
    <div id="container">
        <h1>Verify Your Identity</h1>
        <p>Enter the 6-digit OTP code sent to your email address: <strong><?php echo $_SESSION['email']; ?></strong></p>
        <form method="post" action="otp_verification.php">
            <label style="font-weight: bold; font-size: 18px;" for="otp">Enter OTP Code:</label>
            <input type="number" id="otp" name="otp" pattern="\d{6}" placeholder="Six-Digit OTP" required>
            <br><br>
            <button type="submit">Verify OTP</button>
        </form>
    </div>
</body>
</html>

