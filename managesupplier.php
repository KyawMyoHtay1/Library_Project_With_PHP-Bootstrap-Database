<?php
session_start();
include('dbconnect.php');

if (!isset($_SESSION['SID'])) {
    echo "<script>window.alert('Please Login')</script>";
    echo "<script>window.location='StaffRegister.php'</script>";
} else {
    $SID = $_SESSION['SID'];
    $Sname = $_SESSION['SName'];
    $SEmail = $_SESSION['SEmail'];

    // Handle Supplier Deletion
    if (isset($_GET['delete'])) {
        $supplierID = $_GET['delete'];

        // Delete supplier from database
        $deleteQuery = "DELETE FROM supplier WHERE SupplierID='$supplierID'";
        $deleteResult = mysqli_query($connect, $deleteQuery);

        if ($deleteResult) {
            echo "<script>window.alert('Supplier deleted successfully')</script>";
            echo "<script>window.location='managesupplier.php'</script>";
        } else {
            echo "<script>window.alert('Error occurred while deleting the supplier')</script>";
        }
    }

    // Handle Supplier Editing
    if (isset($_POST['btnedit'])) {
        $supplierID = $_POST['supplierID'];
        $name = $_POST['txtname'];
        $email = $_POST['txtemail'];
        $phone = $_POST['txtphone'];
        $address = $_POST['txtaddress'];

        // Update supplier details in the database
        $updateQuery = "UPDATE supplier 
                        SET Name='$name', Email='$email', Phone='$phone', Address='$address'
                        WHERE SupplierID='$supplierID'";

        $updateResult = mysqli_query($connect, $updateQuery);

        if ($updateResult) {
            echo "<script>window.alert('Supplier updated successfully')</script>";
            echo "<script>window.location='managesupplier.php'</script>";
        } else {
            echo "<script>window.alert('Error occurred while updating the supplier')</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manage Supplier - Admin Dashboard</title>
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
        <?php include('nav.php'); ?>

        <!-- MAIN CONTENT -->
        <div id="page-wrapper">

            <!-- BREADCRUMBS -->
            <ol class="breadcrumb">
                <li><a href="admin.php">Dashboard</a></li>
                <li><a href="#">Suppliers</a></li>
                <li class="active">Manage Supplier</li>
            </ol>
            
            <?php 
            include 'search_supplier.php'; 
            ?>

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
                                <!-- Add responsive table class -->
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
                                                $query = "SELECT * FROM supplier";
                                                $result = mysqli_query($connect, $query);

                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    echo "<tr>";
                                                    echo "<td>" . $row['SupplierName'] . "</td>";
                                                    echo "<td>" . $row['Address'] . "</td>";
                                                    echo "<td>" . $row['Phone'] . "</td>";
                                                    echo "<td>" . $row['Email'] . "</td>";
                                                    echo "<td>
                                                            <a href='editsupplierprocess.php?edit=" . $row['SupplierID'] . "' class='btn btn-primary btn-sm'>Edit</a>
                                                            <a href='deletesupplier.php?delete=" . $row['SupplierID'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this supplier?\")'>Delete</a>
                                                          </td>";
                                                    echo "</tr>";
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

