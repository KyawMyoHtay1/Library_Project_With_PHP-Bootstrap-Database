<?php
session_start();
include('dbconnect.php');  // Database connection

if (!isset($_SESSION['MID'])) {
    echo "<script>window.alert('Please Signin as a Member')</script>";
    echo "<script>window.location='signin.php'</script>";
} else {
    $MID = $_SESSION['MID'];
    $MName = $_SESSION['MName'];
    $MEmail = $_SESSION['MEmail'];
}

// Get the BookID from the URL parameter
$bookID = isset($_GET['bookID']) ? $_GET['bookID'] : '';  // Directly using the bookID as a string since it's VARCHAR


// Fetch book details by BookID
$bookQuery = "SELECT b.BookID, b.Title, b.Author, b.Genre, b.Image, b.Summary, b.Publisher, b.Status, 
                     b.AddedDate, b.Stock, b.ISBN, b.PageCount, b.Language, b.Edition, b.Format, b.Awards, b.PublicationYear, c.CategoryName
              FROM book b 
              LEFT JOIN category c ON b.CategoryID = c.CategoryID
              WHERE b.BookID = ?";

// Use a prepared statement to prevent SQL injection
$stmt = mysqli_prepare($connect, $bookQuery);
mysqli_stmt_bind_param($stmt, "s", $bookID);  // Use "s" for VARCHAR BookID
mysqli_stmt_execute($stmt);
$bookResult = mysqli_stmt_get_result($stmt);
$book = mysqli_fetch_assoc($bookResult);

// If no book is found, redirect to borrow.php
if (!$book) {
    header('Location: borrow.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Meta -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
    
    <!-- Title -->
    <title>..:: LIBRARIA ::..</title>

    <!-- Favicon -->
    <link href="images/favicon.ico" rel="icon" type="image/x-icon" />
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css" />

    <!-- Stylesheet -->
    <link href="style.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="css/homestyles.css?<?php echo time(); ?>">
</head>

<body>
<?php include('header.php'); ?>

<!-- Start: Page Banner -->
<section class="page-banner services-banner">
    <div class="container">
        <div class="banner-header">
            <h2><?php echo $book['Title']; ?></h2>
            <span class="underline center"></span>
            <p class="lead">Discover more about this book and borrow it today!</p>
        </div>
        <div class="breadcrumb">
            <ul>
                <li><a href="index-2.php">Home</a></li>
                <li><a href="borrow.php">Borrow Books</a></li>
                <li><?php echo $book['Title']; ?></li>
            </ul>
        </div>
    </div>
</section>
<!-- End: Page Banner -->

<!-- Start: Book Detail Section -->
<section class="book-detail section-padding">
    <div class="container">
        <div class="book-header">
            <h2><?php echo $book['Title']; ?></h2>
            <span class="underline center"></span>
        </div>

        <div class="row">
            <div class="col-md-4">
                <!-- Book Image -->
                <div class="book-image">
                    <?php if ($book['Image'] != '') { ?>
                        <img src="<?php echo $book['Image']; ?>" alt="<?php echo $book['Title']; ?>" class="img-fluid" />
                    <?php } else { ?>
                        <img src="bookimage/default.jpg" alt="Default Image" class="img-fluid" />
                    <?php } ?>
                </div>
            </div>

            <div class="col-md-8">
                <div class="book-details">
                    <h4><i class="fa fa-user"></i> Author: <?php echo $book['Author']; ?></h4>
                    <p><strong><i class="fa fa-tags"></i> Genre:</strong> <?php echo $book['Genre']; ?></p>
                    <p><strong><i class="fa fa-building"></i> Publisher:</strong> <?php echo $book['Publisher']; ?></p>
                    <p><strong><i class="fa fa-list"></i> Category:</strong> <?php echo $book['CategoryName']; ?></p>
                    <p><strong><i class="fa fa-check-circle"></i> Status:</strong> <?php echo $book['Status']; ?></p>
                    <p><strong><i class="fa fa-cogs"></i> Stock:</strong> <?php echo $book['Stock']; ?> available</p>
                    <p><strong><i class="fa fa-calendar"></i> Added Date:</strong> <?php echo $book['AddedDate']; ?></p>
                    <p><strong><i class="fa fa-barcode"></i> ISBN:</strong> <?php echo $book['ISBN']; ?></p>
                    <p><strong><i class="fa fa-file"></i> Page Count:</strong> <?php echo $book['PageCount']; ?></p>
                    <p><strong><i class="fa fa-language"></i> Language:</strong> <?php echo $book['Language']; ?></p>
                    <p><strong><i class="fa fa-pencil"></i> Edition:</strong> <?php echo $book['Edition']; ?></p>
                    <p><strong><i class="fa fa-th-large"></i> Format:</strong> <?php echo $book['Format']; ?></p>
                    <p><strong><i class="fa fa-trophy"></i> Awards:</strong> <?php echo $book['Awards']; ?></p>
                    <p><strong><i class="fa fa-calendar"></i> Publication Year:</strong> <?php echo $book['PublicationYear']; ?></p>
                    <p><strong><i class="fa fa-align-left"></i> Description:</strong> <?php echo nl2br($book['Summary']); ?></p>
                </div>
            </div>
        </div>

<div class="center-content">
    <form action="borrowcart.php" method="GET">
        <input type="hidden" name="bookID" value="<?php echo $book['BookID']; ?>" />
        <button type="submit" class="btn btn-primary">Add to Borrow Cart</button>
    </form>
</div>


    </div>
</section>
<!-- End: Book Detail Section -->

<?php include('socialnetwork.php'); ?>
<?php include('footer.php'); ?>

<script type="text/javascript" src="js/jquery-1.12.4.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/main.js"></script>

</body>
</html>
