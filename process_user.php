<?php
include('db_connection.php'); 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password = $_POST['password'];
    
    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (fullname, email, role, password, created_at, company_id) VALUES (:username, :email, :role, :hashed_password, NOW(), :company_id)");
    $stmt->bindParam(':company_id', $_SESSION['company_id']); // Add company_id from session
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':role', $role);
    $stmt->bindParam(':hashed_password', $hashed_password);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "New user created successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => $stmt->errorInfo()[2]]);
    }

    $conn = null;
}
?>

