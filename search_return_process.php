<?php
session_start();
include('dbconnect.php');

if (!isset($_SESSION['SID'])) {
    echo "<script>window.alert('Please Login')</script>";
    echo "<script>window.location='StaffRegister.php'</script>";
    exit();
}

$SID = $_SESSION['SID'];
$Sname = $_SESSION['SName'];
$SEmail = $_SESSION['SEmail'];

// Handle search query
$searchQuery = "";
if (isset($_GET['search'])) {
    $searchTerm = mysqli_real_escape_string($connect, $_GET['search']);
    $searchQuery = "AND (m.MemberName LIKE '%$searchTerm%' OR bk.Title LIKE '%$searchTerm%' OR r.ReturnID LIKE '%$searchTerm%')";
}

// Fetch return records based on search query
$returnQuery = "SELECT r.ReturnID, m.MemberName, m.Email, m.Phone, 
                       r.ReturnDate, r.Status, rb.BookID, bk.Title, 
                       rb.BookCondition, rb.Fine, rb.Quantity
                FROM `return` r
                JOIN Borrow b ON r.BorrowID = b.BorrowID
                JOIN Member m ON b.MemberID = m.MemberID
                JOIN ReturnBook rb ON r.ReturnID = rb.ReturnID
                JOIN Book bk ON rb.BookID = bk.BookID
                WHERE 1 $searchQuery";
$returnResult = mysqli_query($connect, $returnQuery);

// Handle status update for return
if (isset($_POST['update_status'])) {
    $returnID = $_POST['return_id'];
    $status = $_POST['status'];
    $statusUpdateQuery = "UPDATE return SET Status='$status' WHERE ReturnID='$returnID'";
    if (mysqli_query($connect, $statusUpdateQuery)) {
        echo "<script>window.alert('Status updated successfully!');</script>";
    } else {
        echo "<script>window.alert('Error occurred while updating status.');</script>";
    }
}

// Handle delete return record
if (isset($_GET['delete'])) {
    $returnID = $_GET['delete'];
    $deleteQuery = "DELETE FROM return WHERE ReturnID='$returnID'";
    $deleteResult = mysqli_query($connect, $deleteQuery);

    if ($deleteResult) {
        echo "<script>window.alert('Return record deleted successfully')</script>";
        echo "<script>window.location='search_return_process.php'</script>";
    } else {
        echo "<script>window.alert('Error occurred while deleting return record')</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Search Return Records - Admin Dashboard</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/custom.css" rel="stylesheet" />
    <link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <link rel="stylesheet" href="css/styles.css?<?php echo time(); ?>">
</head>
<body class="admin_profile">
    <div id="wrapper">
        <?php include('nav.php'); ?>
        <div id="page-wrapper">
            <ol class="breadcrumb">
                <li><a href="admin.php">Dashboard</a></li>
                <li><a href="#">Borrow & Return</a></li>
                <li><a href="admin_return_process.php">Manage Return Records</a></li>
                <li class="active">Search Return Records</li>
            </ol>

            <!-- Include the search_return.php file -->
            <?php include 'search_return.php'; ?>
            <br>

            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h2>Search Return Records</h2>
                        <h5>Search and manage return records</h5>
                    </div>
                </div>
                <br>

                <!-- Return Records Table -->
                <section class="profile-info">
                    <div class="containers">
                        <div class="row">
                            <div class="col-md-12">
                                <h3>Return Records List</h3>
                                <div class="table-responsive">
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
                                                    echo "<td>" . htmlspecialchars($row['MemberName']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['Email']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['Phone']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['Title']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['BookCondition']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['ReturnDate']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['Quantity']) . "</td>";
                                                    echo "<td>$" . number_format($row['Fine'], 2) . "</td>";

                                                    // Status Dropdown Form
                                                    echo "<td>
                                                        <form method='post' style='display:inline-block;'>
                                                            <input type='hidden' name='return_id' value='" . htmlspecialchars($row['ReturnID']) . "'>
                                                            <select name='status' class='form-control' onchange='this.form.submit()'>
                                                                <option value='Pending' " . ($row['Status'] == 'Pending' ? 'selected' : '') . ">Pending</option>
                                                                <option value='Returned' " . ($row['Status'] == 'Returned' ? 'selected' : '') . ">Returned</option>
                                                                <option value='Damaged' " . ($row['Status'] == 'Damaged' ? 'selected' : '') . ">Damaged</option>
                                                            </select>
                                                            <input type='hidden' name='update_status' value='1'>
                                                        </form>
                                                    </td>";

                                                    // Delete Action
                                                    echo "<td>
                                                        <a href='search_return_process.php?delete=" . htmlspecialchars($row['ReturnID']) . "' class='btn btn-danger' onclick=\"return confirm('Are you sure you want to delete this return record?')\">Delete</a>
                                                    </td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='10'>No return records found.</td></tr>";
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

    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/custom.js"></script>
</body>
</html>
