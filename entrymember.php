<?php 
session_start();
include('dbconnect.php');
include('AutoID_Functions.php');
include('nav.php');

if (!isset($_SESSION['SID'])) {
    echo "<script>window.alert('Please Login')</script>";
    echo "<script>window.location='StaffRegister.php'</script>";
} else {

    $SID = $_SESSION['SID'];
    $Sname = $_SESSION['SName'];
    $SEmail = $_SESSION['SEmail'];

    if (isset($_POST['btnsubmit'])) {
        $MemberID = $_POST['txtMemberID'];
        $memberName = $_POST['txtMemberName'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
        $phone = $_POST['txtPhone'];
        $address = $_POST['txtAddress'];
        $membershipStatus = $_POST['membershipStatus'];
        $joinDate = date('Y-m-d'); // Set the join date when the form is submitted

        // Check for duplicate member name or email
        $checkMember = "SELECT * FROM member WHERE MemberName='$memberName' OR Email='$email'";
        $query = mysqli_query($connect, $checkMember);
        $count = mysqli_num_rows($query);

        if ($count > 0) {
            echo "<script>window.alert('Duplicate Member Name or Email')</script>";
            echo "<script>window.location='entrymember.php'</script>";
        } else {
            // Insert member details into the database with hashed password
            $insert = "INSERT INTO member(MemberID, MemberName, Email, Password, Phone, Address, MembershipStatus, JoinDate) 
                       VALUES ('$MemberID', '$memberName', '$email', '$password', '$phone', '$address', '$membershipStatus', CURRENT_TIMESTAMP)";
            $query = mysqli_query($connect, $insert);

            if ($query) {
                echo "<script>window.alert('Member Entry Success')</script>";
                echo "<script>window.location='entrymember.php'</script>";
            } else {
                echo "<script>window.alert('Error occurred, please try again.')</script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Entry Member - Admin Dashboard</title>
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
                <li><a href="#">Members</a></li>
                <li class="active">Entry Member</li>
            </ol>

            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h2>Entry Member</h2>
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
                                <form method="POST" action="entrymember.php">
                                    <div class="form-group">
                                        <label for="memberID">MemberID:</label>
                                        <input type="text" name="txtMemberID" value="<?php echo AutoID($connect, "member", "MemberID", "Me-", 6); ?>" readonly class="form-control" />
                                    </div>
                                    <div class="form-group">
                                        <label for="memberName">Member Name:</label>
                                        <input type="text" class="form-control" id="memberName" name="txtMemberName" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email:</label>
                                        <input type="email" class="form-control" id="email" name="email" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password:</label>
                                        <input type="password" class="form-control" id="password" name="password" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="phone">Phone:</label>
                                        <input type="text" class="form-control" id="phone" name="txtPhone" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="address">Address:</label>
                                        <textarea class="form-control" id="address" name="txtAddress" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="membershipStatus">Membership Status:</label>
                                        <select class="form-control" name="membershipStatus" required>
                                            <option value="Active">Active</option>
                                            <option value="Inactive">Inactive</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="joinDate">Join Date:</label>
                                        <input type="text" name="joinDate" value="<?php echo date('Y-m-d'); ?>" readonly class="form-control" />
                                    </div>

                                    <button type="submit" class="btn btn-success" name="btnsubmit">Add Member</button>
                                    <button type="reset" class="btn btn-primary">Cancel</button>
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
