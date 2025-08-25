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

    // Handle Search of Purchase Books
    if (isset($_GET['search'])) {
        $search = mysqli_real_escape_string($connect, $_GET['search']); // Sanitize input

        // Modify query to search across multiple fields in purchasebook, book, purchase, and supplier tables
        $query = "SELECT pb.PurchaseBookID, pb.PurchaseID, b.Title, pb.Quantity, pb.UnitPrice, p.TotalAmount, p.PurchaseDate, s.SupplierName, st.StaffName
                  FROM purchasebook pb 
                  JOIN book b ON pb.BookID = b.BookID 
                  JOIN purchase p ON pb.PurchaseID = p.PurchaseID
                  JOIN supplier s ON p.SupplierID = s.SupplierID
                  JOIN staff st ON p.StaffID = st.StaffID
                  WHERE b.Title LIKE '%$search%' 
                  OR b.Author LIKE '%$search%' 
                  OR pb.Quantity LIKE '%$search%' 
                  OR pb.UnitPrice LIKE '%$search%' 
                  OR p.TotalAmount LIKE '%$search%' 
                  OR b.Publisher LIKE '%$search%' 
                  OR s.SupplierName LIKE '%$search%' 
                  OR p.PurchaseDate LIKE '%$search%'";
        $result = mysqli_query($connect, $query);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Search Purchase Books - Admin Dashboard</title>
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
                <li><a href="#">Purchase Management</a></li>
                <li><a href="managepurchasebook.php">Manage Purchase</a></li>
                <li class="active">Search Purchase Books</li>
            </ol>

            <?php include 'search_purchasebook.php'; ?>

            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h2>Manage Purchase Book</h2>
                        <h5>Manage purchase book records</h5>
                    </div>
                </div>
                <br>

                <section class="profile-info">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <h3>Purchase Book List</h3>
                                <!-- Make the table scrollable on smaller screens -->
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Purchase ID</th>
                                                <th>Book Title</th>
                                                <th>Quantity</th>
                                                <th>Unit Price</th>
                                                <th>Total Amount</th>
                                                <th>Supplier Name</th>
                                                <th>Staff Name</th>
                                                <th>Purchase Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                if (isset($result) && mysqli_num_rows($result) > 0) {
                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                        echo "<tr>";
                                                        echo "<td>" . $row['PurchaseID'] . "</td>";
                                                        echo "<td>" . $row['Title'] . "</td>";
                                                        echo "<td>" . $row['Quantity'] . "</td>";
                                                        echo "<td>" . $row['UnitPrice'] . "</td>";
                                                        echo "<td>" . $row['TotalAmount'] . "</td>";
                                                        echo "<td>" . $row['SupplierName'] . "</td>";
                                                        echo "<td>" . $row['StaffName'] . "</td>";
                                                        echo "<td>" . $row['PurchaseDate'] . "</td>";
                                                        echo "<td>
                                                                <a href='editpurchasebookprocess.php?edit=" . $row['PurchaseBookID'] . "' class='btn btn-primary'>Edit</a>
                                                                <a href='managepurchasebook.php?delete=" . $row['PurchaseBookID'] . "' class='btn btn-danger' onclick='return confirm(\"Are you sure you want to delete this purchase book entry?\")'>Delete</a>
                                                              </td>";
                                                        echo "</tr>";
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='8'>No purchase books found matching your search.</td></tr>";
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
