<?php
include("dbconnect.php");
session_start();

if (!isset($_SESSION['MID'])) {
    echo "<script>window.alert('Please Signin as a Member')</script>";
    echo "<script>window.location='signin.php'</script>";
} else {
    $MID = $_SESSION['MID'];
    $MName = $_SESSION['MName'];
    $MEmail = $_SESSION['MEmail'];
} 

// Fetch all borrowed books for the logged-in member
$query = "SELECT b.BorrowID, bk.Title, bb.Quantity, b.BorrowDate, b.DueDate, bb.Status
          FROM Borrow b
          JOIN BorrowBook bb ON b.BorrowID = bb.BorrowID
          JOIN Book bk ON bb.BookID = bk.BookID
          WHERE b.MemberID = '$MID'";

$result = mysqli_query($connect, $query);

if (isset($_GET['borrow_id'])) {
    $borrowID = $_GET['borrow_id'];

    // Update borrow status to 'Return Requested' in the BorrowBook table
    $updateQuery = "UPDATE BorrowBook SET Status = 'Return Requested' WHERE BorrowID = '$borrowID'";
    if (mysqli_query($connect, $updateQuery)) {
        // Display success message as a JavaScript alert
        echo "<script>alert('Return request sent successfully for Borrow ID: $borrowID');</script>";
    } else {
        // Display error message as a JavaScript alert
        echo "<script>alert('Error: Could not send the return request.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="zxx">
<head>
    <!-- Meta -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1">

    <!-- Title -->
    <title>..:: LIBRARIA ::..</title>

    <!-- Favicon -->
    <link href="images/favicon.ico" rel="icon" type="image/x-icon" />

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i%7CLato:100,100i,300,300i,400,400i,700,700i,900,900i" rel="stylesheet" />
    <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css" />

    <!-- Mobile Menu -->
    <link href="css/mmenu.css" rel="stylesheet" type="text/css" />
    <link href="css/mmenu.positioning.css" rel="stylesheet" type="text/css" />

    <!-- Stylesheet -->
    <link href="style.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="css/homestyles.css?<?php echo time(); ?>">

</head>

<body>

<?php include('header.php'); ?>

<!-- Start: Page Banner -->
<section class="page-banner services-banner">
    <div class="container">
        <div class="banner-header">
            <h2>My Borrowed Books</h2>
            <span class="underline center"></span>
            <p class="lead">View your borrowed books and request returns.</p>
        </div>
        <div class="breadcrumb">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li>My Borrowed Books</li>
            </ul>
        </div>
    </div>
</section>
<!-- End: Page Banner -->

<!-- Borrowed Books Table -->
<section class="borrowed-books-section">
    <div class="container">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Borrow ID</th>
                        <th>Book Title</th>
                        <th>Quantity</th>
                        <th>Borrow Date</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?php echo $row['BorrowID']; ?></td>
                            <td><?php echo $row['Title']; ?></td>
                            <td><?php echo $row['Quantity']; ?></td>
                            <td><?php echo $row['BorrowDate']; ?></td>
                            <td><?php echo $row['DueDate']; ?></td>
                            <td><?php echo $row['Status']; ?></td>
                            <td>
                                <a href="?borrow_id=<?php echo $row['BorrowID']; ?>" class="btn btn-primary">Request Return</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</section>


<?php include('socialnetwork.php'); ?>

<?php include('footer.php'); ?>

<!-- jQuery Latest Version 1.x -->
<script type="text/javascript" src="js/jquery-1.12.4.min.js"></script>

<!-- jQuery UI -->
<script type="text/javascript" src="js/jquery-ui.min.js"></script>

<!-- jQuery Easing -->
<script type="text/javascript" src="js/jquery.easing.1.3.js"></script>

<!-- Bootstrap -->
<script type="text/javascript" src="js/bootstrap.min.js"></script>

<!-- Mobile Menu -->
<script type="text/javascript" src="js/mmenu.min.js"></script>

<!-- Harvey - State manager for media queries -->
<script type="text/javascript" src="js/harvey.min.js"></script>

<!-- Waypoints - Load Elements on View -->
<script type="text/javascript" src="js/waypoints.min.js"></script>

<!-- Facts Counter -->
<script type="text/javascript" src="js/facts.counter.min.js"></script>

<!-- MixItUp - Category Filter -->
<script type="text/javascript" src="js/mixitup.min.js"></script>

<!-- Owl Carousel -->
<script type="text/javascript" src="js/owl.carousel.min.js"></script>

<!-- Accordion -->
<script type="text/javascript" src="js/accordion.min.js"></script>

<!-- Responsive Tabs -->
<script type="text/javascript" src="js/responsive.tabs.min.js"></script>

<!-- Responsive Table -->
<script type="text/javascript" src="js/responsive.table.min.js"></script>

<!-- Masonry -->
<script type="text/javascript" src="js/masonry.min.js"></script>

<!-- bxSlider -->
<script type="text/javascript" src="js/bxslider.min.js"></script>

<!-- Google Map API -->
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAT5k-RhvFSVIuCALkpHhKgQx6SJUd9gpI"></script>

<!-- Google Map (Custom Style) -->
<script type="text/javascript" src="js/google.map.js"></script>

<!-- Custom Scripts -->
<script type="text/javascript" src="js/main.js"></script>

</body>
</html>
