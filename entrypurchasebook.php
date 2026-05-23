<?php
session_start();
include('dbconnect.php');
include('AutoID_Functions.php');

if (!isset($_SESSION['SID'])) {
    echo "<script>window.alert('Please Login')</script>";
    echo "<script>window.location='StaffRegister.php'</script>";
    exit();
} 

$SID = $_SESSION['SID'];
$Sname = $_SESSION['SName'];
$SEmail = $_SESSION['SEmail'];

if (isset($_POST['btnsubmit'])) {
    // Validate Supplier Selection
    if (empty($_POST['txtSupplierID'])) {
        echo "<script>alert('Please select a supplier.');</script>";
        echo "<script>window.location='entrypurchasebook.php'</script>";
        exit();
    }

    // Validate Books
    if (!isset($_POST['txtBookID']) || count($_POST['txtBookID']) == 0) {
        echo "<script>alert('Please add at least one book.');</script>";
        echo "<script>window.location='entrypurchasebook.php'</script>";
        exit();
    }

    $TotalAmount = 0; 
    $TotalQuantity = 0;

    foreach ($_POST['txtBookID'] as $index => $BookID) {
        if (empty($BookID)) {
            echo "<script>alert('Please select a book for all rows.');</script>";
            echo "<script>window.location='entrypurchasebook.php'</script>";
            exit();
        }
        if ($_POST['txtQuantity'][$index] <= 0) {
            echo "<script>alert('Quantity must be greater than zero.');</script>";
            echo "<script>window.location='entrypurchasebook.php'</script>";
            exit();
        }
        if ($_POST['txtPrice'][$index] <= 0) {
            echo "<script>alert('Price must be greater than zero.');</script>";
            echo "<script>window.location='entrypurchasebook.php'</script>";
            exit();
        }

        // Calculate total amount and quantity
        $TotalAmount += $_POST['txtQuantity'][$index] * $_POST['txtPrice'][$index];
        $TotalQuantity += $_POST['txtQuantity'][$index];
    }

    // Generate AutoID for Purchase
    $PurchaseID = AutoID($connect, "purchase", "PurchaseID", "Pu-", 6);
    $SupplierID = $_POST['txtSupplierID'];
    $PurchaseDate = $_POST['txtPurchaseDate'];

    // Insert into Purchase table
    $insertPurchase = "INSERT INTO purchase (PurchaseID, SupplierID, StaffID, PurchaseDate, TotalAmount, TotalQuantity) 
                       VALUES ('$PurchaseID', '$SupplierID', '$SID', '$PurchaseDate', '$TotalAmount', '$TotalQuantity')";
    $queryPurchase = mysqli_query($connect, $insertPurchase);

    if ($queryPurchase) {
        // Insert books into PurchaseBook table
        foreach ($_POST['txtBookID'] as $index => $BookID) {
            $PurchaseBookID = AutoID($connect, "purchasebook", "PurchaseBookID", "Pb-", 6);
            $Quantity = $_POST['txtQuantity'][$index];
            $Price = $_POST['txtPrice'][$index];

            $insertPurchaseBook = "INSERT INTO purchasebook (PurchaseBookID, PurchaseID, BookID, Quantity, UnitPrice) 
                                   VALUES ('$PurchaseBookID', '$PurchaseID', '$BookID', '$Quantity', '$Price')";
            mysqli_query($connect, $insertPurchaseBook);

            // Update book stock
            $updateStock = "UPDATE book SET Stock = Stock + $Quantity WHERE BookID = '$BookID'";
            mysqli_query($connect, $updateStock);
        }

        echo "<script>window.alert('Purchase and Books Entry Successful')</script>";
        echo "<script>window.location='entrypurchasebook.php'</script>";
    } else {
        echo "<script>window.alert('Error occurred, please try again.')</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Entry Purchase and Books</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/custom.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/styles.css?<?php echo time(); ?>">
    <script>
        function addBookRow() {
            let table = document.getElementById("bookTable");
            let row = table.insertRow();
            row.innerHTML = `
                <td>
                    <select class="form-control book-select" name="txtBookID[]" required onchange="updateBookImage(this)">
                        <option value="">Choose Book</option>
                        <?php
                        $query = "SELECT * FROM book";
                        $result = mysqli_query($connect, $query);
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='" . $row['BookID'] . "' data-image='" . $row['Image'] . "'>" . $row['Title'] . "</option>";
                        }
                        ?>
                    </select>
                </td>
                <td><input type="number" class="form-control" name="txtQuantity[]" required /></td>
                <td><input type="number" step="0.01" class="form-control" name="txtPrice[]" required /></td>
                <td><img src="uploads/default.png" alt="Book Image" class="book-img img-thumbnail" width="50"></td>
                <td><button type="button" class="btn btn-danger" onclick="removeBookRow(this)">Remove</button></td>
            `;
        }

        function updateBookImage(selectElement) {
            let selectedOption = selectElement.options[selectElement.selectedIndex];
            let imageSrc = selectedOption.getAttribute("data-image") || "uploads/default.png";
            selectElement.closest("tr").querySelector(".book-img").src = imageSrc;
        }

        function removeBookRow(button) {
            let row = button.parentNode.parentNode;
            row.parentNode.removeChild(row);
        }
    </script>
</head>
<body class="admin_profile">
    <div id="wrapper">
        <?php include('nav.php'); ?>
        <div id="page-wrapper">
            <ol class="breadcrumb">
                <li><a href="admin.php">Dashboard</a></li>
                <li><a href="#">Purchases</a></li>
                <li class="active">Entry Purchase and Books</li>
            </ol>

            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h2>Entry Purchase & Books</h2>
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
                                <form method="POST" action="entrypurchasebook.php">
                                    <h4>Purchase Details</h4>
                                                                        <div class="form-group">
                                        <label for="purchaseDate">Purchase ID:</label>
                                            <input type="text" name="txtpuID" value="<?php echo AutoID($connect,"Purchase","PurchaseID","Pu-",6); ?>" readonly/>
                                    </div>

                                    <div class="form-group">
                                        <label for="supplier">Supplier:</label>
                                        <select class="form-control" name="txtSupplierID" required>
                                            <option value="">Choose Supplier</option>
                                            <?php
                                            $query = "SELECT * FROM supplier";
                                            $result = mysqli_query($connect, $query);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                echo "<option value='" . $row['SupplierID'] . "'>" . $row['SupplierName'] . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="purchaseDate">Purchase Date:</label>
                                        <input type="date" class="form-control" name="txtPurchaseDate" value="<?php echo date('Y-m-d'); ?>" readonly />

                                    </div>

                                    <h4>Purchase Book</h4>
                                    <table class="table table-bordered" id="bookTable">
                                        <tr>
                                            <th width="150px">Book</th>
                                            <th>Quantity</th>
                                            <th width="100px">Price</th>
                                            <th width="80px">Image</th>
                                            <th>Action</th>
                                        </tr>
                                    </table>
                                    <button type="button" class="btn btn-info" onclick="addBookRow()">+ Add Book</button>
                                    <br><br>

                                    <button type="submit" class="btn btn-success" name="btnsubmit">Submit Purchase</button>
                                    <button type="reset" class="btn btn-primary">Cancel</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</body>
</html>
