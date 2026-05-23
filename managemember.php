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

    // Handle Member Editing
    if (isset($_POST['btnedit'])) {
        $memberID = $_POST['memberID'];
        $name = $_POST['txtname'];
        $email = $_POST['txtemail'];
        $phone = $_POST['txtphone'];
        $address = $_POST['txtaddress'];

        // Update member details in the database
        $updateQuery = "UPDATE member 
                        SET Name='$name', Email='$email', Phone='$phone', Address='$address' 
                        WHERE MemberID='$memberID'";

        $updateResult = mysqli_query($connect, $updateQuery);

        if ($updateResult) {
            echo "<script>window.alert('Member updated successfully')</script>";
            echo "<script>window.location='managemember.php'</script>";
        } else {
            echo "<script>window.alert('Error occurred while updating the member')</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manage Members - Admin Dashboard</title>
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
                <li><a href="#">Members</a></li>
                <li class="active">Manage Members</li>
            </ol>

            <?php 
            include 'search_member.php'; 
            ?>

            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h2>Manage Members</h2>
                        <h5>Manage member records</h5>
                    </div>
                </div>
                <br>

                <section class="profile-info">
                    <div class="containers">
                        <div class="row">
                            <div class="col-md-12">
                                <h3>Members List</h3>
                                <!-- Add table-responsive class here -->
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
                                                $query = "SELECT * FROM member";
                                                $result = mysqli_query($connect, $query);

                                                while ($row = mysqli_fetch_assoc($result)) {
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
        <!-- /. MAIN CONTENT -->

    <!-- JAVASCRIPT FILES -->
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/custom.js"></script>
</body>
</html>

