<?php 
    session_start();
    require_once "dbcontroller.php";
    $db = new dbcontroller;
?>

<?php
$notification = '';
$registration_success=false;
if (isset($_POST['reg'])) {
    $name = $_POST['username'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $konfirmasi = md5($_POST['konfirmasi']);

    $checkEmail = "SELECT email FROM user WHERE email = '$email'";
    $result = $db->rowCOUNT($checkEmail);

    if ($result > 0) {
        $notification = 'Email is already registered. Please use a different email.';
    } 
    else {
    if ($password === $konfirmasi) {
            $sql = "INSERT INTO user (nama, email, password, peran) VALUES ('$name', '$email', '$password', 'user')";
            $db->runSQL($sql);
        if ($db->getAffectedRows() > 0) {
            $registration_success=True;
            $notification = '<span>Registration successful.</span><a href="login.php"> You can login now</a>.';
            } 
            else {
                $notification = 'Registration failed. Please try again.';
            }
        } else 
        {
            $notification ='Maaf password anda tidak sesuai dengan yang dimasukan';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Register</title>
        <link rel="stylesheet" href="css/styles.css">
        <link rel="icon" href="public/images/logo-otojadi.png" type="image/gif" sizes="16x16">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    </head>

    <body class="body-register">

        <!-- <div class="header">
            <a href="login.php"><button class="btn-popup">Login</button></a>
            <a href="register.php"><button class="btn-popup">Register</button></a>
        </div> -->

        <div class="container">

            <div class="form-container">
                <p class="register-login">Start for <span style="color: #46D2C4;">free</span></p>
                <h2 style="font-size: 45px;">Create New Account<span style="color: #46D2C4;">.</span></h2>
                <div class="login">
                    <p class="register-login">Already have an account? <a href="login.php">Login</a></p>
                    <?php if (!empty($notification)): ?>
                        <p class="notification"><?php echo $notification; ?></p> 
                    <?php endif; ?>
                    <?php if ($registration_success): ?>
                        <meta http-equiv="refresh" content ="2; url=login.php"/>
                    <?php endif; ?>
                </div>

                <form action="register.php" method="POST">

                    <div class="input-group">
                        <label for="username"><i class="fas fa-user"></i></label>
                        <input type="text" id="username" name="username" placeholder="Username" required>
                    </div>

                    <div class="input-group">
                        <label for="email"><i class="fas fa-envelope"></i></label>
                        <input type="email" id="email" name="email" placeholder="Email" required>
                    </div>

                    <div class="input-group">
                        <label for="password"><i class="fas fa-lock"></i></label>
                        <input type="password" id="password" name="password" placeholder="Password" required>
                    </div>

                    <div class="input-group">
                        <label for="password"><i class="fas fa-lock"></i></label>
                        <input type="password" id="konfirmasi" name="konfirmasi" placeholder="Reconfirm Password" required>
                    </div>

                    <button type="submit" class="register-button" name="reg">Register</button>

                </form>

            </div>

            <div class="image-placeholder">
                <!-- <h1>Welcome to</h1> -->
                <img src="public\images\logo-otojadi.png" alt="Logo">
            </div>

        </div>
        
    </body>
    

</html>

