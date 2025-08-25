<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Search Purchase Details</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css?<?php echo time(); ?>">
</head>
<body>
    <hr>
    <div class="search_text">
        <h1>Purchase List</h1>
    </div>

    <!-- Search Form -->
    <form action="search_purchasebook_process.php" method="GET">
        <div class="search">
            <input type="text" id="search-input" name="search" placeholder="Enter any keyword to search purchase">
            <input type="submit" name="submit" value="Search">
        </div>
    </form>
</body>
</html>
