<?php
session_start();
// Clear existing session data before login attempt
session_unset();
session_destroy();
session_start(); // Start a fresh session

include 'db_connection.php';

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: home.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    try {
        // First find user by email
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->rowCount() == 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            // Verify password hash
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['fullname'] = $user['fullname'];
                 $_SESSION['role'] = $user['role'];
                 $_SESSION['company_id'] = $user['company_id'];
                
                header("Location: home.php");
                exit();
            } else {
                header("Location: index.php?error=invalid");
                exit();
            }
        } else {
            header("Location: index.php?error=invalid");
            exit();
        }
        
    } catch(PDOException $e) {
        header("Location: index.php?error=system");
        exit();
    }
}
?>