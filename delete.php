<?php
include 'config.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "DELETE FROM login_system WHERE id=$id";
    
    if (mysqli_query($conn, $sql)) {
        header('Location: admin-dashboard-user.php');
        exit();
    } else {
        echo "<div class='alert alert-danger' role='alert'>Error deleting record: " . mysqli_error($conn) . "</div>";
    }
}
?>
