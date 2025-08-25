<!-- Start: Search Section -->
<section class="search-filters">
    <div class="container">
        <div class="filter-box">
            <h3>What are you looking for at the library?</h3>
            <form action="search_borrowbook.php" method="get">
                <div class="col-md-5 col-sm-6">
                    <div class="form-group">
                        <label class="sr-only" for="keywords">Search by Keyword</label>
                        <input class="form-control" placeholder="Search by Keyword" id="keywords" name="keywords" type="text" value="<?php echo isset($_GET['keywords']) ? htmlspecialchars($_GET['keywords']) : ''; ?>">
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="form-group">
                        <select name="category" id="category" class="form-control">
                            <option>Choose Category</option>
                            <?php while ($row = mysqli_fetch_assoc($categoryResult)) { ?>
                                <option value="<?php echo $row['CategoryName']; ?>" <?php echo (isset($_GET['category']) && $_GET['category'] == $row['CategoryName']) ? 'selected' : ''; ?>>
                                    <?php echo $row['CategoryName']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </form>
        </div>
    </div>
</section>
<!-- End: Search Section -->