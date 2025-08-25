<?php 
session_start();
include('dbconnect.php');
include('nav.php');

//xxxxxxxxxxxxxxxxxxx
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
    echo "<script>window.alert('You do not have permission to access this page.')</script>";
    echo "<script>window.location='admin.php'</script>"; // Redirect to a page for non-admin users
    exit();
}

// Handle Staff Editing
if (isset($_POST['btnedit'])) {
    $staffID = $_POST['staffID'];
    $name = $_POST['txtname'];
    $email = $_POST['txtemail'];
    $contactno = $_POST['txtcontactno'];
    $address = $_POST['txtaddress'];
    $role = $_POST['txtrole'];
    $password = $_POST['txtpassword'];

    // If password is provided, hash it before saving
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        // Update query for staff details including password
        $updateQuery = "UPDATE staff 
                        SET StaffName='$name', Email='$email', ContactNo='$contactno', Address='$address', Role='$role', Password='$hashedPassword' 
                        WHERE StaffID='$staffID'";
    } else {
        // Update query without changing the password
        $updateQuery = "UPDATE staff 
                        SET StaffName='$name', Email='$email', ContactNo='$contactno', Address='$address', Role='$role' 
                        WHERE StaffID='$staffID'";
    }

    // Execute the update query
    $updateResult = mysqli_query($connect, $updateQuery);

    if ($updateResult) {
        echo "<script>window.alert('Staff updated successfully')</script>";
        echo "<script>window.location='managestaff.php'</script>"; // Redirect to manage staff page
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
    <title>Edit Staff - Admin Dashboard</title>
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
                <li><a href="managestaff.php">Manage Staff</a></li>
                <li class="active">Edit Staff</li>
            </ol>

            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h2>Edit Staff</h2>
                        <h5>Edit staff details below</h5>
                    </div>
                </div>
                <br>

                <section class="profile-info">
                    <div class="containers">
                        <div class="row">
                            <div class="col-md-12">
                                <?php
                                    if (isset($_GET['edit'])) {
                                        $staffID = $_GET['edit'];
                                        $query = "SELECT * FROM staff WHERE StaffID='$staffID'";
                                        $result = mysqli_query($connect, $query);
                                        $row = mysqli_fetch_assoc($result);
                                    }
                                ?>

                                <form action="editstaffprocess.php" method="POST">
                                    <input type="hidden" name="staffID" value="<?php echo $row['StaffID']; ?>" />
                                    <div class="form-group">
                                        <label for="txtname">Name</label>
                                        <input type="text" class="form-control" name="txtname" id="txtname" value="<?php echo $row['StaffName']; ?>" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="txtemail">Email</label>
                                        <input type="email" class="form-control" name="txtemail" id="txtemail" value="<?php echo $row['Email']; ?>" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="txtcontactno">Phone</label>
                                        <input type="text" class="form-control" name="txtcontactno" id="txtcontactno" value="<?php echo $row['ContactNo']; ?>" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="txtaddress">Address</label>
                                        <textarea class="form-control" name="txtaddress" id="txtaddress" required><?php echo $row['Address']; ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="txtrole">Role</label>
                                        <input type="text" class="form-control" name="txtrole" id="txtrole" value="<?php echo $row['Role']; ?>" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="txtpassword">Password (Leave empty if not changing)</label>
                                        <input type="password" class="form-control" name="txtpassword" id="txtpassword" />
                                    </div>
                                    <button type="submit" name="btnedit" class="btn btn-primary">Update Staff</button>
                                </form>
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
