<?php
session_start();
if (!isset($_SESSION['prn'])) {
    header("Location: student_login.php");
    exit;
}

$prn = $_SESSION['prn'];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $conn = new mysqli("localhost", "root", "", "sdl");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $task_name = $_POST['task_name'];
    $deadline = $_POST['deadline'];

    $stmt = $conn->prepare("INSERT INTO personal_tasks (task_name, deadline, prn) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $task_name, $deadline, $prn);

    if ($stmt->execute()) {
        echo "<script>alert('Personal task added successfully!'); window.location.href='student_dashboard.php';</script>";
    } else {
        echo "<script>alert('Failed to add personal task.');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Personal Task</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container">
    <h2 class="mt-5">Add Personal Task</h2>
    <form method="POST" action="">
        <div class="form-group">
            <label>Task Name:</label>
            <input type="text" name="task_name" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Deadline:</label>
            <input type="date" name="deadline" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Add Task</button>
    </form>
</div>

</body>
</html>
