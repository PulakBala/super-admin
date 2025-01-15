<?php
include('db_connection.php');

$table = isset($_GET['table']) ? $_GET['table'] : 'revenue'; // Default to 'revenue'

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the current data for the given ID
    $sql = "SELECT * FROM $table WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $record = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$record) {
        echo "Record not found.";
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $amount = $_POST['amount'];
    $category = $_POST['category'];
    $descriptionOrNote = $_POST['descriptionOrNote'];

    if ($table === 'revenue') {
        $payment_type = $_POST['payment_type'];
        $sql = "UPDATE $table SET amount = :amount, payment_type = :payment_type, category = :category, note = :note WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':payment_type', $payment_type, PDO::PARAM_STR);
        $stmt->bindParam(':note', $descriptionOrNote, PDO::PARAM_STR);
    } else {
        $sql = "UPDATE $table SET amount = :amount, category = :category, description = :description WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':description', $descriptionOrNote, PDO::PARAM_STR);
    }

    $stmt->bindParam(':amount', $amount, PDO::PARAM_INT);
    $stmt->bindParam(':category', $category, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "Record updated successfully.";
    } else {
        echo "Error updating record.";
    }
}

// Only output the form HTML
?>
<form id="editForm" action="edit_addData.php?table=<?php echo htmlspecialchars($table); ?>" method="post">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($record['id'] ?? ''); ?>">
    <div class="form-group">
        <label for="amount">Amount:</label>
        <input type="number" class="form-control" id="amount" name="amount" value="<?php echo htmlspecialchars($record['amount'] ?? ''); ?>" required>
    </div>
    <?php if ($table === 'revenue'): ?>
        <!--<div class="form-group">-->
        <!--    <label for="payment_type">Payment Type:</label>-->
        <!--    <input type="text" class="form-control" id="payment_type" name="payment_type" value="<?php echo htmlspecialchars($record['payment_type'] ?? ''); ?>" required>-->
        <!--</div>-->
        
        
        <div class="form-group">
        <label for="payment_type">Payment Type:</label>
        <select class="form-control" id="payment_type" name="payment_type" required>
            <option value="" disabled selected>Select payment type</option>
            <option value="Cash" <?php echo isset($record['payment_type']) && $record['payment_type'] === 'Cash' ? 'selected' : ''; ?>>Cash</option>
            <option value="Bkash" <?php echo isset($record['payment_type']) && $record['payment_type'] === 'Bkash' ? 'selected' : ''; ?>>Bkash</option>
            <option value="Nagad" <?php echo isset($record['payment_type']) && $record['payment_type'] === 'Nagad' ? 'selected' : ''; ?>>Nagad</option>
            <option value="Rocket" <?php echo isset($record['payment_type']) && $record['payment_type'] === 'Rocket' ? 'selected' : ''; ?>>Rocket</option>
            <option value="Bank Transfer" <?php echo isset($record['payment_type']) && $record['payment_type'] === 'Bank Transfer' ? 'selected' : ''; ?>>Bank Transfer</option>
        </select>
    </div>
    <?php endif; ?>
<!--    
    <div class="form-group">
        <label for="category">Category:</label>
        <input type="text" class="form-control" id="category" name="category" value="<?php echo htmlspecialchars($record['category'] ?? ''); ?>" required>
    </div>-->
    
    <div class="form-group">
    <label for="category">Category:</label>
    <?php if ($table === 'revenue'): ?>
        <select class="form-select form-select-lg" id="category" name="category" required>
            <option value="" disabled selected>Select Category</option>
            <option value="Sales" <?php echo isset($record['category']) && $record['category'] === 'Sales' ? 'selected' : ''; ?>>Sales</option>
            <option value="Investment" <?php echo isset($record['category']) && $record['category'] === 'Investment' ? 'selected' : ''; ?>>Investment</option>
            <option value="Loan received" <?php echo isset($record['category']) && $record['category'] === 'Loan received' ? 'selected' : ''; ?>>Loan Received</option>
            <option value="Others" <?php echo isset($record['category']) && $record['category'] === 'Others' ? 'selected' : ''; ?>>Others</option>
        </select>
    <?php else: ?>
        <select class="form-select form-select-lg" id="category" name="category" required>
            <option value="" disabled selected>Select Category</option>
            <option value="Salaries" <?php echo isset($record['category']) && $record['category'] === 'Salaries' ? 'selected' : ''; ?>>Salaries</option>
            <option value="Product Purchase" <?php echo isset($record['category']) && $record['category'] === 'Product Purchase' ? 'selected' : ''; ?>>Product Purchase</option>
            <option value="Desk Rent" <?php echo isset($record['category']) && $record['category'] === 'Desk Rent' ? 'selected' : ''; ?>>Desk Rent</option>
            <option value="Loan" <?php echo isset($record['category']) && $record['category'] === 'Loan' ? 'selected' : ''; ?>>Loan</option>
            <option value="Cash Out" <?php echo isset($record['category']) && $record['category'] === 'Cash Out' ? 'selected' : ''; ?>>Cash Out</option>
            <option value="Gift" <?php echo isset($record['category']) && $record['category'] === 'Gift' ? 'selected' : ''; ?>>Gift</option>
            <option value="Mobile Recharge" <?php echo isset($record['category']) && $record['category'] === 'Mobile Recharge' ? 'selected' : ''; ?>>Mobile Recharge</option>
            <option value="Teacher Payment" <?php echo isset($record['category']) && $record['category'] === 'Teacher Payment' ? 'selected' : ''; ?>>Teacher Payment</option>
            <option value="Repaid" <?php echo isset($record['category']) && $record['category'] === 'Repaid' ? 'selected' : ''; ?>>Repaid</option>
            <option value="Marketing" <?php echo isset($record['category']) && $record['category'] === 'Marketing' ? 'selected' : ''; ?>>Marketing</option>
            <option value="Utilities" <?php echo isset($record['category']) && $record['category'] === 'Utilities' ? 'selected' : ''; ?>>Utilities</option>
            <option value="Maintenance and repairs" <?php echo isset($record['category']) && $record['category'] === 'Maintenance and repairs' ? 'selected' : ''; ?>>Maintenance and Repairs</option>
            <option value="Official Documents Cost" <?php echo isset($record['category']) && $record['category'] === 'Official Documents Cost' ? 'selected' : ''; ?>>Official Documents Cost</option>
            <option value="Asset Purchase" <?php echo isset($record['category']) && $record['category'] === 'Asset Purchase' ? 'selected' : ''; ?>>Asset Purchase</option>
            <option value="Others" <?php echo isset($record['category']) && $record['category'] === 'Others' ? 'selected' : ''; ?>>Others</option>
        </select>
    <?php endif; ?>
</div>

    
    
    <div class="form-group">
        <label for="descriptionOrNote"><?php echo $table === 'revenue' ? 'Note' : 'Description'; ?>:</label>
        <textarea class="form-control" id="descriptionOrNote" name="descriptionOrNote" required><?php echo htmlspecialchars($table === 'revenue' ? $record['note'] ?? '' : $record['description'] ?? ''); ?></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
</form>
