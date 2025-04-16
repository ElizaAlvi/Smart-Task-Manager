<?php
session_start();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $conn = new mysqli("localhost", "root", "", "sdl");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $email = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT * FROM teachers WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $teacher = $result->fetch_assoc();
        if (password_verify($password, $teacher['password'])) {
            $_SESSION['teacher_id'] = $teacher['id'];
            $_SESSION['teacher_name'] = $teacher['name'];
            header("Location: teacher_dashboard.php"); // Redirect on success
            exit;
        } else {
            echo "<script>alert('Incorrect password!');</script>";
        }
    } else {
        echo "<script>alert('No account found with that email!');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!-- HTML PART -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Teacher Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #e3f2fd;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        } 
        .login-container {
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
    <div class="login-container">
        <h2 class="mb-3">Teacher Login</h2>
        <form method="POST">
            <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
            <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
        <p class="mt-3">Don't have an account? <a href="teacher_signup.php">Sign Up</a></p>
    </div>
</body>
</html>
