<?php
include('db_connection.php');


if (isset($_SESSION['fullname'])) {
    $welcomeMessage = "Welcome, " . $_SESSION['fullname'];
    $userRole = isset($_SESSION['role']) ? $_SESSION['role'] : '';
} else {
    header("location: index.php");
    exit();
}
?>

<?php
$page_titles = [
    'home.php' => 'Home - DIT SECTOR3',
    'income.php' => 'Revenue - DIT SECTOR3',
    'expense.php' => 'Expense - DIT SECTOR3',
    'statement.php' => 'Statement - DIT SECTOR3',
    'super_admin_dashboard.php' => 'Super Admin',
    'user.php' => 'User - DIT SECTOR3',
    'index.php' => 'Logout - DIT SECTOR3',
];

$current_page = basename($_SERVER['PHP_SELF']);
$page_title = isset($page_titles[$current_page]) ? $page_titles[$current_page] : 'DIT SECTOR3';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Responsive Admin &amp; Dashboard Template based on Bootstrap 5">
    <meta name="author" content="AdminKit">
    <meta name="keywords"
        content="adminkit, bootstrap, bootstrap 5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="shortcut icon" href="img/avatars/logo.png" />

    <link rel="canonical" href="https://demo-basic.adminkit.io/" />

    <title><?php echo $page_title; ?></title>
    <!-- Include Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <link href="css/app.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">






    <!-- toster   7-->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />




    <script>
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-bottom-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
    </script>

    <style>
        .toast-custom {
            background-color: #5ccc63;
            /* Green background for completed tasks */
            color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            animation-duration: 0.5s;
            /* Control the duration of the animation */
        }

        .toast-failed {
            background-color: #a31212;
            /* Red background for failed notifications */
            color: #fff;
            /* White text for contrast */
        }

        body.toggle-sidebar .sidebar {
            margin-left: -264px;
        }

        .sidebar {
            transition: margin-left 0.35s ease-in-out;
        }

        body.toggle-sidebar:not(.sidebar-mobile) .main {
            margin-left: 0 !important;
        }

        .hamburger {
            cursor: pointer;
            width: 24px;
            height: 24px;
            position: relative;
            background: none;
            border: none;
            padding: 0;
            display: inline-block;
        }

        .hamburger:before,
        .hamburger:after,
        .hamburger span {
            content: '';
            display: block;
            width: 24px;
            height: 2px;

            background-color: #666;
            position: absolute;
            left: 0;
        }

        .hamburger:before {
            top: 6px;
        }

        .hamburger span {
            top: 11px;
        }

        .hamburger:after {
            top: 16px;
        }

        .hamburger:before,
        .hamburger:after,
        .hamburger span {
            transition: all 0.2s ease-in-out;
        }

        .welcome-message {
            font-size: 1.2em;
            font-weight: bold;
            color: #4CAF50;
            /* Green color */
            margin: 10px 0;
            text-align: center;
        }

        .user-profile {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        .user-info {
            display: flex;
            align-items: center;
        }

        .user-info i {
            margin-right: 1rem;
            font-size: 2rem;
            color: #666;
        }

        .user-name {
            font-size: 1.2rem;
            font-weight: bold;
            color: white;
        }

        .user-role {
            font-size: 0.9rem;
            color: #666;
        }
    </style>


</head>

<body>
    <div class="wrapper">
        <!-- sidebar  -->
        <nav id="sidebar" class="sidebar js-sidebar">
            <div class="sidebar-content js-simplebar">
                <a class="sidebar-brand" href="#">
                    <!-- <img src="img/avatars/e-bazar-logo-white-final.png" alt="accounts-ebazar" style="max-width: 200px; height: auto;"> -->
                     <!-- <span>DIT SECTOR3</span> -->
                </a>
                
                <div class="user-profile">
                    <div class="user-info">
                        <i class="fas fa-user-circle"></i>
                        <?php if (isset($_SESSION['fullname'])): ?>
                            <span class="user-name"><?php echo htmlspecialchars($_SESSION['fullname']); ?></span>
                            
                        <?php else: ?>
                            <span class="user-name">Guest</span>
                            
                        <?php endif; ?>
                    </div>
                </div>

                <ul class="sidebar-nav">
                  
                    <li class="sidebar-item <?= $current_page == 'home.php' ? 'active' : '' ?>">
                        <a class="sidebar-link" href="home.php">
                            <i class="fas fa-tachometer-alt"></i>
                            <span class="align-middle">Home</span>
                        </a>
                    </li>
                    <li class="sidebar-item <?= $current_page == 'income.php' ? 'active' : '' ?>">
                        <a class="sidebar-link" href="income.php">
                            <i class="fas fa-hand-holding-dollar"></i>
                            <span class="align-middle">Revenue</span>
                        </a>
                    </li>
                    <li class="sidebar-item <?= $current_page == 'expense.php' ? 'active' : '' ?>">
                        <a class="sidebar-link" href="expense.php">
                            <i class="fas fa-coins"></i>
                            <span class="align-middle">Expense</span>
                        </a>
                    </li>
                    <li class="sidebar-item <?= $current_page == 'statement.php' ? 'active' : '' ?>">
                        <a class="sidebar-link" href="statement.php">
                            <i class="fas fa-chart-bar"></i>
                            <span class="align-middle">Statement</span>
                        </a>
                    </li>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'super_admin'): ?>
                        <li class="sidebar-item <?= $current_page == 'super_admin_dashboard.php' ? 'active' : '' ?>">
                            <a class="sidebar-link" href="super_admin_dashboard.php">
                                <i class="fas fa-cogs"></i>
                                <span class="align-middle">Super Admin Dashboard</span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <li class="sidebar-item <?= $current_page == 'user.php' ? 'active' : '' ?>">
                            <a class="sidebar-link" href="user.php">
                                <i class="fas fa-user"></i>
                                <span class="align-middle">User</span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <li class="sidebar-item <?= $current_page == 'index.php' ? 'active' : '' ?>">
                        <a class="sidebar-link" href="index.php?logout=true">
                            <i class="fas fa-power-off"></i>
                            <span class="align-middle">Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>



        <div class="main">
            <nav class="navbar navbar-expand navbar-light navbar-bg">
                <a class="sidebar-toggle js-sidebar-toggle">
                    <i class="hamburger align-self-center">
                        <span></span>
                    </i>
                </a>

                <!-- <div class="navbar-collapse collapse">

                    <ul class="navbar-nav navbar-align">
                        <li class="nav-item dropdown">
                           
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end py-0" aria-labelledby="alertsDropdown" id="notificationDropdown">
                                <div class="dropdown-menu-header">
                                    0 New Notifications
                                </div>
                                <div class="list-group">
                                    <div class="text-center py-3">No new notifications</div>
                                </div>
                                <div class="dropdown-menu-footer">
                                    <a href="all-notifications.php" class="text-muted">Show all notifications</a>
                                </div>
                            </div>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
                                <div class="d-flex align-items-center">
                                    <img src="img/avatars/logo.png" alt="" class="rounded-circle me-2" width="50" height="50">
                                    <div class="text-start">
                                        <span class="fw-bold">eBazar</span>
                                    </div>
                                </div>
                            </a>

                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="profile.php">
                                    <i class="align-middle me-1" data-feather="user"></i> Profile
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="index.php?logout=true">Log Out</a>
                            </div>
                        </li>
                    </ul>
                </div> -->
            </nav>


            <script>
                document.getElementById('alertsDropdown').addEventListener('click', function() {
                    document.getElementById('notification-count').textContent = '0';
                });
            </script>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Sidebar toggle
                    const sidebarToggle = document.querySelector('.js-sidebar-toggle');
                    sidebarToggle.addEventListener('click', function() {
                        document.querySelector('body').classList.toggle('toggle-sidebar');
                    });
                });
            </script>

            <script>
                // Example login logic
                if (password_verify($input_password, $stored_hashed_password)) {
                    $_SESSION['logged_in_user_name'] = $user['fullname'];
                    // Set other session variables as needed
                }
            </script>