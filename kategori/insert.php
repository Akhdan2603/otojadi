<?php
require_once "../dbcontroller.php";
$db = new dbcontroller;
session_start();

$user = $_SESSION['email'];
if (isset($_GET['log'])) {
    session_destroy();
    header("location:../login.php");
}

// Simpan data kategori baru
if (isset($_POST['simpan'])) {
    $kat = $_POST['kategori'];

    $sql = "INSERT INTO kategori VALUES (NULL,'$kat')";
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
    <title>Insert Category - otojadi</title>
    <link rel="icon" href="../public/images/logo-otojadi.png" type="image/gif" sizes="16x16">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/styless.css" rel="stylesheet" />
    <link href="../css/admin.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a style="text-align:center; margin-top:5px;" class="navbar-brand ps-2" href="../kelolaproduk.php">
            <img src="../public/images/nav_logo.png" alt="" style="height:38px;">
        </a>
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle"><i class="fas fa-bars"></i></button>
        <ul class="navbar-nav ms-auto ms-md-8 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user fa-fw"></i>
                </a>
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
                        <a class="nav-link" href="../kelolaproduk.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                        <a class="nav-link" href="select.php">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-list"></i></div>
                            Category
                        </a>
                        <a class="nav-link" href="../barang/select.php">
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
                    <h1 class="mt-4 text-center"><i class="fas fa-plus-circle"></i> Insert Category</h1>
                    
                    <div class="main-content">
                        <div class="form-container">
                            <form action="" method="post">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5 class="mb-3"><i class="fas fa-info-circle"></i> Category Information</h5>
                                        <div class="mb-3">
                                            <label for="kategori" class="form-label">Category Name *</label>
                                            <input type="text" id="kategori" name="kategori" required placeholder="Enter category name" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mt-4">
                                    <a href="select.php" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Back to List
                                    </a>
                                    <button type="submit" name="simpan" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Save Category
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
</body>
</html>
