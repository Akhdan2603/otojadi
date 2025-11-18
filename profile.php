<!-- <?php
session_start();

require_once('dbcontroller.php');   
$db = new dbcontroller();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['iduser'])) {
    header("location: login.php");
    exit;
}

$user_id = $_SESSION['iduser'];

$sql = "SELECT nama, email, id, telp, alamat, jenis_kelamin, tgl_lahir, poto, peran FROM user WHERE id=$user_id";
$db->runSQL($sql);
$row = $db->getITEM($sql);
$_SESSION['phone'] = $row['telp'];
$_SESSION['address'] = $row['alamat'];
$_SESSION['gender'] = $row['jenis_kelamin'];
$_SESSION['dob'] = $row['tgl_lahir'];
$_SESSION['poto'] = $row['poto'];
$_SESSION['role'] = $row['peran'];
$role = $row['peran'];

if (isset($_POST['submitprofile'])) {
    $name = $_POST['username'];
    $telp = $_POST['phone'];
    $alm = $_POST['loc'];
    $jns = $_POST['gender'];
    $tgl = $_POST['dob'];
    
    $sql_update = "UPDATE user SET nama='$name', telp='$telp', alamat='$alm', jenis_kelamin='$jns', tgl_lahir='$tgl' WHERE id=$user_id";
    $db->runSQL($sql_update);

    $_SESSION['name'] = $name;
    $_SESSION['phone'] = $telp; 
    $_SESSION['address'] = $alm;
    $_SESSION['gender'] = $jns; 
    $_SESSION['dob'] = $tgl;

    header("Location: profile.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['myfile']) && $_FILES['myfile']['error'] === UPLOAD_ERR_OK) {
        $fileName = $_FILES['myfile']['name']; 
        $tempPath = $_FILES['myfile']['tmp_name']; 
        $uploadDir = 'public/images/'; 
        $destinationPath = $uploadDir . $fileName; 

        $sql_update = "UPDATE user SET poto = '$fileName' WHERE id = $user_id";
        $_SESSION['poto'] = $fileName;
        $db->runSQL($sql_update);

        move_uploaded_file($tempPath, $destinationPath);
    }
    header("Location: profile.php");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_search'])) {
    $_SESSION['search'] = trim($_POST['search']);
    header('Location: search.php');
    exit;
}

if (isset($_POST['submit-password'])) {
    $oldPassword = md5($_POST['old-password']);
    $newPassword = md5($_POST['new-password']);
    $confirmPassword = md5($_POST['confirm-password']);

    if ($newPassword !== $confirmPassword) {
        $_SESSION['password_error'] = 'New password and confirmation do not match.';
        $_SESSION['show_popup'] = true; 
    } else {
        $sql = "SELECT password FROM user WHERE id=$user_id";
        $row = $db->getITEM($sql);

        if ($oldPassword !== $row['password']) {
            $_SESSION['password_error'] = 'Old password is incorrect.';
            $_SESSION['show_popup'] = true; 
        } else {
            $sql_update = "UPDATE user SET password='$newPassword' WHERE id=$user_id";
            $db->runSQL($sql_update);
            $_SESSION['password_success'] = 'Password updated successfully!';
            unset($_SESSION['show_popup']); 
            unset($_SESSION['password_error']);
        }
    }
    header("Location: profile.php");
    exit();
}

if (isset($_POST['unset']) && $_POST['unset'] == '1') {
    unset($_SESSION['password_error']);
    unset($_SESSION['show_popup']);
}

