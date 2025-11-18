<?php
require_once "../dbcontroller.php";
$db = new dbcontroller;

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM kategori WHERE id=$id ORDER BY id DESC LIMIT 1";

    $db->runSQL($sql);

    header("Location: select.php");
}
