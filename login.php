<?php
session_start();

// Connection to the database
$servername = "localhost";
$username = "root";  // Your MySQL username
$password = "";  // Your MySQL password
$dbname = "lmsdb";  // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Error message if login fails
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = $_POST['login'];  // This can be either username or email
    $password = $_POST['password'];

    // Query to check if the user exists with the provided login (username or email)
    $stmt = $conn->prepare("SELECT id, username, email, password, role FROM users WHERE (username = ? OR email = ?)");
    $stmt->bind_param("ss", $login, $login);
    $stmt->execute();
    $stmt->store_result();

    // If the user exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $db_username, $db_email, $db_password, $db_role);
        $stmt->fetch();

        // Verify password
        if ($password == $db_password) {
            // Store session variables
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $db_username;
            $_SESSION['email'] = $db_email;
            $_SESSION['role'] = $db_role;

            // Redirect based on user role
            if ($db_role == 'admin') {
                header("Location: admin.php");
            } elseif ($db_role == 'student') {
                header("Location: student.php");
            } elseif ($db_role == 'manager') {
                header("Location: manager.php");
            } elseif ($db_role == 'teacher') {
                header("Location: teacher.php");
            }
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "User not found!";
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>User Login</h2>
    <?php if ($error): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <div class="form-group">
            <label for="login">Username or Email</label>
            <input type="text" class="form-control" id="login" name="login" required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>

        <button type="submit" class="btn btn-primary">Login</button>
    </form>

    <!-- Registration link -->
    <div class="mt-3">
        <p>Don't have an account? <a href="reg.php">Register here</a>.</p>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
