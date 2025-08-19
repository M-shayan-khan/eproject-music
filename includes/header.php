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
            <div class="col-lg-8">
                <div class="header__logo">
                    <a href="./index.php"><img src="img/logo.png" alt=""></a>
                </div>
                <nav class="header__menu mobile-menu">
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
            <div class="col-lg-4 d-flex justify-content-end align-items-center">
                <div class="header__right__search">
                    <form action="search.php" method="GET">
                        <input type="text" name="q" placeholder="Search and hit enter..." required>
                        <button type="submit"><i class="fa fa-search"></i></button>
                    </form>
                </div>
                <div class="header__right__auth" style="margin-left: 20px;">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="logout.php" style="color: #fff; text-decoration: none; margin-right: 10px;">Logout</a>
                    <?php else: ?>
                        <a href="login.php" style="color: #fff; text-decoration: none; margin-right: 10px;">Login</a>
                        <a href="register.php" style="color: #fff; text-decoration: none;">Register</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- Header Section End -->