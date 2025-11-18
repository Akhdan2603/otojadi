<?php
    require_once "../dbcontroller.php";
    $db = new dbcontroller;
    session_start();

    $user = $_SESSION['email'];
    if (isset($_GET['log'])) {
        session_destroy();
        header("location:../login.php");
    }

    if (isset($_GET['id'])) {
        $order_id = $_GET['id'];
        
        // Get order information
        $order_sql = "SELECT orders.*, user.nama AS customer_name 
                      FROM orders 
                      INNER JOIN user ON orders.id_user = user.id 
                      WHERE orders.id = $order_id";
        $order_info = $db->getITEM($order_sql);
        
        // Get order details
        $details_sql = "SELECT order_details.*, barang.nama_produk, barang.gambar, kategori.kategori
                        FROM order_details
                        INNER JOIN barang ON order_details.id_barang = barang.id
                        INNER JOIN kategori ON barang.id_kategori = kategori.id
                        WHERE order_details.id_order = $order_id";
        $order_details = $db->getALL($details_sql);
    } else {
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
        <title>Order Detail #<?php echo $order_id ?> - VirtualZone</title>
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

                            <a class="nav-link" href="select.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-luggage-cart"></i></div>
                                Detail Orders
                            </a>

                            <a class="nav-link" href="../diskon/select.php">
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
                        <h1 class="mt-4">Order Detail #<?php echo $order_id ?></h1>

                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="select.php">Order Details</a></li>
                            <li class="breadcrumb-item active">Order #<?php echo $order_id ?></li>
                        </ol>

                        <div class="card mb-4">
                            <div class="card-header">
                                <h5><i class="fas fa-info-circle"></i> Order Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Customer:</strong> <?php echo $order_info['customer_name'] ?></p>
                                        <p><strong>Order Date:</strong> <?php echo date('d/m/Y H:i', strtotime($order_info['tanggal_pembelian'])) ?></p>
                                        <p><strong>Status:</strong> 
                                            <?php
                                            $statusClass = '';
                                            switch($order_info['status']) {
                                                case 'Packaging': $statusClass = 'bg-warning'; break;
                                                case 'Shipping': $statusClass = 'bg-info'; break;
                                                case 'Arrived': $statusClass = 'bg-primary'; break;
                                                case 'Completed': $statusClass = 'bg-success'; break;
                                                default: $statusClass = 'bg-secondary';
                                            }
                                            ?>
                                            <span class="badge <?php echo $statusClass ?>"><?php echo $order_info['status'] ?></span>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Shipping Method:</strong> <?php echo $order_info['shipment'] ?></p>
                                        <p><strong>Payment Method:</strong> <?php echo $order_info['payment'] ?></p>
                                        <p><strong>Total Amount:</strong> <span class="h5 text-success">Rp <?php echo number_format($order_info['total_harga'], 0, ',', '.') ?></span></p>
                                    </div>
                                </div>
                                <p><strong>Shipping Address:</strong> <?php echo $order_info['shipping_address'] ?></p>
                            </div>
                        </div>
                        
                        <!-- Order Items -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5><i class="fas fa-shopping-cart"></i> Order Items</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Category</th>
                                                <th>Image</th>
                                                <th>Specification</th>
                                                <th>Color</th>
                                                <th>Quantity</th>
                                                <th>Unit Price</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(!empty($order_details)) { ?>
                                                <?php foreach ($order_details as $detail) : ?>
                                                    <?php $total = $detail['harga'] * $detail['quantity']; ?>
                                                    <tr>
                                                        <td><strong><?php echo $detail['nama_produk'] ?></strong></td>
                                                        <td><span class="badge bg-secondary"><?php echo $detail['kategori'] ?></span></td>
                                                        <td>
                                                            <img style="width:60px; border-radius: 4px;" src="../public/images/<?php echo $detail['gambar'] ?>" alt="<?php echo $detail['nama_produk'] ?>">
                                                        </td>
                                                        <td><?php echo $detail['spesifikasi'] ?: 'N/A' ?></td>
                                                        <td>
                                                            <?php 
                                                            $colorName = str_replace('.jpg', '', str_replace('.png', '', $detail['warna']));
                                                            echo $colorName;
                                                            ?>
                                                        </td>
                                                        <td><span class="badge bg-info"><?php echo $detail['quantity'] ?></span></td>
                                                        <td>Rp <?php echo number_format($detail['harga'], 0, ',', '.') ?></td>
                                                        <td><strong class="text-primary">Rp <?php echo number_format($total, 0, ',', '.') ?></strong></td>
                                                    </tr>
                                                <?php endforeach ?>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-start">
                            <a href="select.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Orders
                            </a>
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
    </body>
</html>
