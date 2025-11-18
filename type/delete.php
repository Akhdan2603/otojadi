<?php
require_once "../dbcontroller.php";
$db = new dbcontroller;

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // keamanan tambahan agar id pasti integer

    // Hapus data brand berdasarkan id
    $sql = "DELETE FROM type WHERE id = $id";
    $db->runSQL($sql);

    // Kembali ke halaman select.php setelah penghapusan
    header("Location: select.php");
    exit;
}
?>
