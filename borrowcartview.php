<?php 
session_start();
include('dbconnect.php'); // Database connection

// Ensure the user is logged in
if (!isset($_SESSION['MID'])) {
    echo "<script>window.alert('Please Sign in as a Member')</script>";
    echo "<script>window.location='signin.php'</script>";
    exit();
}

// Fetch borrow cart from session
$borrowCart = isset($_SESSION['borrow_cart']) ? $_SESSION['borrow_cart'] : [];

// Remove a book from the cart if requested
if (isset($_GET['remove'])) {
    $bookID = $_GET['remove'];
    if (isset($borrowCart[$bookID])) {
        unset($borrowCart[$bookID]);
        $_SESSION['borrow_cart'] = $borrowCart;
    }
    header('Location: borrowcartview.php?removed=true');
    exit();
}

if (isset($_POST['update'])) {
    foreach ($_POST['quantity'] as $bookID => $quantity) {
        if (isset($borrowCart[$bookID])) {
            if ($quantity > 0) {
                $borrowCart[$bookID]['Quantity'] = $quantity;
            }
        }
    }
    $_SESSION['borrow_cart'] = $borrowCart;
    header('Location: borrowcartview.php?updated=true');
    exit();
}

// Clear the entire cart if requested
if (isset($_GET['clear'])) {
    unset($_SESSION['borrow_cart']);
    header('Location: borrowcartview.php');
    exit();
}

// Update book quantity in the cart
if (isset($_POST['update'])) {
    foreach ($_POST['quantity'] as $bookID => $quantity) {
        if (isset($borrowCart[$bookID])) {
            // Ensure the quantity is not more than available stock
            if ($quantity > 0) {
                $borrowCart[$bookID]['Quantity'] = $quantity;
            }
        }
    }
    $_SESSION['borrow_cart'] = $borrowCart;
    header('Location: borrowcartview.php');
    exit();
}

// Fetch available stock for each book using MySQLi
$bookStock = [];
if (!empty($borrowCart)) {
    $bookIDs = array_keys($borrowCart);
    $placeholders = implode(',', array_fill(0, count($bookIDs), '?'));
    $query = "SELECT BookID, Stock FROM book WHERE BookID IN ($placeholders)";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, str_repeat('i', count($bookIDs)), ...$bookIDs);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $bookID, $stock);
    while (mysqli_stmt_fetch($stmt)) {
        $bookStock[$bookID] = $stock;
    }
    mysqli_stmt_close($stmt);
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
        <link rel="stylesheet" href="css/homestyles.css?<?php echo time(); ?>">

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="js/html5shiv.min.js"></script>
        <script src="js/respond.min.js"></script>
        <![endif]-->

    </head>


    <body>

<?php include('header.php'); ?>

    <section class="page-banner services-banner">
        <div class="container">
            <div class="banner-header">
                <h2>Your Borrow Cart</h2>
                <span class="underline center"></span>
                <p class="lead">Review your selected books before proceeding.</p>
            </div>
                    <div class="breadcrumb">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="borrow.php">Borrow</a></li>
                <li>Borrow Cart</li>
            </ul>
        </div>
        </div>
    </section>

<section class="cart-details">
    <div class="container">
        <h2>Your Borrow Cart</h2>

        <?php if (empty($borrowCart)) { ?>
            <p>Your borrow cart is empty.</p>
        <?php } else { ?>
            <form action="borrowcartview.php" method="POST">
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Book Title</th>
                                <th>Author</th>
                                <th>Genre</th>
                                <th>Quantity</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($borrowCart as $bookID => $book) { ?>
                                <tr data-book-id="<?php echo $bookID; ?>">
                                    <td><?php echo isset($book['Title']) ? htmlspecialchars($book['Title']) : 'Unknown Title'; ?></td>
                                    <td><?php echo isset($book['Author']) ? htmlspecialchars($book['Author']) : 'Unknown Author'; ?></td>
                                    <td><?php echo isset($book['Genre']) ? htmlspecialchars($book['Genre']) : 'Unknown Genre'; ?></td>
                                    <td>
                                        <input type="number" name="quantity[<?php echo $bookID; ?>]" value="<?php echo isset($book['Quantity']) ? $book['Quantity'] : 1; ?>" min="1" max="<?php echo $bookStock[$bookID]; ?>" />
                                    </td>
                                    <td>
                                        <a href="borrowcartview.php?remove=<?php echo $bookID; ?>" class="btn">Remove</a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

<p><strong>Note:</strong> To update the quantity of a book in your cart, make sure to click "Update Quantity" before proceeding to the "Proceed to Borrow" button.</p>


                <div class="cart-actions">
                    <a href="borrow.php" class="btn">Add More Books</a>
                    <button type="submit" name="update" class="btn">Update Quantity</button>
                    <a href="borrowconfirm.php" class="btn">Proceed to Borrow</a>
                    <a href="borrowcartview.php?clear=true" class="btn">Clear Cart</a>
                </div>

            </form>
        <?php } ?>
    </div>
</section>


<?php if (isset($_GET['removed']) && $_GET['removed'] == 'true') { ?>
    <script>
        alert("The book has been removed from your borrow cart.");
    </script>
<?php } ?>

<?php if (isset($_GET['updated']) && $_GET['updated'] == 'true') { ?>
    <script>
        alert("Cart updated successfully!");
    </script>
<?php } ?>

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

<script>
    document.querySelector("form").onsubmit = function(event) {
        var quantities = document.querySelectorAll("input[name^='quantity']");
        var isValid = true;

        quantities.forEach(function(input) {
            var bookID = input.name.match(/\d+/)[0]; // Get bookID from the input name
            var quantity = parseInt(input.value);
            var stock = <?php echo json_encode($bookStock); ?>[bookID];

            if (quantity > stock) {
                isValid = false;
                alert("The quantity for the book '" + document.querySelector("tr[data-book-id='" + bookID + "'] td:first-child").textContent + "' exceeds the available stock (" + stock + ").");
            }
        });

        // Prevent form submission if any quantity exceeds stock
        if (!isValid) {
            event.preventDefault();
        }
    };
</script>

    </body>


</html>
