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

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize the input
    $email = filter_var(trim($_POST['newsletter']), FILTER_SANITIZE_EMAIL);
    
    // Validate the email
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Insert the email into the newsletter subscription table (create this table if not exists)
        $insertQuery = "INSERT INTO newsletter_subscribers (email) VALUES ('$email')";
        $insertResult = mysqli_query($connect, $insertQuery);

        if ($insertResult) {
            echo "<script>alert('Thank you for subscribing to our newsletter!'); window.location='index.php';</script>";
        } else {
            echo "<script>alert('Error: Could not subscribe. Please try again.'); window.location='index.php';</script>";
        }
    } else {
        // Email is not valid
        echo "<script>alert('Please enter a valid email address.'); window.location='index.php';</script>";
    }
}


?>

<!DOCTYPE html>
<html lang="zxx">

<head>
    <!-- Meta -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">

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
    <!--[if lt IE 9]>
    <script src="js/html5shiv.min.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<?php include('header.php'); ?>

    <!-- Start: Slider Section -->
    <div data-ride="carousel" class="carousel slide" id="home-v1-header-carousel">
        <div class="carousel-inner">
            <div class="item active">
                <figure>
                    <img alt="Home Slide" src="images/header-slider/home-v1/header-slide.jpg" />
                </figure>
                                    <div class="container">
                        <div class="carousel-caption">
                            <h3>Online Learning Anytime, Anywhere!</h3>
                            <h2>Uncover Your Knowledge</h2>
                            <p>Explore a variety of resources, carefully designed to enrich your learning journey with flexible access anytime, anywhere.</p>
                            <div class="slide-buttons hidden-sm hidden-xs">    
                                <a href="services.php" class="btn btn-primary">Our Services</a>
                                <a href="signin.php" class="btn btn-default">Sign in / Register</a>
                            </div>
                    </div>
                </div>
            </div>
                            <div class="item">
                    <figure>
                        <img alt="Home Slide" src="images/header-slider/home-v1/header-slide.jpg" />
                    </figure>
<div class="container">
                    <div class="carousel-caption">
                        <h3>Explore the Library's Collection</h3>
                        <h2>Find Your Next Great Read Today</h2>
                        <p>Browse through a wide selection of books from various genres and uncover your next favorite book!</p>
                        <div class="slide-buttons hidden-sm hidden-xs">
                            <a href="borrow.php" class="btn btn-primary">Borrow Now</a>
                            <a href="feedback.php" class="btn btn-default">Feedback Now</a>
                        </div>
                        </div>
                    </div>
                </div>
        </div>

        <!-- Controls -->
        <a class="left carousel-control" href="#home-v1-header-carousel" data-slide="prev"></a>
        <a class="right carousel-control" href="#home-v1-header-carousel" data-slide="next"></a>
    </div>
    <!-- End: Slider Section -->

<?php include('search_section.php'); ?>
        
        <!-- Start: Welcome Section -->
        <section class="welcome-section">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="welcome-wrap">
<div class="welcome-text">
    <h2 class="section-title">Welcome to Libraria</h2>
    <span class="underline left"></span>
    <p class="lead">Explore a world of knowledge at Libraria</p>
    <p>At Libraria, we believe in the power of books to inspire, educate, and transform lives. Our library offers a vast collection of resources, from timeless classics to the latest releases, to cater to every reader's needs. Whether you're a student, a researcher, or simply a lover of literature, we have something for everyone. Our mission is to provide a space where knowledge is accessible, learning is encouraged, and the joy of reading is celebrated. Join us in our journey to foster a community of passionate readers and lifelong learners.</p>
    <a class="btn btn-primary" href="services.php">Read More</a>
</div>

                        </div>
                    </div>
<div class="col-md-3">
    <div class="facts-counter">
        <ul>
            <li class="bg-light-green">
                <div class="fact-item">
                    <div class="fact-icon">
                        <i class="ebook"></i>
                    </div>
                    <span>eBooks<strong class="fact-counter"><?php echo $formatCounts['Ebook'] ?? 0; ?></strong></span>
                </div>
            </li>
            <li class="bg-green">
                <div class="fact-item">
                    <div class="fact-icon">
                        <i class="eaudio"></i>
                    </div>
                    <span>audioB<strong class="fact-counter"><?php echo $formatCounts['Audiobook'] ?? 0; ?></strong></span>
                </div>
            </li>
            <li class="bg-red">
                <div class="fact-item">
                    <div class="fact-icon">
                        <i class="magazine"></i>
                    </div>
                    <span>Hardcover<strong class="fact-counter"><?php echo $formatCounts['Hardcover'] ?? 0; ?></strong></span>
                </div>
            </li>
            <li class="bg-blue">
                <div class="fact-item">
                    <div class="fact-icon">
                        <i class="magazine"></i>
                    </div>
                    <span>Paperback<strong class="fact-counter"><?php echo $formatCounts['Paperback'] ?? 0; ?></strong></span>
                </div>
            </li>
        </ul>
    </div>
