<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
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

// ইউজার তথ্য পাওয়ার জন্য SQL কোড
$stmt_user = $conn->prepare("SELECT email, password FROM users WHERE id = :user_id");
$stmt_user->bindParam(':user_id', $_SESSION['user_id']); // ধরে নিচ্ছি user_id সেশন থেকে আসছে
$stmt_user->execute();
$user = $stmt_user->fetch(PDO::FETCH_ASSOC);

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
                <label for="company_address">Company Address:</label>
                <input type="text" id="company_address" name="company_address" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="admin_email">Admin Email:</label>
                <input type="email" id="admin_email" name="admin_email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="mobile_number">Mobile Number:</label>
                <input type="text" id="mobile_number" name="mobile_number" class="form-control" required>
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
                    <th>Mobile Number</th>
                    <th>Address</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($companies as $company): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($company['id']); ?></td>
                        <td><?php echo htmlspecialchars($company['name']); ?></td>
                        <td><?php echo htmlspecialchars($company['mobile_number']); ?></td>
                        <td><?php echo htmlspecialchars($company['address']); ?></td>
                        <td><?php echo htmlspecialchars($company['status']); ?></td>
                        <td>
                            <a href="edit_company.php?id=<?php echo $company['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="deactivate_company.php?id=<?php echo $company['id']; ?>" class="btn btn-danger btn-sm">Deactivate</a>
                            <a class="button rounded" href="switch_account.php?id=<?php echo $company['id']; ?>&email=<?php echo urlencode($user['email']); ?>&password=<?php echo isset($user['password']) ? urlencode($user['password']) : ''; ?>&company_id=<?php echo isset($company['id']) ? $company['id'] : ''; ?>" style="background-color: #4CAF50; color: white; padding: 5px 10px;">login</a>

                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal" onclick="setCompanyId(<?php echo htmlspecialchars($company['id']); ?>)">
                                Add Category
                            </button>
                            <input type="hidden" id="modalCompanyId" name="companyId" value="">

                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>


        <div class="modal fade" id="addCategoryModal" tabindex="-1"               aria-labelledby="addCategoryModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCategoryModalLabel">Add New Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addCategoryForm">
                            <div class="mb-3">
                                <label for="categoryName" class="form-label">Category Name</label>
                                <input type="text" class="form-control" id="categoryName" name="categoryName" required>
                            </div>
                            <div class="mb-3">
                                <label for="categoryType" class="form-label">Category Type</label>
                                <select class="form-select" id="categoryType" name="categoryType" required>
                                    <option value="" disabled selected>Select Type</option>
                                    <option value="expense">Expense</option>
                                    <option value="revenue">Revenue</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Add Category</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>

<script>
    function setCompanyId(companyId) {
        document.getElementById('modalCompanyId').value = companyId; // Set the company ID in the modal
    }

    // Handle form submission
    document.getElementById('addCategoryForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent default form submission

        const categoryName = document.getElementById('categoryName').value;
        const categoryType = document.getElementById('categoryType').value;
        const companyId = document.getElementById('modalCompanyId').value; // Get the company ID from the modal

        // AJAX request to add category
        $.ajax({
            url: 'add_category.php', // Create this PHP file to handle the insertion
            type: 'POST',
            data: {
                name: categoryName,
                type: categoryType,
                companyId: companyId // Use the correct company ID
            },
            success: function(response) {
                // Handle success (e.g., close modal, show success message)
                $('#addCategoryModal').modal('hide');
                toastr.success('Category added successfully!');
                // Optionally, refresh the category dropdown or list
                location.reload(); // Reload the page to see the new category
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
                toastr.error('Error adding category.');
            }
        });
    });
</script>

<?php include('footer.php') ?>