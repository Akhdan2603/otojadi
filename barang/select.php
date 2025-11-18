<?php
require_once "../dbcontroller.php";
$db = new dbcontroller;
session_start();

$user = $_SESSION['email'];
if (isset($_GET['log'])) {
    session_destroy();
    header("location:../login.php");
}

// Pagination
$jumlahdata = $db->rowCOUNT("SELECT id FROM product");
$banyak = 20;
$halaman = ceil($jumlahdata / $banyak);

if (isset($_GET['p'])) {
    $p = $_GET['p'];
    $mulai = ($p * $banyak) - $banyak;
} else {
    $mulai = 0;
}

// Ambil data dari tabel product dengan join kategori
$sql = "SELECT p.id, p.nama_produk, p.gambar, p.link, p.description, p.created_at, p.updated_at,
        k.kategori AS nama_kategori
        FROM product p
        LEFT JOIN kategori k ON p.id_kategori = k.id
        ORDER BY p.id DESC
        LIMIT $mulai, $banyak";
$row = $db->getALL($sql);
$no = 1 + $mulai;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Product - VirtualZone</title>
    <link rel="icon" href="../public/images/logo-otojadi.png" type="image/gif" sizes="16x16">
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
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
                            <a class="nav-link" href="../barang/select.php">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-box"></i></div>
                                Product
                            </a>

                            <a class="nav-link" href="../type/select.php">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-tags"></i></div>
                                Type
                            </a>

                            <a class="nav-link" href="select.php">
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
                    <h1 class="mt-4">Product</h1>
                    <div class="button mb-2" style="text-align:right;">
                        <a href="insert.php" class="btn btn-outline-primary">Insert</a>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header"><i class="fas fa-table me-1"></i> DataTable Product</div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kategori</th>
                                        <th>Nama Produk</th>
                                        <th>Gambar</th>
                                        <th>Link</th>
                                        <th>Description</th>
                                        <th>Created At</th>
                                        <th>Updated At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if(!empty($row)) { foreach ($row as $r) : ?>
                                    <tr>
                                        <td><?php echo $no++ ?></td>
                                        <td><?php echo $r['nama_kategori'] ?></td>
                                        <td><?php echo $r['nama_produk'] ?></td>
                                        <td>
                                            <?php if($r['gambar']): ?>
                                                <?php 
                                                    // Pisahkan nama file berdasarkan koma
                                                    $gambarList = explode(',', $r['gambar']); 
                                                    // Ambil hanya gambar pertama
                                                    $gambarPertama = trim($gambarList[0]); 
                                                ?>
                                                <img style="width:85px" src="../public/images/produk/<?php echo $gambarPertama; ?>" alt="">
                                            <?php else: ?>
                                                N/A
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo $r['link'] ?></td>
                                        <td><?php echo $r['description'] ?></td>
                                        <td><?php echo $r['created_at'] ?></td>
                                        <td><?php echo $r['updated_at'] ?></td>
                                        <td>
                                            <a href="update.php?id=<?php echo $r['id']; ?>" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                                            <a href="delete.php?id=<?php echo $r['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this product?')"><i class="fas fa-trash-alt"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>

            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">&copy; otojadi</div>
                        <div>
                            <a href="#">Privacy Policy</a> &middot; <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="../js/datatables-simple-demo.js"></script>
</body>
</html>
