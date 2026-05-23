<?php
session_start();
include('dbconnect.php');

if (!isset($_SESSION['SID'])) {
    echo "<script>window.alert('Please Login')</script>";
    echo "<script>window.location='StaffRegister.php'</script>";
    exit();
} else {
    $SID = $_SESSION['SID'];
    $Sname = $_SESSION['SName'];
    $SEmail = $_SESSION['SEmail'];

    if (isset($_GET['delete'])) {
        $PurchaseBookID = $_GET['delete'];

        // Fetch the BookID, Quantity, and PurchaseID linked to the PurchaseBook
        $fetchPurchaseBookQuery = "SELECT BookID, Quantity, PurchaseID FROM purchasebook WHERE PurchaseBookID = '$PurchaseBookID'";
        $purchaseBookResult = mysqli_query($connect, $fetchPurchaseBookQuery);

        if (!$purchaseBookResult) {
            die("Query failed: " . mysqli_error($connect));
        }

        $purchaseBookRow = mysqli_fetch_assoc($purchaseBookResult);

        if (!$purchaseBookRow) {
            echo "<script>window.alert('Purchase book entry not found')</script>";
            echo "<script>window.location='managepurchasebook.php'</script>";
            exit();
        }

        $BookID = $purchaseBookRow['BookID'];
        $QuantityToReduce = $purchaseBookRow['Quantity'];
        $PurchaseID = $purchaseBookRow['PurchaseID'];

        // Start transaction
        mysqli_begin_transaction($connect);

        // Reduce stock in the Book table, ensuring stock doesn't go negative
        $reduceStockQuery = "UPDATE book SET Stock = GREATEST(Stock - $QuantityToReduce, 0) WHERE BookID = '$BookID'";
        $reduceStockResult = mysqli_query($connect, $reduceStockQuery);

        if ($reduceStockResult) {
            // Delete from purchasebook table
            $deletePurchaseBookQuery = "DELETE FROM purchasebook WHERE PurchaseBookID = '$PurchaseBookID'";
            $deletePurchaseBookResult = mysqli_query($connect, $deletePurchaseBookQuery);

            if ($deletePurchaseBookResult) {
                // Check if other purchasebook entries exist for this PurchaseID
                $checkOtherEntries = "SELECT COUNT(*) AS count FROM purchasebook WHERE PurchaseID = '$PurchaseID'";
                $checkResult = mysqli_query($connect, $checkOtherEntries);
                $row = mysqli_fetch_assoc($checkResult);

                if ($row['count'] == 0) {
                    // Only delete purchase if no other related purchasebook entries exist
                    $deletePurchaseQuery = "DELETE FROM purchase WHERE PurchaseID = '$PurchaseID'";
                    $deletePurchaseResult = mysqli_query($connect, $deletePurchaseQuery);

                    if (!$deletePurchaseResult) {
                        mysqli_rollback($connect);
                        echo "<script>window.alert('Error deleting related purchase entry')</script>";
                        exit();
                    }
                }

                // Commit transaction if all deletions succeed
                mysqli_commit($connect);
                echo "<script>window.alert('Purchase Book and related Purchase Entry Deleted')</script>";
                echo "<script>window.location='managepurchasebook.php'</script>";
            } else {
                mysqli_rollback($connect);
                echo "<script>window.alert('Error deleting purchase book entry')</script>";
            }
        } else {
            mysqli_rollback($connect);
            echo "<script>window.alert('Error updating book stock')</script>";
        }
    }

    // Handle Editing of Purchase Book Entry
    if (isset($_POST['update'])) {
        $PurchaseBookID = $_POST['PurchaseBookID'];
        $Quantity = $_POST['Quantity'];
        $UnitPrice = $_POST['UnitPrice'];
        $TotalAmount = $Quantity * $UnitPrice;

        $updateQuery = "UPDATE purchasebook SET Quantity = '$Quantity', UnitPrice = '$UnitPrice' WHERE PurchaseBookID = '$PurchaseBookID'";
        $updateResult = mysqli_query($connect, $updateQuery);

        if ($updateResult) {
            echo "<script>window.alert('Purchase Book Entry Updated')</script>";
            echo "<script>window.location='managepurchasebook.php'</script>";
        } else {
            echo "<script>window.alert('Error occurred while updating the purchase book entry')</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manage Purchase Book - Admin Dashboard</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/custom.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/styles.css?<?php echo time(); ?>">
</head>
<body class="admin_profile">
    <div id="wrapper">
        <?php include('nav.php'); ?>
        <div id="page-wrapper">
            <ol class="breadcrumb">
                <li><a href="admin.php">Dashboard</a></li>
                <li><a href="#">Purchases</a></li>
                <li class="active">Manage Purchase Book</li>
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
                    <div class="containers">
                        <div class="row">
                            <div class="col-md-12">
                                <h3>Purchase Book List</h3>
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
                                            // Dynamically calculate TotalAmount
                                            $query = "SELECT pb.PurchaseBookID, pb.PurchaseID, b.Title, pb.Quantity, pb.UnitPrice, 
                                                             (pb.Quantity * pb.UnitPrice) AS TotalAmount, p.PurchaseDate, s.SupplierName, st.StaffName
                                                      FROM purchasebook pb
                                                      JOIN book b ON pb.BookID = b.BookID
                                                      JOIN purchase p ON pb.PurchaseID = p.PurchaseID
                                                      JOIN supplier s ON p.SupplierID = s.SupplierID
                                                      JOIN staff st ON p.StaffID = st.StaffID";
                                            $result = mysqli_query($connect, $query);

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
    </div>
</body>
</html>
