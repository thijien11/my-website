<?php
// Database connection
$servername = "localhost";
$username = "root"; // update if needed
$password = "";     // update if needed
$dbname = "charity_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]));
}

// Handle POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $conn->real_escape_string($_POST['fullname']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // secure password

    // Check if email already exists
    $check = $conn->query("SELECT id FROM users WHERE email='$email'");
    if ($check->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Email already registered."]);
        exit;
    }

    $sql = "INSERT INTO users (fullname, email, password) VALUES ('$fullname', '$email', '$password')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success", "message" => "Signup successful!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error: " . $conn->error]);
    }
}

$conn->close();
?>
