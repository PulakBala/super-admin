<?php
ob_start();
include('header.php'); // ডাটাবেস সংযোগ


// সুপার অ্যাডমিনের লগইন যাচাই করুন
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'super_admin') {
    header('Location: login.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $company_name = $_POST['company_name'];
    $admin_email = $_POST['admin_email'];
    $admin_password = $_POST['admin_password'];

    // কোম্পানি ডেটা ইনসার্ট করুন
    $stmt = $conn->prepare("INSERT INTO companies (name) VALUES (:name)");
    $stmt->bindParam(':name', $company_name);
    $stmt->execute();
    $company_id = $conn->lastInsertId(); // নতুন কোম্পানির ID

    // অ্যাডমিন ইউজার তৈরি করুন
    $hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (company_id, fullname, email, password, role) VALUES (:company_id, :fullname, :email, :password, 'admin')");
    $stmt->bindParam(':company_id', $company_id);
    $stmt->bindParam(':fullname', $company_name);
    $stmt->bindParam(':email', $admin_email);
    $stmt->bindParam(':password', $hashed_password);
    $stmt->execute();

    // সফলভাবে যোগ করার পর রিডাইরেক্ট করুন
    header('Location: super_admin_dashboard.php');
    exit;
}
?>