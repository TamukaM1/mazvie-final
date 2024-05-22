<?php
global $conn, $error;
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

function isAdmin() {
    return $_SESSION['user']['role_name'] === 'admin';
}

function isTownPlanner() {
    return $_SESSION['user']['role_name'] === 'town_planner';
}

function isEmployee() {
    return $_SESSION['user']['role_name'] === 'employee';
}
include 'config/db_connection.php';

//$sql = "SELECT u.user_id, u.username, r.role_name FROM Users u JOIN Roles r ON u.role_id = r.role_id";
//$result = $conn->query($sql);

//$sql = "SELECT COUNT(*) as count FROM vehicles";
//$result = $conn->query($sql);
//$vehicles_count = $result->fetch_assoc()['count'];

// Fetch count of users
$sql2 = "SELECT COUNT(*) as count FROM users";
$result = $conn->query($sql2);
$users_count = $result->fetch_assoc()['count'];

//// Fetch count of employees
//$sql = "SELECT COUNT(*) as count FROM employees";
//$result = $conn->query($sql);
//$employees_count = $result->fetch_assoc()['count'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role_id = $_POST['role_id'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $job_title = $_POST['job_title'];

    if (empty($username) || empty($password) || empty($role_id) || empty($email) || empty($address) || empty($job_title)) {
        $error = "All fields are required.";
    } else {
        $sql = "INSERT INTO Users (username, password, role_id, email, address, job_title) VALUES ('$username', '$password', '$role_id', '$email', '$address', '$job_title')";
        if ($conn->query($sql) === TRUE) {
            $success = "User added successfully.";

            // Send email with credentials
            $to = $email;
            $subject = "Your Account Credentials";
            $message = "Hello $username,\n\nYour account has been created. Your credentials are:\nUsername: $username\nPassword: {$_POST['password']}\n\nPlease change your password after logging in.";
            $headers = "From: noreply@example.com";

            mail($to, $subject, $message, $headers);
        } else {
            $error = "Error adding user: " . $conn->error;
        }
    }
}


$sql = "SELECT Users.user_id, Users.username, Users.email, Users.address, Users.job_title, Roles.role_name
        FROM Users
        JOIN Roles ON Users.role_id = Roles.role_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>

<html
    lang="en"
    class="light-style layout-menu-fixed layout-compact"
    dir="ltr"
    data-theme="theme-default"
    data-assets-path="../assets/"
    data-template="vertical-menu-template-free">
<head>
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Tables - Basic Tables | Sneat - Bootstrap 5 HTML Admin Template - Pro</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <link rel="stylesheet" href="assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="assets/js/config.js"></script>
</head>

