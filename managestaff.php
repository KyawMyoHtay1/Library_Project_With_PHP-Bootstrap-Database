<?php
session_start();
include('dbconnect.php');
include('nav.php');

// Ensure the user is logged in
if (!isset($_SESSION['SID'])) {
    echo "<script>window.alert('Please Login')</script>";
    echo "<script>window.location='StaffRegister.php'</script>";
    exit();
}

$SID = $_SESSION['SID'];
$Sname = $_SESSION['SName'];
$SEmail = $_SESSION['SEmail'];

// Query to fetch staff details and role securely
$query = "SELECT * FROM staff WHERE StaffID = ?";
$stmt = $connect->prepare($query);
$stmt->bind_param("s", $SID);
$stmt->execute();
$staffResult = $stmt->get_result();
$staff = $staffResult->fetch_assoc();

// Check if the role is Admin
if ($staff['Role'] != 'Admin') {
    echo "<script>window.alert('You do not have permission to access this page. It can only be accessed by Admin.')</script>";
    echo "<script>window.location='admin.php'</script>"; // Redirect to a page for non-admin users
    exit();
}

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

// Handle Staff Editing
if (isset($_POST['btnedit'])) {
    $staffID = $_POST['staffID'];
    $name = $_POST['txtname'];
    $email = $_POST['txtemail'];
    $contactNo = $_POST['txtcontactno'];
    $address = $_POST['txtaddress'];
    $hireDate = $_POST['txthiredate'];
    $role = $_POST['txtrole'];

    // Update staff details in the database
    $updateQuery = "UPDATE staff 
                    SET StaffName='$name', Email='$email', ContactNo='$contactNo', Address='$address', HireDate='$hireDate', Role='$role' 
                    WHERE StaffID='$staffID'";

    $updateResult = mysqli_query($connect, $updateQuery);

    if ($updateResult) {
        echo "<script>window.alert('Staff updated successfully')</script>";
        echo "<script>window.location='managestaff.php'</script>";
    } else {
        echo "<script>window.alert('Error occurred while updating the staff')</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manage Staff - Admin Dashboard</title>
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

        <!-- MAIN CONTENT -->
        <div id="page-wrapper">
            <!-- BREADCRUMBS -->
            <ol class="breadcrumb">
                <li><a href="admin.php">Dashboard</a></li>
                <li><a href="#">Staff</a></li>
                <li class="active">Manage Staff</li>
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
                            <div class="col-md-12">
                                <h3>Staff List</h3>
                                <!-- Make the table responsive -->
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Staff Name</th>
                                                <th>Email</th>
                                                <th>Contact No</th>
                                                <th>Address</th>
                                                <th>Hire Date</th>
                                                <th>Role</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $query = "SELECT * FROM staff";
                                                $result = mysqli_query($connect, $query);

                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    echo "<tr>";
                                                    echo "<td>" . $row['StaffName'] . "</td>";
                                                    echo "<td>" . $row['Email'] . "</td>";
                                                    echo "<td>" . $row['ContactNo'] . "</td>";
                                                    echo "<td>" . $row['Address'] . "</td>";
                                                    echo "<td>" . $row['HireDate'] . "</td>";
                                                    echo "<td>" . $row['Role'] . "</td>";
                                                    echo "<td>
                                                            <a href='editstaffprocess.php?edit=" . $row['StaffID'] . "' class='btn btn-primary'>Edit</a>
                                                            <a href='deletestaff.php?delete=" . $row['StaffID'] . "' class='btn btn-danger' onclick='return confirm(\"Are you sure you want to delete this staff?\")'>Delete</a>
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