?> -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/index copy.css">
    <link rel="icon" href="public/images/logo-otojadi.png" type="image/gif" sizes="16x16">
    <title>Otojadi | Profile</title> 
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body>
    <!-- Navbar -->
    <header class="navbar">
    <div class="nav-left">
      <a href="index.php"><img src="public/images/nav_logo.png" alt="Logo OtoJadi" class="logo"></a>
    </div>

    <form method="POST" class="search-bar">
      <input type="text" id="search" name="search" placeholder="Search templates..." />
      <button type="submit" name="submit_search">
        <i data-lucide="search"></i>
      </button>
    </form>

    <nav>
        <ul>
            <li><a href="myworkspace.php"><i class="fas fa-briefcase"></i>Workspace</a></li>
            <li class="nav-item dropdown">
                 <a href="#" class="dropdown-toggle" id="profileDropdown" role="button" aria-expanded="false">
                    <i class="fas fa-user"></i>Profile
                </a>
                <ul class="dropdown-menu" aria-labelledby="profileDropdown">
                    <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                    <li><a style="color: red;" class="dropdown-item" href="logout.php">Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    
    </header>
  
    

    <div class="container"> 
        <div class="sidebar">
            <div class="profile-container">
                <div class="profile-header">
                    <img src="public/images/<?php echo $_SESSION['poto']; ?>" alt="Profile Picture">
                </div>
                
                <div class="profile-name">
                    <h2><?php echo $_SESSION['name']; ?></h2>
                    <div class="profile-detail">
                        <p><?php echo $_SESSION['email']; ?></p>
                    </div>
                </div>
                <div class="line"></div>
            </div>

            <div class="sidebar-menu">
                <a href="profile.php" class="active">Profile Details</a>
                <a href="favorite.php">Favorite</a>

                <?php if ($_SESSION['role'] == 'admin'): ?>
                        <a href="kelolaproduk.php">Kelola Produk</a>
                <?php endif; ?>
            </div>
        </div>

        <div class="main-content">
            <h2>Profile Details</h2>
            <div class="profile-section">
                <form method="POST" class="profile-form">
                    <div class="input-group">
                        <label for="email">Email</label>
                        <p><?php echo $_SESSION['email']; ?></p>
                    </div>

                    <div class="input-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" required value="<?php echo $row['nama'] ?>">
                    </div>

                    <div class="input-group">
                        <label for="dob">Tanggal Lahir</label>
                        <div class="date"><input type="date" id="dob" name="dob" required value="<?php echo $row['tgl_lahir'] ?>"></div>
                    </div>

                    <div class="input-group">
                        <label for="phone">Nomor Telepon</label>
                        <input type="tel" id="phone" name="phone" required value="<?php echo $row['telp'] ?>">
                    </div>
                        
                    <div class="input-group-gender">
                        <div class="gender">
                            <label for="gender">Jenis Kelamin</label>
                        </div>
                        <div class="option">
                            <div>
                                <input type="radio" id="gender" name="gender" value="Male"<?php if ($row['jenis_kelamin']=='Male') echo ' checked="checked"';?>>
                                <label for="gender">Male</label>
                            </div>
                            <div>
                                <input type="radio" id="gender2" name="gender" value="Female"<?php if ($row['jenis_kelamin']=='Female') echo ' checked="checked"';?>>
                                <label for="gender2">Female</label>
                            </div>
                            <div>
                                <input type="radio" id="gender3" name="gender" value="Rather not disclose"<?php if ($row['jenis_kelamin']=='Rather not disclose') echo ' checked="checked"';?>>
                                <label for="gender3">Rather not disclose</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="input-group">
                        <label for="loc">Alamat</label>
                        <input type="text" id="loc" name="loc"required value="<?php echo $row['alamat'] ?>">
                    </div>

                    <div class="submit">
                        <button type="submit" name="submitprofile">Save Changes</button>
                    </div>
                </form>
                <div class="profile-picture-password">
                    <div class="profile-picture">
                        <Form method="POST" enctype="multipart/form-data">
                            <h2 style="font-size: 24px;">Profile Picture</h2>
        
                            <img src="public/images/<?php echo htmlspecialchars ($row['poto']) ?>" alt="<?php echo htmlspecialchars ($row['poto']) ?>">
                            <button type="button" class="upload-button">
                                <i class="fas fa-upload"></i> Change
                                <input type="file" id="myfile" name="myfile" onchange="this.form.submit();">
                            </button>
                        </Form>
                    </div>    

                    <div class="password">
                        <button type="button" id="change-password">Change Password</button>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <div class="popup" style="display: <?php echo isset($_SESSION['show_popup']) && $_SESSION['show_popup'] ? 'flex' : 'none'; ?>;">
        <div class="popup-content">
            <h2>Change Password</h2>
            <i class="fas fa-times close" style="color:#fff"></i>

            <?php if (isset($_SESSION['password_error'])): ?>
            <p class="error-message"><?php echo $_SESSION['password_error']; ?></p>
            <?php endif; ?>

            <form method="POST">
                
                <input type="password" id="old-password" placeholder="Old Password" name="old-password" required >
                <input type="password" id="new-password" placeholder="New Password" name="new-password" required>
                <input type="password" id="confirm-password" placeholder="Confirm New Password" name="confirm-password" required>
                
                <button type="submit" name="submit-password">Save Changes</button>
            </form>

            <form id="unset-session-form" method="POST" style="display:none;">
                <input type="hidden" name="unset" value="1">
            </form>
        </div>  
    </div>

    <!-- Footer -->
  <footer class="footer-otojadi">
        <div class="footer-top">
        <div class="footer-links">
            <a href="index.php">Home</a>
            <span>|</span>
            <a href="#">Help Center</a>
            <span>|</span>
            <a href="#">License Terms</a>
            <span>|</span>
            <a href="#">Terms & Conditions</a>
        </div>

        <div class="footer-social">
            <a href="https://www.instagram.com/otojadi/"><i data-lucide="instagram"></i></a>
            <a href="#"><i data-lucide="twitter"></i></a>
            <a href="https://www.facebook.com/profile.php?id=61582317293988"><i data-lucide="facebook"></i></a>
        </div>
        </div>

        <p class="footer-copy">
        © 2025 OtoJadi. Trademarks and brands are the property of their respective owners.
        </p>
    </footer>


</body>
</html>

<script>
    lucide.createIcons();
    
    document.getElementById('change-password').addEventListener('click', function() {
        document.querySelector('.popup').style.display = 'flex';
    });

    document.querySelector('.close').addEventListener('click', function() {
        document.querySelector('.popup').style.display = 'none';
        document.getElementById('unset-session-form').submit();
    });

    document.addEventListener('DOMContentLoaded', function() {
    const dropdown = document.getElementById('profileDropdown');
    const dropdownMenu = dropdown.nextElementSibling;
        
        dropdown.addEventListener('click', function(e) {
            e.preventDefault();
            dropdownMenu.classList.toggle('show');
        });
        
        document.addEventListener('click', function(e) {
            if (!dropdown.contains(e.target) && !dropdownMenu.contains(e.target)) {
                dropdownMenu.classList.remove('show');
            }
        });
    });
</script>
