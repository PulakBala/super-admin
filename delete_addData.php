<?php
include('db_connection.php');

$table = isset($_GET['table']) ? $_GET['table'] : 'revenue'; // Default to 'revenue'

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete the record
    $sql = "DELETE FROM $table WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        if ($table === 'revenue') {
            echo "<script>window.location.href='income.php';</script>";
        } elseif ($table === 'expense') {
            echo "<script>window.location.href='expense.php';</script>";
        }
    } else {
        echo "<script>alert('Error deleting record.');</script>";
    }
} else {
    echo "Invalid request.";
}
?>
