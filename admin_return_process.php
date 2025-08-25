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

    // Delete Return Record
    if (isset($_GET['delete'])) {
        $returnID = $_GET['delete'];
        $deleteQuery = "DELETE FROM `Return` WHERE ReturnID='$returnID'";
        $deleteResult = mysqli_query($connect, $deleteQuery);

        if ($deleteResult) {
            echo "<script>window.alert('Return record deleted successfully')</script>";
            echo "<script>window.location='admin_return_process.php'</script>";
        } else {
            echo "<script>window.alert('Error occurred while deleting return record')</script>";
        }
    }

    // Handle status update for return
    if (isset($_POST['update_status'])) {
        $returnID = $_POST['return_id'];
        $status = $_POST['status'];
        $statusUpdateQuery = "UPDATE `return` SET Status='$status' WHERE ReturnID='$returnID'";
        if (mysqli_query($connect, $statusUpdateQuery)) {
            // Status updated successfully
            echo "<script>window.alert('Status updated successfully!');</script>";
        } else {
            // Error occurred during update
            echo "<script>window.alert('Error occurred while updating status.');</script>";
        }
    }

$returnQuery = "SELECT r.ReturnID, m.MemberName, m.Email, m.Phone, 
                       r.ReturnDate, r.Status, rb.BookID, bk.Title, 
                       rb.BookCondition, rb.Fine, rb.Quantity
                FROM `return` r
                JOIN Borrow b ON r.BorrowID = b.BorrowID
                JOIN Member m ON b.MemberID = m.MemberID
                JOIN ReturnBook rb ON r.ReturnID = rb.ReturnID
                JOIN Book bk ON rb.BookID = bk.BookID";



$returnResult = mysqli_query($connect, $returnQuery);

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manage Return Records - Admin Dashboard</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/custom.css" rel="stylesheet" />
    <link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <link rel="stylesheet" href="css/styles.css?<?php echo time(); ?>">
</head>
<body class="admin_profile">
    <div id="wrapper">
        <div id="page-wrapper">
            <ol class="breadcrumb">
                <li><a href="admin.php">Dashboard</a></li>
                <li><a href="#">Borrow & Return</a></li>
                <li class="active">Manage Return Records</li>
            </ol>

            <?php include 'search_return.php'; ?>

            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h2>Manage Return Records</h2>
                        <h5>View and manage return records</h5>
                    </div>
                </div>
                <br>

                <section class="profile-info">
                    <div class="containers">
                        <div class="row">
                            <div class="col-md-12">
                                <h3>Return Records List</h3>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Member Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Book Title</th>
                                            <th>Book Condition</th>
                                            <th>Return Date</th>
                                            <th>Quantity</th>
                                            <th>Fine</th>
                                            <th style="width: 150px;">Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (mysqli_num_rows($returnResult) > 0) {
                                            while ($row = mysqli_fetch_assoc($returnResult)) {
                                                echo "<tr>";
                                                echo "<td>" . $row['MemberName'] . "</td>";
                                                echo "<td>" . $row['Email'] . "</td>";
                                                echo "<td>" . $row['Phone'] . "</td>";
                                                echo "<td>" . $row['Title'] . "</td>";
                                                echo "<td>" . $row['BookCondition'] . "</td>";
                                                echo "<td>" . $row['ReturnDate'] . "</td>";
                                                echo "<td>" . $row['Quantity'] . "</td>";
                                                echo "<td>$" . number_format($row['Fine'], 2) . "</td>";

                                                
                                                // Status Dropdown Form
                                                echo "<td>
                                                    <form method='post' style='display:inline-block;'>
                                                        <input type='hidden' name='return_id' value='" . $row['ReturnID'] . "'>
                                                        <select name='status' class='form-control' onchange='this.form.submit()'>
                                                            <option value='Overdue' " . ($row['Status'] == 'Overdue' ? 'selected' : '') . ">Overdue</option>
                                                            <option value='Returned' " . ($row['Status'] == 'Returned' ? 'selected' : '') . ">Returned</option>
                                                            <option value='Damaged' " . ($row['Status'] == 'Damaged' ? 'selected' : '') . ">Damaged</option>
                                                        </select>
                                                        <input type='hidden' name='update_status' value='1'>
                                                    </form>
                                                </td>";

                                                // Delete Action
                                                echo "<td>
                                                    <a href='admin_return_process.php?delete=" . $row['ReturnID'] . "' class='btn btn-danger' onclick=\"return confirm('Are you sure you want to delete this return record?')\">Delete</a>
                                                </td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='8'>No return records found.</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/custom.js"></script>
</body>
</html>
