<?php
// Start session to track cart

// Ensure the cart session is initialized as an array if not already set
$borrowCart = isset($_SESSION['borrow_cart']) ? $_SESSION['borrow_cart'] : [];

// Calculate cart count (total items in the cart)
$cartCount = count($borrowCart);
?>

<!-- Start: Header Section -->
<header id="header-v1" class="navbar-wrapper">
    <div class="container">
        <div class="row">
            <nav class="navbar navbar-default">
                <div class="row">
                    <div class="col-md-3">
                        <div class="navbar-header">
                            <div class="navbar-brand">
                                <h1>
                                    <a href="index.php">
                                        <img src="images/libraria-logo-v1.png" alt="LIBRARIA" />
                                    </a>
                                </h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <!-- Header Topbar -->
                        <div class="header-topbar hidden-sm hidden-xs">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="topbar-info">
                                        <a href="tel:+61-3-8376-6284"><i class="fa fa-phone"></i>+61-3-8376-6284</a>
                                        <span>/</span>
                                        <a href="mailto:support@libraria.com"><i class="fa fa-envelope"></i>support@libraria.com</a>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="topbar-links">
                                        <a href="signin.php"><i class="fa fa-lock"></i>Signin / Register</a>
                                        <!-- Cart Icon with Dynamic Count -->
                                        <a href="borrowcartview.php">
                                            <i class="fa fa-shopping-cart"></i>
                                            <small><?php echo $cartCount; ?></small> <!-- Updated dynamically -->
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="navbar-collapse hidden-sm hidden-xs">
                            <ul class="nav navbar-nav">
                                <li class="active">
                                    <a href="index.php">Home</a>
                                </li>
                                <li class="dropdown">
                                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">Books <b class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="borrow.php">Borrow</a></li>
                                        <li><a href="my_borrowedbooks.php">My Borrow Lists</a></li>
                                        <li><a href="view_fines.php">My Fines</a></li>
                                    </ul>
                                </li>
                                <li><a href="news-events-list-view.php">News &amp; Events</a></li>
                                <li><a href="services.php">Services</a></li>
                                <li><a href="feedback.php">Feedback</a></li>
                                <li><a href="profile.php">Profile</a></li>
<li class="dropdown">
    <a data-toggle="dropdown" class="dropdown-toggle" href="#">Pages <b class="caret"></b></a>
    <ul class="dropdown-menu">
        <li><a href="borrowcartview.php">Borrow Cart</a></li>
        <li><a href="signin.php">Signin / Register</a></li>
        <li><a href="logout.php">Logout</a></li>
        <li><a href="pdf/User%20Manual.pdf" target="_blank">Tips of User Manual</a></li>
    </ul>
</li>


                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </div>
</header>
<!-- End: Header Section -->
<div class="mobile-menu hidden-lg hidden-md">
                            <a href="#mobile-menu"><i class="fa fa-navicon"></i></a>
                            <div id="mobile-menu">
                                <ul>
                                    <li class="mobile-title">
                                        <h4>Navigation</h4>
                                        <a href="#" class="close"></a>
                                    </li>
                                    <li>
                                        <a href="index.php">Home</a>
                                    </li>
                                    <li>
                                        <a href="#">Books</a>
                                        <ul>
                                            <li><a href="borrow.php">Borrow</a></li>
                                            <li><a href="my_borrowedbooks.php">My Borrow Lists</a></li>
                                            <li><a href="view_fines.php">My Fines</a></li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="news-events-list-view.php">News &amp; Events</a>
                                    </li>
                                    <li><a href="services.php">Services</a></li>
                                                                        <li>
                                        <a href="feedback.php">Feedback</a>
                                    </li>
                                                                        <li>
                                        <a href="profile.php">Profile</a>
                                    </li>
                                    <li>
                                        <a href="#">Pages</a>
                                        <ul>
                                            <li><a href="borrowcartview.php">Borrow Cart</a></li>
                                            <li><a href="signin.php">Signin / Register for Member</a></li>
                                            <li><a href="StaffRegister.php">Signin / Register for Staff</a></li>
                                                                                <li><a href="logout.php">Logout</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </header>
        <!-- End: Header Section -->