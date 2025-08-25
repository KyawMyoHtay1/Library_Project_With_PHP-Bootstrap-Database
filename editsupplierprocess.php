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

    if (isset($_POST['btnedit'])) {
        $supplierID = $_POST['supplierID'];
        $supplierName = $_POST['txtSupplierName'];
        $address = $_POST['txtAddress'];
        $phone = $_POST['txtPhone'];
        $email = $_POST['txtEmail'];

        $updateQuery = "UPDATE supplier 
                        SET SupplierName='$supplierName', Address='$address', Phone='$phone', Email='$email' 
                        WHERE SupplierID='$supplierID'";
        
        $updateResult = mysqli_query($connect, $updateQuery);

        if ($updateResult) {
            echo "<script>window.alert('Supplier updated successfully')</script>";
            echo "<script>window.location='managesupplier.php'</script>";
        } else {
            echo "<script>window.alert('Error occurred while updating the supplier')</script>";
        }
    }

    if (isset($_GET['edit'])) {
        $supplierID = $_GET['edit'];
        $query = "SELECT * FROM supplier WHERE SupplierID='$supplierID'";
        $result = mysqli_query($connect, $query);
        $supplier = mysqli_fetch_assoc($result);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Book - Admin Dashboard</title>
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
        
        <div id="page-wrapper">
                                                        <!-- BREADCRUMBS -->
            <ol class="breadcrumb">
                <li><a href="admin.php">Dashboard</a></li>
                <li><a href="managesupplier.php">Manage Supplier</a></li>
                <li class="active">Edit Supplier</li>
            </ol>

            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h2>Edit Supplier</h2>
                        <h5>Update supplier details</h5>
                    </div>
                </div>
                <br>
                
                <div class="row">
                    <div class="col-md-12">
                        <form method="POST">
                            <input type="hidden" name="supplierID" value="<?php echo $supplier['SupplierID']; ?>" />
                            
                            <div class="form-group">
                                <label>Supplier Name:</label>
                                <input type="text" class="form-control" name="txtSupplierName" value="<?php echo $supplier['SupplierName']; ?>" required />
                            </div>
                            
                            <div class="form-group">
                                <label>Address:</label>
                                <input type="text" class="form-control" name="txtAddress" value="<?php echo $supplier['Address']; ?>" required />
                            </div>

                            <div class="form-group">
                                <label>Phone:</label>
                                <input type="text" class="form-control" name="txtPhone" value="<?php echo $supplier['Phone']; ?>" required />
                            </div>

                            <div class="form-group">
                                <label>Email:</label>
                                <input type="email" class="form-control" name="txtEmail" value="<?php echo $supplier['Email']; ?>" required />
                            </div>

                            <button type="submit" name="btnedit" class="btn btn-primary">Update Supplier</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
</body>
</html>
