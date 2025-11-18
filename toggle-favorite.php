<?php
session_start();
require_once "dbcontroller.php";

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $userId = $_SESSION['iduser'];
    $itemId = intval($data['itemId']);
    $action = $data['action'];

    $db = new dbcontroller();

    if ($action === 'add') {
        $result = $db->addToFavorite($userId, $itemId);
    } elseif ($action === 'remove') {
        $result = $db->removeFromFavorite($userId, $itemId);
    }

    echo json_encode(['success' => $result, 'action' => $action, 'itemId' => $itemId]);
}
?>