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

    // Handle Category Editing
if (isset($_POST['btnedit'])) {
    $categoryID = $_POST['categoryID'];
    $categoryName = $_POST['txtcategoryname'];
    $categoryDescription = $_POST['txtcategorydescription']; // Fetch description

    // Update category details in the database
    $updateQuery = "UPDATE category SET CategoryName='$categoryName', Description='$categoryDescription' WHERE CategoryID='$categoryID'";
    $updateResult = mysqli_query($connect, $updateQuery);

    if ($updateResult) {
        echo "<script>window.alert('Category updated successfully')</script>";
        echo "<script>window.location='managecategory.php'</script>";
    } else {
        echo "<script>window.alert('Error occurred while updating the category')</script>";
    }
}

// Fetch category details if editing
if (isset($_GET['edit'])) {
    $categoryID = $_GET['edit'];
    $query = "SELECT * FROM category WHERE CategoryID='$categoryID'";
    $result = mysqli_query($connect, $query);
    $category = mysqli_fetch_assoc($result);
}

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Category - Admin Dashboard</title>
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
                <li><a href="admin.php">Home</a></li>
                <li><a href="managecategory.php">Manage Category</a></li>
                <li class="active">Edit Category</li>
            </ol>

            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h2>Edit Category</h2>
                        <h5>Update category details</h5>
                    </div>
                </div>
                <br>

                <section class="profile-info">
                    <div class="containers">
                        <div class="row">
                            <div class="col-md-12">
                                <form method="POST">
                                    <input type="hidden" name="categoryID" value="<?php echo $category['CategoryID']; ?>" />

                                    <div class="form-group">
    <label for="txtcategoryname">Category Name:</label>
    <input type="text" class="form-control" name="txtcategoryname" id="txtcategoryname" value="<?php echo $category['CategoryName']; ?>" required />
</div>

<div class="form-group">
    <label for="txtcategorydescription">Description:</label>
    <textarea class="form-control" name="txtcategorydescription" id="txtcategorydescription" required><?php echo $category['Description']; ?></textarea>
</div>

<button type="submit" name="btnedit" class="btn btn-primary">Update Category</button>
<a href="managecategory.php" class="btn btn-danger">Cancel</a>

                                </form>
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
