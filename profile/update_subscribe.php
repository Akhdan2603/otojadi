<?php
require_once "../dbcontroller.php";
$db = new dbcontroller;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $is_subscribed = intval($_POST['is_subscribed']);
    $start = !empty($_POST['subscription_start']) ? $_POST['subscription_start'] : NULL;
    $end = !empty($_POST['subscription_end']) ? $_POST['subscription_end'] : NULL;

    // Pastikan ID valid
    if ($id > 0) {
        $sql = "UPDATE user 
                SET is_subscribed = '$is_subscribed',
                    subscription_start = " . ($start ? "'$start'" : "NULL") . ",
                    subscription_end = " . ($end ? "'$end'" : "NULL") . "
                WHERE id = $id";
        $db->runSQL($sql);
    }
}

// Kembali ke halaman user
header("Location: select.php");
exit;
?>
