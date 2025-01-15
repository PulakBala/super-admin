<?php
include('db_connection.php');

$category = $_GET['category'] ?? '';
$month = $_GET['month'] ?? ''; 
$type = $_GET['type'] ?? 'revenue';

try {
    if ($type === 'revenue') {
        $sql = "SELECT id, full_name, amount, payment_type, category, note, created_at
                FROM revenue 
                WHERE category = :category 
                AND DATE_FORMAT(date, '%Y-%m') = :month";
    } else {
        $sql = "SELECT id, amount, category, description as note, date 
                FROM expense 
                WHERE category = :category 
                AND DATE_FORMAT(date, '%Y-%m') = :month";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':category', $category, PDO::PARAM_STR);
    $stmt->bindParam(':month', $month, PDO::PARAM_STR);
    
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    header('Content-Type: application/json');
    echo json_encode([
        'type' => $type,
        'data' => $data
    ]);
} catch(PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Database error occurred']);
}

$conn = null; 
?>