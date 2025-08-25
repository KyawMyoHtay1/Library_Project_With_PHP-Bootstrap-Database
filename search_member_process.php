<?php
session_start();
include('dbconnect.php');
include('nav.php');

// Check if the user is logged in
if (!isset($_SESSION['SID'])) {
    echo "<script>window.alert('Please Login')</script>";
    echo "<script>window.location='StaffRegister.php'</script>";
} else {
    $SID = $_SESSION['SID'];
    $Sname = $_SESSION['SName'];
    $SEmail = $_SESSION['SEmail'];

    // Handle Member Deletion
    if (isset($_GET['delete'])) {
        $memberID = $_GET['delete'];

        // Delete member from database
        $deleteQuery = "DELETE FROM member WHERE MemberID='$memberID'";
        $deleteResult = mysqli_query($connect, $deleteQuery);

        if ($deleteResult) {
            echo "<script>window.alert('Member deleted successfully')</script>";
            echo "<script>window.location='managemember.php'</script>";
        } else {
            echo "<script>window.alert('Error occurred while deleting the member')</script>";
        }
    }

    // Perform search if the form is submitted
    if (isset($_GET['submit'])) {
        $search = mysqli_real_escape_string($connect, $_GET['search']); // Prevent SQL injection

        // Search query
        $searchQuery = "SELECT * FROM member WHERE MemberName LIKE '%$search%' OR Email LIKE '%$search%'";
        $searchResult = mysqli_query($connect, $searchQuery);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Search Members - Admin Dashboard</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/custom.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/styles.css?<?php echo time(); ?>">
</head>
<body class="admin_profile">
    <div id="wrapper">
        <div id="page-wrapper">
            <ol class="breadcrumb">
                <li><a href="admin.php">Dashboard</a></li>
                <li><a href="#">Members</a></li>
                <li><a href="managemember.php">Manage Member</a></li>
                <li class="active">Search Members</li>
            </ol>

            <?php include 'search_member.php'; ?>

            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h2>Manage Members</h2>
                        <h5>Manage member records</h5>
                    </div>
                </div>
                <br>

                <section class="profile-info">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <h3>Members List</h3>
                                <!-- Make the table scrollable on smaller screens -->
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>MemberName</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Address</th>
                                                <th>MembershipStatus</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                if (isset($searchResult) && mysqli_num_rows($searchResult) > 0) {
                                                    while ($row = mysqli_fetch_assoc($searchResult)) {
                                                        echo "<tr>";
                                                        echo "<td>" . $row['MemberName'] . "</td>";
                                                        echo "<td>" . $row['Email'] . "</td>";
                                                        echo "<td>" . $row['Phone'] . "</td>";
                                                        echo "<td>" . $row['Address'] . "</td>";
                                                        echo "<td>" . $row['MembershipStatus'] . "</td>";
                                                        echo "<td>
                                                                <a href='editmemberprocess.php?edit=" . $row['MemberID'] . "' class='btn btn-primary'>Edit</a>
                                                                <a href='deletemember.php?delete=" . $row['MemberID'] . "' class='btn btn-danger' onclick='return confirm(\"Are you sure you want to delete this member?\")'>Delete</a>
                                                              </td>";
                                                        echo "</tr>";
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='6'>No members found matching your search</td></tr>";
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
