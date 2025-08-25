<?php
session_start();
include('dbconnect.php');

if (isset($_SESSION['SID'])) {
    $SID = $_SESSION['SID'];
    $Sname = $_SESSION['SName'];
    $SEmail = $_SESSION['SEmail'];

    // Query the staff details
    $query = "SELECT * FROM staff WHERE StaffID = '$SID'";
    $result = mysqli_query($connect, $query);
    $staff = mysqli_fetch_assoc($result);

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = mysqli_real_escape_string($connect, $_POST['name']);
        $email = mysqli_real_escape_string($connect, $_POST['email']);
        $contactno = mysqli_real_escape_string($connect, $_POST['contactno']);
        $address = mysqli_real_escape_string($connect, $_POST['address']);
        $password = mysqli_real_escape_string($connect, $_POST['password']);

        // Hash the password if provided
        if (!empty($password)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);  // Hash the password
        }

        // Update query for profile details
        $updateQuery = "UPDATE staff SET StaffName='$name', Email='$email', ContactNo='$contactno', Address='$address'";

        // If password is provided, update it with the hashed password
        if (!empty($password)) {
            $updateQuery .= ", Password='$hashedPassword'";
        }

        // Add the WHERE clause for specific staff member
        $updateQuery .= " WHERE StaffID = '$SID'";

        // Execute the query to update profile
        if (mysqli_query($connect, $updateQuery)) {
            $_SESSION['SName'] = $name;
            $_SESSION['SEmail'] = $email;
            echo "<script>alert('Profile updated successfully!');</script>";
            echo "<script>window.location='admin_profile.php';</script>";
        } else {
            echo "<script>alert('Error updating profile. Please try again.');</script>";
        }
    }
} else {
    echo "<script>window.alert('Please Login');</script>";
    echo "<script>window.location='StaffRegister.php';</script>";
}
?>
