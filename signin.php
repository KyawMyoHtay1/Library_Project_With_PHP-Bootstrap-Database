<?php
session_start();
include('dbconnect.php');
include('AutoID_Functions.php');

// Define the correct secret code (set to 260904)
$correctSecretCode = "260904"; 

// Handle Registration
if (isset($_POST['btnsubmit'])) {
    $MID = $_POST['txtMID'];
    $Mname = $_POST['txtMname'];
    $Memail = $_POST['Memail'];
    $Mpass = $_POST['Mpass'];
    $Mph = $_POST['Mph'];
    $Maddress = $_POST['Maddress'];
    $secretCode = $_POST['secretCode'];

    // Check if entered secret code is correct
    if ($secretCode !== $correctSecretCode) {
        echo "<script>window.alert('Invalid bar code. You cannot register.')</script>";
        echo "<script>window.location='StaffRegister.php'</script>";
        exit();
    }

    $checkquery = "SELECT * FROM member WHERE Email='$Memail'";
    $runquery = mysqli_query($connect, $checkquery);
    $row = mysqli_num_rows($runquery);

    if ($row > 0) {
        echo "<script>window.alert('Email already exists')</script>";
        echo "<script>window.location='StaffRegister.php'</script>";
    } else {
        // Hash password before storing it
        $hashedPassword = password_hash($Mpass, PASSWORD_BCRYPT);

        // Insert with hashed password
        $insert = "INSERT INTO member(MemberID, MemberName, Email, Password, Phone, Address)
                   VALUES('$MID', '$Mname', '$Memail', '$hashedPassword', '$Mph', '$Maddress')";

        $insertrun = mysqli_query($connect, $insert);

        if ($insertrun) {
            echo "<script>window.alert('Register Success')</script>";
            echo "<script>window.location='signin.php'</script>";
        } else {
            echo "<script>window.alert('Error during registration')</script>";
        }
    }
}

