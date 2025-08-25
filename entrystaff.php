<?php
session_start();
include('dbconnect.php');
include('AutoID_Functions.php');
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

if (isset($_POST['btnsubmit'])) {
    $StaffID = AutoID('staff', 'StaffID', 'St-', 6);
    $staffName = $_POST['txtStaffName'];
    $email = $_POST['txtEmail'];
    $password = password_hash($_POST['txtPassword'], PASSWORD_BCRYPT);
    $contactNo = $_POST['txtContactNo'];
    $address = $_POST['txtAddress'];
    $role = $_POST['txtRole'];
    $hireDate = $_POST['hireDate']; // Capture HireDate from form

    // Check for duplicate email
    $checkStaff = "SELECT * FROM staff WHERE Email='$email'";
    $query = mysqli_query($connect, $checkStaff);
    $count = mysqli_num_rows($query);

    if ($count > 0) {
        echo "<script>window.alert('Email already exists!')</script>";
        echo "<script>window.location='entrystaff.php'</script>";
    } else {
        // Insert staff details into the database, including HireDate
        $insert = "INSERT INTO staff (StaffID, StaffName, Email, Password, ContactNo, Address, Role, HireDate) 
                   VALUES ('$StaffID', '$staffName', '$email', '$password', '$contactNo', '$address', '$role', CURRENT_TIMESTAMP)";
        $query = mysqli_query($connect, $insert);

        if ($query) {
            echo "<script>window.alert('Staff Entry Success')</script>";
            echo "<script>window.location='entrystaff.php'</script>";
        } else {
            echo "<script>window.alert('Error occurred, please try again.')</script>";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Entry Staff - Admin Dashboard</title>
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
                <li class="active">Entry Staff</li>
            </ol>

            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h2>Entry Staff</h2>
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
                                <form method="POST" action="entrystaff.php">
                                    <div class="form-group">
                                        <label for="staffID">Staff ID:</label>
                                        <input type="text" name="txtStaffID" value="<?php echo AutoID($connect, 'staff', 'StaffID', 'St-', 6); ?>" readonly class="form-control" />
                                    </div>
                                    <div class="form-group">
                                        <label for="staffName">Staff Name:</label>
                                        <input type="text" class="form-control" id="staffName" name="txtStaffName" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email:</label>
                                        <input type="email" class="form-control" id="email" name="txtEmail" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password:</label>
                                        <input type="password" class="form-control" id="password" name="txtPassword" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="contactNo">Contact No:</label>
                                        <input type="text" class="form-control" id="contactNo" name="txtContactNo" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="address">Address:</label>
                                        <textarea class="form-control" id="address" name="txtAddress" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="role">Role:</label>
                                        <select class="form-control" name="txtRole" required>
                                            <option value="">Select Role</option>
                                            <option value="Admin">Admin</option>
                                            <option value="Staff">Staff</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
    <label for="hireDate">Hire Date:</label>
    <input type="date" class="form-control" id="hireDate" name="hireDate" value="<?php echo date('Y-m-d'); ?>" readonly />
</div>

                                    <button type="submit" class="btn btn-success" name="btnsubmit">Add</button>
                                    <button type="reset" class="btn btn-primary">Clear</button>
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
