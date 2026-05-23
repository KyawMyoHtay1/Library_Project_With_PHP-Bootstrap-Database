<?php
session_start();
include('dbconnect.php');  // Database connection

if (!isset($_SESSION['MID'])) {
    echo "<script>window.alert('Please Signin as a Member')</script>";
    echo "<script>window.location='signin.php'</script>";
    exit();
}

$MID = $_SESSION['MID'];
$MName = $_SESSION['MName'];
$MEmail = $_SESSION['MEmail'];

// Handle search query
$searchQuery = "";
if (isset($_GET['keywords']) || isset($_GET['category'])) {
    $keywords = isset($_GET['keywords']) ? mysqli_real_escape_string($connect, $_GET['keywords']) : '';
    $category = isset($_GET['category']) ? mysqli_real_escape_string($connect, $_GET['category']) : '';

    // Build the search query
    $searchQuery = "WHERE (b.Title LIKE '%$keywords%' OR b.Author LIKE '%$keywords%' OR b.Genre LIKE '%$keywords%')";
    if ($category != 'Choose Category') {
        $searchQuery .= " AND c.CategoryName = '$category'";
    }
}

// Fetch all categories for the filter dropdown
$categoryQuery = "SELECT * FROM category";
$categoryResult = mysqli_query($connect, $categoryQuery);

// Fetch books based on search query
$bookQuery = "SELECT b.BookID, b.Title, b.Author, b.Genre, b.Image, b.Publisher, b.Status, b.AddedDate, b.Stock, c.CategoryName 
              FROM book b 
              LEFT JOIN category c ON b.CategoryID = c.CategoryID 
              $searchQuery";
$bookResult = mysqli_query($connect, $bookQuery);
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
            <h2>Search Books</h2>
            <span class="underline center"></span>
            <p class="lead">Find your next book to borrow.</p>
        </div>
        <div class="breadcrumb">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li>Search Books</li>
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
    <div id="category-filter">
        <ul class="category-list">
            <?php if (mysqli_num_rows($bookResult) > 0) {
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
                <?php }
            } else { ?>
                <li>No books found matching your search.</li>
            <?php } ?>
        </ul>
        <div class="clearfix"></div>
    </div>
</section>
<!-- End: Category Filter -->

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
<script type="text/javascript" src="js/carousel.swipe.min.js"></script>
<script type="text/javascript" src="js/bxslider.min.js"></script>
<script type="text/javascript" src="js/main.js"></script>
</body>
</html>
