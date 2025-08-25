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
        $supplierID = $_GET['delete'];

        // Sanitize $supplierID by escaping any special characters to prevent SQL injection
        $supplierID = mysqli_real_escape_string($connect, $supplierID);

        // Fetch the supplier details (Optional: To show supplier name or email before deletion)
        $supplier_query = "SELECT * FROM supplier WHERE SupplierID = '$supplierID'";  
        $supplier_result = mysqli_query($connect, $supplier_query);
        $supplier_data = mysqli_fetch_assoc($supplier_result);

        if ($supplier_data) {
            $supplier_name = $supplier_data['Name'];
            $supplier_email = $supplier_data['Email'];

            // Show confirmation before deletion
            echo "
            <!DOCTYPE html>
            <html lang='en'>
            <head>
                <meta charset='utf-8' />
                <meta name='viewport' content='width=device-width, initial-scale=1.0' />
                <title>Delete Supplier - Admin Dashboard</title>
                <link href='assets/css/bootstrap.css' rel='stylesheet' />
                <link href='assets/css/font-awesome.css' rel='stylesheet' />
                <link href='assets/css/custom.css' rel='stylesheet' />
                <link href='css/styles.css' rel='stylesheet' />
            </head>
            <body class='admin_profile'>
                <div id='wrapper'>
                    <nav class='navbar navbar-default navbar-cls-top' role='navigation' style='margin-bottom: 0'>
                        <div class='navbar-header'>
                            <a class='navbar-brand' href='admin_profile.php'>$Sname</a>
                        </div>
                        <div style='color: white; padding: 15px 50px 5px 50px; float: right; font-size: 16px;'>
                            <a href='logout.php' class='btn btn-danger square-btn-adjust'>Logout</a>
                        </div>
                    </nav>

                    <nav class='navbar-default navbar-side' role='navigation'>
                        <div class='sidebar-collapse'>
                            <ul class='nav' id='main-menu'>
                                <li><a href='admin.php'><i class='fa fa-dashboard'></i> Dashboard</a></li>
                                <li><a href='managesupplier.php' class='active'><i class='fa fa-truck'></i> Suppliers</a></li>
                            </ul>
                        </div>
                    </nav>

                    <div id='page-wrapper'>
                        <div id='page-inner'>
                            <div class='row'>
                                <div class='col-md-12'>
                                    <h2>Delete Supplier</h2>
                                    <h5>Are you sure you want to delete the supplier '$supplier_name'?</h5>
                                    <div class='supplier-details'>
                                        <p><strong>Name:</strong> $supplier_name</p>
                                        <p><strong>Email:</strong> $supplier_email</p>
                                    </div>
                                    <div class='confirm-buttons'>
                                        <a href='deletesupplier.php?delete=$supplierID' class='btn btn-danger' onclick='return confirm(\"Are you sure you want to delete this supplier?\")'>Delete</a>
                                        <a href='managesupplier.php' class='btn btn-secondary'>Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </body>
            </html>";

            // SQL query to delete the supplier after confirmation
            if (isset($_GET['delete'])) {
                $sql = "DELETE FROM supplier WHERE SupplierID = '$supplierID'";  

                // Execute deletion
                if (mysqli_query($connect, $sql)) {
                    // Success message and redirect to the list of suppliers
                    echo "<script>
                            alert('Supplier deleted successfully!');
                            window.location.href='managesupplier.php';
                          </script>";
                } else {
                    echo "<script>
                            alert('Error occurred during deletion!');
                          </script>";
                }
            }
        } else {
            echo "<script>
                    alert('Supplier not found!');
                    window.location.href='managesupplier.php';
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
