<?php
session_start();
if (!isset($_SESSION['teacher_id'])) {
    header("Location: teacher_login.php");
    exit;
}

$teacher_id = $_SESSION['teacher_id'];

// Database connection
$conn = new mysqli("localhost", "root", "", "sdl");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch assignments by teacher
$sql = "SELECT * FROM assignments WHERE teacher_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Teacher Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f8fb;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar {
            background-color: #0d6efd;
            padding: 15px 30px;
            color: white;
        }
        .navbar h2 {
            margin: 0;
        }
        .btn-logout {
            background-color: #ffffff;
            color: #0d6efd;
            font-weight: bold;
        }
        .container {
            margin-top: 40px;
        }
        .card {
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        table {
            background-color: white;
        }
        html, body {
        height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
        }
        .container {
            flex: 1;
        }

    </style>
</head>
<body>

    <!-- Navbar -->
    <div class="navbar d-flex justify-content-between align-items-center">
        <h2>Teacher Dashboard</h2>
        <a href="index.html" class="btn btn-logout">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Your Assigned Tasks</h4>
            <a href="teacher_assign.php" class="btn btn-primary">+ Add Task</a>
        </div>

        <div class="card p-4">
            <?php if ($result->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th>Assignment Title</th>
                                <th>Year</th>
                                <th>Branch</th>
                                <th>Type</th>
                                <th>Deadline</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['title']) ?></td>
                                <td><?= htmlspecialchars($row['year']) ?></td>
                                <td><?= htmlspecialchars($row['branch']) ?></td>
                                <td><?= htmlspecialchars($row['assignment_type']) ?></td>
                                <td><?= htmlspecialchars($row['deadline']) ?></td>
                                <td><?= htmlspecialchars($row['details']) ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted text-center">No assignments found. Click "Add Task" to get started.</p>
            <?php endif; ?>
        </div>
    </div>

<!-- Footer -->
<footer class="py-3 bg-light text-center text-muted">
    &copy; 2025 Task Manager | All rights reserved.
</footer>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
