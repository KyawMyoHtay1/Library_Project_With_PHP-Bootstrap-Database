<?php
session_start();
include('dbconnect.php');

if (isset($_SESSION['SID'])) 
{
    $SID=$_SESSION['SID'];
    $Sname=$_SESSION['SName'];
    $SEmail=$_SESSION['SEmail'];
    $Srole=$_SESSION['Srole'];

    $query = "SELECT * FROM staff WHERE StaffID = '$SID'";
    $result = mysqli_query($connect, $query);
    $staff = mysqli_fetch_assoc($result);

    $bookQuery = "SELECT COUNT(*) AS total_books FROM book";  // Assuming 'books' is your table name
    $bookResult = mysqli_query($connect, $bookQuery);
    $bookData = mysqli_fetch_assoc($bookResult);
    $totalBooks = $bookData['total_books'];  // Get the total count of books

    // Query to get the total number of users
    $queryUsers = "SELECT COUNT(*) AS total_users FROM member";  // Replace with your actual users table name
    $resultUsers = mysqli_query($connect, $queryUsers);
    $rowUsers = mysqli_fetch_assoc($resultUsers);
    $totalMembers = $rowUsers['total_users'];  // Total number of users

    // Query to get the total number of borrowed books
$borrowedQuery = "SELECT COUNT(*) AS borrowed_books FROM borrowbook WHERE status = 'Borrowed'";
$borrowedResult = mysqli_query($connect, $borrowedQuery);
$borrowedData = mysqli_fetch_assoc($borrowedResult);
$borrowedBooks = $borrowedData['borrowed_books'];

}
else if (!isset($_SESSION['SID']))
{
    echo "<script>window.alert('Please Login')</script>";
    echo "<script>window.location='StaffRegister.php'</script>";
}
?>
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard</title>
    <!-- BOOTSTRAP STYLES-->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONTAWESOME STYLES-->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLES-->
    <link href="assets/css/custom.css" rel="stylesheet" />
    <!-- GOOGLE FONTS-->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <link rel="stylesheet" href="css/styles.css?<?php echo time(); ?>">
    <!-- CHARTJS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="admin">
    <div id="wrapper">
        <?php include('nav.php'); ?>

        <!-- MAIN CONTENT -->
        <div id="page-wrapper">

            <!-- BREADCRUMBS -->
            <ol class="breadcrumb">
                <li><a href="admin.php">Home</a></li>
                <li><a href="admin_profile.php"><?php echo $Srole; ?> Profile</a></li>
                <li class="active">Dashboard</li>
            </ol>

            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h2>Dashboard</h2>
                        <h5>Welcome, <?php echo $Sname; ?>, Love to see you back.</h5>
                    </div>
                </div>
                <br>

                <!-- PROFILE DETAILS -->
                <section class="profile-details">
                    <div class="card">
                        <h3>Staff Information</h3>
                        <p><strong>Name:</strong> <?php echo $Sname; ?></p>
                        <p><strong>Email:</strong> <?php echo $SEmail; ?></p>
                    </div>
                </section>

                <!-- DASHBOARD STATS -->
<section class="dashboard-cards">
    <div class="card">
        <h3>Total Books</h3>
        <p><?php echo $totalBooks; ?></p>  <!-- This will display the actual total number of books from the database -->
    </div>
    <div class="card">
        <h3>Total Members</h3>
        <p><?php echo $totalMembers; ?></p>
    </div>
    <div class="card">
        <h3>Borrowed Books</h3>
        <p><?php echo $borrowedBooks; ?></p>
    </div>
</section>
                <!-- CHARTS -->
                <section class="charts">
                    <div class="row">
                        <div class="col-md-6">
                            <canvas id="bookChart"></canvas>
                        </div>
                        <div class="col-md-6">
                            <canvas id="memberChart"></canvas>
                        </div>
                    </div>
                <!-- NOTIFICATIONS -->
                <section class="notification">
                    <h4>Latest Notifications</h4>
                    <p>New members have signed up for the library.</p>
                    <p>New book purchases were made last week.</p>
                </section>

                <!-- RECENT ACTIVITIES -->
                <section class="recent-activities">
                    <h3>Recent Activities</h3>
                    <ul>
                        <li><strong>Book Purchase:</strong> A total of 10 new books were added today.</li>
                        <li><strong>Member Registration:</strong> 5 new members joined the library today.</li>
                        <li><strong>Borrowed Book:</strong> Member John Doe Borrowed "Book Title."</li>
                    </ul>
                </section>
                
            </div>
        </div>
        <!-- /. MAIN CONTENT -->
    </div>

    <!-- MODAL FOR VIEWING DETAILS -->
    <div class="modal" id="detailsModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Book Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Book details go here.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT FILES -->
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/custom.js"></script>
    <script>
        // Book Chart (Bar Chart)
        var ctx = document.getElementById('bookChart').getContext('2d');
        var bookChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Books in Library', 'Borrowed Books'],
                datasets: [{
                    label: 'Books',
                    data: [<?php echo $totalBooks; ?>, <?php echo $borrowedBooks; ?>],  // Use dynamic data for borrowed books
                    backgroundColor: ['#007bff', '#f04f30'],
                    borderColor: ['#007bff', '#28a745'],
                    borderWidth: 1
                }]
            }
        });

        // Member Chart (Pie Chart)
        var ctx2 = document.getElementById('memberChart').getContext('2d');
        var memberChart = new Chart(ctx2, {
            type: 'pie',
            data: {
                labels: ['Active Members', 'Inactive Members'],
                datasets: [{
                    label: 'Members',
                    data: [<?php echo $totalMembers; ?>, 100],  // Replace 100 with actual data
                    backgroundColor: ['#ffc107', '#6c757d'],
                    borderColor: ['#ffc107', '#6c757d'],
                    borderWidth: 1
                }]
            }
        });
    </script>
</body>
</html>
