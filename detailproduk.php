<?php
session_start();
require_once "dbcontroller.php";

$isLoggedIn = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
$user_id = $isLoggedIn ? $_SESSION['iduser'] : null;

$db = new dbcontroller();

$hasSubscription = false;

if ($isLoggedIn) {
    $subscriptionSQL = "SELECT is_subscribed, subscription_end 
                        FROM user 
                        WHERE id = $user_id";

    $subResult = $db->getALL($subscriptionSQL);

    if ($subResult && count($subResult) > 0) {
        $subData = $subResult[0];
        $today = date("Y-m-d");

        if (
            $subData['is_subscribed'] == 1 &&
            !empty($subData['subscription_end']) &&
            $subData['subscription_end'] >= $today
        ) {
            $hasSubscription = true;
        }
    }
}


// Ambil detail produk berdasarkan id (misal lewat GET)
$product_id = $_GET['id'] ?? 1;
$sql = "SELECT 
        p.id,
        p.nama_produk,
        p.gambar,
        p.link,
        p.description,
        k.kategori AS kategori
        FROM product p
        LEFT JOIN kategori k ON p.id_kategori = k.id
        WHERE p.id = $product_id";


$result = $db->getALL($sql); 
if($result && count($result) > 0){
    $product = $result[0];
    // Pisah gambar jika ada lebih dari 1 (misal dipisah koma di DB)
    $images = explode(',', $product['gambar']); 
    $mainImage = $images[0]; // gambar utama
} else {
    // fallback kalau produk ga ditemukan
    $product = null;
    $images = [];
    $mainImage = 'public/images/default.png'; // gambar default
}


