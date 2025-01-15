<?php
ob_start();
include('header.php'); // ডাটাবেস সংযোগ


// সুপার অ্যাডমিনের লগইন যাচাই করুন
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'super_admin') {
    header('Location: login.php');
    exit;
}

$company_id = $_GET['id'];

// কোম্পানি নিষ্ক্রিয় করুন
$stmt = $conn->prepare("UPDATE companies SET status = 'inactive' WHERE id = :id");
$stmt->bindParam(':id', $company_id);
$stmt->execute();

// সফলভাবে নিষ্ক্রিয় করার পর রিডাইরেক্ট করুন
header('Location: super_admin_dashboard.php');
exit;
?>