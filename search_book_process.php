<?php 
session_start();
include('dbconnect.php');

if (!isset($_SESSION['SID'])) {
    echo "<script>window.alert('Please Login')</script>";
    echo "<script>window.location='StaffRegister.php'</script>";
} else {
    $SID = $_SESSION['SID'];
    $Sname = $_SESSION['SName'];
    $SEmail = $_SESSION['SEmail'];

    // Handle Book Search
    if (isset($_GET['search'])) {
        $search = mysqli_real_escape_string($connect, $_GET['search']); // Sanitize input

        // Modify query to join with category table and fetch CategoryName, and include Stock and other fields
        $query = "SELECT b.*, c.CategoryName 
                  FROM book b 
                  LEFT JOIN category c ON b.CategoryID = c.CategoryID 
                  WHERE b.Title LIKE '%$search%' 
                  OR b.Author LIKE '%$search%' 
                  OR b.Genre LIKE '%$search%' 
                  OR b.Status LIKE '%$search%' 
                  OR b.Publisher LIKE '%$search%'";
        $result = mysqli_query($connect, $query);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Search Books - Admin Dashboard</title>
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
        <?php include('nav.php'); ?>
        <!-- MAIN CONTENT -->
        <div id="page-wrapper">
            <!-- BREADCRUMBS -->
            <ol class="breadcrumb">
                <li><a href="admin.php">Dashboard</a></li>
                <li><a href="#">Books</a></li>
                <li><a href="managebook.php">Manage Book</a></li>
                <li class="active">Search Books</li>
            </ol>

            <?php include 'search_book.php'; ?>

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
                                <!-- Make the table scrollable on small screens -->
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
                                                <th>Stock</th> <!-- Added Stock column -->
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
                                                if (isset($result) && mysqli_num_rows($result) > 0) {
                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                        echo "<tr>";
                                                        echo "<td>" . $row['Title'] . "</td>";
                                                        echo "<td>" . $row['Author'] . "</td>";
                                                        echo "<td>" . $row['Genre'] . "</td>";
                                                        echo "<td>" . $row['Status'] . "</td>";
                                                        echo "<td>" . $row['Publisher'] . "</td>";
                                                        echo "<td>" . $row['CategoryName'] . "</td>";
                                                        echo "<td>" . $row['Stock'] . "</td>"; 
                                                        echo "<td><img src='" . $row['Image'] . "' class='img-fluid' width='50' height='50' /></td>";
                                                        // echo "<td><img src='" . $row['Image2'] . "' class='img-fluid' width='50' height='50' /></td>"; // Display Image2
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
                                                } else {
                                                    echo "<tr><td colspan='9'>No books found matching your search.</td></tr>";
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
