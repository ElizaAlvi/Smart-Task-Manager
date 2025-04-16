<?php
session_start();
if (!isset($_SESSION['teacher_id'])) {
    header("Location: teacher_login.php");
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $conn = new mysqli("localhost", "root", "", "sdl");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $year = isset($_POST['year']) ? implode(", ", $_POST['year']) : "";
    $branch = $_POST['branch'];
    $title = $_POST['title'];
    $details = $_POST['details'];
    $type = isset($_POST['type']) ? implode(", ", $_POST['type']) : "";
    $deadline = $_POST['deadline'];
    $teacher_id = $_SESSION['teacher_id'];

    $stmt = $conn->prepare("INSERT INTO assignments (year, branch, title, details, assignment_type, deadline, teacher_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssi", $year, $branch, $title, $details, $type, $deadline, $teacher_id);

    if ($stmt->execute()) {
        header("Location: teacher_dashboard.php");
        exit;
    } else {
        echo "<script>alert('Failed to assign task.');</script>";
    }
    

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Assign New Task</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #e3f2fd;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .navbar {
            background: #0d6efd;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
        }
        .container {
            padding: 40px;
        }
        .form-group {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<!-- Navigation Bar -->
<div class="navbar d-flex justify-content-between align-items-center">
    <h2>Assign New Task</h2>
    <div>
        <a href="teacher_dashboard.php" class="btn btn-outline-light me-2 border-2">Dashboard</a>
        <form method="post" action="logout.php" style="display: inline;">
            <button class="btn btn-light">Logout</button>
        </form>
    </div>
</div>


<!-- Dashboard Content -->
<div class="container">
    <h3>Assign a Task</h3>
    <form method="POST">
        <div class="form-group">
            <label>Select Year:</label><br>
            <input type="checkbox" name="year[]" value="FY"> FY
            <input type="checkbox" name="year[]" value="SY"> SY
            <input type="checkbox" name="year[]" value="TY"> TY
            <input type="checkbox" name="year[]" value="BTech"> BTech
        </div>

        <div class="form-group">
            <label>Select Branch:</label>
            <select name="branch" class="form-control" required>
                <option value="Computer">Computer</option>
                <option value="IT">IT</option>
                <option value="Mechanical">Mechanical</option>
                <option value="Civil">Civil</option>
                <option value="AIML">AIML</option>
            </select>
        </div>

        <div class="form-group">
            <label>Assignment Title:</label>
            <input type="text" name="title" class="form-control" placeholder="Enter assignment title" required>
        </div>

        <div class="form-group">
            <label>Assignment Details:</label>
            <textarea name="details" class="form-control" rows="3" placeholder="Enter assignment details" required></textarea>
        </div>

        <div class="form-group">
            <label>Type of Assignment:</label><br>
            <input type="checkbox" name="type[]" value="Individual"> Individual
            <input type="checkbox" name="type[]" value="Group"> Group
        </div>

        <div class="form-group">
            <label>Deadline:</label>
            <input type="date" name="deadline" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Assign Task</button>
    </form>
</div>

</body>
</html>
