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
}  // Closing brace added here 

// Fetch all categories
$categoryQuery = "SELECT * FROM category";
$categoryResult = mysqli_query($connect, $categoryQuery);

if (!$categoryResult) {
    die("Category query failed: " . mysqli_error($connect));
}

// Fetch all books with category names
$bookQuery = "SELECT b.BookID, b.Title, b.Author, b.Genre, b.Image, b.Publisher, b.Status, b.AddedDate, b.Stock, c.CategoryName 
              FROM book b 
              LEFT JOIN category c ON b.CategoryID = c.CategoryID";
$bookResult = mysqli_query($connect, $bookQuery);

if (!$bookResult) {
    die("Book query failed: " . mysqli_error($connect));
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

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="js/html5shiv.min.js"></script>
        <script src="js/respond.min.js"></script>
        <![endif]-->

    </head>


    <body>

<?php include('header.php'); ?>

<!-- Start: Page Banner -->
<section class="page-banner services-banner">
    <div class="container">
        <div class="banner-header">
            <h2>Borrow Books</h2>
            <span class="underline center"></span>
            <p class="lead">Explore our collection and borrow books easily with your membership.</p>
        </div>
        <div class="breadcrumb">
            <ul>
                <li><a href="index-2.php">Home</a></li>
                <li>Borrow Books</li>
            </ul>
        </div>
    </div>
</section>
<!-- End: Page Banner -->
<!-- Start: Search Section -->
<section class="search-filters">
    <div class="container">
        <div class="filter-box">
            <h3>What are you looking for at the library?</h3>
            <form action="search_borrowbook.php" method="get">
                <div class="col-md-5 col-sm-6">
                    <div class="form-group">
                        <label class="sr-only" for="keywords">Search by Keyword</label>
                        <input class="form-control" placeholder="Search by Keyword" id="keywords" name="keywords" type="text" value="<?php echo isset($_GET['keywords']) ? htmlspecialchars($_GET['keywords']) : ''; ?>">
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="form-group">
                        <select name="category" id="category" class="form-control">
                            <option>Choose Category</option>
                            <?php while ($row = mysqli_fetch_assoc($categoryResult)) { ?>
                                <option value="<?php echo $row['CategoryName']; ?>" <?php echo (isset($_GET['category']) && $_GET['category'] == $row['CategoryName']) ? 'selected' : ''; ?>>
                                    <?php echo $row['CategoryName']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </form>
        </div>
    </div>
</section>
<!-- End: Search Section -->
<!-- Start: Category Filter -->
<section class="category-filter section-padding">
    <!-- Dynamic Category Buttons -->
    <div class="filter-buttons">
        <div class="filter btn" data-filter="all">All Releases</div>
        <?php 
        // Reset the pointer of the result set to the beginning
        mysqli_data_seek($categoryResult, 0);
        while ($category = mysqli_fetch_assoc($categoryResult)) { ?>
            <div class="filter btn" data-filter=".<?php echo strtolower(str_replace(' ', '-', $category['CategoryName'])); ?>">
                <?php echo $category['CategoryName']; ?>
            </div>
        <?php } ?>
    </div>

    <!-- Dynamic Book Listings -->
    <div id="category-filter">
        <ul class="category-list">
            <?php 
            // Reset the pointer of the result set to the beginning
            mysqli_data_seek($bookResult, 0);
            while ($book = mysqli_fetch_assoc($bookResult)) { ?>
                <li class="category-item <?php echo strtolower(str_replace(' ', '-', $book['CategoryName'])); ?>">
                    <figure>
                        <!-- Book Image -->
                        <?php if ($book['Image'] != '') { ?>
                            <img src="<?php echo $book['Image']; ?>" alt="<?php echo $book['Title']; ?>" />
                        <?php } else { ?>
                            <img src="bookimage/default.jpg" alt="Default Image" />
                        <?php } ?>
                        <figcaption class="bg-orange">
                            <div class="info-block">
                                <h4><?php echo $book['Title']; ?></h4>
                                <span class="author"><strong>Author:</strong> <?php echo $book['Author']; ?></span>
                                <span class="genre"><strong>Genre:</strong> <?php echo $book['Genre']; ?></span>
                                <span class="publisher"><strong>Publisher:</strong> <?php echo $book['Publisher']; ?></span>
                                <div class="rating">
                                    <?php for ($i = 0; $i < 5; $i++) { ?>
                                        <span>☆</span>
                                    <?php } ?>
                                </div>
                                <a href="book-details.php?bookID=<?php echo $book['BookID']; ?>">Read More <i class="fa fa-long-arrow-right"></i></a>
                            </div>
                        </figcaption>
                    </figure>
                </li>
            <?php } ?>
        </ul>
    </div>
    <div class="clearfix"></div>
</section>
<!-- End: Category Filter -->

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
        
        <!-- Carousel Swipe -->
        <script type="text/javascript" src="js/carousel.swipe.min.js"></script>
        
        <!-- bxSlider -->
        <script type="text/javascript" src="js/bxslider.min.js"></script>
        
        <!-- Custom Scripts -->
        <script type="text/javascript" src="js/main.js"></script>
        
    </body>


</html>