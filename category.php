<?php
include('header.php');
?>
<div class="container py-5" id="addCategoryFormContainer">
    <h5>Add New Category</h5>
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

<script>
    function setCompanyId(companyId) {
        document.getElementById('modalCompanyId').value = companyId; // Set the company ID in the modal
    }

    // Handle form submission
    document.getElementById('addCategoryForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent default form submission

        const categoryName = document.getElementById('categoryName').value;
        const categoryType = document.getElementById('categoryType').value;
        const companyId = <?php echo json_encode($_SESSION['company_id']); ?>; // Get the company ID from the session

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
<?php include('footer.php')?>