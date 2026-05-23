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

    // Delete Feedback
    if (isset($_GET['delete'])) {
        $feedbackID = $_GET['delete'];
        $deleteQuery = "DELETE FROM feedback WHERE FeedbackID='$feedbackID'";
        $deleteResult = mysqli_query($connect, $deleteQuery);

        if ($deleteResult) {
            echo "<script>window.alert('Feedback deleted successfully')</script>";
            echo "<script>window.location='managefeedback.php'</script>";
        } else {
            echo "<script>window.alert('Error occurred while deleting feedback')</script>";
        }
    }

    // Handle status update
    if (isset($_POST['update_status'])) {
        $feedbackID = $_POST['feedback_id'];
        $status = $_POST['status'];
        $statusUpdateQuery = "UPDATE feedback SET Status='$status' WHERE FeedbackID='$feedbackID'";
        if (mysqli_query($connect, $statusUpdateQuery)) {
            // Status updated successfully
            echo "<script>window.alert('Status updated successfully!');</script>";
        } else {
            // Error occurred during update
            echo "<script>window.alert('Error occurred while updating status.');</script>";
        }
    }

    // Fetch all feedback with Member details
    $feedbackQuery = "SELECT f.FeedbackID, m.MemberName, m.Email, m.Phone, f.FeedbackDate, f.Content, f.Status 
                      FROM feedback f 
                      JOIN member m ON f.MemberID = m.MemberID";
    $feedbackResult = mysqli_query($connect, $feedbackQuery);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manage Feedback - Admin Dashboard</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/custom.css" rel="stylesheet" />
    <link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <link rel="stylesheet" href="css/styles.css?<?php echo time(); ?>">
</head>
<body class="admin_profile">
    <div id="wrapper">
        <?php include('nav.php'); ?>
        <div id="page-wrapper">
            <ol class="breadcrumb">
                <li><a href="admin.php">Dashboard</a></li>
                <li><a href="#">Feedback</a></li>
                <li class="active">Manage Feedback</li>
            </ol>

            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h2>Manage Feedback</h2>
                        <h5>View and manage user feedback</h5>
                    </div>
                </div>
                <br>

                <section class="profile-info">
                    <div class="containers">
                        <div class="row">
                            <div class="col-md-12">
                                <h3>Feedback List</h3>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Member Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Feedback Date</th>
                                            <th>Content</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (mysqli_num_rows($feedbackResult) > 0) {
                                            while ($row = mysqli_fetch_assoc($feedbackResult)) {
                                                echo "<tr>";
                                                echo "<td>" . $row['MemberName'] . "</td>";
                                                echo "<td>" . $row['Email'] . "</td>";
                                                echo "<td>" . $row['Phone'] . "</td>";
                                                echo "<td>" . $row['FeedbackDate'] . "</td>";
                                                echo "<td>" . $row['Content'] . "</td>";
                                                
                                                // Status Dropdown Form
                                                echo "<td>
                                                    <form method='post' style='display:inline-block;'>
                                                        <input type='hidden' name='feedback_id' value='" . $row['FeedbackID'] . "'>
                                                        <select name='status' class='form-control' onchange='this.form.submit()'>
                                                            <option value='Pending' " . ($row['Status'] == 'Pending' ? 'selected' : '') . ">Pending</option>
                                                            <option value='Reviewed' " . ($row['Status'] == 'Reviewed' ? 'selected' : '') . ">Reviewed</option>
                                                            <option value='Resolved' " . ($row['Status'] == 'Resolved' ? 'selected' : '') . ">Resolved</option>
                                                        </select>
                                                        <input type='hidden' name='update_status' value='1'>
                                                    </form>
                                                </td>";

                                                // Delete Action
                                                echo "<td>
                                                    <a href='managefeedback.php?delete=" . $row['FeedbackID'] . "' class='btn btn-danger' onclick=\"return confirm('Are you sure you want to delete this feedback?')\">Delete</a>
                                                </td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='7'>No feedback found.</td></tr>";
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
    </div>
    
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/custom.js"></script>
</body>
</html>
