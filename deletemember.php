<?php
session_start();
include('dbconnect.php');

// Check if the user is logged in
if (!isset($_SESSION['SID'])) {
    echo "<script>window.alert('Please Login')</script>";
    echo "<script>window.location='StaffRegister.php'</script>";
} else {
    $SID = $_SESSION['SID'];
    $Sname = $_SESSION['SName'];
    $SEmail = $_SESSION['SEmail'];

    // Check if the 'delete' parameter is set
    if (isset($_GET['delete'])) {
        $memberID = $_GET['delete'];

        // Sanitize $memberID by escaping any special characters to prevent SQL injection
        $memberID = mysqli_real_escape_string($connect, $memberID);

        // Fetch the member details (Optional: To show member name or email before deletion)
        $member_query = "SELECT * FROM member WHERE MemberID = '$memberID'";  
        $member_result = mysqli_query($connect, $member_query);
        $member_data = mysqli_fetch_assoc($member_result);

        if($member_data) {
            $member_name = $member_data['Name'];
            $member_email = $member_data['Email'];

            // Show confirmation before deletion
            echo "
            <!DOCTYPE html>
            <html lang='en'>
            <head>
                <meta charset='utf-8' />
                <meta name='viewport' content='width=device-width, initial-scale=1.0' />
                <title>Delete Member - Admin Dashboard</title>
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
                                <li><a href='managemember.php' class='active'><i class='fa fa-users'></i> Members</a></li>
                            </ul>
                        </div>
                    </nav>

                    <div id='page-wrapper'>
                        <div id='page-inner'>
                            <div class='row'>
                                <div class='col-md-12'>
                                    <h2>Delete Member</h2>
                                    <h5>Are you sure you want to delete the member '$member_name'?</h5>
                                    <div class='member-details'>
                                        <p><strong>Name:</strong> $member_name</p>
                                        <p><strong>Email:</strong> $member_email</p>
                                    </div>
                                    <div class='confirm-buttons'>
                                        <a href='deletemember.php?delete=$memberID' class='btn btn-danger' onclick='return confirm(\"Are you sure you want to delete this member?\")'>Delete</a>
                                        <a href='managemember.php' class='btn btn-secondary'>Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </body>
            </html>";

            // SQL query to delete the member after confirmation
            if (isset($_GET['delete'])) {
                $sql = "DELETE FROM member WHERE MemberID = '$memberID'";  

                // Execute deletion
                if (mysqli_query($connect, $sql)) {
                    // Success message and redirect to the list of members
                    echo "<script>
                            alert('Member deleted successfully!');
                            window.location.href='managemember.php';
                          </script>";
                } else {
                    echo "<script>
                            alert('Error occurred during deletion!');
                          </script>";
                }
            }
        } else {
            echo "<script>
                    alert('Member not found!');
                    window.location.href='managemember.php';
                  </script>";
        }

    } else {
        // Only Admin users are allowed to delete
        echo "<script>
                alert('Administration only!');
                window.location.href='admin.php';
              </script>";
    }
}
?>
