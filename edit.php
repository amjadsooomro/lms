<?php
session_start();

// Check if the user is logged in and has the role 'admin'
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
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

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $stmt = $conn->prepare("SELECT id, username, email, password, bcode, timing, address, phone, image_url, course, role FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $username, $email, $password, $bcode, $timing, $address, $phone, $image_url, $course, $role);
    $stmt->fetch();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">Admin Dashboard</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item active">
                <a class="nav-link" href="admin.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>

<!-- Edit User Form -->
<div class="container mt-5">
    <h2>Edit User</h2>
    <form action="update.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" value="<?php echo $username; ?>" class="form-control">
        </div>
        
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?php echo $email; ?>" class="form-control">
        </div>
        
        <div class="form-group">
            <label>Bcode</label>
            <input type="text" name="bcode" value="<?php echo $bcode; ?>" class="form-control">
        </div>
        
        <div class="form-group">
            <label>Timing</label>
            <select name="timing" class="form-control">
                <option value="9-11" <?php echo ($timing == '9-11') ? 'selected' : ''; ?>>9-11</option>
                <option value="11-1" <?php echo ($timing == '11-1') ? 'selected' : ''; ?>>11-1</option>
                <option value="1-3" <?php echo ($timing == '1-3') ? 'selected' : ''; ?>>1-3</option>
                <option value="3-7" <?php echo ($timing == '3-7') ? 'selected' : ''; ?>>3-7</option>
                <option value="7-9" <?php echo ($timing == '7-9') ? 'selected' : ''; ?>>7-9</option>
            </select>
        </div>

        <div class="form-group">
            <label>Address</label>
            <textarea name="address" class="form-control"><?php echo $address; ?></textarea>
        </div>

        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" value="<?php echo $phone; ?>" class="form-control">
        </div>

        <div class="form-group">
            <label>Image URL</label>
            <input type="text" name="image_url" value="<?php echo $image_url; ?>" class="form-control">
        </div>

        <div class="form-group">
            <label>Course</label>
            <select name="course" class="form-control">
                <option value="CPISM" <?php echo ($course == 'CPISM') ? 'selected' : ''; ?>>CPISM</option>
                <option value="DISM" <?php echo ($course == 'DISM') ? 'selected' : ''; ?>>DISM</option>
                <option value="HDSE" <?php echo ($course == 'HDSE') ? 'selected' : ''; ?>>HDSE</option>
            </select>
        </div>

        <div class="form-group">
            <label>Role</label>
            <select name="role" class="form-control">
                <option value="admin" <?php echo ($role == 'admin') ? 'selected' : ''; ?>>Admin</option>
                <option value="student" <?php echo ($role == 'student') ? 'selected' : ''; ?>>Student</option>
                <option value="manager" <?php echo ($role == 'manager') ? 'selected' : ''; ?>>Manager</option>
                <option value="teacher" <?php echo ($role == 'teacher') ? 'selected' : ''; ?>>Teacher</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

<!-- Bootstrap JS and Dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>

<?php
$conn->close();
?>
