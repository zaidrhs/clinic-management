<?php
session_start();
require 'database/db.php';

if (isset($_GET["id"])) {
    $patient_id = $_GET["id"];
    $user_id = $_SESSION["user_id"];

    $stmt = $conn->prepare("DELETE FROM patients WHERE id = ? AND user_id = ?");
    $stmt->execute([$patient_id, $user_id]);

    header("Location: patients.php");
    exit();
}
?>
