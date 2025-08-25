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

    // Handle Book Editing
    if (isset($_POST['btnedit'])) {
        $bookID = $_POST['bookID'];
        $title = $_POST['txttitle'];
        $author = $_POST['txtauthor'];
        $genre = $_POST['txtgenre'];
        $status = $_POST['txtstatus'];
        $publisher = $_POST['txtpublisher'];
        $category = $_POST['category'];
        $stock = $_POST['txtstock'];  
        $image = $_FILES['image']['name'];
        $summary = $_POST['txtsummary'];
        $isbn = $_POST['txtisbn'];
        $pageCount = $_POST['txtpagecount'];
        $language = $_POST['txtlanguage'];
        $edition = $_POST['txtedition'];
        $format = $_POST['txtformat'];
        $awards = $_POST['txtawards'];
        $publicationYear = $_POST['txtpublicationyear'];

        // Handle image uploads
        if ($image != '') {
            $target_dir = "bookimage/".$title."_";
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
        } else {
            $target_file = '';  // No image uploaded
        }

        // Update book details in the database without the image2 field
        $updateQuery = "UPDATE book 
                        SET Title='$title', Author='$author', Genre='$genre', Status='$status', Publisher='$publisher', CategoryID='$category', Stock='$stock', Image='$target_file', Summary='$summary', ISBN='$isbn', PageCount='$pageCount', Language='$language', Edition='$edition', Format='$format', Awards='$awards', PublicationYear='$publicationYear' 
                        WHERE BookID='$bookID'";

        $updateResult = mysqli_query($connect, $updateQuery);

        if ($updateResult) {
            echo "<script>window.alert('Book updated successfully')</script>";
            echo "<script>window.location='managebook.php'</script>";
        } else {
            echo "<script>window.alert('Error occurred while updating the book')</script>";
        }
    }

    // Fetch book details if editing
    if (isset($_GET['edit'])) {
        $bookID = $_GET['edit'];
        $query = "SELECT * FROM book WHERE BookID='$bookID'";
        $result = mysqli_query($connect, $query);
        $book = mysqli_fetch_assoc($result);
    }

    // Fetch categories from the database
    $categoryQuery = "SELECT * FROM category";
    $categoryResult = mysqli_query($connect, $categoryQuery);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Book - Admin Dashboard</title>
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
                <li><a href="managebook.php">Manage Book</a></li>
                <li class="active">Edit Book</li>
            </ol>

            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h2>Edit Book</h2>
                        <h5>Update book details</h5>
                    </div>
                </div>
                <br>

                <section class="profile-info">
                    <div class="containers">
                        <div class="row">
                            <div class="col-md-12">
                                <form method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="bookID" value="<?php echo $book['BookID']; ?>" />

                                    <div class="form-group">
                                        <label for="txttitle">Book Title:</label>
                                        <input type="text" class="form-control" name="txttitle" id="txttitle" value="<?php echo $book['Title']; ?>" required />
                                    </div>

                                    <div class="form-group">
                                        <label for="txtauthor">Author:</label>
                                        <input type="text" class="form-control" name="txtauthor" id="txtauthor" value="<?php echo $book['Author']; ?>" required />
                                    </div>

                                    <div class="form-group">
                                        <label for="txtgenre">Genre:</label>
                                        <input type="text" class="form-control" name="txtgenre" id="txtgenre" value="<?php echo $book['Genre']; ?>" required />
                                    </div>

                                    <div class="form-group">
                                        <label for="txtstatus">Status:</label>
                                        <input type="text" class="form-control" name="txtstatus" id="txtstatus" value="<?php echo $book['Status']; ?>" required />
                                    </div>

                                    <div class="form-group">
                                        <label for="txtpublisher">Publisher:</label>
                                        <input type="text" class="form-control" name="txtpublisher" id="txtpublisher" value="<?php echo $book['Publisher']; ?>" required />
                                    </div>

                                    <div class="form-group">
                                        <label for="category">Category:</label>
                                        <select name="category" id="category" class="form-control" required>
                                            <option value="">Select Category</option>
                                            <?php
                                            while ($category = mysqli_fetch_assoc($categoryResult)) {
                                                $selected = ($category['CategoryID'] == $book['CategoryID']) ? 'selected' : '';
                                                echo "<option value='" . $category['CategoryID'] . "' $selected>" . $category['CategoryName'] . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="txtstock">Stock Quantity:</label>
                                        <input type="number" class="form-control" name="txtstock" id="txtstock" value="<?php echo $book['Stock']; ?>" required />
                                    </div>

                                    <div class="form-group">
                                        <label for="image">Book Image:</label>
                                        <input type="file" name="image" id="image" />
                                        <img src="<?php echo $book['Image']; ?>" width="50" height="50" />
                                    </div>

                                    <div class="form-group">
                                        <label for="txtsummary">Summary:</label>
                                        <textarea class="form-control" name="txtsummary" id="txtsummary" required><?php echo $book['Summary']; ?></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="txtisbn">ISBN:</label>
                                        <input type="text" class="form-control" name="txtisbn" id="txtisbn" value="<?php echo $book['ISBN']; ?>" required />
                                    </div>

                                    <div class="form-group">
                                        <label for="txtpagecount">Page Count:</label>
                                        <input type="number" class="form-control" name="txtpagecount" id="txtpagecount" value="<?php echo $book['PageCount']; ?>" required />
                                    </div>

                                    <div class="form-group">
                                        <label for="txtlanguage">Language:</label>
                                        <input type="text" class="form-control" name="txtlanguage" id="txtlanguage" value="<?php echo $book['Language']; ?>" required />
                                    </div>

                                    <div class="form-group">
                                        <label for="txtedition">Edition:</label>
                                        <input type="text" class="form-control" name="txtedition" id="txtedition" value="<?php echo $book['Edition']; ?>" required />
                                    </div>

                                    <div class="form-group">
                                        <label for="txtformat">Format:</label>
                                        <select name="txtformat" id="txtformat" class="form-control" required>
                                            <option value="Hardcover" <?php echo ($book['Format'] == 'Hardcover') ? 'selected' : ''; ?>>Hardcover</option>
                                            <option value="Paperback" <?php echo ($book['Format'] == 'Paperback') ? 'selected' : ''; ?>>Paperback</option>
                                            <option value="Ebook" <?php echo ($book['Format'] == 'Ebook') ? 'selected' : ''; ?>>Ebook</option>
                                            <option value="Audiobook" <?php echo ($book['Format'] == 'Audiobook') ? 'selected' : ''; ?>>Audiobook</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="txtawards">Awards:</label>
                                        <input type="text" class="form-control" name="txtawards" required id="txtawards" value="<?php echo $book['Awards']; ?>" />
                                    </div>

                                    <div class="form-group">
                                        <label for="txtpublicationyear">Publication Year:</label>
                                        <input type="number" class="form-control" name="txtpublicationyear" required id="txtpublicationyear" value="<?php echo $book['PublicationYear']; ?>" required />
                                    </div>

                                    <button type="submit" name="btnedit" class="btn btn-primary">Update Book</button>
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