<body>
<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->

        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
            <div class="app-brand demo">
                <a href="index.html" class="app-brand-link">
              <span class="app-brand-logo demo">
                <img src="">
              </span>
                    <span class="app-brand-text demo menu-text fw-bold ms-2">Sneat</span>
                </a>

                <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                    <i class="bx bx-chevron-left bx-sm align-middle"></i>
                </a>
            </div>

            <div class="menu-inner-shadow"></div>

            <ul class="menu-inner py-1">
                <!-- Dashboards -->
                <?php if (isAdmin()) { ?>
                    <li class="menu-item active open">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-home-circle"></i>
                            <div data-i18n="Dashboards">Dashboards</div>
                            <div class="badge bg-danger pil ms-auto">Admin</div>
                        </a>
                    </li>

                    <!-- Layouts -->
                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-group"></i>
                            <div data-i18n="Layouts">Users</div>
                        </a>

                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="<?php echo htmlspecialchars('view_users.php'); ?>" class="menu-link">
                                    <div data-i18n="Without menu">View Users</div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Front Pages -->
                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bxs-truck"></i>
                            <div data-i18n="Front Pages">Vehicles</div>

                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a
                                        href="<?php echo htmlspecialchars('view_vehicles.php'); ?>"
                                        class="menu-link"
                                        target="_blank">
                                    <div data-i18n="Landing">View Vehicles</div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="menu-item">
                        <a href="<?php echo htmlspecialchars('view_locations.php'); ?>" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-location-plus"></i>
                            <div data-i18n="Front Pages">Location</div>

                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a
                                        href="<?php echo htmlspecialchars('view_vehicles.php'); ?>"
                                        class="menu-link"
                                        target="_blank">
                                    <div data-i18n="Landing">View Vehicles</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- Front Pages -->
                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-store"></i>
                            <div data-i18n="Front Pages">Tasks</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a
                                        href="<?php echo htmlspecialchars('view_task.php'); ?>"
                                        class="menu-link"
                                        target="_blank">
                                    <div data-i18n="Landing">View Tasks</div>
                                </a>
                            </li>
                        </ul>
                    </li>

                <?php } elseif (isTownPlanner()) { ?>
                    <li class="menu-item active open">
                        <a href="#" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-home-circle"></i>
                            <div data-i18n="Dashboards">Dashboards</div>
                            <div class="badge bg-danger pil ms-auto">Clerk</div>
                        </a>
                    </li>

                    <!-- Layouts -->
                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-layout"></i>
                            <div data-i18n="Layouts">Users</div>
                        </a>

                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="<?php echo htmlspecialchars('view_users.php'); ?>" class="menu-link">
                                    <div data-i18n="Without menu">View Users</div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Front Pages -->
                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-store"></i>
                            <div data-i18n="Front Pages">Vehicles</div>

                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a
                                        href="<?php echo htmlspecialchars('view_vehicles.php'); ?>"
                                        class="menu-link"
                                        target="_blank">
                                    <div data-i18n="Landing">View Vehicles</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- Front Pages -->
                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-store"></i>
                            <div data-i18n="Front Pages">Tasks</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a
                                        href="<?php echo htmlspecialchars('view_task.php'); ?>"
                                        class="menu-link"
                                        target="_blank">
                                    <div data-i18n="Landing">View Tasks</div>
                                </a>
                            </li>
                        </ul>
                    </li>

                <?php } elseif (isEmployee()) { ?>
                    <li class="menu-item active open">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-home-circle"></i>
                            <div data-i18n="Dashboards">Dashboards</div>
                            <div class="badge bg-danger pil ms-auto">Employee</div>
                        </a>
                    </li>

                    <!-- Front Pages -->
                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-store"></i>
                            <div data-i18n="Front Pages">Tasks</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a
                                        href="<?php echo htmlspecialchars('view_task.php'); ?>"
                                        class="menu-link"
                                        target="_blank">
                                    <div data-i18n="Landing">View Tasks</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php } ?>
                <li class="menu-item">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons bx bx-exit"></i>
                        <div data-i18n="Front Pages">Logout</div>

                    </a>
                    <ul class="menu-sub">
                        <li class="menu-item">
                            <a
                                    href="https://demos.themeselection.com/sneat-bootstrap-html-admin-template/html/front-pages/landing-page.html"
                                    class="menu-link"
                                    target="_blank">
                                <div data-i18n="Landing">View Vehicles</div>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </aside>

        <!-- Layout container -->
        <div class="layout-page">
            <!-- Navbar -->

            <nav
                class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
                id="layout-navbar">
                <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                    <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                        <i class="bx bx-menu bx-sm"></i>
                    </a>
                </div>

                <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                    <!-- Search -->
                    <div class="navbar-nav align-items-center">
                        <div class="nav-item d-flex align-items-center">
                            <i class="bx bx-search fs-4 lh-0"></i>
                            <input
                                type="text"
                                class="form-control border-0 shadow-none ps-1 ps-sm-2"
                                placeholder="Search..."
                                aria-label="Search..." />
                        </div>
                    </div>
                    <!-- /Search -->

                    <ul class="navbar-nav flex-row align-items-center ms-auto">
                        <!-- Place this tag where you want the button to render. -->
                        <li class="nav-item lh-1 me-3">
                            <a
                                class="github-button"
                                href="https://github.com/themeselection/sneat-html-admin-template-free"
                                data-icon="octicon-star"
                                data-size="large"
                                data-show-count="true"
                                aria-label="Star themeselection/sneat-html-admin-template-free on GitHub"
                            >Star</a
                            >
                        </li>

                        <!-- User -->
                        <li class="nav-item navbar-dropdown dropdown-user dropdown">
                            <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                                <div class="avatar avatar-online">
                                    <img src="../assets/img/avatars/1.png" alt class="w-px-40 h-auto rounded-circle" />
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="avatar avatar-online">
                                                    <img src="../assets/img/avatars/1.png" alt class="w-px-40 h-auto rounded-circle" />
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <span class="fw-medium d-block">John Doe</span>
                                                <small class="text-muted">Admin</small>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <div class="dropdown-divider"></div>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="bx bx-user me-2"></i>
                                        <span class="align-middle">My Profile</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="bx bx-cog me-2"></i>
                                        <span class="align-middle">Settings</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">
                        <span class="d-flex align-items-center align-middle">
                          <i class="flex-shrink-0 bx bx-credit-card me-2"></i>
                          <span class="flex-grow-1 align-middle ms-1">Billing</span>
                          <span class="flex-shrink-0 badge badge-center rounded-pill bg-danger w-px-20 h-px-20">4</span>
                        </span>
                                    </a>
                                </li>
                                <li>
                                    <div class="dropdown-divider"></div>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0);">
                                        <i class="bx bx-power-off me-2"></i>
                                        <span class="align-middle">Log Out</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!--/ User -->
                    </ul>
                </div>
            </nav>

            <!-- / Navbar -->

            <!-- Content wrapper -->
            <div class="content-wrapper">
                <!-- Content -->

                <div class="container-xxl flex-grow-1 container-p-y">
                    <h4 class="py-3 mb-4"><span class="text-muted fw-light">Users /</span> View users</h4>

                    <!-- Hoverable Table rows -->
                    <div class="card">
                        <div class="mt-3">
                            <!-- Button trigger modal -->
                            <button
                                    type="button"
                                    class="btn btn-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalCenter">
                                Add New User
                            </button>

                            <!-- Modal -->
                            <div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalCenterTitle">Add New User</h5>
                                            <button
                                                    type="button"
                                                    class="btn-close"
                                                    data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                        </div>
                                        <?php if (!empty($error)) { echo "<p>$error</p>"; } ?>
                                        <?php if (!empty($success)) { echo "<p>$success</p>"; } ?>
                                        <form method="post" action="">
                                        <div class="modal-body">

                                            <div class="row">
                                                <div class="col mb-3">
                                                    <label for="nameWithTitle" class="form-label">Name</label>
                                                    <input
                                                            type="text"
                                                            id="nameWithTitle"
                                                            class="form-control"
                                                            name="username"
                                                            placeholder="Enter Name" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col mb-3">
                                                    <label for="nameWithTitle" class="form-label">Password</label>
                                                    <input
                                                            type="text"
                                                            id="nameWithTitle"
                                                            class="form-control"
                                                            name="password"
                                                            placeholder="password" />
                                                </div>
                                            </div>
                                            <div class="row g-2">
                                                <div class="col mb-0">
                                                    <label for="emailWithTitle" class="form-label">Role</label>
                                                    <select class="form-select" id="exampleFormControlSelect1" aria-label="Default select example" name="role_id">
                                                        <option selected>Open this select menu</option>
                                                        <option value="1">Admin</option>
                                                        <option value="2">Town Planner</option>
                                                        <option value="3">Employee</option>
                                                    </select>
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col mb-3">
                                                    <label for="nameWithTitle" class="form-label">Email</label>
                                                    <input
                                                            type="email"
                                                            id="nameWithTitle"
                                                            class="form-control"
                                                            name="email"
                                                            placeholder="Enter Email" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col mb-3">
                                                    <label for="nameWithTitle" class="form-label">Address</label>
                                                    <input
                                                            type="text"
                                                            id="nameWithTitle"
                                                            class="form-control"
                                                            name="address"
                                                            placeholder="Enter Name" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col mb-3">
                                                    <label for="nameWithTitle" class="form-label">Job Title</label>
                                                    <input
                                                            type="text"
                                                            id="nameWithTitle"
                                                            class="form-control"
                                                            name="job_title"
                                                            placeholder="Enter Title" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                                Close
                                            </button>
                                            <button type="submit" class="btn btn-primary">Save changes</button>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive text-nowrap">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>User ID</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Address</th>
                                    <th>Job Title</th>
                                    <th>Role</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                <?php foreach ($result as $row): ?>
                                    <tr>
                                        <td><?= $row['user_id'] ?></td>
                                        <td><?= $row['username'] ?></td>
                                        <td><?= $row['email'] ?></td>
                                        <td><?= $row['address'] ?></td>
                                        <td><?= $row['job_title'] ?></td>
                                        <td><?= $row['role_name'] ?></td>
                                        <td>
                                            <a href="edit_user.php?user_id=<?= $row['user_id'] ?>">Edit</a> |
                                            <a href="delete_user.php?user_id=<?= $row['user_id'] ?>" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                                        </td>

                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!--/ Hoverable Table rows -->

                </div>
                <!-- / Content -->

                <!-- Footer -->
                <footer class="content-footer footer bg-footer-theme">
                    <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                        <div class="mb-2 mb-md-0">
                            ©
                            <script>
                                document.write(new Date().getFullYear());
                            </script>
                            , made with ❤️ by
                            <a href="https://themeselection.com" target="_blank" class="footer-link fw-medium">ThemeSelection</a>
                        </div>
                        <div class="d-none d-lg-inline-block">
                            <a href="https://themeselection.com/license/" class="footer-link me-4" target="_blank">License</a>
                            <a href="https://themeselection.com/" target="_blank" class="footer-link me-4">More Themes</a>

                            <a
                                href="https://demos.themeselection.com/sneat-bootstrap-html-admin-template/documentation/"
                                target="_blank"
                                class="footer-link me-4"
                            >Documentation</a
                            >

                            <a
                                href="https://github.com/themeselection/sneat-html-admin-template-free/issues"
                                target="_blank"
                                class="footer-link"
                            >Support</a
                            >
                        </div>
                    </div>
                </footer>
                <!-- / Footer -->

                <div class="content-backdrop fade"></div>
            </div>
            <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>
