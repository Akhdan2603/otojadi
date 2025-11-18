<?php
session_start();
require_once "dbcontroller.php";

$isLoggedIn = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
$user_id = $isLoggedIn ? $_SESSION['iduser'] : null;

$db = new dbcontroller();

// Reset search jika ada parameter reset_search
if (isset($_GET['reset_search']) && $_GET['reset_search'] == 1) {
    unset($_SESSION['search']);
    $search = '';
} else {
    $search = isset($_SESSION['search']) ? $_SESSION['search'] : '';
}


$categorySql = "SELECT id, kategori FROM kategori ORDER BY id";
$categories = $db->getALL($categorySql);

$typeSql = "SELECT id, nama_type FROM type ORDER BY id";
$types = $db->getALL($typeSql);

$selectedCategories = [];
if (isset($_GET['categories'])) {
    $selectedCategories = is_array($_GET['categories']) ? $_GET['categories'] : [$_GET['categories']];
}
// Jika user memilih 'All', kosongkan filter kategori
if (in_array('All', $selectedCategories)) {
    $selectedCategories = [];
    $categoryFilter = 'All';
} else {
    $categoryFilter = isset($_GET['category']) ? $_GET['category'] : 'All';
}

$selectedTypes = [];
if (isset($_GET['types'])) {
    $selectedTypes = is_array($_GET['types']) ? $_GET['types'] : [$_GET['types']];
}

$typeFilter = isset($_GET['type']) ? $_GET['type'] : 'All';

if (isset($_GET['type']) && $_GET['type'] !== 'All' && is_numeric($_GET['type'])) {
    $typeFilter = intval($_GET['type']);
    $selectedTypes = [$typeFilter];
}

if (isset($_GET['category']) && $_GET['category'] !== 'All' && is_numeric($_GET['category'])) {
    $categoryFilter = intval($_GET['category']);
    $selectedCategories = [$categoryFilter];
}

$search_keyword = "(product.nama_produk LIKE '%$search%' OR kategori.kategori LIKE '%$search%' OR type.nama_type LIKE '%$search%')";

$sql = "SELECT 
        product.id,
        product.nama_produk,
        product.gambar,
        kategori.kategori AS kategori,
        type.nama_type AS type
    FROM product
    LEFT JOIN kategori ON product.id_kategori = kategori.id
    LEFT JOIN type ON product.id_type = type.id
    WHERE $search_keyword";

if (!empty($selectedCategories) && !in_array('All', $selectedCategories)) {
    $categoryIds = array_map('intval', $selectedCategories);
    $categoryIds = array_filter($categoryIds, fn($id) => $id > 0);
    if (!empty($categoryIds)) {
        $sql .= " AND product.id_kategori IN (" . implode(',', $categoryIds) . ")";
    }
} elseif (!empty($categoryFilter) && $categoryFilter !== 'All' && is_numeric($categoryFilter)) {
    $sql .= " AND product.id_kategori = $categoryFilter";
}

if (!empty($selectedTypes) && !in_array('All', $selectedTypes)) {
    $typeIds = array_map('intval', $selectedTypes);
    $typeIds = array_filter($typeIds, fn($id) => $id > 0);
    if (!empty($typeIds)) {
        $sql .= " AND product.id_type IN (" . implode(',', $typeIds) . ")";
    }
} elseif (!empty($typeFilter) && $typeFilter !== 'All' && is_numeric($typeFilter)) {
    $sql .= " AND product.id_type = $typeFilter";
}

