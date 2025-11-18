<?php
require_once "../dbcontroller.php";
$db = new DBController();
session_start();

$user = $_SESSION['email'] ?? null;
if (isset($_GET['log'])) {
    session_destroy();
    header("location:../login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: select.php");
    exit();
}

$id = intval($_GET['id']);
$item = $db->getITEM("SELECT * FROM product WHERE id = $id");
if (!$item) {
    header("Location: select.php");
    exit();
}

$id_kategori = $item['id_kategori'];
$id_type = $item['id_type'];
$gambarLama = $item['gambar'];

$kategoriRow = $db->getALL("SELECT id, kategori FROM kategori ORDER BY kategori ASC");
$typeRow = $db->getALL("SELECT id, nama_type FROM type ORDER BY nama_type ASC");
if (isset($_POST['simpan'])) {
    $id_kategori_post = intval($_POST['kategori']);
    $id_type_post = intval($_POST['type']);
    $nama_produk = trim($_POST['nama']);
    $link = trim($_POST['link']);
    $description = trim($_POST['description']);

    // Handle gambar
    $gambar = $gambarLama; // gunakan variabel yang benar
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === 0) {
        $uploadDir = '../public/images/produk/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $gambar = basename($_FILES['gambar']['name']);
        move_uploaded_file($_FILES['gambar']['tmp_name'], $uploadDir . $gambar);
    }

    // Escape sederhana (pakai addslashes) agar tanda ' aman
    $nama_produk = addslashes($nama_produk);
    $link = addslashes($link);
    $description = addslashes($description);
    $gambar = addslashes($gambar);

    // Update produk
    $sql = "UPDATE product 
            SET id_kategori = $id_kategori_post,
                id_type = $id_type_post,
                nama_produk = '$nama_produk',
                link = '$link',
                description = '$description',
                gambar = '$gambar',
                updated_at = NOW()
            WHERE id = $id";
    $db->runSQL($sql);

    header("Location: select.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Update Product - VirtualZone</title>
        <link rel="icon" href="../public/images/logo-otojadi.png" type="image/gif" sizes="16x16">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/styless.css" rel="stylesheet" />
        <link href="../css/admin.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>

    <body class="sb-nav-fixed">
        
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <a style = "text-align: center; margin-top: 5px;"class="navbar-brand ps-2" href="../kelolaproduk.php"><img src="../public/images/nav_logo.png" alt="" style="height: 38px;"></a>
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
           
            <ul class="navbar-nav ms-auto ms-md-8 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="?log=logout">Logout</a></li>
                        <li><a class="dropdown-item" href="../index.php">Halaman Utama</a></li>
                    </ul>
                </li>
            </ul>
        </nav>

        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Core</div>
                            <a class="nav-link" href="..\kelolaproduk.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Dashboard
                            </a>
                            
                            <a class="nav-link" href="../kategori/select.php">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-list"></i></div>
                                Category
                            </a>
                            <a class="nav-link" href="select.php">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-box"></i></div>
                                Product
                            </a>

                            <a class="nav-link" href="../type/select.php">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-tags"></i></div>
                                Type
                            </a>

                            <a class="nav-link" href="../profile/select.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
                                User
                            </a>

                        </div>
                    </div>

                </nav>
            </div>
            
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4 text-center"><i class="fas fa-edit"></i> Update Product</h1>
                        <div class="main-content">
                            <div class="form-container">
                                <form action="" method="post" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h5 class="mb-3"><i class="fas fa-info-circle"></i> Basic Information</h5>
                                            
                                            <div class="mb-3">
                                                <label for="kategori" class="form-label">Category *</label>
                                                <select class="form-select" name="kategori" id="kategori" required>
                                                <option value="">Select Category</option>
                                                <?php foreach ($kategoriRow as $r) : ?>
                                                    <option value="<?= $r['id'] ?>" <?= $r['id'] == $id_kategori ? 'selected' : '' ?>>
                                                        <?= $r['kategori'] ?>
                                                    </option>
                                                <?php endforeach ?>
                                            </select>
                                            </div>

                                            <div class="mb-3">
                                                <label for="type" class="form-label">type *</label>
                                                <select class="form-select" name="type" required>
                                                <option value="">Select Type</option>
                                                <?php foreach ($typeRow as $b) : ?>
                                                    <option value="<?= $b['id'] ?>" <?= $b['id'] == $id_type ? 'selected' : '' ?>>
                                                        <?= $b['nama_type'] ?>
                                                    </option>
                                                <?php endforeach ?>
                                            </select>

                                            </div>

                                            <div class="mb-3">
                                                <label for="nama" class="form-label">Product Name *</label>
                                                <input type="text" id="nama" name="nama" required placeholder="Enter product name" class="form-control" value="<?php echo $item['nama_produk'] ?>">
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="detail" class="form-label">Product Link *</label>
                                                <textarea id="link" name="link" required placeholder="Enter product link" class="form-control" rows="3"><?php echo $item['link'] ?></textarea>
                                            </div>


                                            <div class="mb-3">
                                                <label for="detail" class="form-label">Product Description *</label>
                                                <textarea id="description" name="description" required required placeholder="Enter product link" class="form-control" rows="3"><?php echo $item['description'] ?></textarea>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <h5 class="mb-3"><i class="fas fa-image"></i> Product Image</h5>
                                            
                                            <div class="mb-3">
                                                <label for="gambar" class="form-label">Image</label>
                                                <input type="file" id="gambar" name="gambar" class="form-control" accept="image/*" onchange="previewImage(this)">
                                                <div class="image-preview" id="imagePreview">
                                                    <img src="../public/images/produk/<?php echo $item['gambar'] ?>" alt="Current Image">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between mt-4">
                                        <a href="select.php" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left"></i> Back to List
                                        </a>
                                        <button type="submit" name="simpan" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Update Product
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </main>

                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; otojadi</div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="../js/scripts.js"></script>
        
        <script>
            const selectedSpecifications = <?php echo json_encode($selectedSpecsByType); ?>;
            
            function previewImage(input) {
                const preview = document.getElementById('imagePreview');
                const file = input.files[0];
                
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                    }
                    reader.readAsDataURL(file);
                }
            }

            function loadSpecifications() {
                const kategoriId = document.getElementById('kategori').value;
                const specsContainer = document.getElementById('specifications-container');
                
                if (kategoriId) {
                    fetch('', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `get_specs=1&kategori_id=${kategoriId}`
                    })
                    .then(response => response.json())
                    .then(specs => {
                        specsContainer.innerHTML = '';
                        
                        if (specs.length > 0) {
                            specs.forEach(spec => {
                                loadSpecificationValues(spec.id, spec.spesifikasi);
                            });
                        }
                    });
                }
            }

            function loadSpecificationValues(specId, specName) {
                fetch('', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `get_spec_values=1&spec_id=${specId}`
                })
                .then(response => response.json())
                .then(values => {
                    const specsContainer = document.getElementById('specifications-container');
                    
                    if (values.length > 0) {
                        const specDiv = document.createElement('div');
                        specDiv.className = 'mb-3';
                        specDiv.innerHTML = `
                            <label class="form-label">${specName}</label>
                            <div class="row">
                                ${values.map(value => `
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="specifications[${specId}][]" value="${value.id}" id="spec_${specId}_${value.id}" ${selectedSpecifications[specId] && selectedSpecifications[specId].includes(value.id.toString()) ? 'checked' : ''}>
                                            <label class="form-check-label" for="spec_${specId}_${value.id}">
                                                ${value.value}
                                            </label>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                        `;
                        specsContainer.appendChild(specDiv);
                    }
                });
            }

            // Load specifications on page load
            document.addEventListener('DOMContentLoaded', function() {
                loadSpecifications();
            });
        </script>
    </body>
</html>
