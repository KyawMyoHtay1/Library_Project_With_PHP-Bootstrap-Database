<?php
session_start();
include('dbconnect.php');

// Ensure the user is logged in
if (!isset($_SESSION['SID'])) {
    echo "<script>window.alert('Please Login')</script>";
    echo "<script>window.location='StaffRegister.php'</script>";
} else {
    $SID = $_SESSION['SID'];
    $Sname = $_SESSION['SName'];
    $SEmail = $_SESSION['SEmail'];

    // Handle Staff Deletion
    if (isset($_GET['delete'])) {
        $staffID = $_GET['delete'];

        // Delete staff from database
        $deleteQuery = "DELETE FROM staff WHERE StaffID='$staffID'";
        $deleteResult = mysqli_query($connect, $deleteQuery);

        if ($deleteResult) {
            echo "<script>window.alert('Staff deleted successfully')</script>";
            echo "<script>window.location='managestaff.php'</script>";
        } else {
            echo "<script>window.alert('Error occurred while deleting the staff')</script>";
        }
    }

    // Perform search if the form is submitted
    if (isset($_GET['submit'])) {
        $search = mysqli_real_escape_string($connect, $_GET['search']); // Prevent SQL injection

        // Search query
        $searchQuery = "SELECT * FROM staff WHERE StaffName LIKE '%$search%' OR Email LIKE '%$search%'";
        $searchResult = mysqli_query($connect, $searchQuery);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Search Staff - Admin Dashboard</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/custom.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/styles.css?<?php echo time(); ?>">
    <style>
        /* Ensures the table is scrollable on small screens */
        .table-responsive {
            overflow-x: auto;
        }
        /* Adds some margin between buttons */
        .btn {
            margin: 5px 0;
        }
    </style>
</head>
<body class="admin_profile">
    <div id="wrapper">
        <?php include('nav.php'); ?>
        <div id="page-wrapper">
            <ol class="breadcrumb">
                <li><a href="admin.php">Dashboard</a></li>
                <li><a href="#">Staff</a></li>
                <li><a href="managestaff.php">Manage Staff</a></li>
                <li class="active">Search Staff</li>
            </ol>

            <?php include 'search_staff.php'; ?>

            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h2>Manage Staff</h2>
                        <h5>Manage staff records</h5>
                    </div>
                </div>
                <br>

                <section class="profile-info">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <h3>Staff List</h3>
                                <!-- Make the table scrollable on smaller screens -->
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Staff Name</th>
                                                <th>Email</th>
                                                <th>Contact No</th>
                                                <th>Address</th>
                                                <th>Role</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                if (isset($searchResult) && mysqli_num_rows($searchResult) > 0) {
                                                    while ($row = mysqli_fetch_assoc($searchResult)) {
                                                        echo "<tr>";
                                                        echo "<td>" . $row['StaffName'] . "</td>";
                                                        echo "<td>" . $row['Email'] . "</td>";
                                                        echo "<td>" . $row['ContactNo'] . "</td>";
                                                        echo "<td>" . $row['Address'] . "</td>";
                                                        echo "<td>" . $row['Role'] . "</td>";
                                                        echo "<td>
                                                                <a href='editstaffprocess.php?edit=" . $row['StaffID'] . "' class='btn btn-primary'>Edit</a>
                                                                <a href='managestaff.php?delete=" . $row['StaffID'] . "' class='btn btn-danger' onclick='return confirm(\"Are you sure you want to delete this staff?\")'>Delete</a>
                                                              </td>";
                                                        echo "</tr>";
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='6'>No staff found matching your search</td></tr>";
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

