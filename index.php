<?php
session_start();
require_once "dbcontroller.php";

$isLoggedIn = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
$user_id = $isLoggedIn ? $_SESSION['iduser'] : null;
$peran = $isLoggedIn && isset($_SESSION['role']) ? $_SESSION['role'] : null;


$db = new dbcontroller();

$subscriptionMessage = '';
if ($isLoggedIn) {
    $subSQL = "SELECT is_subscribed, subscription_end 
               FROM user 
               WHERE id = $user_id";
    $subResult = $db->getALL($subSQL);

    if ($subResult && count($subResult) > 0) {
        $subData = $subResult[0];
        $today = date("Y-m-d");

        if ($subData['is_subscribed'] == 1 && !empty($subData['subscription_end']) && $subData['subscription_end'] >= $today) {
            $subscriptionMessage = "✅ Subscription aktif sampai " . date("d M Y", strtotime($subData['subscription_end']));
        }
    }
}

$sql = "SELECT 
        product.id,
        product.nama_produk,
        product.gambar,
        kategori.kategori AS kategori
        FROM product
        LEFT JOIN kategori 
        ON product.id_kategori = kategori.id
        ORDER BY product.created_at DESC
        LIMIT 6";
$products = $db->getALL($sql);


$sqlKategori = "
    SELECT 
        kategori.id,
        kategori.kategori,
        (
            SELECT product.gambar 
            FROM product 
            WHERE product.id_kategori = kategori.id 
            ORDER BY product.created_at DESC 
            LIMIT 1
        ) AS gambar,
        COUNT(product.id) AS total_produk
    FROM kategori
    LEFT JOIN product ON product.id_kategori = kategori.id
    GROUP BY kategori.id
    HAVING total_produk > 0
    ORDER BY total_produk DESC
    LIMIT 6
";
$kategoriList = $db->getALL($sqlKategori);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_search'])) {
    $_SESSION['search'] = trim($_POST['search']);
    header('Location: search.php');
    exit;
}

?>


