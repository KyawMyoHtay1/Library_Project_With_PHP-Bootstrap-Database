<?php 
session_start();
include('dbconnect.php');

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

// Check if the 'delete' parameter is set
if (isset($_GET['delete'])) {
    $staffID = $_GET['delete'];

    // Sanitize $staffID by escaping any special characters to prevent SQL injection
    $staffID = mysqli_real_escape_string($connect, $staffID);

    // Fetch the staff details (Optional: To show staff name or email before deletion)
    $staff_query = "SELECT * FROM staff WHERE StaffID = '$staffID'";  
    $staff_result = mysqli_query($connect, $staff_query);
    $staff_data = mysqli_fetch_assoc($staff_result);

    if ($staff_data) {
        $staff_name = $staff_data['Name'];
        $staff_email = $staff_data['Email'];

        // Show confirmation before deletion
        echo "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='utf-8' />
            <meta name='viewport' content='width=device-width, initial-scale=1.0' />
            <title>Delete Staff - Admin Dashboard</title>
            <link href='assets/css/bootstrap.css' rel='stylesheet' />
            <link href='assets/css/font-awesome.css' rel='stylesheet' />
            <link href='assets/css/custom.css' rel='stylesheet' />
            <link href='css/styles.css' rel='stylesheet' />
        </head>
        <body class='admin_profile'>
            <div id='wrapper'>
                <nav class='navbar navbar-default navbar-cls-top' role='navigation' style='margin-bottom: 0'>
                    <div class='navbar-header'>
                        <a class='navbar-brand' href='admin_profile.php'>$Sname</a>
                    </div>
                    <div style='color: white; padding: 15px 50px 5px 50px; float: right; font-size: 16px;'>
                        <a href='logout.php' class='btn btn-danger square-btn-adjust'>Logout</a>
                    </div>
                </nav>

                <nav class='navbar-default navbar-side' role='navigation'>
                    <div class='sidebar-collapse'>
                        <ul class='nav' id='main-menu'>
                            <li><a href='admin.php'><i class='fa fa-dashboard'></i> Dashboard</a></li>
                            <li><a href='managestaff.php' class='active'><i class='fa fa-users'></i> Staff</a></li>
                        </ul>
                    </div>
                </nav>

                <div id='page-wrapper'>
                    <div id='page-inner'>
                        <div class='row'>
                            <div class='col-md-12'>
                                <h2>Delete Staff</h2>
                                <h5>Are you sure you want to delete the staff '$staff_name'?</h5>
                                <div class='staff-details'>
                                    <p><strong>Name:</strong> $staff_name</p>
                                    <p><strong>Email:</strong> $staff_email</p>
                                </div>
                                <div class='confirm-buttons'>
                                    <a href='deletestaff.php?delete=$staffID' class='btn btn-danger' onclick='return confirm(\"Are you sure you want to delete this staff member?\")'>Delete</a>
                                    <a href='managestaff.php' class='btn btn-secondary'>Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </body>
        </html>";

        // SQL query to delete the staff member after confirmation
        if (isset($_GET['delete'])) {
            $sql = "DELETE FROM staff WHERE StaffID = '$staffID'";  

            // Execute deletion
            if (mysqli_query($connect, $sql)) {
                // Success message and redirect to the list of staff members
                echo "<script>
                        alert('Staff member deleted successfully!');
                        window.location.href='managestaff.php';
                      </script>";
            } else {
                echo "<script>
                        alert('Error occurred during deletion!');
                      </script>";
            }
        }
    } else {
        echo "<script>
                alert('Staff member not found!');
                window.location.href='managestaff.php';
              </script>";
    }
}
?>