</div>
<!-- / Layout wrapper -->

<div class="buy-now">
    <a
        href="https://themeselection.com/item/sneat-bootstrap-html-admin-template/"
        target="_blank"
        class="btn btn-danger btn-buy-now"
    >Upgrade to Pro</a
    >
</div>

<!-- Core JS -->
<!-- build:js assets/vendor/js/core.js -->

<script src="assets/vendor/libs/jquery/jquery.js"></script>
<script src="assets/vendor/libs/popper/popper.js"></script>
<script src="assets/vendor/js/bootstrap.js"></script>
<script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
<script src="assets/vendor/js/menu.js"></script>

<!-- endbuild -->

<!-- Vendors JS -->

<!-- Main JS -->
<script src="assets/js/main.js"></script>
<script src="assets/vendor/libs/jquery/jquery.js"></script>
<script src="assets/vendor/libs/popper/popper.js"></script>
<script src="assets/vendor/js/bootstrap.js"></script>
<script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
<script src="assets/vendor/js/menu.js"></script>

<!-- endbuild -->

<!-- Vendors JS -->

<!-- Main JS -->
<script src="assets/js/main.js"></script>

<!-- Page JS -->
<script src="assets/js/ui-modals.js"></script>

<!-- Place this tag in your head or just before your close body tag. -->
<script async defer src="https://buttons.github.io/buttons.js"></script>

<!-- Page JS -->

<!-- Place this tag in your head or just before your close body tag. -->
<script async defer src="https://buttons.github.io/buttons.js"></script>
</body>
</html>
