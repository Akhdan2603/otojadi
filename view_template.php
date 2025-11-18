<?php
session_start();
require_once "dbcontroller.php";

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: myworkspace.php");
    exit;
}

$db = new dbcontroller();
$user_id = $_SESSION['iduser'];
$product_id = intval($_GET['id']);

$user = $db->getITEM("SELECT * FROM user WHERE id = $user_id");

if (!$user['is_subscribed'] || strtotime($user['subscription_end']) < time()) {
    header("Location: subscribe.php");
    exit;
}

$product = $db->getITEM("
    SELECT p.*, k.kategori, t.nama_type
    FROM product p
    JOIN kategori k ON p.id_kategori = k.id
    JOIN type t ON p.id_type = t.id
    WHERE p.id = $product_id
");

if (!$product) {
    header("Location: myworkspace.php");
    exit;
}

$exists = $db->rowCOUNT("SELECT * FROM template_recent WHERE user_id = $user_id AND product_id = $product_id");

if ($exists > 0) {
    $db->runSQL("UPDATE template_recent SET accessed_at = NOW() WHERE user_id = $user_id AND product_id = $product_id");
} else {
    $db->runSQL("INSERT INTO template_recent (user_id, product_id) VALUES ($user_id, $product_id)");
}

header("Location: " . $product['link']);
exit;
