<?php
session_start();
header('Content-Type: application/json');
require_once "../dbcontroller.php";

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

$db = new dbcontroller();
$user_id = $_SESSION['iduser'];

if (!isset($_POST['action'])) {
    echo json_encode(['success' => false, 'message' => 'No action specified']);
    exit;
}

$action = $_POST['action'];

switch ($action) {
    case 'create':
        if (!isset($_POST['folder_name']) || trim($_POST['folder_name']) == '') {
            echo json_encode(['success' => false, 'message' => 'Folder name is required']);
            exit;
        }

        $folder_name = mysqli_real_escape_string($db->koneksiDB(), trim($_POST['folder_name']));
        $sql = "INSERT INTO workspace_folders (user_id, folder_name) VALUES ($user_id, '$folder_name')";
        $db->runSQL($sql);

        if ($db->getAffectedRows() > 0) {
            echo json_encode(['success' => true, 'message' => 'Folder created successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to create folder']);
        }
        break;

    case 'delete':
        if (!isset($_POST['folder_id'])) {
            echo json_encode(['success' => false, 'message' => 'Folder ID is required']);
            exit;
        }

        $folder_id = intval($_POST['folder_id']);
        
        $folder = $db->getITEM("SELECT * FROM workspace_folders WHERE id = $folder_id AND user_id = $user_id");
        if (!$folder) {
            echo json_encode(['success' => false, 'message' => 'Folder not found or access denied']);
            exit;
        }

        $db->runSQL("UPDATE user_templates SET folder_id = NULL WHERE folder_id = $folder_id AND user_id = $user_id");
        $db->runSQL("DELETE FROM workspace_folders WHERE id = $folder_id AND user_id = $user_id");

        if ($db->getAffectedRows() > 0) {
            echo json_encode(['success' => true, 'message' => 'Folder deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete folder']);
        }
        break;

    case 'rename':
        if (!isset($_POST['folder_id']) || !isset($_POST['folder_name'])) {
            echo json_encode(['success' => false, 'message' => 'Missing parameters']);
            exit;
        }

        $folder_id = intval($_POST['folder_id']);
        $folder_name = mysqli_real_escape_string($db->koneksiDB(), trim($_POST['folder_name']));

        $folder = $db->getITEM("SELECT * FROM workspace_folders WHERE id = $folder_id AND user_id = $user_id");
        if (!$folder) {
            echo json_encode(['success' => false, 'message' => 'Folder not found or access denied']);
            exit;
        }

        $db->runSQL("UPDATE workspace_folders SET folder_name = '$folder_name' WHERE id = $folder_id AND user_id = $user_id");

        if ($db->getAffectedRows() > 0) {
            echo json_encode(['success' => true, 'message' => 'Folder renamed successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to rename folder']);
        }
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
}
