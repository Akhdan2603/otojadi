<?php
session_start();

require_once('dbcontroller.php');   
$db = new dbcontroller();

$user_id = $_SESSION['iduser'];

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

$db = new dbcontroller();
$sql = "
    SELECT 
        product.id,
        product.nama_produk,
        product.gambar,
        kategori.kategori
    FROM favorite
    INNER JOIN product ON favorite.id_barang = product.id
    LEFT JOIN kategori ON product.id_kategori = kategori.id
    WHERE favorite.id_user = {$user_id}
";
$products = $db->getALL($sql);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_search'])) {
    $_SESSION['search'] = trim($_POST['search']);
    header('Location: search.php');
    exit;
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/index copy.css">
    <link rel="icon" href="public/images/logo-otojadi.png" type="image/gif" sizes="16x16">
     <script src="https://unpkg.com/lucide@latest"></script>
    <title>VirtualZone | Favorite</title> 
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
                <a href="profile.php" >Profile Details</a>
                <a href="Favorite.php" class="active">Favorite</a>
        

                <?php if ($_SESSION['role'] == 'admin'): ?>
                        <a href="kelolaproduk.php">Kelola Produk</a>
                <?php endif; ?>
            </div>
        </div>

        <div class="wishlist-content">
            <h2>Favorite</h2>

            <div class="template-grid">
                <?php foreach ($products as $item): ?>
                    <div class="card">
                    <a href="detailproduk.php?id=<?= $item['id'] ?>" class="card-link">
                        <?php 
                        $mainImage = explode(',', $item['gambar'])[0]; 
                        ?>
                        <img src="public/images/produk/<?= htmlspecialchars(trim($mainImage)); ?>" 
                            alt="<?= htmlspecialchars($item['nama_produk']); ?>">
                    </a>
                    <div class="favorite">                 
                        <?php $isInFavorite = $db->isInFavorite($_SESSION['iduser'], $item['id']); ?>
                        <i id="heart-icon-<?php echo $item['id']; ?>" 
                            class="fas fa-heart" 
                            style="color: <?php echo $isInFavorite ?  'rgb(255, 0, 0)' : "rgb(155, 155, 155)"; ?>; font-size: 25px; cursor: pointer;"
                            onclick="toggleFavorite(<?php echo $_SESSION['iduser']; ?>, <?php echo $item['id']; ?>)">
                        </i>               
                    </div>
                    <h3><?= $item['nama_produk'] ?></h3>
                    <button>Add to Workspace</button>
                    </div>
                <?php endforeach; ?>
            </div>

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

<script>
    lucide.createIcons();

    function toggleFavorite(userId, itemId) {
        const icon = document.getElementById(`heart-icon-${itemId}`);
        const isInFavorite = icon.style.color === "rgb(255, 0, 0)"; 

        fetch('toggle-favorite.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                userId: userId,
                itemId: itemId,
                action: isInFavorite ? 'remove' : 'add'
            }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                icon.style.color = isInFavorite ? "rgb(155, 155, 155)" : "rgb(255, 0, 0)";
            } else {
                alert('Failed to update favorite: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

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
</body>
</html>