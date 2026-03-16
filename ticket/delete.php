<?php

require_once '../db.php';

$id = $_GET['id'] ?? 0;
if($id > 0) {
    $sql = 'DELETE FROM tickets where id = ?';
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header('location: index.php');
    exit();
}

echo 'Ticket not found!';