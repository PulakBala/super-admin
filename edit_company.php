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
    $mobile_number = $_POST['mobile_number'];
    $address = $_POST['address'];

    // কোম্পানি ডেটা আপডেট করুন
    $stmt = $conn->prepare("UPDATE companies SET name = :name, status = :status, mobile_number = :mobile_number, address = :address WHERE id = :id");
    $stmt->bindParam(':name', $company_name);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':mobile_number', $mobile_number);
    $stmt->bindParam(':address', $address);
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


  <div class="container py-4">
  <h1>Edit Company</h1>
    <form action="edit_company.php" method="POST" class="form-group">
        <input type="hidden" name="company_id" value="<?php echo htmlspecialchars($company['id']); ?>">
        <div class="mb-3">
            <label for="company_name" class="form-label">Company Name:</label>
            <input type="text" id="company_name" name="company_name" value="<?php echo htmlspecialchars($company['name']); ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status:</label>
            <select id="status" name="status" class="form-select">
                <option value="active" <?php echo $company['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                <option value="inactive" <?php echo $company['status'] === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="mobile_number" class="form-label">Mobile Number:</label>
            <input type="text" id="mobile_number" name="mobile_number" value="<?php echo htmlspecialchars($company['mobile_number']); ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Address:</label>
            <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($company['address']); ?>" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Company</button>
    </form>
  </div>

<?php include('footer.php') ?>