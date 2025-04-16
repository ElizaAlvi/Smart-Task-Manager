<?php
session_start();
if (!isset($_SESSION['prn'])) {
    header("Location: student_login.php");
    exit;
}

$prn = $_SESSION['prn'];

// DB connection
$conn = new mysqli("localhost", "root", "", "sdl");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch student info
$stmt = $conn->prepare("SELECT full_name, branch, year FROM students WHERE prn = ?");
$stmt->bind_param("s", $prn);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();

$name = $student['full_name'];
$branch = $student['branch'];
$year = $student['year'];

// Fetch assignments
$stmt = $conn->prepare("SELECT * FROM assignments WHERE branch = ? AND FIND_IN_SET(?, year)");
$stmt->bind_param("ss", $branch, $year);
$stmt->execute();
$assignments = $stmt->get_result();

// Fetch personal tasks
$stmt = $conn->prepare("SELECT * FROM personal_tasks WHERE prn = ?");
$stmt->bind_param("s", $prn);
$stmt->execute();
$personal_tasks = $stmt->get_result();

// Handle delete task
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_task_id'])) {
    $delete_id = $_POST['delete_task_id'];
    $delete_stmt = $conn->prepare("DELETE FROM personal_tasks WHERE task_id = ? AND prn = ?");
    $delete_stmt->bind_param("is", $delete_id, $prn);
    $delete_stmt->execute();
    header("Location: student_dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }
        .navbar {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        .card {
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .card-header {
            background-color: #e9f2ff;
            font-weight: 600;
            color: #0d6efd;
        }
        .delete-btn {
            border: none;
            background: none;
            color: red;
            font-size: 1.3rem;
            cursor: pointer;
        }
        .task-info {
            font-size: 0.9rem;
        }
        footer {
            font-size: 0.85rem;
            color: #555;
            text-align: center;
            margin-top: 40px;
            padding: 10px 0;
            background: #f1f1f1;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white py-3">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">🎓 Student Task Manager</a>
        <div class="collapse navbar-collapse justify-content-end">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link text-danger" href="index.html">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Dashboard Content -->
<div class="container mt-4">
    <h3 class="text-center mb-4">Welcome, <?php echo htmlspecialchars($name); ?>!</h3>

    <div class="row g-4">
        <!-- Student Details (reduced width) -->
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-header">👤 Student Details</div>
            <div class="card-body">
                <p><strong>Name:</strong> <?php echo htmlspecialchars($name); ?></p>
                <p><strong>Branch:</strong> <?php echo htmlspecialchars($branch); ?></p>
                <p><strong>Year:</strong> <?php echo htmlspecialchars($year); ?></p>
                <p><strong>Assignments:</strong> <?php echo $assignments->num_rows; ?></p>
                <p><strong>Personal Tasks:</strong> <?php echo $personal_tasks->num_rows; ?></p>
            </div>
        </div>
        <div class="text-center mt-3">
            <a href="student_personal.php" class="btn btn-primary w-100">+ Add Personal Task</a>
        </div>
    </div>


        <!-- Assigned Tasks -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">📋 Assigned Tasks</div>
                <div class="card-body">
                    <ul class="list-group">
                        <?php while ($row = $assignments->fetch_assoc()) { ?>
                            <li class="list-group-item">
                                <h6 class="mb-1"><?php echo htmlspecialchars($row['title']); ?></h6>
                                <p class="mb-1 task-info"><?php echo htmlspecialchars($row['details']); ?></p>
                                <span class="badge bg-warning text-dark">Deadline: <?php echo htmlspecialchars($row['deadline']); ?></span>
                            </li>
                        <?php } ?>
                        <?php if ($assignments->num_rows === 0): ?>
                            <li class="list-group-item text-muted">No assignments assigned yet.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>

            <!-- Personal Tasks List -->
            <div class="card mt-4">
                <div class="card-header">📝 Personal Tasks</div>
                <div class="card-body">
                    <ul class="list-group">
                        <?php
                        // Re-run query for fresh result set
                        $stmt = $conn->prepare("SELECT * FROM personal_tasks WHERE prn = ?");
                        $stmt->bind_param("s", $prn);
                        $stmt->execute();
                        $personal_tasks = $stmt->get_result();
                        while ($task = $personal_tasks->fetch_assoc()) { ?>
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                <div class="me-2">
                                    <div class="fw-semibold"><?php echo htmlspecialchars($task['task_name']); ?></div>
                                    <div class="task-info">Deadline: <?php echo htmlspecialchars($task['deadline']); ?></div>
                                </div>
                                <form method="POST" class="m-0">
                                    <input type="hidden" name="delete_task_id" value="<?php echo $task['task_id']; ?>">
                                    <button type="submit" class="delete-btn" title="Remove Task"><b>&minus;</button>
                                </form>
                            </li>
                        <?php } ?>
                        <?php if ($personal_tasks->num_rows === 0): ?>
                            <li class="list-group-item text-muted">No personal tasks yet.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer>
    &copy; 2025 Task Manager | All rights reserved.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>
