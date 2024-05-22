<?php
session_start();
include 'config/db_connection.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT u.user_id, u.username, r.role_name FROM Users u JOIN Roles r ON u.role_id = r.role_id WHERE u.username = '$username' AND u.password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['user'] = $row;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password";
    }
}
?>


<!DOCTYPE html>

<html
    lang="en"
    class="light-style layout-wide customizer-hide"
    dir="ltr"
    data-theme="theme-default"
    data-assets-path="../assets/"
    data-template="vertical-menu-template-free">
<head>
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Mutare City Council</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />

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
    <!-- Page -->
    <link rel="stylesheet" href="assets/vendor/css/pages/page-auth.css" />

    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="assets/js/config.js"></script>
</head>

<body>
<!-- Content -->

<div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
            <!-- Register -->
            <div class="card">
                <div class="card-body">
                    <!-- Logo -->
                    <div class="app-brand justify-content-center">
                        <a href="index.html" class="app-brand-link gap-2">
                  <span class="app-brand-logo demo">
                    <img src="data:image/jpeg;base64,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
                    p1BCoy3VlZHB4++66+2cr8FhnxmptMf9oVg9w3Xf/ANOSrNHvKAAFyW7Gxdk2b9zzlqWFoUv9tgHgFBcTqU/h0yIAEVVAFAKAKHehXtedElQlcIjCIwih9d4Z0srs7xepvrKMyb6/1b
                    CN39byCAdUXei8N6SL6NPGDd7ioLfb1NZ4/nJRSPkrW3aNvxXH9sImGp6BpnYO0Kbg
                    b3KNpsfJWr/g4NxCiLym2p8LQPXqmWiWG2Z+/seSe3t8e2QpUpodGkMaxRrtRBSiya/qeT/JyUTjCIwiMIjCL/2Q==">
                  </span>

                        </a>
                    </div>
                    <!-- /Logo -->
                    <h4 class="mb-2">Welcome to Mutare City Council! 👋</h4>


                    <form id="formAuthentication" class="mb-3" action="" method="post">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email or Username</label>
                            <input
                                type="text"
                                class="form-control"
                                id="email"
                                name="username"
                                placeholder="Enter your email or username"
                                autofocus />
                        </div>
                        <div class="mb-3 form-password-toggle">
                            <div class="d-flex justify-content-between">
                                <label class="form-label" for="password">Password</label>
                                <a href="auth-forgot-password-basic.html">
                                    <small>Forgot Password?</small>
                                </a>
                            </div>
                            <div class="input-group input-group-merge">
                                <input
                                    type="password"
                                    id="password"
                                    class="form-control"
                                    name="password"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                    aria-describedby="password" />
                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember-me" />
                                <label class="form-check-label" for="remember-me"> Remember Me </label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-primary d-grid w-100" type="submit">Sign in</button>
                        </div>
                    </form>

                    <p class="text-center">
                        <span>New on our platform?</span>
                        <a href="auth-register-basic.html">
                            <span>Create an account</span>
                        </a>
                    </p>
                </div>
            </div>
            <!-- /Register -->
        </div>
    </div>
</div>

<!-- / Content -->

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

<script async defer src="https://buttons.github.io/buttons.js"></script>
</body>
</html>
