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

// Ambil data kategori dan type untuk select
$kategorirow = $db->getALL("SELECT id, kategori FROM kategori ORDER BY kategori ASC");
$typerow = $db->getALL("SELECT id, nama_type FROM type ORDER BY nama_type ASC"); // sesuai database


// Handle form insert product
if (isset($_POST['simpan'])) {
    $id_kategori = $_POST['kategori'];
    $id_type = $_POST['type'];
    $nama_produk = mysqli_real_escape_string($db->koneksiDB(), trim($_POST['nama']));
    $link = mysqli_real_escape_string($db->koneksiDB(), trim($_POST['link']));
    $description = mysqli_real_escape_string($db->koneksiDB(), trim($_POST['description']));
    $gambar_name = $_FILES['gambar']['name'];
    $temp_gambar = $_FILES['gambar']['tmp_name'];

    if (empty($nama_produk) || empty($id_kategori) || empty($id_type) || empty($link) || empty($description) || empty($gambar_name)) {
        $error = "Semua field wajib diisi.";
    } else {
        $uploadDir = '../public/images/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $gambar_name = basename($_FILES['gambar']['name']); // nama asli file
        $targetFile = $uploadDir . $gambar_name;

        if (move_uploaded_file($temp_gambar, $targetFile)) {
            $sql_product = "INSERT INTO product (id_kategori, id_type, nama_produk, gambar, link, description, created_at, updated_at) 
                            VALUES ($id_kategori, $id_type, '$nama_produk', '$gambar_name', '$link', '$description', NOW(), NOW())";
            $db->runSQL($sql_product);

            $id_barang = $db->getLastInsertId();

            if (isset($_POST['specifications'])) {
                foreach ($_POST['specifications'] as $spec_id => $values) {
                    if (!empty($values) && is_array($values)) {
                        foreach ($values as $value_id) {
                            $sql_spec = "INSERT INTO spesifikasi_barang (id_barang, id_spesifikasi, id_value_spesifikasi) 
                                         VALUES ($id_barang, $spec_id, $value_id)";
                            $db->runSQL($sql_spec);
                        }
                    }
                }
            }

            header("Location: select.php");
            exit();
        } else {
            $error = "Gagal mengupload gambar.";
        }
    }
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
        <title>Insert Product - otojadi</title>
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
                        <h1 class="mt-4 text-center"><i class="fas fa-plus-circle"></i> Insert Product</h1>

                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger text-center"><?php echo $error; ?></div>
                        <?php endif; ?>

                        <div class="main-content">
                            <div class="form-container">
                                <form action="" method="post" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h5 class="mb-3"><i class="fas fa-info-circle"></i> Basic Information</h5>
                                            
                                            <div class="mb-3">
                                                <label for="kategori" class="form-label">Category *</label>
                                                <select class="form-select" name="kategori" id="kategori" required onchange="loadSpecifications()">
                                                    <option value="">Select Category</option>
                                                    <?php foreach ($kategorirow as $r) : ?>
                                                        <option value="<?php echo $r['id'] ?>"><?php echo $r['kategori'] ?></option>
                                                    <?php endforeach ?>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label for="type" class="form-label">type *</label>
                                                <select class="form-select" name="type" required>
                                                    <option value="">Select type</option>
                                                    <?php foreach ($typerow as $b) : ?>
                                                        <option value="<?php echo $b['id'] ?>"><?php echo $b['nama_type'] ?></option>
                                                    <?php endforeach ?>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label for="nama" class="form-label">Product Name *</label>
                                                <input type="text" id="nama" name="nama" required placeholder="Enter product name" class="form-control">
                                            </div>

                                            <!-- Product link -->
                                            <div class="mb-3">
                                                <label for="link" class="form-label">Product link *</label>
                                                <textarea id="link" name="link" required placeholder="Enter product link" class="form-control" rows="3"></textarea>
                                            </div>

                                            <!-- Product description -->
                                            <div class="mb-3">
                                                <label for="description" class="form-label">Product Description *</label>
                                                <textarea id="description" name="description" required placeholder="Enter product description" class="form-control" rows="3"></textarea>
                                            </div>

                                        </div>

                                        <div class="col-md-6">
                                            <h5 class="mb-3"><i class="fas fa-image"></i> Product Image</h5>
                                            
                                            <div class="mb-3">
                                                <label for="gambar" class="form-label">Image *</label>
                                                <input type="file" id="gambar" name="gambar" class="form-control" accept="image/*" onchange="previewImage(this)" required>
                                                <div class="image-preview" id="imagePreview">
                                                    <i class="fas fa-image fa-3x text-muted"></i>
                                                    <p class="text-muted mt-2">Image preview will appear here</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between mt-4">
                                        <a href="select.php" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left"></i> Back to List
                                        </a>
                                        <button type="submit" name="simpan" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Save Product
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
            function previewImage(input) {
                const preview = document.getElementById('imagePreview');
                const file = input.files[0];
                
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                    }
                    reader.readAsDataURL(file);
                } else {
                    preview.innerHTML = `
                        <i class="fas fa-image fa-3x text-muted"></i>
                        <p class="text-muted mt-2">Image preview will appear here</p>
                    `;
                }
            }

            function loadSpecifications() {
                const kategoriId = document.getElementById('kategori').value;
                const specsSection = document.getElementById('specifications-section');
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
                            specsSection.style.display = 'block';
                        } else {
                            specsSection.style.display = 'none';
                        }
                    });
                } else {
                    specsSection.style.display = 'none';
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
                                            <input class="form-check-input" type="checkbox" name="specifications[${specId}][]" value="${value.id}" id="spec_${specId}_${value.id}">
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
        </script>
    </body>
</html>
