<?php
session_start();
include('dbconnect.php');
include('AutoID_Functions.php');
include('nav.php'); // Include the navigation bar

if (!isset($_SESSION['SID'])) {
    echo "<script>window.alert('Please Login')</script>";
    echo "<script>window.location='StaffRegister.php'</script>";
    exit(); // Stop further execution
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['return'])) {
    $borrowID = $_POST['borrowID'];
    $returnID = AutoID($connect, "`return`", "ReturnID", "Rt-", 6);
 // Generate Return ID
    $returnDate = date('Y-m-d'); // Current date

    // Insert into Return table
$insertReturn = "INSERT INTO `return` (ReturnID, BorrowID, ReturnDate, TotalFine, Status)
                 VALUES ('$returnID', '$borrowID', '$returnDate', 0, 'Processing')";
mysqli_query($connect, $insertReturn);


    // Fetch borrowed books
    $borrowedBooks = mysqli_query($connect, "SELECT * FROM BorrowBook WHERE BorrowID='$borrowID'");

    $totalFine = 0; // Initialize total fine
    $isDamaged = false; // Flag to check if any book is damaged
    $isOverdue = false; // Flag to check if any book is overdue

    while ($row = mysqli_fetch_assoc($borrowedBooks)) {
        $bookID = $row['BookID'];
        $borrowedQuantity = $row['Quantity'];

        // Calculate fine for late returns
        $borrowDetails = mysqli_fetch_assoc(mysqli_query($connect, "SELECT DueDate FROM Borrow WHERE BorrowID='$borrowID'"));
        $dueDate = $borrowDetails['DueDate'];
        $daysLate = max(0, (strtotime($returnDate) - strtotime($dueDate)) / (60 * 60 * 24)); // Late days
        $finePerDay = 1; // Fine per day (adjust as needed)
        $fine = $daysLate * $finePerDay * $borrowedQuantity;


        // Get book condition from form input
        $bookCondition = $_POST["condition_$bookID"];
        $bookFine = isset($_POST["fine_$bookID"]) ? floatval($_POST["fine_$bookID"]) : 0;


        // Extra fine for damaged books
        if ($bookCondition == "Damaged") {
            $fine += 5; // Extra fine for damaged books
            $bookConditionStatus = 'Damaged'; // Assign Damaged status for damaged books
            $isDamaged = true; // Set flag to true if any book is damaged
        } else {
            $bookConditionStatus = 'Good'; // Assuming Good if not Damaged
        }

        // Add user-input fine
        if ($bookFine != '') {
            $fine += $bookFine; // Add the fine entered by the user for each book
        }

        // Insert into ReturnBook
        $returnBookID = AutoID($connect, "ReturnBook", "ReturnBookID", "Rb-", 6); // Generate ReturnBook ID
        $insertReturnBook = "INSERT INTO ReturnBook (ReturnBookID, ReturnID, BookID, BookCondition, Fine, Quantity)
                             VALUES ('$returnBookID', '$returnID', '$bookID', '$bookConditionStatus', '$fine', '$borrowedQuantity')";
        mysqli_query($connect, $insertReturnBook);

        // Update book stock
if ($bookConditionStatus == 'Good') {
    $updateStock = "UPDATE Book SET Stock = Stock + $borrowedQuantity WHERE BookID='$bookID'";
    mysqli_query($connect, $updateStock);
}


        // Add to the total fine
        $totalFine += $fine;

        // Update Status in BorrowBook table based on overdue and condition
if ($daysLate > 0) {
    $updateBorrowBookStatus = "UPDATE BorrowBook SET Status='Overdue' WHERE BorrowID='$borrowID' AND BookID='$bookID'";
    $isOverdue = true;
} else {
    $updateBorrowBookStatus = "UPDATE BorrowBook SET Status='Returned' WHERE BorrowID='$borrowID' AND BookID='$bookID'";
}

        mysqli_query($connect, $updateBorrowBookStatus);
    }

    // Update total fine in Return table
 $updateFine = "UPDATE `return` SET TotalFine = '$totalFine' WHERE ReturnID='$returnID'";
mysqli_query($connect, $updateFine);


    // Update Status in return based on book condition and overdue status
    if ($isDamaged) {
        $updateReturnStatus = "UPDATE `return` SET Status='Damaged' WHERE ReturnID='$returnID'";
    } elseif ($isOverdue) {
        $updateReturnStatus = "UPDATE `return` SET Status='Overdue' WHERE ReturnID='$returnID'";
    } else {
        $updateReturnStatus = "UPDATE `return` SET Status='Returned' WHERE ReturnID='$returnID'";
    }
    mysqli_query($connect, $updateReturnStatus);

    echo "<script>alert('Return Process Completed Successfully!');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Return - Library Management System</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/custom.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/styles.css?<?php echo time(); ?>">
</head>
<body class="admin_profile">
    <div id="wrapper">
        <!-- Page wrapper -->
        <div id="page-wrapper">
            <ol class="breadcrumb">
                <li><a href="admin.php">Dashboard</a></li>
                <li><a href="#">Book Return</a></li>
                <li class="active">Return Book</li>
            </ol>

            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h2>Book Return</h2>
                        <h5>Welcome, <?php echo $_SESSION['SName']; ?>!</h5>
                    </div>
                </div>
                <br>

                <form method="POST" action="entryreturn.php">
    <div class="form-group">
        <label for="borrowID">Enter Borrow ID:</label>
        <select name="borrowID" class="form-control" required>
            <?php
            // Keep the selected value
            $selectedBorrowID = isset($_POST['borrowID']) ? $_POST['borrowID'] : '';

            // Fetch BorrowIDs with status 'Borrowed' in BorrowBook table
            $result = mysqli_query($connect, 
                "SELECT DISTINCT b.BorrowID, m.MemberName 
                 FROM Borrow b 
                 JOIN Member m ON b.MemberID = m.MemberID 
                 JOIN BorrowBook bb ON b.BorrowID = bb.BorrowID 
                 WHERE bb.Status='Borrowed'");

            while ($row = mysqli_fetch_assoc($result)) {
                $borrowID = $row['BorrowID'];
                $memberName = $row['MemberName'];
                // Check if this option should be selected
                $selected = ($borrowID == $selectedBorrowID) ? 'selected' : '';
                echo "<option value='$borrowID' $selected>$borrowID - $memberName</option>";
            }
            ?>
        </select>
    </div>

    <!-- Here is your Search button -->
    <button type="submit" name="fetch" class="btn btn-primary mt-2">Search Borrowed Books</button>
</form>


                <?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['fetch'])) {
    $borrowID = $_POST['borrowID'];
    $returnDate = date('Y-m-d'); // Today's date (ReturnDate)

    // Fetch the DueDate from the Borrow table
    $borrowDetails = mysqli_fetch_assoc(mysqli_query($connect, "SELECT DueDate FROM Borrow WHERE BorrowID='$borrowID'"));
    $dueDate = $borrowDetails['DueDate'];

// Calculate overdue days as integer (no decimals)
$overdueDays = max(0, floor((strtotime($returnDate) - strtotime($dueDate)) / (60 * 60 * 24)));
$overdueFine = $overdueDays > 0 ? $overdueDays * 1 : 0; // Adjust fine rate if needed

echo '<form method="POST" action="entryreturn.php">';
echo "<input type='hidden' name='borrowID' value='$borrowID'>";

// Display Due Date (Read-Only)
echo "<label for='dueDate'>Due Date:</label>";
echo "<input type='text' name='dueDate' class='form-control' value='$dueDate' readonly><br>";

// Display Return Date (Read-Only)
echo "<label for='returnDate'>Return Date:</label>";
echo "<input type='text' name='returnDate' class='form-control' value='$returnDate' readonly><br>";

// Overdue Warning
if ($overdueDays > 0) {
    echo "<p style='color: red; font-weight: bold;'>Overdue by $overdueDays days! Fine: $$overdueFine</p>";
    echo "<input type='hidden' name='overdueFine' value='$overdueFine'>";
} else {
    echo "<p style='color: green;'>Returned on time.</p>";
}

echo '<h3>Book Condition:</h3>';


    // Fetch Borrowed Books
    $books = mysqli_query($connect, "SELECT b.BookID, b.Title FROM BorrowBook bb 
                                      JOIN Book b ON bb.BookID = b.BookID 
                                      WHERE bb.BorrowID = '$borrowID' AND bb.Status='Borrowed'");

    while ($row = mysqli_fetch_assoc($books)) {
        echo "<p>{$row['Title']} (ID: {$row['BookID']})</p>";
        echo "<select name='condition_{$row['BookID']}' class='form-control'>
                <option value='Good'>Good</option>
                <option value='Damaged'>Damaged</option>
              </select><br>";

        // Add fine input for each book
        echo "<label for='fine_{$row['BookID']}'>Fine:</label>";
        echo "<input type='number' name='fine_{$row['BookID']}' class='form-control' min='0' placeholder='Enter fine (if damaged, an additional $5 will be applied.)'><br>";
    }

    echo '<button type="submit" name="return" class="btn btn-success">Process Return</button>';
    echo '</form>';
}
                ?>
            </div>
        </div>
    </div>

    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/custom.js"></script>
</body>
</html>