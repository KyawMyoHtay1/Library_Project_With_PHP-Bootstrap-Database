<?php
session_start();
include('dbconnect.php');
include('nav.php');

if (isset($_SESSION['SID'])) {
    $SID = $_SESSION['SID'];
    $Sname = $_SESSION['SName'];
    $SEmail = $_SESSION['SEmail'];
    $Srole=$_SESSION['Srole'];

    // Query the staff details
    $query = "SELECT * FROM staff WHERE StaffID = '$SID'";
    $result = mysqli_query($connect, $query);
    $staff = mysqli_fetch_assoc($result);
} else {
    echo "<script>window.alert('Please Login')</script>";
    echo "<script>window.location='StaffRegister.php'</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Profile - Admin Dashboard</title>
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
                <li class="active"><?php echo $Srole; ?> Profile</li>
            </ol>

            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h2><?php echo $Srole; ?> Profile</h2>
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
                            <form method="POST" action="updateProfile.php">
                                <div class="form-group">
                                    <label for="name">Name:</label>
                                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $Sname; ?>" required />
                                </div>
                                <div class="form-group">
                                    <label for="email">Email:</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?php echo $SEmail; ?>" required />
                                </div>
                                 <div class="form-group">
                                    <label for="contactno">ContactNo:</label>
                                    <input type="text" class="form-control" id="email" name="contactno" value="<?php echo $staff['ContactNo']; ?>" required />
                                </div>
                                 <div class="form-group">
                                    <label for="address">Address:</label>
                                    <input type="text" class="form-control" id="email" name="address" value="<?php echo $staff['Address']; ?>" required />
                                </div>
                                <div class="form-group">
                                    <label for="password">Change Password:</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Leave empty if not changing" />
                                </div>
                                <button type="submit" class="btn btn-success">Update Profile</button>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <!-- /. MAIN CONTENT -->

    <!-- JAVASCRIPT FILES -->
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/custom.js"></script>
</body>
</html>
