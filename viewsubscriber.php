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

    // Delete Subscriber
    if (isset($_GET['delete'])) {
        $subscribeID = $_GET['delete'];

        $deleteQuery = "DELETE FROM newsletter_subscribers WHERE SubscribeID='$subscribeID'";
        $deleteResult = mysqli_query($connect, $deleteQuery);

        if ($deleteResult) {
            echo "<script>window.alert('Subscriber deleted successfully')</script>";
            echo "<script>window.location='viewsubscriber.php'</script>";
        } else {
            echo "<script>window.alert('Error occurred while deleting subscriber')</script>";
        }
    }

    // Fetch all subscribers
    $subscriberQuery = "SELECT * FROM newsletter_subscribers";
    $subscriberResult = mysqli_query($connect, $subscriberQuery);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>View Subscribers - Admin Dashboard</title>
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
                <li><a href="#">Feedback</a></li>
                <li class="active">View Subscribers</li>
            </ol>

            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h2>View Subscribers</h2>
                        <h5>Manage newsletter subscribers</h5>
                    </div>
                </div>
                <br>

                <!-- Subscriber List -->
                <section class="profile-info">
                    <div class="containers">
                        <div class="row">
                            <div class="col-md-12">
                                <h3>Subscriber List</h3>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Subscribe ID</th>
                                            <th>Email</th>
                                            <th>Subscribed At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (mysqli_num_rows($subscriberResult) > 0) {
                                            while ($row = mysqli_fetch_assoc($subscriberResult)) {
                                                echo "<tr>";
                                                echo "<td>" . $row['SubscribeID'] . "</td>";
                                                echo "<td>" . $row['Email'] . "</td>";
                                                echo "<td>" . $row['SubscribedAt'] . "</td>";
                                                echo "<td>
                                                    <a href='viewsubscriber.php?delete=" . $row['SubscribeID'] . "' class='btn btn-danger' onclick='return confirm(\"Are you sure you want to delete this subscriber?\")'>Delete</a>
                                                    </td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='4'>No subscribers found.</td></tr>"; // Updated colspan for 4 columns
                                        }
                                        ?>
                                    </tbody>
                                </table>
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
