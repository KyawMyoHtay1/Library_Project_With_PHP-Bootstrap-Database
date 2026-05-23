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

    // Fetch current record to edit
    if (isset($_GET['edit'])) {
        $PurchaseBookID = $_GET['edit'];
        
        $query = "SELECT pb.PurchaseBookID, pb.PurchaseID, b.BookID, b.Title, pb.Quantity, pb.UnitPrice, p.TotalAmount, p.TotalQuantity, p.PurchaseDate, p.SupplierID, s.SupplierName
                  FROM purchasebook pb
                  JOIN book b ON pb.BookID = b.BookID
                  JOIN purchase p ON pb.PurchaseID = p.PurchaseID
                  JOIN supplier s ON p.SupplierID = s.SupplierID
                  WHERE pb.PurchaseBookID = '$PurchaseBookID'";

        $result = mysqli_query($connect, $query);
        $row = mysqli_fetch_assoc($result);
    }

    // Handle the update logic
    if (isset($_POST['update'])) {
        $PurchaseBookID = $_POST['PurchaseBookID'];
        $Quantity = $_POST['Quantity'];
        $UnitPrice = $_POST['UnitPrice'];
        $SupplierID = $_POST['SupplierID']; // Supplier ID is selected from the dropdown
        $TotalAmount = $Quantity * $UnitPrice;

        // Validation for Quantity and UnitPrice
        if ($Quantity <= 0) {
            echo "<script>window.alert('Quantity must be greater than zero.')</script>";
            echo "<script>window.location='editpurchasebookprocess.php?edit=$PurchaseBookID'</script>";
            exit();
        }

        if ($UnitPrice <= 0) {
            echo "<script>window.alert('Unit Price must be greater than zero.')</script>";
            echo "<script>window.location='editpurchasebookprocess.php?edit=$PurchaseBookID'</script>";
            exit();
        }

        // Fetch current stock from Book table
        $bookQuery = "SELECT Stock FROM book WHERE BookID = '" . $row['BookID'] . "'";
        $bookResult = mysqli_query($connect, $bookQuery);
        $bookData = mysqli_fetch_assoc($bookResult);
        $currentStock = $bookData['Stock'];

        // Calculate the difference in quantity
        $quantityDifference = $Quantity - $row['Quantity'];

        // Ensure there is enough stock if decreasing
        if ($quantityDifference < 0 && abs($quantityDifference) > $currentStock) {
            echo "<script>window.alert('Not enough stock to reduce by this quantity.')</script>";
            echo "<script>window.location='editpurchasebookprocess.php?edit=$PurchaseBookID'</script>";
            exit();
        }

        // Update query for purchasebook and supplier
        $updateQuery = "UPDATE purchasebook pb 
                        JOIN purchase p ON pb.PurchaseID = p.PurchaseID
                        SET pb.Quantity = '$Quantity', pb.UnitPrice = '$UnitPrice', p.TotalAmount = '$TotalAmount', p.SupplierID = '$SupplierID' 
                        WHERE pb.PurchaseBookID = '$PurchaseBookID'";

        // Update the stock in the Book table
        $updateStockQuery = "UPDATE book 
                             SET Stock = Stock + $quantityDifference 
                             WHERE BookID = '" . $row['BookID'] . "'";

        // Update the total amount in the purchase table
        $updatePurchaseQuery = "UPDATE purchase 
                                 SET TotalAmount = (SELECT SUM(Quantity * UnitPrice) FROM purchasebook WHERE PurchaseID = '" . $row['PurchaseID'] . "'),
                                     TotalQuantity = (SELECT SUM(Quantity) FROM purchasebook WHERE PurchaseID = '" . $row['PurchaseID'] . "')
                                 WHERE PurchaseID = '" . $row['PurchaseID'] . "'";

        // Execute the update queries
        $updateResult = mysqli_query($connect, $updateQuery);
        $updateStockResult = mysqli_query($connect, $updateStockQuery);
        $updatePurchaseResult = mysqli_query($connect, $updatePurchaseQuery);

        if ($updateResult && $updateStockResult && $updatePurchaseResult) {
            echo "<script>window.alert('Purchase Book Entry, Stock, and Total Quantity Updated')</script>";
            echo "<script>window.location='managepurchasebook.php'</script>";
        } else {
            echo "<script>window.alert('Error occurred while updating the purchase book entry or stock')</script>";
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Purchase Book - Admin Dashboard</title>
    <!-- BOOTSTRAP STYLES-->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONTAWESOME STYLES-->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLES-->
    <link href="assets/css/custom.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/styles.css?<?php echo time(); ?>">
</head>
<body class="admin_profile">
    <div id="wrapper">
        <div id="page-wrapper">
            <ol class="breadcrumb">
                <li><a href="admin.php">Dashboard</a></li>
                <li><a href="managepurchasebook.php">Manage Purchases</a></li>
                <li class="active">Edit Purchase Book</li>
            </ol>

            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h2>Edit Purchase Book</h2>
                        <h5>Update purchase book record</h5>
                    </div>
                </div>
                <br>

                <section class="profile-info">
                    <div class="containers">
                        <div class="row">
                            <div class="col-md-12">
                                <form method="POST" action="">
                                    <input type="hidden" name="PurchaseBookID" value="<?php echo $row['PurchaseBookID']; ?>" />

                                    <div class="form-group">
                                        <label for="PurchaseID">Purchase ID</label>
                                        <input type="text" name="PurchaseID" class="form-control" value="<?php echo $row['PurchaseID']; ?>" readonly />
                                    </div>

                                    <div class="form-group">
                                        <label for="BookID">Book Title</label>
                                        <input type="text" name="BookID" class="form-control" value="<?php echo $row['Title']; ?>" readonly />
                                    </div>

                                    <div class="form-group">
                                        <label for="Quantity">Quantity</label>
                                        <input type="number" name="Quantity" value="<?php echo $row['Quantity']; ?>" class="form-control" required />
                                    </div>

                                    <div class="form-group">
                                        <label for="UnitPrice">Unit Price</label>
                                        <input type="text" name="UnitPrice" value="<?php echo $row['UnitPrice']; ?>" class="form-control" required />
                                    </div>

                                    <div class="form-group">
                                        <label for="SupplierID">Supplier</label>
                                        <select name="SupplierID" class="form-control" required>
                                            <?php
                                            // Fetch all suppliers for the dropdown
                                            $supplierQuery = "SELECT * FROM supplier";
                                            $supplierResult = mysqli_query($connect, $supplierQuery);
                                            
                                            // Loop through the suppliers and add them to the dropdown
                                            while ($supplierRow = mysqli_fetch_assoc($supplierResult)) {
                                                echo "<option value='" . $supplierRow['SupplierID'] . "' " . ($supplierRow['SupplierID'] == $row['SupplierID'] ? 'selected' : '') . ">";
                                                echo $supplierRow['SupplierName'];
                                                echo "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="TotalAmount">Total Amount</label>
                                        <input type="text" name="TotalAmount" value="<?php echo $row['TotalAmount']; ?>" class="form-control" readonly />
                                    </div>

                                    <button type="submit" name="update" class="btn btn-primary">Update</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT FILES -->
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/custom.js"></script>
</body>
</html>
