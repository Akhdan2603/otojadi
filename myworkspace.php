<?php
session_start();
require_once "dbcontroller.php";

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

$db = new dbcontroller();
$user_id = $_SESSION['iduser'];

$user = $db->getITEM("SELECT * FROM user WHERE id = $user_id");

if (!$user['is_subscribed'] || strtotime($user['subscription_end']) < time()) {
    header("Location: subscribe.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_search'])) {
    $_SESSION['search'] = trim($_POST['search']);
    header('Location: search.php');
    exit;
}

$folders = $db->getALL("SELECT * FROM workspace_folders WHERE user_id = $user_id ORDER BY created_at DESC");
$allTemplates = $db->getALL("
    SELECT ut.*, p.nama_produk, p.gambar, p.link, k.kategori, t.nama_type, ut.folder_id, f.id_user IS NOT NULL AS is_favorite
    FROM user_templates ut
    JOIN product p ON ut.product_id = p.id
    JOIN kategori k ON p.id_kategori = k.id
    JOIN type t ON p.id_type = t.id
    LEFT JOIN favorite f ON f.id_user = ut.user_id AND f.id_barang = ut.product_id
    WHERE ut.user_id = $user_id
    ORDER BY is_favorite DESC, ut.added_at DESC
");

$recentTemplates = $db->getALL("
    SELECT tr.*, p.nama_produk, p.gambar, p.link, k.kategori, t.nama_type
    FROM template_recent tr
    JOIN product p ON tr.product_id = p.id
    JOIN kategori k ON p.id_kategori = k.id
    JOIN type t ON p.id_type = t.id
    WHERE tr.user_id = $user_id
    ORDER BY tr.accessed_at DESC
    LIMIT 10
");

$templates = $allTemplates;
$selectedFolder = null;

if (isset($_GET['folder_id']) && $_GET['folder_id'] != '') {
    $folder_id = intval($_GET['folder_id']);
    $selectedFolder = $db->getITEM("SELECT * FROM workspace_folders WHERE id = $folder_id AND user_id = $user_id");
    
    if ($selectedFolder) {
        $templates = array_filter($allTemplates, function($t) use ($folder_id) {
            return $t['folder_id'] == $folder_id;
        });
    }
} else {
    // For "All Templates", show unique products only (no duplicates)
    $uniqueTemplates = [];
    $seenProducts = [];
    if (is_array($allTemplates)) {
        foreach ($allTemplates as $template) {
            if (!in_array($template['product_id'], $seenProducts)) {
                $uniqueTemplates[] = $template;
                $seenProducts[] = $template['product_id'];
            }
        }
    }
    $templates = $uniqueTemplates;
}

$allProducts = $db->getALL("
    SELECT p.*, k.kategori, t.nama_type
    FROM product p
    JOIN kategori k ON p.id_kategori = k.id
    JOIN type t ON p.id_type = t.id
    ORDER BY p.created_at DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Workspace - Otojadi</title>
    <link rel="stylesheet" href="css/index copy.css" />
    <link rel="icon" href="public/images/logo-otojadi.png" type="image/gif" sizes="16x16">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; }
        .template-card { transition: all 0.3s ease; }
        .template-card:hover { transform: translateY(-8px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); }
        .folder-item:hover { background-color: #f3f4f6; }
        .modal { display: none; position: fixed; z-index: 1000; inset: 0; background-color: rgba(0,0,0,0.5); }
        .modal.show { display: flex; align-items: center; justify-content: center; }
    </style>
</head>
<body class="bg-gray-50" style="display: flex; flex-direction: column; min-height: 100vh;">

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
                        <?php if (isset($user['peran']) && $user['peran'] === 'admin'): ?>
                            <li><a class="dropdown-item" href="kelolaproduk.php">Admin</a></li>
                        <?php endif; ?>
                        <li><a style="color: red;" class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" style="flex-grow: 1;">
        <div class="flex gap-8">
            
            <!-- Sidebar -->
            <aside class="w-64 flex-shrink-0">
                <div class="bg-white rounded-lg shadow-sm p-6 sticky top-24" style="max-height: calc(100vh - 120px); overflow-y: auto;">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-semibold text-gray-900">Folders</h2>
                        <button onclick="openAddFolderModal()" class="text-blue-600 hover:text-blue-700">
                            <i data-lucide="folder-plus" class="w-5 h-5"></i>
                        </button>
                    </div>

                    <div class="space-y-1">
                        <?php
                        // Count unique products for "All Templates"
                        $uniqueProductIds = [];
                        if (!empty($allTemplates)) {
                            foreach ($allTemplates as $t) {
                                if (!in_array($t['product_id'], $uniqueProductIds)) {
                                    $uniqueProductIds[] = $t['product_id'];
                                }
                            }
                        }
                        $uniqueCount = count($uniqueProductIds);
                        ?>
                        <a href="myworkspace.php" 
                            class="folder-item flex items-center gap-3 px-3 py-2 rounded-lg <?php echo !isset($_GET['folder_id']) ? 'bg-blue-50 text-blue-700' : 'text-gray-700'; ?>">
                            <i data-lucide="grid-3x3" class="w-5 h-5"></i>
                            <span class="font-medium">All Templates</span>
                            <span class="ml-auto text-sm"><?php echo $uniqueCount; ?></span>
                        </a>

                        <?php if (!empty($folders)): ?>
                            <?php foreach ($folders as $folder): ?>
                                <?php 
                                $folderCount = count(array_filter($allTemplates ?: [], function($t) use ($folder) {
                                    return $t['folder_id'] == $folder['id'];
                                }));
                                $isActive = isset($_GET['folder_id']) && $_GET['folder_id'] == $folder['id'];
                                ?>
                                <div class="group relative">
                                    <a href="?folder_id=<?php echo $folder['id']; ?>" 
                                        class="folder-item flex items-center gap-3 px-3 py-2 rounded-lg <?php echo $isActive ? 'bg-blue-50 text-blue-700' : 'text-gray-700'; ?>">
                                        <i data-lucide="folder" class="w-5 h-5"></i>
                                        <span class="flex-1 truncate"><?php echo htmlspecialchars($folder['folder_name']); ?></span>
                                        <span class="text-sm"><?php echo $folderCount; ?></span>
                                    </a>
                                    <button onclick="deleteFolder(<?php echo $folder['id']; ?>)" 
                                        class="absolute right-2 top-1/2 transform -translate-y-1/2 opacity-0 group-hover:opacity-100 text-red-500 hover:text-red-700">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <hr class="my-6">

                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                            <i data-lucide="clock" class="w-4 h-4"></i> Recent
                        </h3>
                        <?php if (!empty($recentTemplates)): ?>
                            <div class="space-y-2">
                                <?php foreach (array_slice($recentTemplates, 0, 5) as $recent): ?>
                                    <?php 
                                    $recentImages = explode(',', $recent['gambar']);
                                    $recentMainImage = trim($recentImages[0]);
                                    ?>
                                    <a href="view_template.php?id=<?php echo $recent['product_id']; ?>" 
                                        class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-50 transition-colors group">
                                        <img src="public/images/produk/<?php echo htmlspecialchars($recentMainImage); ?>" 
                                            alt="<?php echo htmlspecialchars($recent['nama_produk']); ?>"
                                            class="w-12 h-12 object-cover rounded border border-gray-200">
                                        <span class="text-sm text-gray-600 group-hover:text-blue-600 truncate flex-1">
                                            <?php echo htmlspecialchars($recent['nama_produk']); ?>
                                        </span>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-sm text-gray-400">No recent activity</p>
                        <?php endif; ?>
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="flex-1">
                
                <!-- Header -->
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-2">
                        <h1 class="text-3xl font-bold text-gray-900">
                            <?php echo $selectedFolder ? htmlspecialchars($selectedFolder['folder_name']) : 'My Templates'; ?>
                        </h1>
                        <button onclick="openBrowseModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                            Add Template
                        </button>
                    </div>
                    <p class="text-gray-600">
                        Manage and organize your template collection
                        <?php if ($user['subscription_end']): ?>
                            <span class="text-sm">• Subscription active until <?php echo date('M d, Y', strtotime($user['subscription_end'])); ?></span>
                        <?php endif; ?>
                    </p>
                </div>

                <!-- Templates Grid -->
                <?php if (!empty($templates)): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php foreach ($templates as $template): ?>
                            <?php 
                            $images = explode(',', $template['gambar']);
                            $mainImage = $images[0];
                            ?>
                            <div class="template-card bg-white rounded-xl shadow-sm overflow-hidden">
                                <a href="view_template.php?id=<?php echo $template['product_id']; ?>">
                                    <div class="aspect-video bg-gray-100 overflow-hidden">
                                        <img src="public/images/produk/<?php echo htmlspecialchars(trim($mainImage)); ?>" 
                                            alt="<?php echo htmlspecialchars($template['nama_produk']); ?>"
                                            class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                    </div>
                                </a>
                                <div class="p-4">
                                    <div class="flex items-start justify-between gap-2 mb-2">
                                        <span class="inline-block px-2 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded">
                                            <?php echo htmlspecialchars($template['kategori']); ?>
                                        </span>
                                        <span class="text-xs text-gray-500">
                                            <?php echo htmlspecialchars($template['nama_type']); ?>
                                        </span>
                                    </div>
                                    <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2 hover:text-blue-600">
                                        <a href="view_template.php?id=<?php echo $template['product_id']; ?>">
                                            <?php echo htmlspecialchars($template['nama_produk']); ?>
                                        </a>
                                    </h3>
                                    <div class="flex items-center gap-2">
                                        <button onclick="toggleFavorite(this)" data-product-id="<?php echo $template['product_id']; ?>"
                                            class="text-sm text-gray-600 hover:text-blue-600 py-1 px-3 border border-gray-300 rounded hover:border-blue-600">
                                            <i class="fas fa-star" style="color: <?php echo $db->isInFavorite($user_id, $template['product_id']) ? 'yellow' : 'rgb(155, 155, 155)'; ?>;"></i>
                                        </button>
                                        <button onclick="moveToFolder(<?php echo $template['id']; ?>)" 
                                            class="flex-1 text-sm text-gray-600 hover:text-blue-600 py-1 border border-gray-300 rounded hover:border-blue-600">
                                            <i data-lucide="folder-input" class="w-4 h-4 inline"></i> Move
                                        </button>
                                        <button onclick="removeTemplate(<?php echo $template['id']; ?>)" 
                                            class="text-sm text-red-600 hover:text-red-700 py-1 px-3 border border-red-300 rounded hover:border-red-600">
                                            <i data-lucide="trash-2" class="w-4 h-4 inline"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-16">
                        <i data-lucide="inbox" class="w-16 h-16 mx-auto text-gray-300 mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">No templates yet</h3>
                        <p class="text-gray-500 mb-6">Start adding templates to your workspace</p>
                        <button onclick="openBrowseModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                            Browse Templates
                        </button>
                    </div>
                <?php endif; ?>
            </main>
        </div>
    </div>

    <!-- Modal: Add Folder -->
    <div id="addFolderModal" class="modal">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <h2 class="text-xl font-semibold mb-4">Create New Folder</h2>
            <input type="text" id="folderNameInput" placeholder="Folder name..." 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg mb-4 focus:ring-2 focus:ring-blue-500">
            <div class="flex gap-3">
                <button onclick="createFolder()" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg">Create</button>
                <button onclick="closeAddFolderModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 py-2 rounded-lg">Cancel</button>
            </div>
        </div>
    </div>

    <!-- Modal: Browse Templates -->
    <div id="browseModal" class="modal">
        <div class="bg-white rounded-lg p-6 max-w-4xl w-full mx-4 max-h-[80vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-semibold">Browse Templates</h2>
                <button onclick="closeBrowseModal()" class="text-gray-400 hover:text-gray-600">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <?php if (!empty($allProducts)): ?>
                    <?php foreach ($allProducts as $product): ?>
                        <?php 
                        $images = explode(',', $product['gambar']);
                        $mainImage = $images[0];
                        
                        // Check if already added to current folder/view
                        $alreadyInFolder = false;
                        if (!empty($allTemplates)) {
                            if (isset($_GET['folder_id'])) {
                                // In specific folder - check if product exists in that folder
                                $folder_id = intval($_GET['folder_id']);
                                foreach ($allTemplates as $ut) {
                                    if ($ut['product_id'] == $product['id'] && $ut['folder_id'] == $folder_id) {
                                        $alreadyInFolder = true;
                                        break;
                                    }
                                }
                            } else {
                                // In "All Templates" - check if product exists at all (any folder)
                                foreach ($allTemplates as $ut) {
                                    if ($ut['product_id'] == $product['id']) {
                                        $alreadyInFolder = true;
                                        break;
                                    }
                                }
                            }
                        }
                        ?>
                        <div class="border rounded-lg p-4 hover:border-blue-500">
                            <img src="public/images/produk/<?php echo htmlspecialchars(trim($mainImage)); ?>" 
                                class="w-full h-32 object-cover rounded mb-3">
                            <h4 class="font-semibold text-sm mb-1 line-clamp-2"><?php echo htmlspecialchars($product['nama_produk']); ?></h4>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-500"><?php echo htmlspecialchars($product['kategori']); ?></span>
                                <?php if (!$alreadyInFolder): ?>
                                    <button onclick="addTemplate(<?php echo $product['id']; ?>)" 
                                        class="text-sm bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded">
                                        Add
                                    </button>
                                <?php else: ?>
                                    <span class="text-xs text-green-600">✓ Added</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Modal: Move to Folder -->
    <div id="moveFolderModal" class="modal">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <h2 class="text-xl font-semibold mb-4">Move to Folder</h2>
            <div class="space-y-2 mb-4">
                <button onclick="moveTemplateToFolder(null)" class="w-full text-left px-4 py-2 border rounded hover:bg-gray-50">
                    <i data-lucide="grid-3x3" class="w-4 h-4 inline mr-2"></i> No Folder
                </button>
                <?php if (!empty($folders)): ?>
                    <?php foreach ($folders as $folder): ?>
                        <button onclick="moveTemplateToFolder(<?php echo $folder['id']; ?>)" 
                            class="w-full text-left px-4 py-2 border rounded hover:bg-gray-50">
                            <i data-lucide="folder" class="w-4 h-4 inline mr-2"></i> 
                            <?php echo htmlspecialchars($folder['folder_name']); ?>
                        </button>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <button onclick="closeMoveFolderModal()" class="w-full bg-gray-200 hover:bg-gray-300 text-gray-700 py-2 rounded-lg">Cancel</button>
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
            <a href="https://www.instagram.com/otojadi/"><i data-lucide="instagram" style="display: inline-block;"></i></a>
            <a href="#"><i data-lucide="twitter" style="display: inline-block;"></i></a>
            <a href="https://www.facebook.com/profile.php?id=61582317293988"><i data-lucide="facebook" style="display: inline-block;"></i></a>
        </div>
        </div>

        <p class="footer-copy">
        © 2025 OtoJadi. Trademarks and brands are the property of their respective owners.
        </p>
    </footer>

    <script>
        lucide.createIcons();

        const profileDropdown = document.getElementById('profileDropdown');
        const dropdownMenu = profileDropdown.nextElementSibling;
        
        profileDropdown.addEventListener('click', (e) => {
            e.preventDefault();
            dropdownMenu.classList.toggle('show');
        });

        document.addEventListener('click', (e) => {
            if (!profileDropdown.contains(e.target) && !dropdownMenu.contains(e.target)) {
                dropdownMenu.classList.remove('show');
            }
        });

        function openAddFolderModal() {
            document.getElementById('addFolderModal').classList.add('show');
            document.getElementById('folderNameInput').focus();
        }

        function closeAddFolderModal() {
            document.getElementById('addFolderModal').classList.remove('show');
        }

        function createFolder() {
            const name = document.getElementById('folderNameInput').value.trim();
            if (!name) {
                alert('Please enter a folder name');
                return;
            }

            fetch('api/folder_actions.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'action=create&folder_name=' + encodeURIComponent(name)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Failed to create folder');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Error creating folder');
            });
        }

        function deleteFolder(id) {
            if (!confirm('Delete this folder? Templates will be moved to "All Templates"')) return;

            fetch('api/folder_actions.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'action=delete&folder_id=' + id
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    location.href = 'myworkspace.php';
                } else {
                    alert(data.message || 'Failed to delete folder');
                }
            });
        }

        function openBrowseModal() {
            document.getElementById('browseModal').classList.add('show');
            lucide.createIcons();
        }

        function closeBrowseModal() {
            document.getElementById('browseModal').classList.remove('show');
        }

        function addTemplate(productId) {
            const urlParams = new URLSearchParams(window.location.search);
            const folderId = urlParams.get('folder_id') || '';
            
            fetch('api/template_actions.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'action=add&product_id=' + productId + '&folder_id=' + folderId
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Failed to add template');
                }
            });
        }

        let selectedTemplateId = null;

        function moveToFolder(templateId) {
            selectedTemplateId = templateId;
            document.getElementById('moveFolderModal').classList.add('show');
            lucide.createIcons();
        }

        function closeMoveFolderModal() {
            document.getElementById('moveFolderModal').classList.remove('show');
            selectedTemplateId = null;
        }

        function moveTemplateToFolder(folderId) {
            if (selectedTemplateId === null) return;

            fetch('api/template_actions.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'action=move&template_id=' + selectedTemplateId + '&folder_id=' + (folderId || '')
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Failed to move template');
                }
            });
        }

        function removeTemplate(templateId) {
            if (!confirm('Remove this template from your workspace?')) return;

            fetch('api/template_actions.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'action=remove&template_id=' + templateId
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Failed to remove template');
                }
            });
        }

        function toggleFavorite(buttonElement) {
            const productId = buttonElement.dataset.productId;
            const icon = buttonElement.querySelector('i');
            const isFavorited = icon.style.color === 'yellow';
            const action = isFavorited ? 'remove' : 'add';

            fetch('toggle-favorite.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    userId: <?php echo $user_id; ?>,
                    itemId: productId,
                    action: action
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    icon.style.color = isFavorited ? 'rgb(155, 155, 155)' : 'yellow';
                    if (!isFavorited) {
                        const card = buttonElement.closest('.template-card');
                        const grid = card.closest('.grid');
                        grid.prepend(card);
                    }
                } else {
                    alert(data.message || 'Failed to update favorite');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Error updating favorite');
            });
        }
    </script>
</body>
</html>
