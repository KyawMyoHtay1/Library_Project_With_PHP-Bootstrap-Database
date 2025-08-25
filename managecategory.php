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

    // Add Category
    if (isset($_POST['btnadd'])) {
        $categoryName = $_POST['categoryName'];

        $insertQuery = "INSERT INTO category (CategoryName) VALUES ('$categoryName')";
        $insertResult = mysqli_query($connect, $insertQuery);

        if ($insertResult) {
            echo "<script>window.alert('Category added successfully')</script>";
            echo "<script>window.location='managecategory.php'</script>";
        } else {
            echo "<script>window.alert('Error occurred while adding category')</script>";
        }
    }

if (isset($_POST['btnedit'])) {
    $categoryID = $_POST['categoryID'];
    $categoryName = $_POST['categoryName'];
    $description = $_POST['description'];

    $updateQuery = "UPDATE category SET CategoryName='$categoryName', Description='$description' WHERE CategoryID='$categoryID'";
    $updateResult = mysqli_query($connect, $updateQuery);

    if ($updateResult) {
        echo "<script>window.alert('Category updated successfully')</script>";
        echo "<script>window.location='managecategory.php'</script>";
    } else {
        echo "<script>window.alert('Error occurred while updating category')</script>";
    }
}


    // Delete Category
    if (isset($_GET['delete'])) {
        $categoryID = $_GET['delete'];

        $deleteQuery = "DELETE FROM category WHERE CategoryID='$categoryID'";
        $deleteResult = mysqli_query($connect, $deleteQuery);

        if ($deleteResult) {
            echo "<script>window.alert('Category deleted successfully')</script>";
            echo "<script>window.location='managecategory.php'</script>";
        } else {
            echo "<script>window.alert('Error occurred while deleting category')</script>";
        }
    }

    // Fetch all categories including BookCount
    $categoryQuery = "SELECT * FROM category";
    $categoryResult = mysqli_query($connect, $categoryQuery);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manage Categories - Admin Dashboard</title>
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
                <li class="active">Manage Categories</li>
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
                echo "<td>" . $row['Description'] . "</td>";
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
