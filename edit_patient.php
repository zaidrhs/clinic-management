<?php
session_start();
require 'database/db.php';

if (!isset($_GET["id"])) {
    header("Location: patients.php");
    exit();
}

$patient_id = $_GET["id"];
$user_id = $_SESSION["user_id"];

$stmt = $conn->prepare("SELECT * FROM patients WHERE id = ? AND user_id = ?");
$stmt->execute([$patient_id, $user_id]);
$patient = $stmt->fetch();

if (!$patient) {
    echo "You cannot edit this patient!";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $age = $_POST["age"];
    $gender = $_POST["gender"];
    $phone = $_POST["phone"];

    $stmt = $conn->prepare("UPDATE patients SET name = ?, age = ?, gender = ?, phone = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$name, $age, $gender, $phone, $patient_id, $user_id]);

    header("Location: patients.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Patient Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0">
                <div class="card-body">
                    <h3 class="text-center text-primary"><i class="fas fa-edit"></i> Edit Patient Details</h3>
                    <form method="POST" class="mt-4">
                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-user"></i> Name</label>
                            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($patient["name"]) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-birthday-cake"></i> Age</label>
                            <input type="number" name="age" class="form-control" value="<?= htmlspecialchars($patient["age"]) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-venus-mars"></i> Gender</label>
                            <select name="gender" class="form-select">
                                <option value="Male" <?= $patient["gender"] == "Male" ? "selected" : "" ?>>Male</option>
                                <option value="Female" <?= $patient["gender"] == "Female" ? "selected" : "" ?>>Female</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-phone"></i> Phone</label>
                            <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($patient["phone"]) ?>" required>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="patients.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
                            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
