<?php
session_start();
include('dbconnect.php');
include('nav.php');

if (!isset($_SESSION['SID'])) {
    echo "<script>window.alert('Please Login')</script>";
    echo "<script>window.location='StaffRegister.php'</script>";
} else {
    $SID = $_SESSION['SID'];
    $Sname = $_SESSION['SName'];
    $SEmail = $_SESSION['SEmail'];

    // Handle Supplier Search
    if (isset($_GET['search'])) {
        $search = mysqli_real_escape_string($connect, $_GET['search']); // Sanitize input

        // Query to search for suppliers by name or email
        $query = "SELECT * FROM supplier WHERE SupplierName LIKE '%$search%' OR Email LIKE '%$search%' OR Address LIKE '%$search%'";
        $result = mysqli_query($connect, $query);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Search Suppliers - Admin Dashboard</title>
    <!-- BOOTSTRAP STYLES-->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONTAWESOME STYLES-->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLES-->
    <link href="assets/css/custom.css" rel="stylesheet" />
    <!-- GOOGLE FONTS-->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <link rel="stylesheet" href="css/styles.css?<?php echo time(); ?>">
</head>
<body class="admin_profile">
    <div id="wrapper">
        <!-- MAIN CONTENT -->
        <div id="page-wrapper">

            <!-- BREADCRUMBS -->
            <ol class="breadcrumb">
                <li><a href="admin.php">Dashboard</a></li>
                <li><a href="#">Suppliers</a></li>
                <li><a href="managesupplier.php">Manage Suppliers</a></li>
                <li class="active">Search Suppliers</li>
            </ol>

            <?php include 'search_supplier.php'; ?>

            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h2>Manage Supplier</h2>
                        <h5>Manage supplier records</h5>
                    </div>
                </div>
                <br>

                <section class="profile-info">
                    <div class="containers">
                        <div class="row">
                            <div class="col-md-12">
                                <h3>Suppliers List</h3>
                                <!-- Make the table scrollable on small screens -->
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>SupplierName</th>
                                                <th>Address</th>
                                                <th>Phone</th>
                                                <th>Email</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                if (isset($result) && mysqli_num_rows($result) > 0) {
                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                        echo "<tr>";
                                                        echo "<td>" . $row['SupplierName'] . "</td>";
                                                        echo "<td>" . $row['Address'] . "</td>";
                                                        echo "<td>" . $row['Phone'] . "</td>";
                                                        echo "<td>" . $row['Email'] . "</td>";
                                                        echo "<td>
                                                                <a href='editsupplierprocess.php?edit=" . $row['SupplierID'] . "' class='btn btn-primary'>Edit</a>
                                                                <a href='deletesupplier.php?delete=" . $row['SupplierID'] . "' class='btn btn-danger' onclick='return confirm(\"Are you sure you want to delete this supplier?\")'>Delete</a>
                                                              </td>";
                                                        echo "</tr>";
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='5'>No suppliers found matching your search.</td></tr>";
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <!-- /. MAIN CONTENT -->

        <!-- JAVASCRIPT FILES -->
        <script src="assets/js/jquery-3.6.0.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="assets/js/custom.js"></script>
    </body>
</html>
