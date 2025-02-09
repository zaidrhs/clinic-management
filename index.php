<?php
session_start();
require 'database/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $specialty = $_POST["specialty"];
    $experience = $_POST["experience"];
    $phone = $_POST["phone"];

    try {
        $conn->beginTransaction();

        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $password]);

        $user_id = $conn->lastInsertId();

        $stmt = $conn->prepare("INSERT INTO doctors (user_id, name, email, password, specialty, experience, phone) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $name, $email, $password, $specialty, $experience, $phone]);

        $conn->commit();

        $_SESSION["user_id"] = $user_id;
        header("Location: dashboard.php");
        exit();
    } catch (Exception $e) {
        $conn->rollBack();
        echo "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Registration</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body class="bg-light">
<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4 shadow" style="width: 400px;">
        <h3 class="text-center"><i class="fas fa-user-md"></i> Doctor Registration</h3>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label"><i class="fas fa-user"></i> Full Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label"><i class="fas fa-envelope"></i> Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label"><i class="fas fa-lock"></i> Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label"><i class="fas fa-stethoscope"></i> Specialty</label>
                <input type="text" name="specialty" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label"><i class="fas fa-calendar-alt"></i> Experience (Years)</label>
                <input type="number" name="experience" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label"><i class="fas fa-phone"></i> Phone Number</label>
                <input type="text" name="phone" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-user-plus"></i> Register</button>
        </form>
        <p class="mt-3 text-center">Already have an account? <a href="login.php">Login</a></p>
    </div>
</div>
</body>
</html>
