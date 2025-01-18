<?php
include('db_connection.php');

// Check if a company ID is provided
if (isset($_GET['company_id'])) {
    $company_id = (int) $_GET['company_id'];


    // Fetch users details based on the company ID
    $stmt = $conn->prepare("SELECT * FROM users WHERE company_id = ?");
    $stmt->execute([$company_id]);
    $users = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($users) {
        // Replace the current session with the user's details
        $_SESSION['user_id'] = $users['id'];
        $_SESSION['fullname'] = $users['fullname'];
        $_SESSION['email'] = $users['email'];
        $_SESSION['role'] = $users['role'];
        $_SESSION['company_id'] = $users['company_id'];

        // Redirect to the user's dashboard or home page
        header("Location: home.php");
        exit();
    } else {
        echo "User not found for company ID: " . htmlspecialchars($company_id);
    }
} else {
    echo "No company ID provided.";
}
?>