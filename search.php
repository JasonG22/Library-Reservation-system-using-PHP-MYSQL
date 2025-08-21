<?php
session_start(); 

// check if user is logged in
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("Location: login.php"); // back to login if not logged in
    exit(); 
}
//logout
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy(); 
    header("Location: login.php"); // redirect to  login page
    exit();
}

// database connection details
$servername = "localhost"; 
$dbUsername = "root";      
$dbPassword = "";          
$dbName = "website";     

// create a connection to  database
$conn = new mysqli($servername, $dbUsername, $dbPassword, $dbName);

// check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// fetch categories
$categoryQuery = "SELECT Category_desc, CategoryID FROM categories";
$categoryResult = $conn->query($categoryQuery);
$categories = [];
while ($row = $categoryResult->fetch_assoc()) {
    $categories[] = $row;
}

// Initialize search parameters
$title = $author = $category = "";
$books = [];

// initialize search parameters for book search
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture form data for search
    $title = $_POST['title'] ?? '';
    $author = $_POST['author'] ?? '';
    $category = $_POST['category'] ?? '';

    // build SQL query based on search criteria
    $sql = "SELECT books.ISBN, books.BookTitle, books.Author, books.Edition, books.Year, categories.Category_desc, books.Rese
            FROM books
            JOIN categories ON books.Category = categories.CategoryID
            WHERE 1=1";

    // add conditions based on user input allowing for similir search
    if (!empty($title)) {
        $sql .= " AND books.BookTitle LIKE '%" . $conn->real_escape_string($title) . "%'";
    }
    if (!empty($author)) {
        $sql .= " AND books.Author LIKE '%" . $conn->real_escape_string($author) . "%'";
    }
    if (!empty($category)) {
        $sql .= " AND books.Category = " . intval($category);
    }

    // execute query
    $result = $conn->query($sql);

    // fetch books 
    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Books</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="search_body">

<header>
    <nav>
        <ul> <!--nav links-->
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
            <li><a href="search.php">Search Books</a></li>
            <li><a href="reserved.php">Reserved Books</a></li>
            <li><a href="?action=logout" class="btn">Sign Out</a></li>
        </ul>
    </nav>
</header>

<h2>Search for a Book</h2>

<!-- search form -->
<form method="POST" action="search.php">
    <div class="form-group">
        <label for="title">Book Title:</label>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>" placeholder="Search by title">
    </div>
    <br>
    <div class="form-group">
        <label for="author">Author:</label>
        <input type="text" id="author" name="author" value="<?php echo htmlspecialchars($author); ?>" placeholder="Search by author">
    </div>
    <br>
    <div class="form-group">
        <label for="category">Category:</label>
        <select name="category" id="category">
            <option value="">Select a Category</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?php echo $cat['CategoryID']; ?>" <?php echo $cat['CategoryID'] == $category ? 'selected' : ''; ?>>
                    <?php echo $cat['Category_desc']; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <br>
    <button type="submit" class="btn">Search</button>
</form>

<!-- display error message if reservation failed -->
<?php if (isset($errorMessage)): ?>
    <div class="error-message"><?php echo htmlspecialchars($errorMessage); ?></div>
<?php endif; ?>

<h3>Search Results</h3>

<?php if (!empty($books)): ?>
    <div class="book-results">
        <!--display books from array-->
        <?php foreach ($books as $book): ?>
            <div class="book-item">
                <b><?php echo htmlspecialchars($book['BookTitle']); ?></b><br>
                Author: <?php echo htmlspecialchars($book['Author']); ?><br>
                Category: <?php echo htmlspecialchars($book['Category_desc']); ?><br>

              <!--checks if book reserved and if so disables reserev button-->
                <?php if ($book['Rese'] == 'Y'): ?>
                    <button class="reserved-btn" disabled>Reserved</button>
                <?php else: ?>

                    <!-- create a form for reservation -->
                    <form action="after_reserved.php" method="POST">
                        <input type="hidden" name="isbn" value="<?php echo $book['ISBN']; ?>">
                        <button type="submit" class="btn">Reserve</button>
                    </form>
                    <br><br>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php elseif ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
    <p>No books found matching your search criteria.</p>
<?php endif; ?>

<!--common footer -->
<div class = "footer">
        <footer>Jason Gaynor's Library Website</footer>
    </div>


</body>
</html>
