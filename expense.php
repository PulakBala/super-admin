<?php
include('header.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $amount = $_POST['amount'];
    $date = $_POST['date'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $user_id = $_SESSION['user_id']; // Assuming user ID is stored in session
    $company_id = $_SESSION['company_id'];

    
    // Fetch company_id based on user_id
    $user_sql = "SELECT company_id FROM users WHERE id = :user_id";
    $user_stmt = $conn->prepare($user_sql);
    $user_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $user_stmt->execute();
    $company_id = $user_stmt->fetchColumn(); // Get company_id

    // Prepare and execute the SQL query to insert data using PDO
    $sql = "INSERT INTO expense (amount, date, category, description, user_id, company_id) VALUES (:amount, :date, :category, :description, :user_id, :company_id)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':amount', $amount);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':category', $category);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':company_id', $company_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "<script>toastr.success('Expense added successfully!');</script>";
        echo "<script>window.location.href = window.location.href;</script>";
    } else {
        echo "<script>toastr.error('Error: " . $stmt->errorInfo()[2] . "');</script>";
    }
}

// Pagination settings
$records_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;

// Fetch total number of records
$total_sql = "SELECT COUNT(*) FROM expense";
$total_stmt = $conn->prepare($total_sql);
$total_stmt->execute();
$total_records = $total_stmt->fetchColumn();
$total_pages = ceil($total_records / $records_per_page);


// Retrieve user_id from session
$user_id = $_SESSION['user_id']; // Assuming user ID is stored in session


// Fetch company_id based on user_id
$user_sql = "SELECT company_id FROM users WHERE id = :user_id";
$user_stmt = $conn->prepare($user_sql);
$user_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$user_stmt->execute();
$company_id = $user_stmt->fetchColumn(); // Get company_id

// Check if company_id is null
if ($company_id === null) {
    echo "<script>toastr.error('Error: Company ID not found for the user.');</script>";
    exit; // Stop further execution
}

// Modify the query to include pagination
$sql = "SELECT expense.id, expense.amount, expense.date, expense.category, expense.description, users.fullname, ec.category_name 
        FROM expense 
        JOIN users ON expense.user_id = users.id
        JOIN expense_categories ec ON expense.category = ec.id
        WHERE expense.company_id = :company_id
        ORDER BY expense.date DESC, expense.id DESC 
        LIMIT :offset, :records_per_page";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':company_id', $company_id, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->bindParam(':records_per_page', $records_per_page, PDO::PARAM_INT);
$stmt->execute();
$expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ক্যাটাগরি ফেচ করা
// ক্যাটাগরি ফেচ করা
$category_sql = "SELECT id, category_name FROM expense_categories WHERE company_id = :company_id";
$category_stmt = $conn->prepare($category_sql);
$category_stmt->bindParam(':company_id', $company_id, PDO::PARAM_INT);
$category_stmt->execute();
$expense_categories = $category_stmt->fetchAll(PDO::FETCH_ASSOC);

// Check if no categories are found
if (empty($expense_categories)) {
    // No categories found, handle accordingly (e.g., show a message or do nothing)
    // echo "<script>toastr.error('No categories found for this company.');</script>";
    // exit; // Uncomment if you want to stop further execution
}
?>
<!-- Include Toastr CSS and JS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<!-- Include jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<style>
    /* Custom Toastr styles */
    .toast-success {
        background-color: #ff4d4d !important;
        /* Red background for success */
    }

    .toast-error {
        background-color: #ff4d4d !important;
        /* Red background for error */
    }
</style>

<!-- Modal HTML -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center w-100" id="confirmModalLabel" style="font-size: 1.25rem; font-weight: bold; margin-bottom: 10px; margin-top: 20px;">Are you sure?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p>Are you sure you want to add this expense?</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="width: 100px;">No</button>
                <button type="button" class="btn btn-primary" id="confirmAdd" style="width: 100px;">Yes</button>
            </div>
        </div>
    </div>
</div>

