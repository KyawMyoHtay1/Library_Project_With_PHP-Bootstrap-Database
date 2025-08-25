<?php
include('dbconnect.php');

if (isset($_SESSION['SID'])) {
    $SID = $_SESSION['SID'];
    $Sname = $_SESSION['SName'];
    $SEmail = $_SESSION['SEmail'];
    $Srole = $_SESSION['Srole']; // Missing semicolon was added

    $query = "SELECT * FROM staff WHERE StaffID = '$SID'";
    $result = mysqli_query($connect, $query);
    $staff = mysqli_fetch_assoc($result);
} else {
    echo "<script>window.alert('Please Login')</script>";
    echo "<script>window.location='StaffRegister.php'</script>";
}
?>

<!-- NAV TOP -->
<nav class="navbar navbar-default navbar-cls-top" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="admin_profile.php"><?php echo $Sname; ?></a>
    </div>
    <div style="color: white; padding: 15px 50px 5px 50px; float: right; font-size: 16px;">
        Last Created: 30 January 2025 &nbsp; 
        <a href="logout.php" class="btn btn-danger square-btn-adjust">Logout</a>
    </div>
</nav>
<!-- /. NAV TOP -->

<!-- NAV SIDE -->
<nav class="navbar-default navbar-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav" id="main-menu">
            <li class="text-center">
                <img src="assets/img/find_user.png" class="user-image img-responsive" />
            </li>
            <li><a href="admin.php" class="active"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="submenu">
                <a href="#"><i class="fa fa-list"></i> Categories <span class="fa fa-chevron-down"></span></a>
                <ul class="submenu-items">
                    <li><a href="entrycategory.php">Entry Category</a></li>
                    <li><a href="managecategory.php">Manage Category</a></li>
                </ul>
            </li>
            <li class="submenu">
                <a href="#"><i class="fa fa-book"></i> Books <span class="fa fa-chevron-down"></span></a>
                <ul class="submenu-items">
                    <li><a href="entrybook.php">Entry Book</a></li>
                    <li><a href="managebook.php">Manage Book</a></li>
                </ul>
            </li>
            <!-- New Submenu for Borrow and Return Management -->
            <li class="submenu">
                <a href="#"><i class="fa fa-exchange"></i> Borrow & Return <span class="fa fa-chevron-down"></span></a>
                <ul class="submenu-items">
                    <li><a href="manageborrow.php">Manage Borrow</a></li>
                    <li><a href="entryreturn.php">Entry Return</a></li>
                    <li><a href="admin_return_process.php">Manage Return</a></li>
                </ul>
            </li>
            <li class="submenu">
                <a href="#"><i class="fa fa-truck"></i> Suppliers <span class="fa fa-chevron-down"></span></a>
                <ul class="submenu-items">
                    <li><a href="entrysupplier.php">Entry Supplier</a></li>
                    <li><a href="managesupplier.php">Manage Supplier</a></li>
                </ul>
            </li>
            <li class="submenu">
                <a href="#"><i class="fa fa-users"></i> Members <span class="fa fa-chevron-down"></span></a>
                <ul class="submenu-items">
                    <li><a href="entrymember.php">Entry Member</a></li>
                    <li><a href="managemember.php">Manage Member</a></li>        
                </ul>
            </li>
            <!-- Staff Management Menu -->
            <li class="submenu">
                <a href="#"><i class="fa fa-chevron-down"></i> Staff <span class="fa fa-chevron-down"></span></a>
                <ul class="submenu-items">
                    <li><a href="entrystaff.php">Entry Staff</a></li>
                    <li><a href="managestaff.php">Manage Staff</a></li>
                </ul>
            </li>
            <li class="submenu">
                <a href="#"><i class="fa fa-shopping-cart"></i> Purchase Management <span class="fa fa-chevron-down"></span></a>
                <ul class="submenu-items">
                    <li><a href="entrypurchasebook.php">New Purchase</a></li>
                    <li><a href="managepurchasebook.php">Manage Purchases</a></li>
                </ul>
            </li>
            <li class="submenu">
                <a href="#"><i class="fa fa-comments"></i> Feedback <span class="fa fa-chevron-down"></span></a>
                <ul class="submenu-items">
                    <li><a href="managefeedback.php">Manage Feedback</a></li>
                    <li><a href="viewsubscriber.php">View Subscriber</a></li>
                </ul>
            </li>
            <li><a href="admin_profile.php" class="active"><i class="fa fa-user"></i> Profile</a></li>
        </ul>
    </div>
</nav>
<!-- /. NAV SIDE -->

<!-- Content -->
<div style="margin-left: 250px; padding-top: 60px; padding-left: 20px;">
</div>