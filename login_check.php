<?php
include 'db_connection.php';


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
                
                header("Location: home.php?company_id=" . $user['company_id']);
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