<!DOCTYPE html>
<html lang="id">
<head><link rel="stylesheet" href="index.css">
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>OtoJadi Template Marketplace</title>
  <link rel="stylesheet" href="css/index copy.css" />
  <link rel="icon" href="public\images\logo-otojadi.png" type="image/gif" sizes="16x16">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
            <?php if ($isLoggedIn): ?>
                <li><a href="myworkspace.php"><i class="fas fa-briefcase"></i>Workspace</a></li>
                <li class="nav-item dropdown">
                    <a href="#" class="dropdown-toggle" id="profileDropdown" role="button" aria-expanded="false">
                        <i class="fas fa-user"></i>Profile
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="profileDropdown">
                        <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <li><a class="dropdown-item" href="kelolaproduk.php">Admin</a></li>
                        <?php endif; ?>
                        <li><a style="color: red;" class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </li>
            <?php else: ?>
                <li><a href="#" onclick="showLoginPrompt()"><i class="fas fa-briefcase"></i>Workspace</a></li>
                <li><a href="login.php"><i class="fas fa-sign-in-alt"></i>Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>
    
  </header>

  <!-- Announcement Bar -->
 
    <section class="announcement">
        <?php if ($subscriptionMessage): ?>
            <p><?= $subscriptionMessage ?></p>
        <?php else: ?>
            <p>🎉 Get access to all premium templates by subscribing now! 
                <?php if ($isLoggedIn): ?>         
                <a href="subscribe.php">Subscribe Now</a>
                <?php else: ?>
                <a href="#" onclick="showLoginPrompt()">Subscribe Now</a>
                <?php endif; ?>
            </p>
        <?php endif; ?>
    </section>


  <!-- Hero Section -->
  <section class="hero">
    <h1>Find the Best Design Templates</h1>
    <p>Choose from various categories such as Education, Business, and Creative for more engaging design.</p>
  </section>

  <!-- Category Section -->
  <section class="categories">
    <h2>Template Type</h2>
      <div class="category-grid">

        <!-- Education -->
        <a href="search.php?categories[]=1" class="category-card">
          <i data-lucide="book-open"></i>
          <p>Education</p>
        </a>

        <!-- Business -->
        <a href="search.php?categories[]=3" class="category-card">
          <i data-lucide="briefcase"></i>
          <p>Commercial</p>
        </a>

        <!-- Creative -->
        <a href="search.php?categories[]=6" class="category-card">
          <i data-lucide="palette"></i>
          <p>minimalist</p>
        </a>

        <!-- More Category -->
        <a href="search.php?reset_search=1&categories=All" class="category-card">
          <i data-lucide="plus"></i>
          <p>More</p>
        </a>

      </div>
  </section>


  <!-- Top Category Nomination (Poster & Flyer Templates) -->
  <section class="top-category">
    <h2>Top Category</h2>
    <p class="subtitle">Best design category of the month — Posters and Flyers only 📢</p>
    <div class="template-grid">
      <?php foreach ($kategoriList as $kategori): ?>
        <div class="card">
            <?php 
            $firstImage = explode(',', $kategori['gambar'])[0]; 
            ?>
            <img src="public/images/produk/<?= htmlspecialchars($firstImage); ?>" 
                alt="<?= htmlspecialchars($kategori['kategori']); ?>">
            <h3><?= htmlspecialchars($kategori['kategori']); ?></h3>
            <form method="GET" action="search.php">
                <input type="hidden" name="categories[]" value="<?= $kategori['id']; ?>">
                <button type="submit">Select Category</button>
            </form>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- Top Picks -->
  <section class="top-picks">
    <h2>Top Design of the Week</h2>
    <div class="template-grid">
      <?php foreach ($products as $item): ?>
        <div class="card">
          <a href="detailproduk.php?id=<?= $item['id'] ?>" class="card-link">
            <?php 
            $firstImage = explode(',', $item['gambar'])[0]; 
            ?>
            <img src="public/images/produk/<?= htmlspecialchars($firstImage); ?>" 
                alt="<?= htmlspecialchars($item['nama_produk']); ?>">
          </a>
          <div class="favorite">
              <?php if ($isLoggedIn): ?>
                  <?php $isInFavorite = $db->isInFavorite($_SESSION['iduser'], $item['id']); ?>
                  <i id="heart-icon-<?php echo $item['id']; ?>" 
                      class="fas fa-heart" 
                      style="color: <?php echo $isInFavorite ?  'rgb(255, 0, 0)' : "rgb(155, 155, 155)"; ?>; font-size: 25px; cursor: pointer;"
                      onclick="toggleFavorite(<?php echo $_SESSION['iduser']; ?>, <?php echo $item['id']; ?>)">
                  </i>
              <?php else: ?>
                  <i class="fas fa-heart" 
                      style="color: rgb(155, 155, 155); font-size: 25px; cursor: pointer;"
                      onclick="showLoginPrompt()">
                  </i>
              <?php endif; ?>
          </div>

          <h3><?= $item['nama_produk'] ?></h3>
          <button onclick="addToWorkspace(<?= $item['id'] ?>)">Add to Workspace</button>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <div id="loginPrompt" class="popup-2" style="display: none;">
      <div class="popup-content">
          <h3>Login Required</h3>
          <p>You need to login to access this feature.</p>
          <div class="cart">
              <button onclick="window.location.href='login.php'" class="checkout-btn">
                  <i class="fas fa-sign-in-alt"></i> Login
              </button>
              <button onclick="window.location.href='register.php'" class="checkout-btn" style="background-color: #28a745;">
                  <i class="fas fa-user-plus"></i> Register
              </button>
              <button type="button" onclick="closeLoginPrompt()" class="cancel-btn" style="background-color: #f44336;">
                  Cancel
              </button>
          </div>
      </div>
  </div>

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
    const isLoggedIn = <?php echo $isLoggedIn ? 'true' : 'false'; ?>;

    function showLoginPrompt() {
        document.getElementById('loginPrompt').style.display = 'flex';
    }

    function closeLoginPrompt() {
        document.getElementById('loginPrompt').style.display = 'none';
    }

    function toggleFavorite(userId, itemId) {
        if (!isLoggedIn) {
            showLoginPrompt();
            return;
        }
        
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

    function addToWorkspace(productId) {
        if (!isLoggedIn) {
            showLoginPrompt();
            return;
        }

        fetch('api/template_actions.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'action=add&product_id=' + productId
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert('Template added to your workspace!');
            } else {
                alert(data.message || 'Failed to add template');
            }
        })
        .catch(err => {
            console.error(err);
            alert('Error adding template');
        });
    }


    window.onclick = function(event) {
        const loginPrompt = document.getElementById('loginPrompt');
        if (event.target === loginPrompt) {
            closeLoginPrompt();
        }
    }

    <?php if ($isLoggedIn): ?>
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
    <?php endif; ?>

</script>