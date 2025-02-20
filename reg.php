<?php
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

// Variables to capture user input
$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $bcode = $_POST['bcode'];
    $timing = $_POST['timing'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $image_url = '';
    $course = $_POST['course'];
    $role = $_POST['role'];

    // Check if image file is uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $imageName = $_FILES['image']['name'];
        $imageTmp = $_FILES['image']['tmp_name'];
        $imageExt = pathinfo($imageName, PATHINFO_EXTENSION);
        $imageExt = strtolower($imageExt);
        $allowedExts = array('jpg', 'jpeg', 'png', 'gif');

        // Check if the image type is allowed
        if (in_array($imageExt, $allowedExts)) {
            // Set the upload path and move the file
            $imagePath = 'uploads/' . time() . '.' . $imageExt;
            if (move_uploaded_file($imageTmp, $imagePath)) {
                $image_url = $imagePath;
            } else {
                $error = 'Image upload failed.';
            }
        } else {
            $error = 'Invalid image type. Only JPG, JPEG, PNG, and GIF are allowed.';
        }
    }

    // Simple validation (You can add more checks here)
    if (empty($username) || empty($email) || empty($password) || empty($timing) || empty($course) || empty($role)) {
        $error = "Please fill in all required fields.";
    } else {
        // Insert user data into the database
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, bcode, timing, address, phone, image_url, course, role) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssss", $username, $email, $password, $bcode, $timing, $address, $phone, $image_url, $course, $role);

        if ($stmt->execute()) {
            $success = "Registration successful!";
        } else {
            $error = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #thumbnail {
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2>User Registration</h2>
    <?php if ($error): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success" role="alert">
            <?php echo $success; ?>
        </div>
    <?php endif; ?>

    <form action="reg.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>

        <div class="form-group">
            <label for="bcode">Bcode</label>
            <select class="form-control" id="bcode" name="bcode" required>
                <option value="23012G">23012G</option>
                <option value="2405D">2405D</option>
                <option value="2405G">2405G</option>
                <option value="2401E">2401E</option>
            </select>
        </div>

        <div class="form-group">
            <label for="timing">Timing</label>
            <select class="form-control" id="timing" name="timing" required>
                <option value="9-11">9-11</option>
                <option value="11-1">11-1</option>
                <option value="1-3">1-3</option>
                <option value="3-7">3-7</option>
                <option value="7-9">7-9</option>
            </select>
        </div>

        <div class="form-group">
            <label for="address">Address</label>
            <textarea class="form-control" id="address" name="address" rows="3"></textarea>
        </div>

        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone">
        </div>

        <div class="form-group">
            <label for="image">Profile Image</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*" onchange="previewImage(event)">
            <img id="thumbnail" src="" alt="Image Preview" style="display: none;">
        </div>

        <div class="form-group">
            <label for="course">Course</label>
            <select class="form-control" id="course" name="course" required>
                <option value="CPISM">CPISM</option>
                <option value="DISM">DISM</option>
                <option value="HDSE">HDSE</option>
            </select>
        </div>

        <div class="form-group">
            <label for="role">Role</label>
            <select class="form-control" id="role" name="role" required>
                <option value="admin">Admin</option>
                <option value="teacher">Teacher</option>
                <option value="student">Student</option>
                <option value="manager">Manager</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Register</button>
    </form>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('thumbnail');
            output.src = reader.result;
            output.style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

</body>
</html>
