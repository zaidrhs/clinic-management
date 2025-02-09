<?php
session_start();
require 'database/db.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$doctor_id = $_SESSION["user_id"];
$doctor_name = $_SESSION["user_name"] ?? 'Unknown';
$stmt = $conn->prepare("SELECT diagnoses.*, patients.name AS patient_name 
                        FROM diagnoses 
                        JOIN patients ON diagnoses.patient_id = patients.id
                        WHERE diagnoses.doctor_id = ?");
$stmt->execute([$doctor_id]);
$diagnoses = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Diagnosis List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="text-center mb-4"><i class="fas fa-notes-medical"></i> Diagnosis List</h2>

    <div class="d-flex justify-content-between mb-3">
        <h4><i class="fas fa-user-md"></i> Doctor: <?= htmlspecialchars($doctor_name) ?></h4>
        <div>
            <a href="patients.php" class="btn btn-warning"><i class="fas fa-users"></i> View Patients</a>
            <a href="add_diagnosis.php" class="btn btn-primary"><i class="fas fa-file-medical"></i> Add New Diagnosis</a>
        </div>
    </div>

    <div class="mb-3">
        <input type="text" id="searchInput" class="form-control" placeholder="Search for a patient...">
    </div>

    <table class="table table-bordered table-striped shadow-sm bg-white">
        <thead class="table-dark">
            <tr>
                <th>Patient Name</th>
                <th>Diagnosis</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="diagnosisTable">
            <?php foreach ($diagnoses as $diagnosis): ?>
                <tr>
                    <td><?= htmlspecialchars($diagnosis["patient_name"]) ?></td>
                    <td><?= htmlspecialchars($diagnosis["diagnosis"]) ?></td>
                    <td>
                        <a href="delete_diagnosis.php?id=<?= $diagnosis["id"] ?>" 
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Are you sure you want to delete?')">
                           <i class="fas fa-trash"></i> Delete
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
document.getElementById("searchInput").addEventListener("keyup", function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll("#diagnosisTable tr");

    rows.forEach(row => {
        let patientName = row.cells[0].textContent.toLowerCase();
        row.style.display = patientName.includes(filter) ? "" : "none";
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
