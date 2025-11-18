<!-- <?php
 ob_start();
    session_start();
    require_once "dbcontroller.php";
    $db = new dbcontroller;

    require_once 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$clientID = $_ENV['CLIENT_ID'];
$clientSecret = $_ENV['CLIENT_SECRET'];
$redirectUri = $_ENV['REDIRECT_URI'];

$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");


if (isset($_GET['code'])) {
  $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
  $client->setAccessToken($token['access_token']);

    $google_oauth = new Google_Service_Oauth2($client);
    $google_account_info = $google_oauth->userinfo->get();

    $email =  $google_account_info->email;
    $name =  $google_account_info->name;
    $token=$token['access_token'];
    $sql_check = "SELECT * FROM user WHERE email='$email'";
    $count = $db->rowCOUNT($sql_check);

    if ($count == 0) {
        
        $sql_insert = "INSERT INTO user (nama, email, password) VALUES ('$name', '$email', '$token')";
        $db->runSQL($sql_insert);
        
        $sql_get_id = "SELECT id, peran FROM user WHERE email = '$email'";
        $user = $db->getITEM($sql_get_id);
        $userId = $user['id'];
    } else {
        
        $sql_get_id = "SELECT id, peran FROM user WHERE email = '$email'";
        $user = $db->getITEM($sql_get_id);
        $userId = $user['id'];
    }
    
    $_SESSION['email'] = $email;
    $_SESSION['name'] = $name;
    $_SESSION['iduser'] = $userId;
    $_SESSION['role'] = $user['peran'];
    $_SESSION['loggedin'] = true; 
    
    if ($user['peran'] == 'admin') {
        header("Location: kelolaproduk.php");
    } else {
        header("Location: index.php");
    }
    exit();

} 
?>

<?php
if (isset($_POST['log'])) {
    $email = $_POST['email']; 
    $password = md5($_POST['password']);
    $notification = '';
    
    // NOTE: This secret key should be stored in the .env file with the others!
    $recaptchaSecret = '6Le0Zm8rAAAAAFLYNIgZbNooYkRJROhcmipAUpjm';
    $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';
    
    if (empty($recaptchaResponse)) {
        $notification = 'Please complete the reCAPTCHA verification.';
    } else {
        $recaptchaVerify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptchaSecret}&response={$recaptchaResponse}");
        $recaptchaData = json_decode($recaptchaVerify);
        
        // **<< ADD THIS CRITICAL CHECK >>**
        if ($recaptchaData->success) {
            
            $sql = "SELECT * FROM user WHERE email='$email' AND password='$password'";
            $count = $db->rowCOUNT($sql);

            if ($count == 0) {
                $notification = 'Email atau password salah';
            } else {
                $sql = "SELECT * FROM user WHERE email='$email' AND password='$password'";
                $row = $db->getITEM($sql);

                $_SESSION['email'] = $row['email'];
                $_SESSION['name'] = $row['nama'];
                $_SESSION['iduser'] = $row['id'];
                $_SESSION['role'] = $row['peran'];
                $_SESSION['poto'] = $row['poto'];
                $_SESSION['address'] = $row['alamat'];
                $_SESSION['loggedin'] = true; 
                
                if ($row['peran'] == 'admin') {
                    header("Location: kelolaproduk.php");
                } else {
                    header("Location: index.php"); // <-- This is where non-admin users go
                }
                exit();
            }
        } else {
            // ReCAPTCHA failed its server-side verification
            $notification = 'reCAPTCHA verification failed. Please try again.';
        }
    }
    
}
?> -->

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="public/images/logo-otojadi.png" type="image/gif" sizes="16x16">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

</head>

<body>
    <div class="container">

        <div class="form-container">

            <p class="register-login">Explore our <a href="index.php" style="color: #46D2C4; text-decoration: none;"> Otojadi</a></p>
            <h2>Time to Login<span style="color: #46D2C4;">.</span></h2>
            <p class="register-login">Don't have an account? <a href="register.php">Register</a></p>

            <form action="login.php" method="POST">

                <div class="input-group login">
                    <label for="email"><i class="fas fa-envelope"></i></label>
                    <input type="email" id="email" name="email" placeholder="Email" required>
                </div>
               
                <div class="input-group login">
                    <label for="password"><i class="fas fa-lock"></i></label>
                    <input type="password" id="password" name="password" placeholder="Password" required>
                </div>
                <div class="captcha-submit">
                    <div class="input-group-captcha">
                    <div class="g-recaptcha" data-theme="light" data-sitekey="6Le0Zm8rAAAAAOPturYMvc8fHc7nlLNilEh9i6t6" data-callback="enableSubmit"></div>
                    </div>
                    <button type="submit" class="login-button" name="log">Login</button> 
                </div>
                    
           
            </form>

            <div class="separator">
                <hr>
                <span>or login with</span>
                <hr>
            </div>

            <div class="google-login">
                
            <a href="<?php echo $client->createAuthUrl(); ?>">
                <button type="button" class="google-button">
                    <img src="public\images\google-icon.webp" alt="Google Logo" margin-right="5" height="20">Google
                </button>
            </a>
            </div>

            
            <!-- <p class="register-login">Don't have an account? <a href="register.php">Register</a></p> -->

            <?php if (!empty($notification)): ?>
                <p class="notification"><?php echo $notification; ?></p> 
            <?php endif; ?>

        </div>       
        
        <div class="image-placeholder">
            <!-- <h1>Welcome to</h1> -->
            <img src="public\images\logo-otojadi.png"  alt="Logo">
        </div>

    </div>
    
    
</body>
</html>

<script>
    function enableSubmit() {
        console.log("reCAPTCHA verified successfully");
    }
    
    document.getElementById('loginForm').addEventListener('submit', function(event) {
        const recaptchaResponse = grecaptcha.getResponse();
        console.log("reCAPTCHA response length:", recaptchaResponse.length);
        // if (!recaptchaResponse) {
        //     event.preventDefault();
        //     alert('Please complete the reCAPTCHA verification.');
        // }
    });
</script>