<?php
session_start();
include('dbconnect.php');
include('AutoID_Functions.php');

if (!isset($_SESSION['SID'])) {
    echo "<script>window.alert('Please Login')</script>";
    echo "<script>window.location='StaffRegister.php'</script>";
} else {

    $SID=$_SESSION['SID'];
    $Sname=$_SESSION['SName'];
    $SEmail=$_SESSION['SEmail'];

    if (isset($_POST['btnsubmit'])) {
        $SupplierID = $_POST['txtSupplierID'];
        $supplierName = $_POST['txtSupplierName'];
        $address = $_POST['txtAddress'];
        $phone = $_POST['txtPhone'];
        $email = $_POST['email'];

        // Check for duplicate supplier name
        $checkSupplier = "SELECT * FROM supplier WHERE SupplierName='$supplierName'";
        $query = mysqli_query($connect, $checkSupplier);
        $count = mysqli_num_rows($query);

        if ($count > 0) {
            echo "<script>window.alert('Duplicate Supplier Name')</script>";
            echo "<script>window.location='entrysupplier.php'</script>";
        } else {
            // Insert supplier details into the database
            $insert = "INSERT INTO supplier(SupplierID, SupplierName, Address, Phone, Email) 
                       VALUES ('$SupplierID','$supplierName', '$address', '$phone', '$email')";
            $query = mysqli_query($connect, $insert);

            if ($query) {
                echo "<script>window.alert('Supplier Entry Success')</script>";
                echo "<script>window.location='entrysupplier.php'</script>";
            } else {
                echo "<script>window.alert('Error occurred, please try again.')</script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Entry Supplier - Admin Dashboard</title>
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
                <li class="active">Entry Supplier</li>
            </ol>

            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h2>Entry Supplier</h2>
                        <h5>Welcome, <?php echo $Sname; ?>, Love to see you back.</h5>
                    </div>
                </div>
                <br>

                <section class="profile-info">
                    <div class="containers">
                        <div class="row">
                            <div class="col-md-4 text-center">
                                <img src="assets/img/find_user.png" alt="Profile Picture" class="img-responsive img-thumbnail" />
                                <h3><?php echo $Sname; ?></h3>
                                <p><?php echo $SEmail; ?></p>
                            </div>
                            <div class="col-md-6">
                                <form method="POST" action="entrysupplier.php">
                                    <div class="form-group">
                                        <label for="supplierID">SupplierID:</label>
                                        <input type="text" name="txtSupplierID" value="<?php echo AutoID($connect, "supplier", "SupplierID", "Su-", 6); ?>" readonly class="form-control" />
                                    </div>
                                    <div class="form-group">
                                        <label for="supplierName">Supplier Name:</label>
                                        <input type="text" class="form-control" id="supplierName" name="txtSupplierName" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="address">Address:</label>
                                        <textarea class="form-control" id="address" name="txtAddress" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="contact">Phone:</label>
                                        <input type="text" class="form-control" id="phone" name="txtPhone" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="contact">Email:</label>
                                        <input type="email" class="form-control" id="email" name="email" required />
                                    </div>
                                    <button type="submit" class="btn btn-success" name="btnsubmit">Add Supplier</button>
                                    <button type="clear" class="btn btn-primary" name="btncancel">Cancel</button>
                                </form>
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
