<?php
ob_start();
include('header.php'); // ডাটাবেস সংযোগ


// সুপার অ্যাডমিনের লগইন যাচাই করুন
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'super_admin') {
    header('Location: login.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $company_id = $_POST['company_id'];
    $company_name = $_POST['company_name'];
    $status = $_POST['status'];

    // কোম্পানি ডেটা আপডেট করুন
    $stmt = $conn->prepare("UPDATE companies SET name = :name, status = :status WHERE id = :id");
    $stmt->bindParam(':name', $company_name);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':id', $company_id);
    $stmt->execute();

    // সফলভাবে আপডেট করার পর রিডাইরেক্ট করুন
    header('Location: super_admin_dashboard.php');
    exit;
}

// কোম্পানির তথ্য পাওয়ার জন্য
$company_id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM companies WHERE id = :id");
$stmt->bindParam(':id', $company_id);
$stmt->execute();
$company = $stmt->fetch(PDO::FETCH_ASSOC);
?>


    <h1>Edit Company</h1>
    <form action="edit_company.php" method="POST">
        <input type="hidden" name="company_id" value="<?php echo htmlspecialchars($company['id']); ?>">
        <label for="company_name">Company Name:</label>
        <input type="text" id="company_name" name="company_name" value="<?php echo htmlspecialchars($company['name']); ?>" required>
        <br>
        <label for="status">Status:</label>
        <select id="status" name="status">
            <option value="active" <?php echo $company['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
            <option value="inactive" <?php echo $company['status'] === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
        </select>
        <br>
        <button type="submit">Update Company</button>
    </form>

<?php include('footer.php') ?>