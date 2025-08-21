<?php
session_start(); 

// check if user is logged in
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("Location: login.php"); // back to login if not logged in
    exit(); // 
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

// create connection to database
$conn = new mysqli($servername, $dbUsername, $dbPassword, $dbName);

// check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// initialize  reserved books array
$reservedBooks = [];

// get reserved books for current user
$username = $_SESSION['username'];
$reservedQuery = "SELECT books.ISBN, books.BookTitle, books.Author, books.Edition, books.Year, categories.Category_desc
                  FROM reservedbooks
                  JOIN books ON reservedbooks.ISBN = books.ISBN
                  JOIN categories ON books.Category = categories.CategoryID
                  WHERE reservedbooks.username = ?";

$stmt = $conn->prepare($reservedQuery);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
//fetches each row and adds to rservedbooks array
while ($row = $result->fetch_assoc()) {
    $reservedBooks[] = $row;
}

// handle removal of a reservation
if (isset($_POST['remove']) && !empty($_POST['remove'])) {
    $isbnToRemove = $_POST['remove'];

    // delete the reservation from reservedbooks table
    $removeQuery = "DELETE FROM reservedbooks WHERE username = ? AND ISBN = ?";
    $removeStmt = $conn->prepare($removeQuery);
    $removeStmt->bind_param("ss", $username, $isbnToRemove);

    
        if ($removeStmt->execute()) {
            // update the Rese field to N in books table 
            $updateSql = "UPDATE books SET Rese = 'N' WHERE ISBN = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param("s", $isbnToRemove);
            $updateStmt->execute(); 


        // after successful removal, redirect with success message
            header("Location: reserved.php?success=Reservation removed successfully");
            exit(); 
    }   else {
            $errorMessage = "Failed to remove reservation";
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
    <title>Your Reserved Books</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="reserved_body">

<header> <!--nav links -->
    <nav>
        <ul>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
            <li><a href="search.php">Search Books</a></li>
            <li><a href="reserved.php">Reserved Books</a></li>
            <li><a href="?action=logout" class="btn">Sign Out</a></li>
        </ul>
    </nav>
</header>

<h2>Your Reserved Books</h2>

<!-- Display success message if reservation removed -->
<?php if (isset($_GET['success'])): ?>
    <div class="success-message"><?php echo htmlspecialchars($_GET['success']); ?></div>
<?php endif; ?>

<!-- Display error message if reservation removal failed -->
<?php if (isset($errorMessage)): ?>
    <div class="error-message"><?php echo htmlspecialchars($errorMessage); ?></div>
<?php endif; ?>

<?php if (!empty($reservedBooks)): ?>
    <div class="reserved-books-list">
        <br><br>
        <?php foreach ($reservedBooks as $book): ?>
            <div class="book-item">
                <b><?php echo htmlspecialchars($book['BookTitle']); ?><b><br>
                Author: <?php echo htmlspecialchars($book['Author']); ?><br>
                Edition: <?php echo htmlspecialchars($book['Edition']); ?><br>
                Year: <?php echo htmlspecialchars($book['Year']); ?><br>
                Category: <?php echo htmlspecialchars($book['Category_desc']); ?><br>

                <!-- Form to remove reservation -->
                <form action="reserved.php" method="POST" style="display:inline;">
                    <input type="hidden" name="remove" value="<?php echo $book['ISBN']; ?>">
                    <button type="submit" class="btn">Remove Reservation</button>
                </form>
                <br><br>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p>You have no reserved books at the moment.</p>
    <a href="search.php">Back to search</a>
<?php endif; ?>


<!--common footer -->
<div class = "footer">
        <footer>Jason Gaynor's Library Website</footer>
    </div>


</body>
</html>
