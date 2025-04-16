<?php
// Start session to keep user logged in
session_start();

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $conn = new mysqli("localhost", "root", "", "sdl");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM students WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Store user PRN in session (you can use this for dashboard personalization)
            $_SESSION['prn'] = $row['prn'];
            header("Location: student_dashboard.php");
            exit;
        } else {
            echo "<script>alert('Incorrect password!'); window.history.back();</script>";
            exit;
        }
    } else {
        echo "<script>alert('No user found with that email!'); window.history.back();</script>";
        exit;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4 shadow-lg rounded" style="width: 100%; max-width: 450px;">
            <h2 class="text-center text-primary mb-3">Student Login</h2>
            <form method="POST">
                <div class="mb-3">
                    <input type="email" class="form-control" name="email" placeholder="Email" required>
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
            <p class="text-center mt-3">
                Don't have an account? <a href="student_signup.php">Sign up</a>
            </p>
        </div>
    </div>
</body>
</html>
