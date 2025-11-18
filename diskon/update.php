<?php
    require_once "../dbcontroller.php";
    $db = new dbcontroller;
    session_start();

    $user = $_SESSION['email'];
    if (isset($_GET['log'])) {
        session_destroy();
        header("location:..\login.php");
    }

    if (isset($_GET['id'])) {
        $id = $_GET['id']; 
        $sql = "SELECT * FROM barang WHERE id=$id";
        $item = $db->getITEM($sql);
    }

    if (isset($_POST['simpan'])) {
        $dis = $_POST['diskon'];

        $sql = "UPDATE barang SET diskon='$dis' WHERE id='$id'";
        $db->runSQL($sql);
    
        echo "<script> window.location.assign('select.php'); </script>";
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
        <title>Update Discount - VirtualZone</title>
        <link rel="icon" href="../public/images/logo2.png" type="image/gif" sizes="16x16">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/styless.css" rel="stylesheet" />
        <link href="../css/admin.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>

    <body class="sb-nav-fixed">
        
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <a style = "text-align: center; margin-top: 5px;"class="navbar-brand ps-2" href="../kelolaproduk.php"><img src="../public/images/logo_nobg.png" alt="" style="height: 38px;"></a>
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

                            <a class="nav-link" href="../brand/select.php">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-tags"></i></div>
                                Brand
                            </a>

                            <a class="nav-link" href="../profile/select.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
                                User
                            </a>

                            <a class="nav-link" href="../order/select.php">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-cart-shopping"></i></div>
                                Order
                            </a>

                            <a class="nav-link" href="../orderdetail/select.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-luggage-cart"></i></div>
                                Detail Orders
                            </a>

                            <a class="nav-link" href="select.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tag"></i></div>
                                Diskon
                            </a>
                            
                        </div>
                    </div>
                </nav>
            </div>
            
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4 text-center"><i class="fas fa-percentage"></i> Update Product Discount</h1>

                        <div class="main-content">
                            <div class="form-container">
                                <?php
                                    $hargaAwal = $item['harga'];
                                    $diskonSekarang = $item['diskon'] ?? 0;
                                    $hargaDiskon = $hargaAwal - ($hargaAwal * $diskonSekarang / 100);
                                ?>
                                

                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5><i class="fas fa-info-circle"></i> Product Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <img src="../public/images/<?php echo $item['gambar'] ?>" alt="<?php echo $item['nama_produk'] ?>" class="img-fluid rounded">
                                            </div>
                                            <div class="col-md-8">
                                                <h4><?php echo $item['nama_produk'] ?></h4>
                                                <p class="text-muted"><?php echo $item['detail'] ?></p>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <strong>Original Price:</strong><br>
                                                        <span class="h5 text-dark">Rp <?php echo number_format($hargaAwal, 0, ',', '.') ?></span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>Current Discount:</strong><br>
                                                        <span class="h5 text-danger">
                                                            <?php echo $diskonSekarang ?>%
                                                        </span>
                                                    </div>
                                                </div>
                                                <?php if($diskonSekarang > 0): ?>
                                                <div class="mt-2">
                                                    <strong>Current Sale Price:</strong><br>
                                                    <span class="h4 text-primary">Rp <?php echo number_format($hargaDiskon, 0, ',', '.') ?></span>
                                                    <small class="text-danger">(-Rp <?php echo number_format($hargaAwal - $hargaDiskon, 0, ',', '.') ?> Prize Cut)</small>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <form action="" method="post">
                                    <div class="row">
                                        <div class="col-md-6 mx-auto">
                                            <h5 class="mb-3"><i class="fas fa-tag"></i> Discount Settings</h5>
                                            
                                            <div class="mb-3">
                                                <label for="diskon" class="form-label">Discount Percentage (%)</label>
                                                <input type="number" id="diskon" name="diskon" class="form-control" 
                                                       value="<?= $item['diskon'] ?? 0 ?>" min="0" max="100" required
                                                       onchange="calculateDiscount()">
                                                <div class="form-text">Enter a value between 0 and 100</div>
                                            </div>

                                            <div class="card bg-light">
                                                <div class="card-body">
                                                    <h6>Price Preview:</h6>
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <small class="text-muted">Original Price:</small><br>
                                                            <strong id="originalPrice" class="text-dark">Rp <?php echo number_format($hargaAwal, 0, ',', '.') ?></strong>
                                                        </div>
                                                        <div class="col-6">
                                                            <small class="text-muted">Sale Price:</small><br>
                                                            <strong id="salePrice" class="text-primary">Rp <?php echo number_format($hargaDiskon, 0, ',', '.') ?></strong>
                                                        </div>
                                                    </div>
                                                    <small class="text-muted">Prize Cut: <span id="savings" class="text-danger">-Rp <?php echo number_format($hargaAwal - $hargaDiskon, 0, ',', '.') ?></span></small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between mt-4">
                                        <a href="select.php" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left"></i> Back to List
                                        </a>
                                        <button type="submit" name="simpan" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Update Discount
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
                            <div class="text-muted">Copyright &copy; VirtualZone</div>
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
            const originalPrice = <?php echo $hargaAwal; ?>;
            
            function calculateDiscount() {
                const discountPercent = document.getElementById('diskon').value || 0;
                const discount = originalPrice * (discountPercent / 100);
                const salePrice = originalPrice - discount;
                
                document.getElementById('salePrice').innerHTML = '<span class="text-primary">Rp ' + salePrice.toLocaleString('id-ID') + '</span>';
                document.getElementById('savings').textContent = '-Rp ' + discount.toLocaleString('id-ID');
            }
        </script>
    </body>
</html>

