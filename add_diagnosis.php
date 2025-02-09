<?php
session_start();
require 'database/db.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patient_id = $_POST["patient_id"];
    $diagnosis = $_POST["diagnosis"];
    $doctor_id = $user_id;

    $stmt = $conn->prepare("SELECT id FROM patients WHERE id = ? AND user_id = ?");
    $stmt->execute([$patient_id, $user_id]);
    
    if ($stmt->rowCount() == 0) {
        die("Error: This patient does not exist or you do not have permission to diagnose them!");
    }

    try {
        $stmt = $conn->prepare("INSERT INTO diagnoses (patient_id, doctor_id, diagnosis) VALUES (?, ?, ?)");
        $stmt->execute([$patient_id, $doctor_id, $diagnosis]);

        header("Location: diagnoses.php");
        exit();
    } catch (PDOException $e) {
        die("Error occurred while inserting data: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Diagnosis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h4>Add New Diagnosis</h4>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label for="patient_id" class="form-label">Select Patient:</label>
                            <select name="patient_id" id="patient_id" class="form-select" required>
                                <option value="" disabled selected>Choose a patient</option>
                                <?php
                                $stmt = $conn->prepare("SELECT id, name FROM patients WHERE user_id = ?");
                                $stmt->execute([$user_id]);
                                while ($patient = $stmt->fetch()) {
                                    echo "<option value='{$patient["id"]}'>{$patient["name"]}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="diagnosis" class="form-label">Diagnosis:</label>
                            <textarea name="diagnosis" id="diagnosis" class="form-control" placeholder="Enter diagnosis here..." rows="4" required></textarea>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Add Diagnosis</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="text-center mt-3">
                <a href="diagnoses.php" class="btn btn-secondary">Back to List</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
