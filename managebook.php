<?php
session_start();
include('dbconnect.php');
include('nav.php');

if (!isset($_SESSION['SID'])) {
    echo "<script>window.alert('Please Login')</script>";
    echo "<script>window.location='StaffRegister.php'</script>";
} else {
    $SID = $_SESSION['SID'];
    $Sname = $_SESSION['SName'];
    $SEmail = $_SESSION['SEmail'];

    // Handle Book Deletion
    if (isset($_GET['delete'])) {
        $bookID = $_GET['delete'];

        // Delete book from database
        $deleteQuery = "DELETE FROM book WHERE BookID='$bookID'";
        $deleteResult = mysqli_query($connect, $deleteQuery);

        if ($deleteResult) {
            echo "<script>window.alert('Book deleted successfully')</script>";
            echo "<script>window.location='managebook.php'</script>";
        } else {
            echo "<script>window.alert('Error occurred while deleting the book')</script>";
        }
    }

    // Handle Book Editing
    if (isset($_POST['btnedit'])) {
        $bookID = $_POST['bookID'];
        $title = $_POST['txttitle'];
        $author = $_POST['txtauthor'];
        $genre = $_POST['txtgenre'];
        $status = $_POST['txtstatus'];
        $publisher = $_POST['txtpublisher'];
        $stock = $_POST['txtstock'];  // Added stock input
        $image = $_FILES['image']['name'];
        // $image2 = $_FILES['image2']['name']; // Added Image2 input
        $summary = $_POST['txtsummary']; // Added summary input
        $isbn = $_POST['txtisbn']; // Added ISBN input
        $pageCount = $_POST['txtpageCount']; // Added page count input
        $language = $_POST['txtlanguage']; // Added language input
        $edition = $_POST['txtedition']; // Added edition input
        $format = $_POST['txtformat']; // Added format input
        $awards = $_POST['txtawards']; // Added awards input
        $publicationYear = $_POST['txtpublicationYear']; // Added publication year input

        // Handle image upload
        if ($image != '') {
            $target_dir = "bookimage/" . $title . "_";
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
        } else {
            $target_file = '';  // No image uploaded
        }

        // if ($image2 != '') {
        //     $target_dir2 = "bookimage/" . $title . "_2";
        //     $target_file2 = $target_dir2 . basename($_FILES["image2"]["name"]);
        //     move_uploaded_file($_FILES["image2"]["tmp_name"], $target_file2);
        // } else {
        //     $target_file2 = '';  // No image2 uploaded
        // }

        // Update book details in the database
$updateQuery = "UPDATE book 
                SET Title='$title', Author='$author', Genre='$genre', Status='$status', Publisher='$publisher', Stock='$stock', 
                    Image='$target_file', Summary='$summary', ISBN='$isbn', PageCount='$pageCount', 
                    Language='$language', Edition='$edition', Format='$format', Awards='$awards', PublicationYear='$publicationYear' 
                WHERE BookID='$bookID'";

        $updateResult = mysqli_query($connect, $updateQuery);

        if ($updateResult) {
            echo "<script>window.alert('Book updated successfully')</script>";
            echo "<script>window.location='managebook.php'</script>";
        } else {
            echo "<script>window.alert('Error occurred while updating the book')</script>";
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manage Book - Admin Dashboard</title>
    <!-- BOOTSTRAP STYLES-->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONTAWESOME STYLES-->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLES-->
    <link href="assets/css/custom.css" rel="stylesheet" />
    <!-- GOOGLE FONTS-->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <link rel="stylesheet" href="css/styles.css?<?php echo time(); ?>">
</head>
<body class="managebook-page">
    <div id="wrapper">

        <!-- MAIN CONTENT -->
        <div id="page-wrapper">

            <!-- BREADCRUMBS -->
            <ol class="breadcrumb">
                <li><a href="admin.php">Dashboard</a></li>
                <li><a href="#">Books</a></li>
                <li class="active">Manage Book</li>
            </ol>

            <?php 
include 'search_book.php'; 
?>

            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h2>Manage Book</h2>
                        <h5>Manage book records</h5>
                    </div>
                </div>
                <br>

                <section class="profile-info">
    <div class="containers">
        <div class="row">
            <div class="col-md-12">
                <h3>Books List</h3>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Genre</th>
                                <th>Status</th>
                                <th>Publisher</th>
                                <th>Category</th>
                                <th>Stock</th>
                                <th>Image</th>
                                <!-- <th>Image2</th> Added Image2 column -->
                                <th>Summary</th> <!-- Added Summary column -->
                                <th>ISBN</th> <!-- Added ISBN column -->
                                <th>Page Count</th> <!-- Added Page Count column -->
                                <th>Language</th> <!-- Added Language column -->
                                <th>Edition</th> <!-- Added Edition column -->
                                <th>Format</th> <!-- Added Format column -->
                                <th>Awards</th> <!-- Added Awards column -->
                                <th>Publication Year</th> <!-- Added Publication Year column -->
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                // Join book with category table and select stock and other fields
                                $query = "SELECT b.*, c.CategoryName FROM book b 
                                          LEFT JOIN category c ON b.CategoryID = c.CategoryID";
                                $result = mysqli_query($connect, $query);

                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>" . $row['Title'] . "</td>";
                                    echo "<td>" . $row['Author'] . "</td>";
                                    echo "<td>" . $row['Genre'] . "</td>";
                                    echo "<td>" . $row['Status'] . "</td>";
                                    echo "<td>" . $row['Publisher'] . "</td>";
                                    echo "<td>" . $row['CategoryName'] . "</td>";
                                    echo "<td>" . $row['Stock'] . "</td>";
                                    echo "<td><img src='" . $row['Image'] . "' width='50' height='50' /></td>";
                                    // echo "<td><img src='" . $row['Image2'] . "' width='50' height='50' /></td>"; // Display Image2
                                    echo "<td>" . $row['Summary'] . "</td>"; // Display Summary
                                    echo "<td>" . $row['ISBN'] . "</td>"; // Display ISBN
                                    echo "<td>" . $row['PageCount'] . "</td>"; // Display Page Count
                                    echo "<td>" . $row['Language'] . "</td>"; // Display Language
                                    echo "<td>" . $row['Edition'] . "</td>"; // Display Edition
                                    echo "<td>" . $row['Format'] . "</td>"; // Display Format
                                    echo "<td>" . $row['Awards'] . "</td>"; // Display Awards
                                    echo "<td>" . $row['PublicationYear'] . "</td>"; // Display Publication Year
                                    echo "<td>
                                            <a href='editbookprocess.php?edit=" . $row['BookID'] . "' class='btn btn-primary'>Edit</a>
                                            <a href='deletebook.php?delete=" . $row['BookID'] . "' class='btn btn-danger' onclick='return confirm(\"Are you sure you want to delete this book?\")'>Delete</a>
                                          </td>";
                                    echo "</tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

            </div>
        </div>
        <!-- /. MAIN CONTENT -->

    <!-- JAVASCRIPT FILES -->
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/custom.js"></script>
</body>
</html>