// Ambil tags (kategori + type)
$tags = [];
if (!empty($product['kategori'])) $tags[] = $product['kategori'];
// misal ada type bisa ditambahkan lagi
//$tags[] = $product['type'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['nama_produk']) ?> - Otojadi</title>
    <link rel="stylesheet" href="css/detailproduk.css">
    <link rel="stylesheet" href="css/index copy.css" />
    <link rel="icon" href="public/images/logo-otojadi.png" type="image/gif" sizes="16x16">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
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
                <li><a href="myworkspace.php" style = "text-decoration:none;"><i class="fas fa-briefcase"></i>Workspace</a></li>
                <li class="nav-item dropdown">
                    <a href="#" class="dropdown-toggle" id="profileDropdown" role="button" aria-expanded="false" style = "text-decoration:none;">
                        <i class="fas fa-user"></i>Profile
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="profileDropdown">
                        <li><a class="dropdown-item" href="profile.php" style = "text-decoration:none;">Profile</a></li>
                        <li><a style="color: red; text-decoration:none;" class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </li>
            <?php else: ?>
                <li><a href="#" onclick="showLoginPrompt()"><i class="fas fa-briefcase"></i>Workspace</a></li>
                <li><a href="login.php" style = "text-decoration:none;"><i class="fas fa-sign-in-alt"></i>Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<div class="container">

    <div class="product-grid">
        <div class="product-preview">
            <div class="main-image">
                <img src="public/images/produk/<?= htmlspecialchars($mainImage) ?>" id="mainProductImage">
            </div>

            <div class="thumbnail-carousel">
                <button id="thumb-prev" class="thumb-arrow">&lt;</button>
                <div class="thumbnail-wrapper">
                    <div class="thumbnail-list">
                        <?php if(!empty($images)): ?>
                            <?php foreach($images as $img): ?>
                                <img src="public/images/produk/<?= htmlspecialchars($img) ?>"
                                    class="<?= $img === $mainImage ? 'active' : '' ?>">
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <button id="thumb-next" class="thumb-arrow">&gt;</button>
            </div>
        </div>

        <div class="product-info">
            <h1><?= htmlspecialchars($product['nama_produk']) ?></h1>
            <p class="product-author">By <a href="index.php">Otojadi</a></p>

            <?php if (!$hasSubscription): ?>
                <!-- BEFORE SUBSCRIBED -->
                <div class="purchase-box">
                    <div class="price">Rp 9.000</div>
                    <p>Or get unlimited downloads starting from <strong>Rp 9.000/month</strong></p>
                    <ul class="purchase-features">
                        <li>26+ million premium assets & templates</li>
                        <li>Lifetime commercial license</li>
                        <li>Cancel anytime</li>
                    </ul>
                    <?php if ($isLoggedIn): ?>
                        <button class="cta-primary"
                            onclick="window.location.href='subscribe.php'">
                            Subscribe to Download
                        </button>
                    <?php else: ?>
                        <button class="cta-primary"
                            onclick="showLoginPrompt()">
                            Subscribe to Download
                        </button>
                    <?php endif; ?>

                    <button class="cta-secondary"
                    <?php if ($isLoggedIn): ?>
                        onclick="addToWorkspace(<?= $product['id']; ?>)"
                    <?php else: ?>
                        onclick="showLoginPrompt()"
                    <?php endif; ?>
                    >
                    Add to Workspace
                    </button>

                </div>
            <?php else: ?>
                <!-- AFTER SUBSCRIBED -->
                <div class="purchase-box">
                    <button class="cta-primary">
                        <a href="myworkspace.php" 
                        target="_blank"
                        style="text-decoration:none; color:white;">
                            Open Template
                        </a>
                    </button>
                    <button class="cta-secondary" onclick="addToWorkspace(<?= $product['id']; ?>)">Add to Workspace</button>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <div class="description-details">
        <div class="description-content">
            <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
        </div>

        <div class="item-tags">
            <h3>Item Tags</h3>
            <div class="tags-container">
                <?php foreach ($tags as $tag): ?>
                    <span class="tag"><?= htmlspecialchars($tag) ?></span>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

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

    document.addEventListener("DOMContentLoaded", function() {
    const mainImage = document.getElementById("mainProductImage");
    const thumbnails = document.querySelectorAll(".thumbnail-list img");

    thumbnails.forEach(thumbnail => {
        thumbnail.addEventListener("click", function() {
            mainImage.src = this.src;
            thumbnails.forEach(t => t.classList.remove("active"));
            this.classList.add("active");
        });
    });

    const prevButton = document.getElementById("thumb-prev");
    const nextButton = document.getElementById("thumb-next");
    const thumbList = document.querySelector(".thumbnail-list");
    let currentIndex = 0;
    const visibleItems = 6;

    function updateCarousel() {
        const itemWidth = thumbnails[0].offsetWidth + 10;
        thumbList.style.transform = `translateX(${-currentIndex*itemWidth}px)`;
        prevButton.disabled = currentIndex === 0;
        nextButton.disabled = currentIndex + visibleItems >= thumbnails.length;
    }

    nextButton.addEventListener("click", function() {
        if(currentIndex + visibleItems < thumbnails.length) currentIndex += visibleItems;
        updateCarousel();
    });
    prevButton.addEventListener("click", function() {
        if(currentIndex > 0) currentIndex -= visibleItems;
        updateCarousel();
    });

    updateCarousel();
    window.addEventListener("resize", updateCarousel);
    const isLoggedIn = <?php echo $isLoggedIn ? 'true' : 'false'; ?>;

    function showLoginPrompt() {
        document.getElementById('loginPrompt').style.display = 'flex';
    }

    function closeLoginPrompt() {
        document.getElementById('loginPrompt').style.display = 'none';
    }
    window.onclick = function(event) {
        const loginPrompt = document.getElementById('loginPrompt');
        if (event.target === loginPrompt) {
            closeLoginPrompt();
        }
    }
    
});

function addToWorkspace(productId) {
    fetch('api/template_actions.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'action=add&product_id=' + productId
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert('Template added to workspace!');
        } else {
            if (data.message === 'Template already added') {
                alert('Template already in your workspace');
            } else {
                alert(data.message || 'Failed to add template');
            }
        }
    })
    .catch(err => {
        console.error(err);
        alert('Error adding template');
    });
}
</script>
</body>
</html>
