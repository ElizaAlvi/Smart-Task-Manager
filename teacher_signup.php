<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $conn = new mysqli("localhost", "root", "", "sdl");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $name = $_POST["name"];
    $email = $_POST["email"];
    $department = $_POST["department"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"];

    if ($password !== $confirmPassword) {
        echo "<script>alert('Passwords do not match!'); window.history.back();</script>";
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO teachers (name, email, department, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $department, $hashedPassword);

    if ($stmt->execute()) {
        echo "<script>alert('Signup successful! Redirecting to login...'); window.location.href='teacher_login.php';</script>";
    } else {
        echo "<script>alert('Signup failed: Email might already exist.'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!-- HTML Part (same design as you gave, just posting form via POST) -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Teacher Signup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #e3f2fd;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .signup-container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <h2 class="mb-3">Teacher Signup</h2>
        <form method="POST">
            <input type="text" name="name" class="form-control mb-3" placeholder="Full Name" required>
            <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
            <select name="department" class="form-control mb-3" required>
                <option value="" disabled selected>Select Department</option>
                <option value="Computer Science">Computer Science</option>
                <option value="Information Technology">Information Technology</option>
                <option value="Mechanical">Mechanical</option>
                <option value="Civil">Civil</option>
                <option value="AI & ML">AI & ML</option>
            </select>
            <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
            <input type="password" name="confirmPassword" class="form-control mb-3" placeholder="Confirm Password" required>
            <button class="btn btn-success w-100">Sign Up</button>
        </form>
        <p class="mt-3">Already have an account? <a href="teacher_login.php">Login</a></p>
    </div>
</body>
</html>