<main class="content" style="display: flex; justify-content: center; align-items: center; height: 100vh;">
    <div class="container-fluid p-0">
        <div class="row justify-content-start align-items-start">
            <div class="col-12 col-md-8 col-lg-7">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0" style="font-size: 1.5rem;">Add Expense</h5>
                    </div>
                    <div class="card-body" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                        <form action="" method="post" onsubmit="return confirmSubmission()">
                            <div class="mb-3">
                                <label class="form-label" style="font-size: 1rem; font-weight: bold;">Amount (৳)</label>
                                <input type="number" class="form-control form-control-lg" name="amount" placeholder="Amount" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" style="font-size: 1rem; font-weight: bold;">Date</label>
                                <input type="date" class="form-control form-control-lg" name="date" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" style="font-size: 1rem; font-weight: bold;">Category</label>
                                <select class="form-select form-select-lg" name="category" required>
                                    <option value="" disabled selected>Select Category</option>
                                    <?php foreach ($expense_categories as $category): ?>
                                        <option value="<?php echo htmlspecialchars($category['id']); ?>"><?php echo htmlspecialchars($category['category_name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" style="font-size: 1rem; font-weight: bold;">Expense Description</label>
                                <input type="text" class="form-control form-control-lg" name="description" placeholder="Expense Description" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="align-middle" data-feather="plus"></i>
                                Add New Expense
                            </button>
                        </form>
                        <div id="expense-list" class="mt-4">
                            <ul id="expenses"></ul>
                        </div>
                    </div>
                </div>
                <!-- Display the data in a table -->

            </div>
        </div>
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0" style="font-size: 1.5rem;">Expense List</h5>
            </div>
            <div class="card-body">
                <table class="table table-striped" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Category</th>
                            <th>Amount</th>
                            <th>Description</th>
                            <th>Added By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($expenses as $expense): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($expense['date']); ?></td>
                                <td><?php echo htmlspecialchars($expense['category_name']); ?></td>
                                <td><?php echo htmlspecialchars(number_format($expense['amount'], 0)); ?></td>
                                <td><?php echo htmlspecialchars($expense['description']); ?></td>
                                 <td><?php echo htmlspecialchars($expense['fullname']); ?></td> 
                                <td>
                                <a href="javascript:void(0);" onclick="loadEditForm(<?php echo htmlspecialchars($expense['id']); ?>);" class="btn btn-sm btn-warning">Edit</a>
                                <a href="javascript:void(0);" onclick="openDeleteModal('delete_addData.php?table=expense&id=<?php echo htmlspecialchars($expense['id']); ?>');" class="btn btn-sm btn-danger">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Professional Pagination -->
                <div class="d-flex justify-content-center align-items-center mt-4">
                    <div class="d-flex gap-3">
                        <a href="?page=<?php echo $page - 1; ?>"
                            onclick="return handlePageChange(this, <?php echo $page - 1; ?>)"
                            class="btn btn-outline-primary px-4 <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                            Previous
                        </a>

                        <a href="?page=<?php echo $page + 1; ?>"
                            onclick="return handlePageChange(this, <?php echo $page + 1; ?>)"
                            class="btn btn-outline-primary px-4 <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                            Next
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Add Modal HTML -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Expense</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Content will be loaded here from "edit_addData.php" -->
            </div>
        </div>
    </div>
</div>

<script>
    // Function to load edit form into the modal
    function loadEditForm(id) {
        $.ajax({
            url: 'edit_addData.php',
            type: 'GET',
            data: { id: id, table: 'expense' },
            success: function(response) {
                $('#editModal .modal-body').html(response);
                $('#editModal').modal('show');

                // Attach submit event handler to the form
                $('#editForm').on('submit', function(event) {
                    event.preventDefault(); // Prevent default form submission

                    $.ajax({
                        url: $(this).attr('action'),
                        type: 'POST',
                        data: $(this).serialize(),
                        success: function(response) {
                            // Handle success (e.g., close modal, show success message)
                            $('#editModal').modal('hide');
                            toastr.success('Record updated successfully!');
                            // Optionally, refresh the data on the page
                            location.reload();
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error:', status, error);
                            toastr.error('Error updating record.');
                        }
                    });
                });
            }
        });
    }
</script>
<!-- Add this script before the footer -->
<script>
     function confirmSubmission() {
        $('#confirmModal').modal('show');
        return false; // Prevent form submission
    }

    document.getElementById('confirmAdd').addEventListener('click', function() {
        $('#confirmModal').modal('hide');
        document.querySelector('form').submit(); // Submit the form
    });

    function handlePageChange(link, pageNum) {
        if (pageNum < 1 || pageNum > <?php echo $total_pages; ?>) {
            return false;
        }

        // Store current scroll position in session storage
        sessionStorage.setItem('scrollPosition', window.scrollY);

        // Prevent default behavior and handle navigation manually
        event.preventDefault();
        window.location.href = '?page=' + pageNum;
        return false;
    }

    // Restore scroll position when page loads
    window.onload = function() {
        let scrollPosition = sessionStorage.getItem('scrollPosition');
        if (scrollPosition) {
            window.scrollTo(0, scrollPosition);
            sessionStorage.removeItem('scrollPosition');
        }
    }
</script>

<!-- Add Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center w-100" id="deleteConfirmModalLabel" style="font-size: 1.25rem; font-weight: bold; margin-top: 20px;">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p>Are you sure you want to delete this record?</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="width: 100px;">No</button>
                <button type="button" class="btn btn-primary" id="confirmDelete" style="width: 100px;">Yes</button>
            </div>
        </div>
    </div>
</div>


<script>
    let deleteUrl = '';

    // Update the delete button to open the modal
    function openDeleteModal(url) {
        deleteUrl = url;
        $('#deleteConfirmModal').modal('show');
    }

    // Handle the confirmation within the modal
    document.getElementById('confirmDelete').addEventListener('click', function() {
        $('#deleteConfirmModal').modal('hide');
        window.location.href = deleteUrl;
    });
</script>

<?php include('footer.php') ?>