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
    case 'add':
        if (!isset($_POST['product_id'])) {
            echo json_encode(['success' => false, 'message' => 'Product ID is required']);
            exit;
        }

        $product_id = intval($_POST['product_id']);
        $folder_id = isset($_POST['folder_id']) && $_POST['folder_id'] !== '' ? intval($_POST['folder_id']) : 'NULL';
        
        // Check if already exists in the same folder
        if ($folder_id !== 'NULL') {
            $exists = $db->rowCOUNT("SELECT * FROM user_templates WHERE user_id = $user_id AND product_id = $product_id AND folder_id = $folder_id");
        } else {
            $exists = $db->rowCOUNT("SELECT * FROM user_templates WHERE user_id = $user_id AND product_id = $product_id AND folder_id IS NULL");
        }
        
        if ($exists > 0) {
            echo json_encode(['success' => false, 'message' => 'Template already added to this folder']);
            exit;
        }

        // Verify folder belongs to user if folder_id is set
        if ($folder_id !== 'NULL') {
            $folder = $db->getITEM("SELECT * FROM workspace_folders WHERE id = $folder_id AND user_id = $user_id");
            if (!$folder) {
                echo json_encode(['success' => false, 'message' => 'Folder not found or access denied']);
                exit;
            }
        }

        $sql = "INSERT INTO user_templates (user_id, product_id, folder_id) VALUES ($user_id, $product_id, $folder_id)";
        $db->runSQL($sql);

        if ($db->getAffectedRows() > 0) {
            echo json_encode(['success' => true, 'message' => 'Template added successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add template']);
        }
        break;

    case 'remove':
        if (!isset($_POST['template_id'])) {
            echo json_encode(['success' => false, 'message' => 'Template ID is required']);
            exit;
        }

        $template_id = intval($_POST['template_id']);
        
        $template = $db->getITEM("SELECT * FROM user_templates WHERE id = $template_id AND user_id = $user_id");
        if (!$template) {
            echo json_encode(['success' => false, 'message' => 'Template not found or access denied']);
            exit;
        }

        $db->runSQL("DELETE FROM user_templates WHERE id = $template_id AND user_id = $user_id");

        if ($db->getAffectedRows() > 0) {
            echo json_encode(['success' => true, 'message' => 'Template removed successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to remove template']);
        }
        break;

    case 'move':
        if (!isset($_POST['template_id'])) {
            echo json_encode(['success' => false, 'message' => 'Template ID is required']);
            exit;
        }

        $template_id = intval($_POST['template_id']);
        $folder_id = isset($_POST['folder_id']) && $_POST['folder_id'] !== '' ? intval($_POST['folder_id']) : 'NULL';
        
        $template = $db->getITEM("SELECT * FROM user_templates WHERE id = $template_id AND user_id = $user_id");
        if (!$template) {
            echo json_encode(['success' => false, 'message' => 'Template not found or access denied']);
            exit;
        }

        if ($folder_id !== 'NULL') {
            $folder = $db->getITEM("SELECT * FROM workspace_folders WHERE id = $folder_id AND user_id = $user_id");
            if (!$folder) {
                echo json_encode(['success' => false, 'message' => 'Folder not found or access denied']);
                exit;
            }
        }

        $db->runSQL("UPDATE user_templates SET folder_id = $folder_id WHERE id = $template_id AND user_id = $user_id");

        if ($db->getAffectedRows() > 0) {
            echo json_encode(['success' => true, 'message' => 'Template moved successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to move template']);
        }
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
}
