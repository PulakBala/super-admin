<?php
include('header.php'); // ডাটাবেস সংযোগ

// সুপার অ্যাডমিনের লগইন যাচাই করুন
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'super_admin') {
    header('Location: login.php');
    exit;
}

// কোম্পানির তালিকা পাওয়ার জন্য SQL কোড
$stmt = $conn->prepare("SELECT * FROM companies");
$stmt->execute();
$companies = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- <h1 class="text-center my-4">Super Admin Dashboard</h1> -->

<div class="container">

    <div class="col-md-6 shadow-lg  mb-5 bg-white rounded">
        <h2 class="my-4">Add New Company</h2>
        <form action="add_company.php" method="POST" class="mb-4">
            <div class="form-group">
                <label for="company_name">Company Name:</label>
                <input type="text" id="company_name" name="company_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="admin_email">Admin Email:</label>
                <input type="email" id="admin_email" name="admin_email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="admin_password">Admin Password:</label>
                <input type="password" id="admin_password" name="admin_password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Company</button>
        </form>
    </div>

   <div class="col-md-12">
   <h2 class="my-4">All Companies</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($companies as $company): ?>
                <tr>
                    <td><?php echo htmlspecialchars($company['id']); ?></td>
                    <td><?php echo htmlspecialchars($company['name']); ?></td>
                    <td><?php echo htmlspecialchars($company['status']); ?></td>
                    <td>
                        <a href="edit_company.php?id=<?php echo $company['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="deactivate_company.php?id=<?php echo $company['id']; ?>" class="btn btn-danger btn-sm">Deactivate</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
   </div>
</div>

<?php include('footer.php') ?>