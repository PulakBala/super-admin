<?php
include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $type = $_POST['type'];
    $companyId = $_POST['companyId'];

    // SQL ইনসার্ট কোড
    if ($type === 'expense') {
        $sql = "INSERT INTO expense_categories (category_name, company_id) VALUES (:name, :companyId)";
    } else {
        $sql = "INSERT INTO revenue_categories (category_name, company_id) VALUES (:name, :companyId)";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':companyId', $companyId);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $stmt->errorInfo()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>