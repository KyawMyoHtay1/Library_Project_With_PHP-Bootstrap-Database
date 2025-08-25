<?php
session_start();
include('dbconnect.php'); // Database connection
include('AutoID_Functions.php'); // Include the AutoID functions

// Ensure the user is logged in
if (!isset($_SESSION['MID'])) {
    echo "<script>window.alert('Please Sign in as a Member');</script>";
    echo "<script>window.location='signin.php';</script>";
    exit();
}

// Initialize memberName as empty string
$memberName = "";

// Get the member ID
$memberID = $_SESSION['MID'];

// Fetch the member's name from the database
$query = "SELECT MemberName FROM member WHERE MemberID = ?";
$stmt = mysqli_prepare($connect, $query);
mysqli_stmt_bind_param($stmt, "s", $memberID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    $memberName = $row['MemberName'];
} else {
    $memberName = "Unknown Member"; // Fallback if no name is found
}

// Fetch the borrow cart from session
$borrowCart = isset($_SESSION['borrow_cart']) ? $_SESSION['borrow_cart'] : [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $borrowDate = date('Y-m-d');
    $dueDate = $_POST['due_date'];
    $totalQuantity = 0;

    // Validate due date
    if (strtotime($dueDate) < strtotime($borrowDate)) {
        echo "<script>alert('Due date cannot be in the past.');</script>";
        exit();
    }

    if (strtotime($dueDate) > strtotime('+30 days', strtotime($borrowDate))) {
        echo "<script>alert('Due date cannot be more than 30 days from today.');</script>";
        exit();
    }

    // Calculate total quantity (sum of all selected quantities)
    foreach ($borrowCart as $bookID => $book) {
        $totalQuantity += $_POST['quantity_' . $bookID];
    }

    // Generate the BorrowID using the AutoID function
    $borrowID = AutoID($connect, "borrow", "BorrowID", "Bw-", 6);

    // Start transaction
    mysqli_begin_transaction($connect);

    try {
        // Insert into borrow table (no Status here)
        $query = "INSERT INTO borrow (BorrowID, MemberID, BorrowDate, DueDate, TotalQuantity) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($connect, $query);
        mysqli_stmt_bind_param($stmt, "ssssi", $borrowID, $memberID, $borrowDate, $dueDate, $totalQuantity);
        mysqli_stmt_execute($stmt);

        // Insert each book into borrowbook table
        if (isset($_POST['bookID'])) {
            foreach ($_POST['bookID'] as $index => $bookID) {
                $quantity = $_POST['quantity_' . $bookID];

                // Check stock before proceeding
                $query = "SELECT Stock FROM book WHERE BookID = ?";
                $stmt = mysqli_prepare($connect, $query);
                mysqli_stmt_bind_param($stmt, "s", $bookID);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $stock);
                mysqli_stmt_fetch($stmt);
                mysqli_stmt_close($stmt);

                if ($quantity > $stock) {
                    throw new Exception("The quantity for book ID $bookID exceeds available stock.");
                }

                // Generate the BorrowBookID using AutoID function
                $borrowBookID = AutoID($connect, "borrowbook", "BorrowBookID", "Bb-", 6);

                // Insert into borrowbook table (add Status as 'pending')
                $query = "INSERT INTO borrowbook (BorrowBookID, BorrowID, BookID, Quantity, Status) VALUES (?, ?, ?, ?, 'Pending')";
                $stmt = mysqli_prepare($connect, $query);
                mysqli_stmt_bind_param($stmt, "sssi", $borrowBookID, $borrowID, $bookID, $quantity);
                mysqli_stmt_execute($stmt);

                // Decrease stock from book table
                $query = "UPDATE book SET Stock = Stock - ? WHERE BookID = ?";
                $stmt = mysqli_prepare($connect, $query);
                mysqli_stmt_bind_param($stmt, "is", $quantity, $bookID);
                mysqli_stmt_execute($stmt);
            }
        }

        // Commit transaction
        mysqli_commit($connect);

        // Clear the cart after successful submission
        unset($_SESSION['borrow_cart']);
        header('Location: borrowconfirm.php?success=true');
        exit();
    } catch (Exception $e) {
        // Rollback transaction on error
        mysqli_rollback($connect);
        echo "<script>alert('Error submitting borrow request: " . $e->getMessage() . "');</script>";
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
    <title>..:: LIBRARIA ::..</title>
    <link href="images/favicon.ico" rel="icon" type="image/x-icon" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i%7CLato:100,100i,300,300i,400,400i,700,700i,900,900i" rel="stylesheet" />
    <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="css/mmenu.css" rel="stylesheet" type="text/css" />
    <link href="css/mmenu.positioning.css" rel="stylesheet" type="text/css" />
    <link href="style.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="css/homestyles.css?<?php echo time(); ?>">
</head>
<body>
    <?php include('header.php'); ?>

    <section class="page-banner services-banner">
        <div class="container">
            <div class="banner-header">
                <h2>Confirm Your Borrow</h2>
                <span class="underline center"></span>
                <p class="lead">Please review and confirm your borrow details</p>
            </div>
            <div class="breadcrumb">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="borrowcartview.php">Borrow Cart</a></li>
                    <li>Borrow Confirmation</li>
                </ul>
            </div>
        </div>
    </section>

    <h3>Welcome, <?php echo htmlspecialchars($memberName); ?>!</h3>

    <section class="borrow-confirm">
    <div class="container">
        <h2>Your Borrow Cart</h2>

        <?php if (empty($borrowCart)) { ?>
            <p>Your borrow cart is empty.</p>
        <?php } else { ?>
            <form id="borrow-form" action="borrowconfirm.php" method="post">
                <!-- Added table-responsive div -->
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Book Title</th>
                                <th>Author</th>
                                <th>Genre</th>
                                <th>Book ID</th>
                                <th>Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($borrowCart as $bookID => $book) { ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($book['Title']); ?></td>
                                    <td><?php echo htmlspecialchars($book['Author']); ?></td>
                                    <td><?php echo htmlspecialchars($book['Genre']); ?></td>
                                    <td><?php echo htmlspecialchars($bookID); ?></td>
                                    <td>
                                        <input type="number" name="quantity_<?php echo $bookID; ?>" value="<?php echo isset($book['Quantity']) ? $book['Quantity'] : 1; ?>" min="1" required />
                                        <input type="hidden" name="bookID[]" value="<?php echo $bookID; ?>" />
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div> <!-- End of table-responsive -->

                <div class="form-group">
                    <label for="due_date">Due Date:</label>
                    <input type="date" id="due_date" name="due_date" required />
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn">Confirm Borrow</button>
                </div>
            </form>
        <?php } ?>
    </div>
</section>


    <?php include('socialnetwork.php'); ?>
    <?php include('footer.php'); ?>

    <!-- jQuery and other scripts -->
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
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAT5k-RhvFSVIuCALkpHhKgQx6SJUd9gpI"></script>
    <script type="text/javascript" src="js/google.map.js"></script>
    <script type="text/javascript" src="js/main.js"></script>

    <script type="text/javascript">
        // Handle form submission with confirmation alert
        document.getElementById('borrow-form').addEventListener('submit', function(event) {
            var confirmBorrow = confirm("Are you sure you want to confirm your borrow?");
            if (!confirmBorrow) {
                event.preventDefault(); // Prevent form submission if the user cancels
            }
        });
    </script>
</body>
</html>