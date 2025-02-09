<?php
session_start();
require 'database/db.php';

if (isset($_GET["id"])) {
    $diagnosis_id = $_GET["id"];
    $doctor_id = $_SESSION["user_id"];

    $stmt = $conn->prepare("DELETE FROM diagnoses WHERE id = ? AND doctor_id = ?");
    $stmt->execute([$diagnosis_id, $doctor_id]);

    header("Location: diagnoses.php");
    exit();
}
?>
