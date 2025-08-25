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

// Ensure search parameter exists
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($connect, $_GET['search']); // Sanitize input

    // Modify query to include Description and BookCount
    $query = "SELECT CategoryID, CategoryName, Description, BookCount FROM category WHERE CategoryName LIKE '%$search%'";
    $categoryResult = mysqli_query($connect, $query);

    // Check for errors in query execution
    if (!$categoryResult) {
        die("Query Failed: " . mysqli_error($connect));
    }
} else {
    // Initialize with an empty result set to prevent errors
    $categoryResult = mysqli_query($connect, "SELECT CategoryID, CategoryName, Description, BookCount FROM category LIMIT 0");
}

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Search Categories - Admin Dashboard</title>
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
<body class="admin_profile">
    <div id="wrapper">
        <!-- MAIN CONTENT -->
        <div id="page-wrapper">
            <!-- BREADCRUMBS -->
            <ol class="breadcrumb">
                <li><a href="admin.php">Dashboard</a></li>
                <li><a href="#">Categories</a></li>
                <li><a href="managecategory.php">Manage Category</a></li>
                <li class="active">Search Categories</li>
            </ol>

            <?php 
include 'search_category.php'; 
?>

            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h2>Manage Categories</h2>
                        <h5>Manage categories of books in the library</h5>
                    </div>
                </div>
                <br>

                <!-- Category List -->
                <section class="profile-info">
                    <div class="containers">
                        <div class="row">
                            <div class="col-md-12">
                                <h3>Category List</h3>
                                <table class="table table-bordered">
                                    <thead>
    <tr>
        <th>Category Name</th>
        <th>Description</th>
        <th>Book Count</th>
        <th>Actions</th>
    </tr>
</thead>
<tbody>
    <?php
    if (mysqli_num_rows($categoryResult) > 0) {
        while ($row = mysqli_fetch_assoc($categoryResult)) {
            echo "<tr>";
            echo "<td>" . $row['CategoryName'] . "</td>";
            echo "<td>" . $row['Description'] . "</td>"; // Display the Description
            echo "<td>" . $row['BookCount'] . "</td>";
            echo "<td>
                    <a href='editcategoryprocess.php?edit=" . $row['CategoryID'] . "' class='btn btn-primary'>Edit</a>
                    <a href='managecategory.php?delete=" . $row['CategoryID'] . "' class='btn btn-danger' onclick='return confirm(\"Are you sure you want to delete this category?\")'>Delete</a>
                  </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='4'>No categories found.</td></tr>";
    }
    ?>
</tbody>

                                </table>
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
