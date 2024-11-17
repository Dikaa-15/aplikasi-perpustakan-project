<?php
require_once '../Controller/UserController.php';

$userController = new UserController();

if (isset($_GET['id'])) {
    $id_user = $_GET['id'];
    $result = $userController->deleteUser($id_user);
    if ($result) { 
        header('Location: view.php'); 
        exit; 
    } else {
        echo "Failed to delete user.";
    }
} else {
    echo "No user ID provided.";
}
?>