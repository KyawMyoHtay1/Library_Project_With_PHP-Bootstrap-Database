<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Search Category</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css?<?php echo time(); ?>">
</head>
<body>
    <hr>
    <div class="search_text">
        <h1>Category List</h1>
    </div>

    <!-- Search Form -->
    <form action="search_category_process.php" method="GET">
        <div class="search">
            <input type="text" id="search-input" name="search" placeholder="Enter any keyword to search category">
            <input type="submit" name="submit" value="Search">
        </div>
    </form>
</body>
</html>
