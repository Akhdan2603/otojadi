<?php
require_once "dbcontroller.php";
$db = new dbcontroller;
session_start();

// Cek login admin
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$userEmail = $_SESSION['email'];
$userData = $db->getITEM("SELECT * FROM user WHERE email='$userEmail'");
if (!$userData || $userData['peran'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Logout
if (isset($_GET['log'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}

// Statistik
$jumlahRegisteredUsers = $db->rowCOUNT("SELECT id FROM user WHERE peran='user'");
$jumlahProductsOnSale = $db->rowCOUNT("SELECT id FROM product");

// Hitung pending subscriptions (belum subscribe)
$jumlahPendingSubscriptions = $db->rowCOUNT("SELECT id FROM user WHERE is_subscribed = 0");
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Dashboard - SB Admin</title>
        <link rel="icon" href="public\images\logo-otojadi.png" type="image/gif" sizes="16x16">
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="css/styless.css" rel="stylesheet" />
        <link href="css/admin.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>

    <body class="sb-nav-fixed">
        
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <a style = "text-align: center; margin-top: 5px;"class="navbar-brand ps-2" href="kelolaproduk.php"><img src="public/images/nav_logo.png" alt="" style="height: 38px;"></a>
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
            <ul class="navbar-nav ms-auto ms-md-8 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="?log=logout">Logout</a></li>
                        <li><a class="dropdown-item" href="index.php">Halaman Utama</a></li>
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
                            
                            <a class="nav-link" href="kelolaproduk.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Dashboard
                            </a>
                            
                            <a class="nav-link" href="kategori/select.php">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-list"></i></div>
                                Category
                            </a>
                            <a class="nav-link" href="barang/select.php">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-box"></i></div>
                                Product
                            </a>

                            <a class="nav-link" href="type/select.php">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-tags"></i></div>
                                Type
                            </a>

                            <a class="nav-link" href="profile/select.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
                                User
                            </a>

                    

                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as: <span><?php echo $_SESSION['email'] ?></span></div>
                    </div>
                </nav>
            </div>
            
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Dashboard Admin</h1>

                        <ol class="breadcrumb mb-4 d-flex justify-content-between align-items-center">
                            <li class="breadcrumb-item active">Selamat Datang Admin</li>
                            <a style="background: #031E27;" class="btn btn-success" href="laporan.php" role="button">
                                <i  class="far fa-file-excel"></i> Laporan

                            </a>
                        </ol>

                        <div class="row">
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-primary text-white mb-4">
                                    <div class="card-body">Registered Users: <?php echo $jumlahRegisteredUsers; ?></div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="profile/select.php">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-info text-white mb-4">
                                    <div class="card-body">Products on Sale: <?php echo $jumlahProductsOnSale; ?></div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="barang/select.php">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                        
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-warning text-white mb-4">
                                    <div class="card-body">UnSubscribe <?php echo $jumlahPendingSubscriptions; ?></div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="order/select.php">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                User Subscriptions
                            </div>

                            <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Email</th>
                                            <th>Tanggal Lahir</th>
                                            <th>Status Subscription</th>
                                            <th>Mulai Subscription</th>
                                            <th>Berakhir Subscription</th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody>
                                    <?php
                                    // Ambil semua user
                                    $users = $db->getALL("SELECT * FROM user WHERE peran='user' ORDER BY created_at DESC");
                                    $no = 1;
                                    if(!empty($users)) {
                                        foreach ($users as $user) : 
                                    ?>
                                        <tr>
                                            <td><?php echo $no++; ?></td>
                                            <td><?php echo $user['nama']; ?></td>
                                            <td><?php echo $user['email']; ?></td>
                                            <td><?php echo !empty($user['tgl_lahir']) ? date('d/m/Y', strtotime($user['tgl_lahir'])) : '-'; ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo $user['is_subscribed'] ? 'success' : 'secondary'; ?>">
                                                    <?php echo $user['is_subscribed'] ? 'Aktif' : 'Belum Aktif'; ?>
                                                </span>
                                            </td>
                                            <td><?php echo !empty($user['subscription_start']) ? date('d/m/Y', strtotime($user['subscription_start'])) : '-'; ?></td>
                                            <td><?php echo !empty($user['subscription_end']) ? date('d/m/Y', strtotime($user['subscription_end'])) : '-'; ?></td>
                                        </tr>
                                    <?php 
                                        endforeach;
                                    } 
                                    ?>
                                        
                                    </tbody>
                                </table>
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

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>
    </body>
</html>
