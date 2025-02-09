<?php
session_start();
require 'database/db.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM patients WHERE user_id = ?");
$stmt->execute([$_SESSION["user_id"]]);
$patients = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Patient List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="text-primary">Patient List</h2>
        <div>
            <a href="dashboard.php" class="btn btn-secondary">Dashboard</a>
            <a href="add_patient.php" class="btn btn-success">Add New Patient</a>
        </div>
    </div>

    <div class="mb-3">
        <input type="text" id="searchInput" class="form-control" placeholder="Search by name or phone...">
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-primary">
            <tr>
                <th>Name</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="patientTable">
            <?php foreach ($patients as $patient): ?>
                <tr>
                    <td><?= htmlspecialchars($patient["name"]) ?></td>
                    <td><?= htmlspecialchars($patient["phone"]) ?></td>
                    <td>
                        <a href="edit_patient.php?id=<?= $patient["id"] ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="delete_patient.php?id=<?= $patient["id"] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this patient?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
document.getElementById("searchInput").addEventListener("keyup", function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll("#patientTable tr");

    rows.forEach(row => {
        let name = row.cells[0].textContent.toLowerCase();
        let phone = row.cells[1].textContent.toLowerCase();
        row.style.display = (name.includes(filter) || phone.includes(filter)) ? "" : "none";
    });
});
</script>

</body>
</html>