$sql .= " ORDER BY product.created_at DESC";

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
    <link rel="stylesheet" href="css/index copy.css">
    <link rel="stylesheet" href="css/filter.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="icon" href="public\images\logo-otojadi.png" type="image/gif" sizes="16x16">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    <title>otojadi</title>
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

    <div class="container-2">
        <div class="content-2">
            <div class="filter-sidebar">
                <div class="filter-section">
                    <h3>By Category</h3>
                    <div class="filter-group">
                        <label class="filter-item">
                            <input type="checkbox" name="category" value="All" 
                                <?= (empty($selectedCategories) && $categoryFilter == 'All') ? 'checked' : '' ?>
                                onchange="updateFilter('category', this.value, this.checked)">
                            <span>All Categories</span>
                        </label>
                        <?php foreach ($categories as $category): ?>
                            <label class="filter-item">
                                <input type="checkbox" name="category" value="<?= $category['id'] ?>"
                                    <?php echo (in_array($category['id'], $selectedCategories)) ? 'checked' : ''; ?>
                                    onchange="updateFilter('category', this.value, this.checked)">
                                <span><?= htmlspecialchars($category['kategori']); ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="filter-section">
                    <h3>By Type</h3>
                    <div class="filter-group">
                        <label class="filter-item">
                            <input type="checkbox" name="type" value="All"
                            <?= (empty($selectedTypes) && $typeFilter == 'All') ? 'checked' : '' ?>
                            onchange="updateFilter('type', this.value, this.checked)">
                            <span>All Types</span>
                        </label>
                        <?php foreach ($types as $type): ?>
                            <label class="filter-item">
                                <input type="checkbox" name="type" value="<?= $type['id'] ?>"
                                <?= (in_array($type['id'], $selectedTypes) || ($typeFilter == $type['id'] && empty($selectedTypes))) ? 'checked' : '' ?>
                                onchange="updateFilter('type', this.value, this.checked)">
                                <span><?= htmlspecialchars($type['nama_type']) ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="container-cardd">
                <div class="page-detail">
                    <h2>Hasil Pencarian <span>"<?php echo htmlspecialchars($search); ?>"</span></h2>
                    <form method="GET" action="" class="sort-form">
                        <!-- Simpan search -->
                        <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>" />

                        <!-- Simpan kategori terpilih -->
                        <?php if (!empty($selectedCategories)): ?>
                            <?php foreach ($selectedCategories as $catId): ?>
                                <input type="hidden" name="categories[]" value="<?php echo htmlspecialchars($catId); ?>" />
                            <?php endforeach; ?>
                        <?php elseif (!empty($categoryFilter) && $categoryFilter !== 'All'): ?>
                            <input type="hidden" name="category" value="<?php echo htmlspecialchars($categoryFilter); ?>" />
                        <?php endif; ?>

                        <!-- Simpan type terpilih -->
                        <?php if (!empty($selectedTypes)): ?>
                            <?php foreach ($selectedTypes as $typeId): ?>
                                <input type="hidden" name="types[]" value="<?php echo htmlspecialchars($typeId); ?>" />
                            <?php endforeach; ?>
                        <?php elseif (!empty($typeFilter) && $typeFilter !== 'All'): ?>
                            <input type="hidden" name="type" value="<?php echo htmlspecialchars($typeFilter); ?>" />
                        <?php endif; ?>
                    </form>
                </div>
                <div class="card-container">
                    <?php if (empty($products)): ?>
                        <h style="font-size: 20px;">No products available. Stay toon, new products are coming soon!!</h>
                    <?php else: ?>
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

                                                    <?php endif; ?>  

                                                </div>

                                            </div> 

                                        </div>

                                    </div>  

                                 

                                    <!-- Login Prompt Popup -->

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

                                

                                    function updateFilter(type, value, checked) {

                                    let selectedCategories = [];

                                    let selectedTypes = [];

                                    

                                    document.querySelectorAll('input[name="category"]:checked').forEach(cb => selectedCategories.push(cb.value));

                                    document.querySelectorAll('input[name="type"]:checked').forEach(cb => selectedTypes.push(cb.value));

                                

                                    if (type === 'category') {

                                        if (value === 'All' && checked) {

                                            document.querySelectorAll('input[name="category"]').forEach(cb => { if(cb.value !== 'All') cb.checked = false; });

                                            selectedCategories = ['All'];

                                        } else if (value !== 'All' && checked) {

                                            document.querySelector('input[name="category"][value="All"]').checked = false;

                                            selectedCategories = selectedCategories.filter(c => c !== 'All');

                                        }

                                    } else if (type === 'type') {

                                        if (value === 'All' && checked) {

                                            document.querySelectorAll('input[name="type"]').forEach(cb => { if(cb.value !== 'All') cb.checked = false; });

                                            selectedTypes = ['All'];

                                        } else if (value !== 'All' && checked) {

                                            document.querySelector('input[name="type"][value="All"]').checked = false;

                                            selectedTypes = selectedTypes.filter(t => t !== 'All');

                                        }

                                    }

                                

                                    let url = 'search.php?';

                                    let params = [];

                                    params.push(`search=<?= urlencode($search) ?>`);

                                

                                    if (selectedCategories.length > 0 && !selectedCategories.includes('All')) selectedCategories.forEach(c => params.push(`categories[]=${encodeURIComponent(c)}`));

                                    if (selectedTypes.length > 0 && !selectedTypes.includes('All')) selectedTypes.forEach(t => params.push(`types[]=${encodeURIComponent(t)}`));

                                

                                    if ((selectedCategories.includes('All') || selectedCategories.length===0)) params.push('category=All');

                                    if ((selectedTypes.includes('All') || selectedTypes.length===0)) params.push('type=All');

                                

                                    window.location.href = url + params.join('&');

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

                                

                                </body>

                                </html>