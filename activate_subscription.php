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

if ($user['is_subscribed']) {
    $allProducts = $db->getALL("SELECT id FROM product");
    
    if (!empty($allProducts)) {
        foreach ($allProducts as $product) {
            $product_id = $product['id'];
            $exists = $db->rowCOUNT("SELECT * FROM user_templates WHERE user_id = $user_id AND product_id = $product_id");
            
            if ($exists == 0) {
                $db->runSQL("INSERT INTO user_templates (user_id, product_id) VALUES ($user_id, $product_id)");
            }
        }
    }
    
    header("Location: myworkspace.php?activated=1");
} else {
    header("Location: subscribe.php");
}
exit;