</div>
                </div>
            </div>
            <div class="welcome-image"></div>
        </section>
        <!-- End: Welcome Section -->

<!-- Start: Category Filter -->
<section class="category-filter section-padding">
    <div class="container">
        <div class="center-content">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <h2 class="section-title">Discover Our Latest Releases</h2>
                    <span class="underline center"></span>
                    <p class="lead">Explore our newest additions to the collection. Dive into exciting stories and fresh ideas waiting for you!</p>
                </div>
            </div>
        </div>

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

        
<!-- Start: Newsletter -->
<section class="newsletter section-padding">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="center-content">
                    <h2 class="section-title">Subscribe to our Newsletters</h2>
                    <span class="underline center"></span>
                    <p class="lead">Stay up-to-date with the latest books, events, and news from Libraria by subscribing to our newsletter!</p>
                </div>
                <form method="POST" action="index.php">
                    <div class="form-group">
                        <input class="form-control" placeholder="Enter your Email!" id="newsletter" name="newsletter" type="email" required>
                        <input class="form-control" value="Subscribe" type="submit">
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<!-- End: Newsletter -->


        
<!-- Start: Meet Staff -->
<section class="team section-padding">
    <div class="container">
        <div class="center-content">
            <h2 class="section-title">Meet Our Dedicated Team</h2>
            <span class="underline center"></span>
            <p class="lead">Our team is committed to providing exceptional service and support to ensure the best experience for our users.</p>
        </div>
        <div class="team-list">
            <div class="team-member">
                <figure>
                    <img src="images/team-img-01.jpg" alt="team" />
                </figure>
                <div class="content-block">
                    <div class="member-info">
                        <h4>David J. Seleb</h4>
                        <span class="designation">Executive Director</span>
                        <ul class="social">
                            <li>
                                <a href="https://www.linkedin.com/in/davidjseleb" target="_blank">
                                    <i class="fa fa-linkedin"></i>
                                </a>
                            </li>
                            <li>
                                <a href="https://www.facebook.com/davidjseleb" target="_blank">
                                    <i class="fa fa-facebook-f"></i>
                                </a>
                            </li>
                            <li>
                                <a href="https://twitter.com/davidjseleb" target="_blank">
                                    <i class="fa fa-twitter"></i>
                                </a>
                            </li>
                            <li>
                                <a href="skype:live:davidjseleb?call" target="_blank">
                                    <i class="fa fa-skype"></i>
                                </a>
                            </li>
                        </ul>
                        <p>David brings years of leadership experience to the team, ensuring our organization operates efficiently and effectively.</p>
                        <a class="btn btn-primary" href="services.php">Read More</a>
                    </div>
                </div>
            </div>
            <div class="team-member">
                <figure>
                    <img src="images/team-img-02.jpg" alt="team" />
                </figure>
                <div class="content-block">
                    <div class="member-info">
                        <h4>Robert Simmons</h4>
                        <span class="designation">Deputy Director</span>
                        <ul class="social">
                            <li>
                                <a href="https://www.linkedin.com/in/robertsimmons" target="_blank">
                                    <i class="fa fa-linkedin"></i>
                                </a>
                            </li>
                            <li>
                                <a href="https://www.facebook.com/robertsimmons" target="_blank">
                                    <i class="fa fa-facebook-f"></i>
                                </a>
                            </li>
                            <li>
                                <a href="https://twitter.com/robertsimmons" target="_blank">
                                    <i class="fa fa-twitter"></i>
                                </a>
                            </li>
                            <li>
                                <a href="skype:live:robertsimmons?call" target="_blank">
                                    <i class="fa fa-skype"></i>
                                </a>
                            </li>
                        </ul>
                        <p>With a keen eye for strategy, Robert oversees operations and ensures our goals are met with precision and innovation.</p>
                        <a class="btn btn-primary" href="services.php">Read More</a>
                    </div>
                </div>
            </div>
            <div class="team-member">
                <figure>
                    <img src="images/team-img-03.jpg" alt="team" />
                </figure>
                <div class="content-block">
                    <div class="member-info">
                        <h4>Phyo Ei</h4>
                        <span class="designation">Staff</span>
                        <ul class="social">
                            <li>
                                <a href="https://www.linkedin.com/in/phyoei" target="_blank">
                                    <i class="fa fa-linkedin"></i>
                                </a>
                            </li>
                            <li>
                                <a href="https://www.facebook.com/phyoei" target="_blank">
                                    <i class="fa fa-facebook-f"></i>
                                </a>
                            </li>
                            <li>
                                <a href="https://twitter.com/phyoei" target="_blank">
                                    <i class="fa fa-twitter"></i>
                                </a>
                            </li>
                            <li>
                                <a href="skype:live:phyoei?call" target="_blank">
                                    <i class="fa fa-skype"></i>
                                </a>
                            </li>
                        </ul>
                        <p>Phyo is dedicated to assisting the team and ensuring smooth daily operations with a focus on detail and efficiency.</p>
                        <a class="btn btn-primary" href="services.php">Read More</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End: Meet Staff -->

        
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


        
<!-- Start: News & Event -->
<section class="news-events section-padding banner">
    <div class="container">
        <div class="center-content">
            <h2 class="section-title c-light">Library News & Upcoming Events</h2>
            <span class="underline center"></span>
            <p class="lead c-light">Stay informed about our latest library updates and upcoming events for book lovers and learners!</p>
        </div>
        <div class="news-events-list">
            <div class="single-news-event">
                <figure>
                    <img src="images/news-event/news-event-01.jpg" alt="Library Event" />
                </figure>
                <div class="content-block">
                    <div class="member-info">
                        <div class="content_meta_category">
                            <span class="arrow-right"></span>
                            <span>Event</span>
                        </div>
                        <ul class="news-event-info">
                            <li>
                                <span><i class="fa fa-calendar"></i> April 25, 2025</span>
                            </li>
                            <li>
                                <span><i class="fa fa-clock-o"></i> 10:00 AM - 4:00 PM</span>
                            </li>
                            <li>
                                <span><i class="fa fa-map-marker"></i> Libraria Central Library</span>
                            </li>
                        </ul>
                        <h3>Annual Book Fair</h3>
                        <p>Join us for our Annual Book Fair where you can explore a wide variety of books, meet authors, and participate in fun reading activities for all ages. Don't miss out on exclusive book deals!</p>
                        <a class="btn btn-primary" href="news-events-list-view.php">Read More</a>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="single-news-event">
                <figure>
                    <img src="images/news-event/news-event-02.jpg" alt="Library Event" />
                </figure>
                <div class="content-block">
                    <div class="member-info">
                        <div class="content_meta_category">
                            <span class="arrow-right"></span>
                            <span>Event</span>
                        </div>
                        <ul class="news-event-info">
                            <li>
                                <span><i class="fa fa-calendar"></i> May 5, 2025</span>
                            </li>
                            <li>
                                <span><i class="fa fa-map-marker"></i> Libraria Community Hall</span>
                            </li>
                        </ul>
                        <h3>Storytelling for Kids</h3>
                        <p>Bring your little ones to our Storytelling Event! Enjoy captivating tales and engage in fun activities to nurture their love for reading and imagination.</p>
                        <a class="btn btn-primary" href="news-events-list-view.php">Read More</a>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="single-news-event">
                <figure>
                    <img src="images/news-event/news-event-03.jpg" alt="Library Event" />
                </figure>
                <div class="content-block">
                    <div class="member-info">
                        <div class="content_meta_category">
                            <span class="arrow-right"></span>
                            <span>Event</span>
                        </div>
                        <ul class="news-event-info">
                            <li>
                                <span><i class="fa fa-calendar"></i> June 10, 2025</span>
                            </li>
                            <li>
                                <span><i class="fa fa-map-marker"></i> Libraria Meeting Room</span>
                            </li>
                        </ul>
                        <h3>Book Club Discussion: Modern Literature</h3>
                        <p>Join our monthly book club meeting and participate in a lively discussion about the latest in modern literature. A great way to connect with fellow book enthusiasts!</p>
                        <a class="btn btn-primary" href="news-events-list-view.php">Read More</a>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</section>
<!-- End: News & Event -->


        
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
