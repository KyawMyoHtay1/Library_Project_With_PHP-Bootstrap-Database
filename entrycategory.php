<?php
session_start();
include('dbconnect.php');
include('AutoID_Functions.php');
include('nav.php');

if (!isset($_SESSION['SID'])) {
    echo "<script>window.alert('Please Login')</script>";
    echo "<script>window.location='StaffRegister.php'</script>";
} else {

    $SID = $_SESSION['SID'];
    $Sname = $_SESSION['SName'];
    $SEmail = $_SESSION['SEmail'];

    if (isset($_POST['btnsubmit'])) {
        $categoryID = $_POST['txtCategoryID'];
        $categoryName = $_POST['txtCategoryName'];
        $description = $_POST['txtDescription'];  // Added description

        // Check if category already exists
        $checkCategory = "SELECT * FROM category WHERE CategoryName='$categoryName'";
        $query = mysqli_query($connect, $checkCategory);
        $count = mysqli_num_rows($query);

        if ($count > 0) {
            echo "<script>window.alert('Duplicate Category Name')</script>";
            echo "<script>window.location='entrycategory.php'</script>";
        } else {
            // Insert category into the database with BookCount set to 0
            $insert = "INSERT INTO category(CategoryID, CategoryName, Description, BookCount) 
                       VALUES ('$categoryID','$categoryName', '$description', 0)";
            $query = mysqli_query($connect, $insert);

            if ($query) {
                echo "<script>window.alert('Category Entry Success')</script>";
                echo "<script>window.location='entrycategory.php'</script>";
            } else {
                echo "<script>window.alert('Error occurred, please try again.')</script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Entry Category - Library Management System</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/custom.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/styles.css?<?php echo time(); ?>">
</head>
<body class="admin_profile">
    <div id="wrapper">

        <div id="page-wrapper">
            <ol class="breadcrumb">
                <li><a href="admin.php">Dashboard</a></li>
                <li><a href="#">Categories</a></li>
                <li class="active">Entry Category</li>
            </ol>

            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h2>Entry Category</h2>
                        <h5>Welcome, <?php echo $Sname; ?>, Love to see you back.</h5>
                    </div>
                </div>
                <br>

                <section class="profile-info">
                    <div class="containers">
                        <div class="row">
                            <div class="col-md-4 text-center">
                                <img src="assets/img/find_user.png" alt="Profile Picture" class="img-responsive img-thumbnail" />
                                <h3><?php echo $Sname; ?></h3>
                                <p><?php echo $SEmail; ?></p>
                            </div>
                            <div class="col-md-6">
                                <form method="POST" action="entrycategory.php">
                                    <div class="form-group">
                                        <label for="categoryID">CategoryID:</label>
                                        <input type="text" name="txtCategoryID" value="<?php echo AutoID($connect,"category", "CategoryID", "Ca-", 6); ?>" readonly />
                                    </div>
                                    <div class="form-group">
                                        <label for="categoryName">Category Name:</label>
                                        <input type="text" class="form-control" id="categoryName" name="txtCategoryName" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Description:</label>
                                        <textarea class="form-control" id="description" name="txtDescription" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-success" name="btnsubmit">Add Category</button>
                                    <button type="reset" class="btn btn-primary">Cancel</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>

    </div>

    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/custom.js"></script>
</body>
</html>
