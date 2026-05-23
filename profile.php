<?php
session_start();
include('dbconnect.php');

// Check if the user is logged in
if (isset($_SESSION['MID'])) {
    // Get the member ID from the session
    $MID = $_SESSION['MID']; 

    // Query the member details from the database
    $query = "SELECT * FROM Member WHERE MemberID = '$MID'";
    $result = mysqli_query($connect, $query);
    $member = mysqli_fetch_assoc($result);

    // Assign member data to variables
    if ($member) {
        $MName = $member['MemberName'];
        $MEmail = $member['Email'];
        $MPhone = $member['Phone'];
        $MAddress = $member['Address'];
    }

    // Check if form is submitted to update profile
    if (isset($_POST['btnsubmit'])) {
        $MName = $_POST['member-name'];
        $MEmail = $_POST['email'];
        $MPhone = $_POST['phone'];
        $MAddress = $_POST['address'];
        $newPassword = $_POST['password']; // Get the new password

        // If the user entered a new password, hash it before storing
        if (!empty($newPassword)) {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $updateQuery = "UPDATE Member SET MemberName='$MName', Email='$MEmail', Phone='$MPhone', Address='$MAddress', Password='$hashedPassword' WHERE MemberID='$MID'";
        } else {
            // Update without changing the password
            $updateQuery = "UPDATE Member SET MemberName='$MName', Email='$MEmail', Phone='$MPhone', Address='$MAddress' WHERE MemberID='$MID'";
        }

        // Execute the query
        if (mysqli_query($connect, $updateQuery)) {
            echo "<script>window.alert('Profile Updated Successfully');</script>";
            echo "<script>window.location='index.php';</script>";
        } else {
            echo "<script>window.alert('Error updating profile. Please try again.');</script>";
        }
    }
} else {
    // Redirect to login page if the user is not logged in
    echo "<script>window.alert('Please Signin as a member')</script>";
    echo "<script>window.location='signin.php'</script>";
}
?>

<!DOCTYPE html>
<html lang="zxx">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1">
    <title>..:: LIBRARIA ::..</title>
    <link href="images/favicon.ico" rel="icon" type="image/x-icon" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" />
    <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="style.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?php include('header.php'); ?>

<!-- Start: Page Banner -->
<section class="page-banner services-banner">
    <div class="container">
        <div class="banner-header">
            <h2>My Profile</h2>
            <span class="underline center"></span>
            <p class="lead">View and update your profile details.</p>
        </div>
        <div class="breadcrumb">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li>My Profile</li>
            </ul>
        </div>
    </div>
</section>
<!-- End: Page Banner -->
<br>
<!-- Start: Profile Section -->
<div id="content" class="site-content">
    <div id="primary" class="content-area">
        <main id="main" class="site-main">
            <div class="profile-main">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="profile-info">
                                <h3>Profile Information</h3>
                                <form action="profile.php" method="POST">
                                    <div class="form-group">
                                        <label for="member-name">Full Name</label>
                                        <input class="form-control" type="text" name="member-name" id="member-name" value="<?php echo htmlspecialchars($member['MemberName']); ?>" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input class="form-control" type="email" name="email" id="email" value="<?php echo htmlspecialchars($member['Email']); ?>" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="phone">Phone</label>
                                        <input class="form-control" type="text" name="phone" id="phone" value="<?php echo htmlspecialchars($member['Phone']); ?>" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <input class="form-control" type="text" name="address" id="address" value="<?php echo htmlspecialchars($member['Address']); ?>" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Change Password</label>
                                        <input class="form-control" type="password" name="password" id="password" placeholder="Leave empty if not changing" />
                                    </div>
                                    <div class="form-group form-submit">
                                        <input class="btn btn-default" type="submit" name="btnsubmit" value="Update Profile" required />
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <!-- Additional information or a profile picture section can go here -->
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<!-- End: Profile Section -->

<?php include('footer.php'); ?>

<!-- Scripts -->
<script type="text/javascript" src="js/jquery-1.12.4.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/main.js"></script>

</body>
</html>
