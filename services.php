<?php
session_start();
include('dbconnect.php');  // Database connection

// Fetch all categories
$categoryQuery = "SELECT * FROM category";
$categoryResult = mysqli_query($connect, $categoryQuery);

// Fetch all books with category names
$bookQuery = "SELECT b.Title, b.Author, b.Genre, b.Image, b.Publisher, b.Status, b.AddedDate, b.Stock, c.CategoryName 
              FROM book b 
              LEFT JOIN category c ON b.CategoryID = c.CategoryID";

$bookResult = mysqli_query($connect, $bookQuery);

// Fetch all books with category names
$bookQuery = "SELECT b.BookID, b.Title, b.Author, b.Genre, b.Image, b.Publisher, b.Status, b.AddedDate, b.Stock, c.CategoryName 
              FROM book b 
              LEFT JOIN category c ON b.CategoryID = c.CategoryID";
$bookResult = mysqli_query($connect, $bookQuery);

// Fetch book counts by format
$formatQuery = "SELECT Format, COUNT(*) as count FROM book GROUP BY Format";
$formatResult = mysqli_query($connect, $formatQuery);

if (!$formatResult) {
    die("Format query failed: " . mysqli_error($connect));
}

// Store counts in an associative array
$formatCounts = [];
while ($row = mysqli_fetch_assoc($formatResult)) {
    $formatCounts[$row['Format']] = $row['count'];
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
            <h2>Our Services</h2>
            <span class="underline center"></span>
            <p class="lead">Discover a variety of services tailored to enhance your learning and library experience.</p>
        </div>
        <div class="breadcrumb">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li>Services</li>
            </ul>
        </div>
    </div>
</section>
<!-- End: Page Banner -->

<!-- Start: Services Section -->
<div id="content" class="site-content">
    <div id="primary" class="content-area">
        <main id="main" class="site-main">
            <div class="services-main">
                <div class="services-pg">                            
                    <section class="services-offering">
                        <div class="container">
                            <div class="center-content">
                                <h2 class="section-title">SERVICE WE ARE OFFERING</h2>
                                <span class="underline center"></span>
                                <p class="lead">Explore the services we offer at Libraria.</p>
                                <div class="clearfix"></div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="contact-location">
                                <div class="flipcard">
                                    <div class="front">
                                        <div class="top-info">
                                            <h3><i class="fa fa-book" aria-hidden="true"></i><span>Book Borrowing</span></h3>
                                        </div>
                                        <div class="bottom-info">
                                            <span class="top-arrow"></span>
                                            <p>Browse and borrow books from our extensive collection.</p>
                                            <a href="#">View Selection <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
                                        </div>
                                    </div>
                                    <div class="back">
                                        <div class="bottom-info orange-bg">
                                            <span class="bottom-arrow"></span>
                                            <p>Browse and borrow books from our extensive collection.</p>
                                            <a href="#">View Selection <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
                                        </div>
                                        <div class="top-info dark-bg">
                                            <h3><i class="fa fa-book" aria-hidden="true"></i><span>Book Borrowing</span></h3>
                                        </div>                                                
                                    </div>
                                </div>
                                <div class="flipcard">
                                    <div class="front">
                                        <div class="top-info">
                                            <h3><i class="fa fa-bookmark" aria-hidden="true"></i><span>Study Rooms</span></h3>
                                        </div>
                                        <div class="bottom-info">
                                            <span class="top-arrow"></span>
                                            <p>Reserve our quiet study rooms for a focused learning environment.</p>
                                            <a href="#">View Availability <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
                                        </div>
                                    </div>
                                    <div class="back">
                                        <div class="bottom-info orange-bg">
                                            <span class="bottom-arrow"></span>
                                            <p>Reserve our quiet study rooms for a focused learning environment.</p>
                                            <a href="#">View Availability <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
                                        </div>
                                        <div class="top-info dark-bg">
                                            <h3><i class="fa fa-bookmark" aria-hidden="true"></i><span>Study Rooms</span></h3>
                                        </div>                                                
                                    </div>
                                </div>
                                <div class="flipcard">
                                    <div class="front">
                                        <div class="top-info">
                                            <h3><i class="fa fa-truck" aria-hidden="true"></i><span>Home Delivery</span></h3>
                                        </div>
                                        <div class="bottom-info">
                                            <span class="top-arrow"></span>
                                            <p>Request home delivery of borrowed books for your convenience.</p>
                                            <a href="#">Learn More <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
                                        </div>
                                    </div>
                                    <div class="back">
                                        <div class="bottom-info orange-bg">
                                            <span class="bottom-arrow"></span>
                                            <p>Request home delivery of borrowed books for your convenience.</p>
                                            <a href="#">Learn More <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
                                        </div>
                                        <div class="top-info dark-bg">
                                            <h3><i class="fa fa-truck" aria-hidden="true"></i><span>Home Delivery</span></h3>
                                        </div>                                                
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </main>
    </div>
</div>
<!-- End: Services Section -->

                            <section class="who-we-are">
                                <div class="company-info">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-sm-8 border">
                                                <div class="row">
                                                    <div class="col-sm-11">
                                                        <div class="company-detail">
                                                            <h3 class="section-title">Who we are</h3>
                                                            <span class="underline left"></span>
                                                            <p>Libraria is dedicated to providing an efficient, user-friendly, and modern library management system that enhances the experience for both library staff and users. Our mission is to automate and streamline library operations, making it easier for members to borrow, return, and discover books while maintaining accurate records.</p>
                                                            <p>Our team combines technology and library expertise to create a system that is designed to improve inventory management, reduce human errors, and provide a seamless experience for everyone. From real-time tracking of borrowed items to detailed reports on library resources, Libraria aims to be the ideal solution for modern libraries looking to improve their operations and service to the community.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="company-image"></div>
                                </div>
                            </section>

<div class="fun-stuff">
    <div class="container">
        <div class="facts-counter">
            <ul>
                <li class="col-sm-3">
                    <div class="fact-item icon-ebooks">
                        <div class="fact-icon">
                            <img src="images/icon-ebooks.png" alt="" />
                        </div>
                        <span>eBooks<strong class="fact-counter">
                            <?php echo isset($formatCounts['Ebook']) ? $formatCounts['Ebook'] : 0; ?>
                        </strong></span>
                    </div>
                </li>
                <li class="col-sm-3">
                    <div class="fact-item icon-eaudio">
                        <div class="fact-icon">
                            <img src="images/icon-eaudio.png" alt="" />
                        </div>
                        <span>audioB<strong class="fact-counter">
                            <?php echo isset($formatCounts['Audiobook']) ? $formatCounts['Audiobook'] : 0; ?>
                        </strong></span>
                    </div>
                </li>
                <li class="col-sm-3">
                    <div class="fact-item icon-magazine">
                        <div class="fact-icon">
                            <img src="images/icon-magazine.png" alt="" />
                        </div>
                        <span>Hardcover<strong class="fact-counter">
                            <?php echo isset($formatCounts['Hardcover']) ? $formatCounts['Hardcover'] : 0; ?>
                        </strong></span>
                    </div>
                </li>
                <li class="col-sm-3">
                    <div class="fact-item icon-magazine">
                        <div class="fact-icon">
                            <img src="images/icon-magazine.png" alt="" />
                        </div>
                        <span>Paperback<strong class="fact-counter">
                            <?php echo isset($formatCounts['Paperback']) ? $formatCounts['Paperback'] : 0; ?>
                        </strong></span>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>

                            <section class="company-info-box">
                                <div class="company-info">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-md-10 aligncenter">
                                            <div class="col-md-6 border-dark-left">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="company-detail bg-dark margin-left">
                                                            <h3 class="section-title">Study Rooms</h3>
                                                            <span class="underline left"></span>
                                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur nec mauris a neque tincidunt iaculis. Sed tristique luctus sapien. Vestibulum arcu magna, ullamcorper quis porta ac, venenatis non ante. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Cras commodo sollicitudin felis vel porttitor. Quisque vitae egestas sapien.</p>
                                                            <a href="mailto:support@libraria.com" class="btn btn-primary">Get a card</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 border-dark">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="company-detail bg-light margin-right">
                                                            <h3 class="section-title">Books and more</h3>
                                                            <span class="underline left"></span>
                                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur nec mauris a neque tincidunt iaculis. Sed tristique luctus sapien. Vestibulum arcu magna, ullamcorper quis porta ac, venenatis non ante. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Cras commodo sollicitudin felis vel porttitor. Quisque vitae egestas sapien.</p>
                                                            <a href="mailto:support@libraria.com" class="btn btn-dark-gray">Make a request</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                            <!-- Start: Our Community Section -->
<section class="community-testimonial">
    <div class="container">
        <div class="text-center">
            <h2 class="section-title">Words From Our Library Community</h2>
            <span class="underline center"></span>
            <p class="lead">Our library community shares their experiences with Libraria and the benefits of a modernized library system.</p>
        </div>
        <div class="owl-carousel">
            <div class="single-testimonial-box">
                <div class="top-portion">
                    <img src="images/testimonial-image-01.jpg" alt="Testimonial Image" />
                    <div class="user-comment">
                        <div class="arrow-left"></div>
                        <blockquote cite="#">
                            "Libraria has completely transformed our library experience. The automated system allows for quick access to books and resources, making my visits more efficient and enjoyable."
                        </blockquote>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="bottom-portion">
                    <a href="#" class="author">
                        Adem <small>(Library Member)</small>
                    </a>
                    <div class="social-share-links">
                        <ul>
                            <!-- LinkedIn Link Added -->
                            <li><a href="https://www.linkedin.com/in/adem" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
                            <!-- Facebook Link Added -->
                            <li><a href="https://www.facebook.com/adem" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                            <!-- Twitter Link Added -->
                            <li><a href="https://twitter.com/adem" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                            <!-- Skype Link Added -->
                            <li><a href="skype:adem?chat" target="_blank"><i class="fa fa-skype" aria-hidden="true"></i></a></li>
                            <!-- Google Plus Link Added -->
                            <li><a href="https://plus.google.com/+adem" target="_blank"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
                        </ul>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="single-testimonial-box">
                <div class="top-portion">
                    <img src="images/testimonial-image-02.jpg" alt="Testimonial Image" />
                    <div class="user-comment">
                        <div class="arrow-left"></div>
                        <blockquote cite="#">
                            "The new system makes it so much easier to track books and their availability. It's a huge improvement from the manual system we used before. I can find what I need in no time."
                        </blockquote>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="bottom-portion">
                    <a href="#" class="author">
                        Maria B <small>(Library Member)</small>
                    </a>
                    <div class="social-share-links">
                        <ul>
                            <!-- LinkedIn Link Added -->
                            <li><a href="https://www.linkedin.com/in/mariab" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
                            <!-- Facebook Link Added -->
                            <li><a href="https://www.facebook.com/mariab" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                            <!-- Twitter Link Added -->
                            <li><a href="https://twitter.com/mariab" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                            <!-- Skype Link Added -->
                            <li><a href="skype:mariab?chat" target="_blank"><i class="fa fa-skype" aria-hidden="true"></i></a></li>
                            <!-- Google Plus Link Added -->
                            <li><a href="https://plus.google.com/+mariab" target="_blank"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
                        </ul>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End: Our Community Section -->
                            <section class="category-filter new-release">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-6 col-md-offset-3 text-center">
                                            <h2 class="section-title">Check Out The New Releases</h2>
                                            <span class="underline center"></span>
                                            <p class="lead">The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested.</p>
                                        </div>
                                    </div>
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
                            <img src="images/error-img.png" alt="Default Image" />
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
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <!-- End: Services Section -->
        
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
