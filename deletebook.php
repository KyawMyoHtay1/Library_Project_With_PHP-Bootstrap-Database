<?php 
session_start();
include('dbconnect.php');

// Check if the user is logged in
if (!isset($_SESSION['SID'])) {
    echo "<script>window.alert('Please Login')</script>";
    echo "<script>window.location='StaffRegister.php'</script>";
} else {
    $SID = $_SESSION['SID'];
    $Sname = $_SESSION['SName'];
    $SEmail = $_SESSION['SEmail'];

    // Check if the 'delete' parameter is set
    if (isset($_GET['delete'])) {
        $bookID = $_GET['delete'];

        // Sanitize $bookID by escaping any special characters to prevent SQL injection
        $bookID = mysqli_real_escape_string($connect, $bookID);

        // Fetch the book details (Optional: To show book title or image before deletion)
        $book_query = "SELECT * FROM book WHERE BookID = '$bookID'";  
        $book_result = mysqli_query($connect, $book_query);
        $book_data = mysqli_fetch_assoc($book_result);

        if($book_data) {
            $book_title = $book_data['Title'];
            $book_image = $book_data['Image'];
            $categoryID = $book_data['CategoryID'];  // Get the category ID of the book

            // SQL query to delete the book
            $sql = "DELETE FROM book WHERE BookID = '$bookID'";  

            // Execute deletion
            if(mysqli_query($connect, $sql)) {
                // Update the book count in the category
                $update_category = "UPDATE category SET BookCount = BookCount - 1 WHERE CategoryID = '$categoryID'";
                mysqli_query($connect, $update_category);

                // If the book has an image, delete it from the server
                if($book_image && file_exists($book_image)) {
                    unlink($book_image);
                }

                // Success message and redirect to the list of books
                echo "<script>
                        alert('Book deleted successfully!');
                        window.location.href='managebook.php';
                      </script>";
            } else {
                echo "<script>
                        alert('Error occurred during deletion!');
                      </script>";
            }
        } else {
            echo "<script>
                    alert('Book not found!');
                    window.location.href='editdeletebook.php';
                  </script>";
        }

    } else {
        // Only Admin users are allowed to delete
        echo "<script>
                alert('Administration only!');
                window.location.href='admin.php';
              </script>";
    }
} 
?>
