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

    // Handle Member Editing
    if (isset($_POST['btnedit'])) {
        $memberID = $_POST['memberID'];
        $name = $_POST['txtname'];
        $email = $_POST['txtemail'];
        $phone = $_POST['txtphone'];
        $address = $_POST['txtaddress'];
        $membershipStatus = $_POST['txtmembershipStatus'];

        // Update member details in the database
        $updateQuery = "UPDATE member 
                        SET MemberName='$name', Email='$email', Phone='$phone', Address='$address', MembershipStatus='$membershipStatus' 
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
    <title>Edit Member - Admin Dashboard</title>
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
                <li><a href="managemember.php">Manage Member</a></li>
                <li class="active">Edit Member</li>
            </ol>

            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h2>Edit Member</h2>
                        <h5>Edit member details below</h5>
                    </div>
                </div>
                <br>

                <section class="profile-info">
                    <div class="containers">
                        <div class="row">
                            <div class="col-md-12">
                                <?php
                                    if (isset($_GET['edit'])) {
                                        $memberID = $_GET['edit'];
                                        $query = "SELECT * FROM member WHERE MemberID='$memberID'";
                                        $result = mysqli_query($connect, $query);
                                        $row = mysqli_fetch_assoc($result);
                                    }
                                ?>

                                <form action="editmemberprocess.php" method="POST">
                                    <input type="hidden" name="memberID" value="<?php echo $row['MemberID']; ?>" />
                                    <div class="form-group">
                                        <label for="txtname">Name</label>
                                        <input type="text" class="form-control" name="txtname" id="txtname" value="<?php echo $row['MemberName']; ?>" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="txtemail">Email</label>
                                        <input type="email" class="form-control" name="txtemail" id="txtemail" value="<?php echo $row['Email']; ?>" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="txtphone">Phone</label>
                                        <input type="text" class="form-control" name="txtphone" id="txtphone" value="<?php echo $row['Phone']; ?>" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="txtaddress">Address</label>
                                        <textarea class="form-control" name="txtaddress" id="txtaddress" required><?php echo $row['Address']; ?></textarea>
                                    </div>
                            <div class="form-group">
                                <label for="membershipStatus">Membership Status:</label>
                            <select class="form-control" name="txtmembershipStatus" id="txtmembershipStatus" required>
                                <option value="Active" <?php if($row['MembershipStatus'] == 'Active') echo 'selected'; ?>>Active</option>
                                <option value="Inactive" <?php if($row['MembershipStatus'] == 'Inactive') echo 'selected'; ?>>Inactive</option>
                                </select>
                                            </div>

                                    <button type="submit" name="btnedit" class="btn btn-primary">Update Member</button>
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
