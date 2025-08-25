<?php 
session_start();
include('dbconnect.php');
include('AutoID_Functions.php');
include('nav.php');

if (!isset($_SESSION['SID'])) {
    echo "<script>window.alert('Please Login')</script>";
    echo "<script>window.location='StaffRegister.php'</script>";
} else {

    $SID=$_SESSION['SID'];
    $Sname=$_SESSION['SName'];
    $SEmail=$_SESSION['SEmail'];

    if (isset($_POST['btnsubmit'])) {
        $BID = $_POST['txtBID'];
        $title = $_POST['txttitle'];
        $author = $_POST['txtauthor'];
        $genre = $_POST['txtgenre'];
        $status = $_POST['txtstatus'];
        $image = $_FILES['image']['name'];
        $publisher = $_POST['txtpublisher'];
        $category = $_POST['txtcategory'];
        $summary = $_POST['txtsummary'];
        $isbn = $_POST['txtisbn'];
        $pagecount = $_POST['txtpagecount'];
        $language = $_POST['txtlanguage'];
        $edition = $_POST['txtedition'];
        $format = $_POST['txtformat'];
        $awards = $_POST['txtawards'];
        $publicationyear = $_POST['txtpublicationyear'];

        // Check for duplicate book title
        $checkbook = "SELECT * FROM book WHERE Title='$title'";
        $query = mysqli_query($connect, $checkbook);
        $count = mysqli_num_rows($query);

        if ($count > 0) {
            echo "<script>window.alert('Duplicate Book Title')</script>";
            echo "<script>window.location='entrybook.php'</script>";
        } else {
            // Handle image upload
            if ($image != '') {
                $target_dir = "bookimage/".$title."_";
                $target_file = $target_dir . basename($_FILES["image"]["name"]);
                move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
            } else {
                $target_file = '';  // No image uploaded
            }

$insert = "INSERT INTO book 
(BookID, Title, Author, Genre, Status, Image, Summary, ISBN, PageCount, Language, Edition, Format, Awards, PublicationYear, Publisher, CategoryID) 
VALUES 
('$BID', '$title', '$author', '$genre', '$status', '$target_file', '$summary', '$isbn', '$pagecount', '$language', '$edition', '$format', '$awards', '$publicationyear', '$publisher', '$category')";




            $query = mysqli_query($connect, $insert);

            if ($query) {
                // Update BookCount in category table
                $updateCategory = "UPDATE category SET BookCount = BookCount + 1 WHERE CategoryID = '$category'";
                $updateQuery = mysqli_query($connect, $updateCategory);

                if ($updateQuery) {
                    echo "<script>window.alert('Book Entry Success')</script>";
                    echo "<script>window.location='entrybook.php'</script>";
                } else {
                    echo "<script>window.alert('Error updating book count. Please try again.')</script>";
                }
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
    <title>Profile - Admin Dashboard</title>
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
                <li><a href="#">Books</a></li>
                <li class="active">Entry Book</li>
            </ol>

            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h2>Entry Book</h2>
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
                            <form method="POST" action="entrybook.php" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="title">BookID:</label>
                                    <input type="text" name="txtBID" value="<?php echo AutoID($connect, "book","BookID","Bo-",6); ?>" readonly/>
                                </div>
                                <div class="form-group">
                                    <label for="title">Title:</label>
                                    <input type="text" class="form-control" id="title" name="txttitle" required />
                                </div>
                                <div class="form-group">
                                    <label for="author">Author:</label>
                                    <input type="text" class="form-control" id="author" name="txtauthor" required />
                                </div>
                                 <div class="form-group">
                                    <label for="genre">Genre:</label>
                                    <input type="text" class="form-control" id="genre" name="txtgenre" required />
                                </div>
                                 <div class="form-group">
                                    <label for="status">Status:</label>
                                    <input type="text" class="form-control" id="status" name="txtstatus" required />
                                </div>
                                <div class="form-group">
                                    <label for="status">Publisher:</label>
                                    <input type="text" class="form-control" id="status" name="txtpublisher" required />
                                </div>
                                <div class="form-group">
                                    <label for="image">Image:</label>
                                    <input type="file" class="form-control" id="image" name="image" required />
                                </div>
<div class="form-group">
    <label for="summary">Summary:</label>
    <textarea class="form-control" id="summary" name="txtsummary" required rows="3"></textarea>
</div>
<div class="form-group">
    <label for="category">Category:</label>
    <select class="form-control" id="category" name="txtcategory" required>
        <option value="" disabled selected>Choose Assign Category</option>
        <?php
            // Fetch categories from the database
            $categoryQuery = "SELECT * FROM category";
            $categoryResult = mysqli_query($connect, $categoryQuery);

            if (mysqli_num_rows($categoryResult) > 0) {
                while ($category = mysqli_fetch_assoc($categoryResult)) {
                    echo "<option value='" . htmlspecialchars($category['CategoryID']) . "'>" . htmlspecialchars($category['CategoryName']) . "</option>";
                }
            } else {
                echo "<option disabled>No categories available</option>";
            }
        ?>
    </select>
</div>

<div class="form-group">
    <label for="isbn">ISBN:</label>
    <input type="text" class="form-control" id="isbn" name="txtisbn" required />
</div>

<div class="form-group">
    <label for="pagecount">Page Count:</label>
    <input type="number" class="form-control" id="pagecount" name="txtpagecount" required/>
</div>

<div class="form-group">
    <label for="language">Language:</label>
    <input type="text" class="form-control" id="language" name="txtlanguage" required />
</div>

<div class="form-group">
    <label for="edition">Edition:</label>
    <input type="text" class="form-control" id="edition" name="txtedition" required/>
</div>

<div class="form-group">
    <label for="format">Format:</label>
    <select name="txtformat" id="format" class="form-control" required>
        <option value="Hardcover">Hardcover</option>
        <option value="Paperback">Paperback</option>
        <option value="Ebook">Ebook</option>
        <option value="Audiobook">Audiobook</option>
    </select>
</div>


<div class="form-group">
    <label for="awards">Awards:</label>
    <textarea class="form-control" id="awards" name="txtawards"  required rows="2"></textarea>
</div>

<div class="form-group">
    <label for="publicationyear">Publication Year:</label>
    <input type="number" class="form-control" id="publicationyear" name="txtpublicationyear" required />
</div>
                                <button type="submit" class="btn btn-success" name="btnsubmit">Add Book</button>
                                <button type="clear" class="btn btn-primary" name="btncancel">Cancel</button>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <!-- /. MAIN CONTENT -->

    <!-- JAVASCRIPT FILES -->
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/custom.js"></script>
</body>
</html>
