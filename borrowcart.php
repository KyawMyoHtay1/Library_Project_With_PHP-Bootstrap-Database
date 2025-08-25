<?php
session_start();
include('dbconnect.php');  // Database connection

// Ensure the member is logged in
if (!isset($_SESSION['MID'])) {
    echo "<script>alert('Please Sign in as a Member');</script>";
    echo "<script>window.location='signin.php';</script>";
    exit();
}

$MID = $_SESSION['MID'];
$bookID = isset($_GET['bookID']) ? $_GET['bookID'] : '';

// Check if bookID is valid
if (empty($bookID)) {
    echo "<script>alert('Invalid book selection.');</script>";
    echo "<script>window.location='borrow.php';</script>";
    exit();
}

// Fetch book details from database
$bookQuery = "SELECT BookID, Title, Author, Genre, Stock FROM book WHERE BookID = ?";
$stmt = mysqli_prepare($connect, $bookQuery);
mysqli_stmt_bind_param($stmt, "s", $bookID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$book = mysqli_fetch_assoc($result);

if (!$book) {
    echo "<script>alert('Book not found.');</script>";
    echo "<script>window.location='borrow.php';</script>";
    exit();
}

// Check if book is available in stock
if ($book['Stock'] <= 0) {
    echo "<script>alert('Sorry, this book is currently unavailable.');</script>";
    echo "<script>window.location='borrow.php';</script>";
    exit();
}

// Initialize borrow cart if not already initialized
if (!isset($_SESSION['borrow_cart'])) {
    $_SESSION['borrow_cart'] = [];
}

// Add the book to the borrow cart if not already present
if (!isset($_SESSION['borrow_cart'][$bookID])) {
    // Ensure the BookID format is consistent
$formattedBookID = "" . str_pad($book['BookID'], 6, "0", STR_PAD_LEFT);


    $_SESSION['borrow_cart'][$formattedBookID] = [
        'BookID' => $formattedBookID,
        'Title' => $book['Title'],
        'Author' => $book['Author'], 
        'Genre' => $book['Genre'],
        'Quantity' => 1
    ];
    echo "<script>alert('Book added to borrow cart successfully.');</script>";
} else {
    echo "<script>alert('This book is already in your borrow cart.');</script>";
}


// Redirect to borrow cart view page
echo "<script>window.location='borrowcartview.php';</script>";
?>
