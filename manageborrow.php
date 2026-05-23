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

    // Delete Borrow Record
    if (isset($_GET['delete'])) {
        $borrowID = $_GET['delete'];
        $deleteQuery = "DELETE FROM Borrow WHERE BorrowID='$borrowID'";
        $deleteResult = mysqli_query($connect, $deleteQuery);

        if ($deleteResult) {
            echo "<script>window.alert('Borrow record deleted successfully')</script>";
            echo "<script>window.location='manageborrow.php'</script>";
        } else {
            echo "<script>window.alert('Error occurred while deleting borrow record')</script>";
        }
    }

    // Handle status update
    if (isset($_POST['update_status'])) {
        $borrowID = $_POST['borrow_id'];
        $bookID = $_POST['book_id']; // Add BookID to the status update
        $status = $_POST['status'];
        
        // Update the Status in the BorrowBook table
        $statusUpdateQuery = "UPDATE BorrowBook SET Status='$status' WHERE BorrowID='$borrowID' AND BookID='$bookID'";
        if (mysqli_query($connect, $statusUpdateQuery)) {
            // Status updated successfully
            echo "<script>window.alert('Status updated successfully!');</script>";
        } else {
            // Error occurred during update
            echo "<script>window.alert('Error occurred while updating status.');</script>";
        }
    }

    // Fetch all borrow records with Member and Book details
    $borrowQuery = "SELECT b.BorrowID, m.MemberName, m.Email, m.Phone, b.BorrowDate, b.DueDate, bb.Status, bb.BookID, bk.Title, bb.Quantity
                    FROM Borrow b
                    JOIN Member m ON b.MemberID = m.MemberID
                    JOIN BorrowBook bb ON b.BorrowID = bb.BorrowID
                    JOIN Book bk ON bb.BookID = bk.BookID";
    $borrowResult = mysqli_query($connect, $borrowQuery);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manage Borrow Records - Admin Dashboard</title>
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
                <li class="active">Manage Borrow Records</li>
            </ol>

            <?php include 'search_borrow.php'; ?>

            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h2>Manage Borrow Records</h2>
                        <h5>View and manage borrow records</h5>
                    </div>
                </div>
                <br>

                <section class="profile-info">
                    <div class="containers">
                        <div class="row">
                            <div class="col-md-12">
                                <h3>Borrow Records List</h3>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Member Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Book Title</th>
                                            <th>Quantity</th>
                                            <th>Borrow Date</th>
                                            <th>Due Date</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (mysqli_num_rows($borrowResult) > 0) {
                                            while ($row = mysqli_fetch_assoc($borrowResult)) {
                                                echo "<tr>";
                                                echo "<td>" . htmlspecialchars($row['MemberName']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['Email']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['Phone']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['Title']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['Quantity']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['BorrowDate']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['DueDate']) . "</td>";
                                                
                                                // Status Dropdown Form
                                                echo "<td>
                                                    <form method='post' style='display:inline-block;'>
                                                        <input type='hidden' name='borrow_id' value='" . htmlspecialchars($row['BorrowID']) . "'>
                                                        <input type='hidden' name='book_id' value='" . htmlspecialchars($row['BookID']) . "'>
                                                        <select name='status' class='form-control' onchange='this.form.submit()'>
                                                            <option value='Pending' " . ($row['Status'] == 'Pending' ? 'selected' : '') . ">Pending</option>
                                                            <option value='Borrowed' " . ($row['Status'] == 'Borrowed' ? 'selected' : '') . ">Borrowed</option>
                                                            <option value='Returned' " . ($row['Status'] == 'Returned' ? 'selected' : '') . ">Returned</option>
                                                            <option value='Overdue' " . ($row['Status'] == 'Overdue' ? 'selected' : '') . ">Overdue</option>
                                                        </select>
                                                        <input type='hidden' name='update_status' value='1'>
                                                    </form>
                                                </td>";

                                                // Delete Action
                                                echo "<td>
                                                    <a href='manageborrow.php?delete=" . htmlspecialchars($row['BorrowID']) . "' class='btn btn-danger' onclick=\"return confirm('Are you sure you want to delete this borrow record?')\">Delete</a>
                                                </td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='9'>No borrow records found.</td></tr>";
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
