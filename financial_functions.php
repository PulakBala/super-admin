<?php
require_once 'db_connection.php';

// Rename $conn to $pdo for consistency
$pdo = $conn;

function getTotalRevenue($month, $year) {
    global $pdo;
    $companyId = $_SESSION['company_id'];
    $stmt = $pdo->prepare("SELECT SUM(amount) FROM revenue WHERE MONTH(date) = :month AND YEAR(date) = :year AND company_id = :company_id");
    $stmt->execute(['month' => $month, 'year' => $year, 'company_id' => $companyId]);
    return $stmt->fetchColumn() ?: 0;
}

function getTotalExpense($month, $year) {
    global $pdo;
    $companyId = $_SESSION['company_id'];
    $stmt = $pdo->prepare("SELECT SUM(amount) FROM expense WHERE MONTH(date) = :month AND YEAR(date) = :year AND company_id = :company_id");
    $stmt->execute(['month' => $month, 'year' => $year, 'company_id' => $companyId]);
    return $stmt->fetchColumn() ?: 0;
}
?>