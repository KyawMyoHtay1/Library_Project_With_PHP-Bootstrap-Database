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

// Fetch fines for the logged-in member
$query = "SELECT r.ReturnID, bk.Title, r.ReturnDate, rb.BookCondition, rb.Fine, bb.Status 
          FROM ReturnBook rb
          JOIN `Return` r ON rb.ReturnID = r.ReturnID
          JOIN Borrow b ON r.BorrowID = b.BorrowID
          JOIN Book bk ON rb.BookID = bk.BookID
          JOIN BorrowBook bb ON rb.BookID = bb.BookID AND r.BorrowID = bb.BorrowID
          WHERE b.MemberID = '$MID' AND rb.Fine > 0";


$result = mysqli_query($connect, $query);
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
            <h2>My Fines</h2>
            <span class="underline center"></span>
            <p class="lead">View your outstanding library fines.</p>
        </div>
        <div class="breadcrumb">
            <ul>
                <li><a href="index-2.php">Home</a></li>
                <li>My Fines</li>
            </ul>
        </div>
    </div>
</section>
<!-- End: Page Banner -->

<!-- Fines Table -->
<section class="borrowed-books-section">
    <div class="container">
        <div class="table-responsive"> <!-- Added div for scroll functionality -->
            <table class="table">
                <thead>
                    <tr>
                        <th>Return ID</th>
                        <th>Book Title</th>
                        <th>Return Date</th>
                        <th>Book Condition</th>
                        <th>Fine Amount</th>
                        <th>Status</th> <!-- Added Status column -->
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0) { 
                        while ($row = mysqli_fetch_assoc($result)) { ?>
                            <tr>
                                <td><?php echo $row['ReturnID']; ?></td>
                                <td><?php echo $row['Title']; ?></td>
                                <td><?php echo $row['ReturnDate']; ?></td>
                                <td><?php echo $row['BookCondition']; ?></td>
                                <td>$<?php echo number_format($row['Fine'], 2); ?></td>
                                <td><?php echo $row['Status']; ?></td> <!-- Display Status -->
                            </tr>
                    <?php } } else { ?>
                        <tr><td colspan="6">No fines found.</td></tr> <!-- Updated colspan to 6 -->
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</section>


<?php include('socialnetwork.php'); ?>
<?php include('footer.php'); ?>

<!-- Scripts -->
<script type="text/javascript" src="js/jquery-1.12.4.min.js"></script>
<script type="text/javascript" src="js/jquery-ui.min.js"></script>
<script type="text/javascript" src="js/jquery.easing.1.3.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/mmenu.min.js"></script>
<script type="text/javascript" src="js/harvey.min.js"></script>
<script type="text/javascript" src="js/waypoints.min.js"></script>
<script type="text/javascript" src="js/facts.counter.min.js"></script>
<script type="text/javascript" src="js/mixitup.min.js"></script>
<script type="text/javascript" src="js/owl.carousel.min.js"></script>
<script type="text/javascript" src="js/accordion.min.js"></script>
<script type="text/javascript" src="js/responsive.tabs.min.js"></script>
<script type="text/javascript" src="js/responsive.table.min.js"></script>
<script type="text/javascript" src="js/masonry.min.js"></script>
<script type="text/javascript" src="js/bxslider.min.js"></script>
<script type="text/javascript" src="js/google.map.js"></script>
<script type="text/javascript" src="js/main.js"></script>

</body>
</html>