<?php 
session_start();
include('dbconnect.php');
include('AutoID_Functions.php'); // Keep this line

if (!isset($_SESSION['MID'])) {
    echo "<script>window.alert('Please Signin as a Member')</script>";
    echo "<script>window.location='signin.php'</script>";
} else {
    $MID = $_SESSION['MID'];
    $MName = $_SESSION['MName'];
    $MEmail = $_SESSION['MEmail'];

    // Use the AutoID function to generate the FeedbackID
    $FeedbackID = AutoID($connect, 'Feedback', 'FeedbackID', 'Fb-', 6); // Feedback table, FeedbackID field, prefix 'Fb-', and 6 leading zeros

    if (isset($_POST['btnsubmit'])) {
        $message = $_POST['message'];

        $insert = "INSERT INTO Feedback (FeedbackID, MemberID, FeedbackDate, Content, Status) 
                   VALUES ('$FeedbackID', '$MID', NOW(), '$message', 'Pending')";

        $query = mysqli_query($connect, $insert);

        if ($query) {
            echo "<script>window.alert('Feedback Submitted Successfully')</script>";
            echo "<script>window.location='feedback.php'</script>";
        } else {
            echo "<script>window.alert('Error occurred, please try again.')</script>";
        }
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
            <h2>Provide Your Feedback</h2>
            <span class="underline center"></span>
            <p class="lead">We value your thoughts and suggestions to help us improve your experience with Libraria.</p>
        </div>
        <div class="breadcrumb">
            <ul>
                <li><a href="index-2.php">Home</a></li>
                <li>Feedback</li>
            </ul>
        </div>
    </div>
</section>
<!-- End: Page Banner -->

        
<!-- Start: Feedback Section -->
<div id="content" class="site-content">
    <div id="primary" class="content-area">
        <main id="main" class="site-main">
            <div class="contact-main">
                <div class="contact-us">
                    <div class="container">
                        <div class="contact-location">
                            <!-- Flipcards for contact info here -->
                            <!-- Assuming flipcards code here remains intact -->
                        </div>
                        <div class="row">
                            <div class="contact-area">
                                <div class="container">
                                    <div class="col-md-5 col-md-offset-1 border-gray-left">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="contact-map bg-light margin-left">
                                                    <div class="company-map" id="map" style="height: 605px;"></div> <!-- Inline height added -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-5 border-gray-right">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="contact-form bg-light margin-right">
                                                    <h2>Send us a feedback</h2>
                                                    <span class="underline left"></span>
                                                    <div class="contact-fields">
                                                        <form id="contact" name="contact" action="feedback.php" method="post">
                                                            <div class="row">
                                                                <!-- Read-Only Feedback AutoID -->
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <input class="form-control" type="text" name="feedback-id" id="feedback-id" value="<?php echo htmlspecialchars($FeedbackID); ?>" readonly />
                                                                    </div>
                                                                </div>
                                                                <!-- Read-Only Member Name -->
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <input class="form-control" type="text" name="member-name" id="member-name" value="<?php echo htmlspecialchars($MName); ?>" readonly />
                                                                    </div>
                                                                </div>
                                                                <!-- Read-Only Email -->
                                                                <div class="col-md-6 col-sm-6">
                                                                    <div class="form-group">
                                                                        <input class="form-control" type="email" name="email" id="email" value="<?php echo htmlspecialchars($MEmail); ?>" readonly />
                                                                    </div>
                                                                </div>
                                                                <!-- Message -->
                                                                <div class="col-sm-12">
                                                                    <div class="form-group">
                                                                        <textarea class="form-control" placeholder="Your message" name="message" id="message" required></textarea>
                                                                        <div class="clearfix"></div>
                                                                    </div>
                                                                </div>
                                                                <!-- Submit Button -->
                                                                <div class="col-sm-12">
                                                                    <div class="form-group form-submit">
                                                                        <input class="btn btn-default" type="submit" name="btnsubmit" value="Send Message" />
                                                                    </div>
                                                                </div>
                                                                <!-- Success/Error Messages -->
                                                                <div id="success">
                                                                    <span>Your message was sent successfully! Our team will contact you soon.</span>
                                                                </div>
                                                                <div id="error">
                                                                    <span>Something went wrong, try refreshing and submitting the form again.</span>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>                                                                   
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- Closing div for contact-area -->
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<!-- End: Feedback Section -->


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
        
        <!-- Google Map API -->
        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAT5k-RhvFSVIuCALkpHhKgQx6SJUd9gpI"></script>

        <!-- Google Map (Custom Style) -->
        <script type="text/javascript" src="js/google.map.js"></script>

        <!-- Custom Scripts -->
        <script type="text/javascript" src="js/main.js"></script>

    </body>


</html>
