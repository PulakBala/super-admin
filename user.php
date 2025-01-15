<?php include('header.php') ?>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$company_id = $_SESSION['company_id'];

try {
    $stmt = $conn->prepare("SELECT fullname, email, role, created_at FROM users WHERE company_id = :company_id");
    $stmt->bindParam(':company_id', $company_id);
    $stmt->execute();

    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$conn = null;
?>
<main class="content">
    <div class="container-fluid pb-5">
        <div class="row">
            <div class="col-12 col-lg-8 col-xxl-6">
                <form id="userForm" class="p-4 border rounded shadow-sm bg-white">
                    <div class="form-group mb-3">
                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="role">Role :</label>
                        <select id="role" name="role" class="form-control" required>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                            <!-- <option value="super_admin">Super Admin</option> -->
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Create User</button>
                </form>
            </div>
        </div>
    </div>

    <div id="popup" class="popup" style="display:none;">
        <div class="popup-content">
            <span id="popup-message"></span>
            <button onclick="closePopup()">Close</button>
        </div>
    </div>


    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars(date('d-m-Y', strtotime($user['created_at']))); ?></td>
                        <td><?php echo htmlspecialchars($user['fullname']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['role']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>


    <script>
        document.getElementById('userForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission

            var formData = new FormData(this);
            fetch('process_user.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('popup-message').innerText = data.message;
                    document.getElementById('popup').style.display = 'block';
                    if (data.status === 'success') {
                        this.reset(); // Clear the form
                    }
                })
                .catch(error => console.error('Error:', error));
        });

        function closePopup() {
            document.getElementById('popup').style.display = 'none';
        }
    </script>

    <style>
        .popup {
            position: fixed;
            left: 50%;
            top: 60px;
            transform: translateX(-50%);
            width: 400px;
            background-color: rgba(0, 0, 0, 0.5);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .popup-content {
            background: white;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</main>
<?php include('footer.php') ?>