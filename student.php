<?php
session_start();

// Check if the user is logged in and has the role 'student'
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lmsdb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch student details from the database
$sql = "SELECT id, username, email, course, bcode, timing, address, phone, image_url FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($id, $username, $email, $course, $bcode, $timing, $address, $phone, $image_url);
$stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">Student Dashboard</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item active">
                <a class="nav-link" href="student.php">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>

<!-- Container for displaying student contact details -->
<div class="container mt-5">
    <h2>Contact Details</h2>
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4>Contact Information</h4>
        </div>
        <div class="card-body">
            <h5 class="card-title"><?php echo $username; ?> (Student)</h5>
            <p class="card-text"><strong>Email:</strong> <?php echo $email; ?></p>
            <p class="card-text"><strong>Phone:</strong> <?php echo $phone; ?></p>
            <p class="card-text"><strong>Address:</strong> <?php echo $address; ?></p>
            <p class="card-text"><strong>Course:</strong> <?php echo $course; ?></p>
            <p class="card-text"><strong>BCODE:</strong> <?php echo $bcode; ?></p>
            <p class="card-text"><strong>Timing:</strong> <?php echo $timing; ?></p>
        </div>
        <div class="card-footer text-muted">
            Last updated: <?php echo date('Y-m-d'); ?>
        </div>
    </div>
</div>

<!-- Bootstrap JS and Dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
