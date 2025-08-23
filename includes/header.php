<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<style>
    /* Nav links underline remove */
    .header__menu ul li a,
    .offcanvas-body .nav-link {
        text-decoration: none !important;
    }
</style>

<?php
// Start session (only once at the very top)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!-- Header Section Begin -->
<header class="header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-6 col-lg-8 d-flex align-items-center">
                <div class="header__logo">
                    <a href="./index.php"><img src="img/logo.png" alt=""></a>
                </div>

                <!-- Desktop Menu -->
                <nav class="header__menu d-none d-lg-block">
                    <ul>
                        <li class="active"><a href="./index.php">Home</a></li>
                        <li><a href="./about.php">About</a></li>
                        <li><a href="./contact.php">Contact</a></li>
                        <li><a href="./episodes.php">Episodes</a></li>
                        <li><a href="#">Pages</a>
                            <ul class="dropdown">
                                <li><a href="./about.php">About</a></li>
                                <li><a href="./episodes-details.php">Episodes</a></li>
                                <li><a href="./blog.php">Blog</a></li>
                                <li><a href="./blog-details.php">Blog Details</a></li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>

            <div class="col-6 col-lg-4 d-flex justify-content-end align-items-center">
                <!-- Mobile Toggle Button -->
                <button class="btn text-white d-lg-none" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#mobileMenu">
                    <i class="fa fa-bars"></i>
                </button>

                <!-- Search (desktop only) -->
                <div class="header__right__search d-none d-lg-block d-inline-flex">
                    <form action="search.php" method="GET">
                        <input type="text" name="q" placeholder="Search and hit enter..." required>
                        <button type="submit"><i class="fa fa-search"></i></button>
                    </form>
                </div>

                <!-- Auth (desktop only) -->
                <div class="header__right__auth d-none d-lg-flex align-items-center ms-3">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="logout.php" class="btn btn-outline-danger btn-sm me-2">Logout</a>
                    <?php else: ?>
                        <a href="login.php" class="btn-sm me-2">Login</a>
                        <a href="register.php" class="btn-sm">Register</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- Header Section End -->

<!-- Offcanvas Menu (Mobile Only) -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="mobileMenu">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">Menu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" href="./index.php">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="./about.php">About</a></li>
            <li class="nav-item"><a class="nav-link" href="./contact.php">Contact</a></li>
            <li class="nav-item"><a class="nav-link" href="./episodes.php">Episodes</a></li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Pages</a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="./about.php">About</a></li>
                    <li><a class="dropdown-item" href="./episodes-details.php">Episodes</a></li>
                    <li><a class="dropdown-item" href="./blog.php">Blog</a></li>
                    <li><a class="dropdown-item" href="./blog-details.php">Blog Details</a></li>
                </ul>
            </li>
        </ul>

        <!-- Search (mobile only) -->
        <div class="mt-3">
            <form action="search.php" method="GET">
                <input class="form-control outline-primary mb-2" type="text" name="q" placeholder="Search and hit enter..." required>
                <button class="btn btn-primary w-100" type="submit"><i class="fa fa-search"></i> Search</button>
            </form>
        </div>

        <!-- Auth (mobile only) -->
        <div class="mt-3">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="logout.php" class="btn btn-danger w-100">Logout</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-outline-primary w-100 mb-2">Login</a>
                <a href="register.php" class="btn btn-outline-primary w-100">Register</a>
            <?php endif; ?>
        </div>
    </div>
</div>