// Handle Login
if (isset($_POST['btnlogin'])) {
    $email = $_POST['txtemail'];
    $password = $_POST['txtpass'];

    // Initialize login attempt count
    if (!isset($_SESSION['error'])) {
        $_SESSION['error'] = 0;
    }

    // Retrieve stored password hash
    $checkaccount = "SELECT * FROM member WHERE Email='$email'";
    $checkaccountquery = mysqli_query($connect, $checkaccount);

    if (!$checkaccountquery) {
        die("Query Failed: " . mysqli_error($connect));
    }

    $memberdatarow = mysqli_num_rows($checkaccountquery);

    if ($memberdatarow > 0) {
        $array = mysqli_fetch_array($checkaccountquery);
        $storedHash = $array['Password'];
        $MID = $array['MemberID'];
        $MName = $array['MemberName'];
        $MEmail = $array['Email'];

        // Verify password
        if (password_verify($password, $storedHash)) {
            $_SESSION['MID'] = $MID;
            $_SESSION['MName'] = $MName;
            $_SESSION['MEmail'] = $MEmail;
            
            $_SESSION['error'] = 0; // Reset failed attempts on success

            echo "<script>window.alert('Member Login Success')</script>";
            echo "<script>window.location='index.php'</script>";
            exit();
        }
    }

    // Increase error count on failure
    $_SESSION['error']++;

    // Show error messages based on attempt number
    if ($_SESSION['error'] == 1) {
        echo "<script>window.alert('Login Failed! Attempt 1')</script>";
    } elseif ($_SESSION['error'] == 2) {
        echo "<script>window.alert('Login Failed! Attempt 2')</script>";
    } elseif ($_SESSION['error'] >= 3) {
        echo "<script>window.alert('Login Failed! Attempt 3. You are locked out.')</script>";
        echo "<script>window.location='loginTimer2.php'</script>";
        exit();
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
                    <h2>Signin</h2>
                    <span class="underline center"></span>
                    <p class="lead">Join our team by completing the form below.</p>
                </div>
                <div class="breadcrumb">
                    <ul>
                        <li><a href="StaffRegister.php">Staff</a></li>
                        <li>Member</li>
                    </ul>
                </div>
            </div>
        </section>
        <!-- End: Page Banner -->
        <!-- Start: Cart Section -->
        <div id="content" class="site-content">
    <div id="primary" class="content-area">
        <main id="main" class="site-main">
            <div class="signin-main">
                <div class="container">
                    <div class="woocommerce">
                        <div class="woocommerce-login">
                            <div class="company-info signin-register">
                                <!-- Sign In Section -->
                                <div class="col-md-5 col-md-offset-1 border-dark-left">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="company-detail bg-dark margin-left" style="min-height: 550px;"> <!-- Adjusted height -->
                                                <div class="signin-head">
                                                    <h2>Sign In as Member</h2>
                                                    <span class="underline left"></span>
                                                </div>
                                                <form action="signin.php" method="POST">
                                                    <p class="form-row form-row-first input-required">
                                                        <label>
                                                            <span class="first-letter">Email</span>
                                                            <span class="second-letter">*</span>
                                                        </label>
                                                        <input type="email" id="email" name="txtemail" class="input-text" required>
                                                    </p>
                                                    <p class="form-row form-row-last input-required">
                                                        <label>
                                                            <span class="first-letter">Password</span>
                                                            <span class="second-letter">*</span>
                                                        </label>
                                                        <input type="password" id="memberPassword" name="txtpass" class="input-text" required>
                                                    </p>
                                                    <div class="clear"></div>
                                                    <div class="password-form-row">
                                                        <p class="form-row input-checkbox">
                                                            <input type="checkbox" value="forever" id="rememberMe" name="rememberMe">
                                                            <label class="inline" for="rememberMe">Remember me</label>
                                                        </p>
                                                        <p class="lost_password">
                                                            <a href="#">Forgot your password?</a>
                                                        </p>
                                                    </div>
                                                    <div style="height: 450px;"></div> <!-- Added spacing -->
                                                    <input type="submit" value="Login" name="btnlogin" class="button btn btn-default">
                                                    <div class="clear"></div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Register Section -->
                                <div class="col-md-5 border-dark new-user">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="company-detail new-account bg-light margin-right" style="min-height: 550px;">
                                                <div class="new-user-head">
                                                    <h2>Register as Member</h2>
                                                    <span class="underline left"></span>
                                                    <p>If no barcode has been assigned for your account, please contact the library.</p>
                                                </div>
<form action="signin.php" method="POST" onsubmit="return validatePassword(event)">
    <p class="form-row form-row-first input-required">
        <input type="text" name="txtMID" value="<?php echo AutoID($connect, "member","MemberID","Me-",6); ?>" readonly/>
    </p>
    <p class="form-row form-row-first input-required">
        <label>
            <span class="first-letter">Barcode</span>
            <span class="second-letter">**</span>
        </label>
        <input type="text" id="barcode" name="secretCode" class="input-text" required>
    </p>
    <p class="form-row form-row-first input-required">
        <label>
            <span class="first-letter">Full Name</span>
            <span class="second-letter">*</span>
        </label>
        <input type="text" id="memberFullName" name="txtMname" class="input-text" required>
    </p>
    <p class="form-row form-row-first input-required">
        <label>
            <span class="first-letter">Email</span>
            <span class="second-letter">*</span>
        </label>
        <input type="email" id="memberEmail" name="Memail" class="input-text" required>
    </p>
    <p class="form-row input-required">
        <label>
            <span class="first-letter">Password</span>
            <span class="second-letter">*</span>
        </label>
        <input type="password" id="memberRegPassword" name="Mpass" class="input-text" required>
    </p>
    <p class="form-row input-required">
        <label>
            <span class="first-letter">Confirm Password</span>
            <span class="second-letter">*</span>
        </label>
        <input type="password" id="confirmPassword" name="confirmPassword" class="input-text" required>
    </p>
    <p class="form-row form-row-first input-required">
        <label>
            <span class="first-letter">Phone</span>
            <span class="second-letter">*</span>
        </label>
        <input type="text" id="memberPhone" name="Mph" class="input-text" required>
    </p>
    <p class="form-row form-row-last input-required">
        <textarea id="memberAddress" name="Maddress" class="input-text" placeholder="Address" required></textarea>
    </p>
    <div class="clear"></div>
    <input type="submit" value="Register" name="btnsubmit" class="button btn btn-default">
    <div class="clear"></div>
</form>

<script>
    // Function to validate password and confirm password
    function validatePassword(event) {
        var password = document.getElementById("memberRegPassword").value;
        var confirmPassword = document.getElementById("confirmPassword").value;

        if (password.length < 5) {
            alert("Password must be at least 5 characters long.");
            event.preventDefault(); // Prevent form submission
            return false; // Prevent further execution
        }

        if (password !== confirmPassword) {
            alert("Passwords do not match!");
            event.preventDefault(); // Prevent form submission
            return false; // Prevent further execution
        }

        return true; // Allow form submission
    }
</script>




                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
        <!-- End: Cart Section -->
        
        <!-- Start: Social Network -->
        <section class="social-network section-padding">
            <div class="container">
                <div class="center-content">
                    <h2 class="section-title">Follow Us</h2>
                    <span class="underline center"></span>
                    <p class="lead">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                </div>
                <ul>
                    <li>
                        <a class="facebook" href="#" target="_blank">
                            <span>
                                <i class="fa fa-facebook-f"></i>
                            </span>
                        </a>
                    </li>
                    <li>
                        <a class="twitter" href="#" target="_blank">
                            <span>
                                <i class="fa fa-twitter"></i>
                            </span>
                        </a>
                    </li>
                    <li>
                        <a class="google" href="#" target="_blank">
                            <span>
                                <i class="fa fa-google-plus"></i>
                            </span>
                        </a>
                    </li>
                    <li>
                        <a class="rss" href="#" target="_blank">
                            <span>
                                <i class="fa fa-rss"></i>
                            </span>
                        </a>
                    </li>
                    <li>
                        <a class="linkedin" href="#" target="_blank">
                            <span>
                                <i class="fa fa-linkedin"></i>
                            </span>
                        </a>
                    </li>
                    <li>
                        <a class="youtube" href="#" target="_blank">
                            <span>
                                <i class="fa fa-youtube"></i>
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </section>
        <!-- End: Social Network -->
        
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
