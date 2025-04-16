<?php
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $conn = new mysqli("localhost", "root", "", "sdl");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $full_name = $_POST['full_name'];
    $prn = $_POST['prn'];
    $email = $_POST['email'];
    $year = $_POST['year'];
    $course = $_POST['course'];
    $branch = $_POST['branch'];
    $division = $_POST['division'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if PRN already exists
    $check_sql = "SELECT * FROM students WHERE prn = '$prn'";
    $result = $conn->query($check_sql);
    if ($result->num_rows > 0) {
        echo "<script>alert('PRN already registered!'); window.history.back();</script>";
        exit;
    }

    $sql = "INSERT INTO students (full_name, prn, email, year, course, branch, division, password)
            VALUES ('$full_name', '$prn', '$email', '$year', '$course', '$branch', '$division', '$password')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Signup successful!'); window.location.href='student_dashboard.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Signup</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script>
        function validatePasswords(event) {
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('confirm-password').value;
            if (password !== confirm) {
                alert('Passwords do not match!');
                event.preventDefault();
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4 shadow-lg rounded" style="width: 100%; max-width: 450px;">
            <h2 class="text-center text-primary mb-3">Student Signup</h2>
            <form method="POST" onsubmit="return validatePasswords(event)">
                <div class="mb-3">
                    <input type="text" class="form-control" name="full_name" placeholder="Full Name" required>
                </div>
                <div class="mb-3">
                    <input type="text" class="form-control" name="prn" placeholder="PRN" required>
                </div>
                <div class="mb-3">
                    <input type="email" class="form-control" name="email" placeholder="Email" required>
                </div>
                <div class="mb-3">
                    <select class="form-select" name="year" required>
                        <option value="" disabled selected>Year</option>
                        <option value="FY">First Year (FY)</option>
                        <option value="SY">Second Year (SY)</option>
                        <option value="TY">Third Year (TY)</option>
                        <option value="BE">Final Year (BE)</option>
                    </select>
                </div>
                <div class="mb-3">
                    <select class="form-select" name="course" required>
                        <option value="" disabled selected>Graduation Course</option>
                        <option value="B.Tech">B.Tech</option>
                    </select>
                </div>
                <div class="mb-3">
                    <select class="form-select" name="branch" required>
                        <option value="" disabled selected>Branch</option>
                        <option value="Computer">Computer</option>
                        <option value="IT">IT</option>
                        <option value="Civil">Civil</option>
                        <option value="Mechanical">Mechanical</option>
                        <option value="ENTC">ENTC</option>
                        <option value="AIML">AIML</option>
                    </select>
                </div>
                <div class="mb-3">
                    <select class="form-select" name="division" required>
                        <option value="" disabled selected>Division</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                        <option value="D">D</option>
                    </select>
                </div>
                <div class="mb-3">
                    <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
                </div>
                <div class="mb-3">
                    <input type="password" id="confirm-password" class="form-control" placeholder="Confirm Password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Signup</button>
            </form>
            <p class="text-center mt-3">
                Already have an account? <a href="student_login.php">Login</a>
            </p>
        </div>
    </div>
</body>
</html>
