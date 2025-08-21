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
    header("Location: login.php"); // redirect to login page
    exit();
}

// database connection details
$servername = "localhost"; 
$dbUsername = "root";      
$dbPassword = "";          
$dbName = "website";       

// Create a connection to  database
$conn = new mysqli($servername, $dbUsername, $dbPassword, $dbName);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the ISBN from the POST request
$isbn = $_POST['isbn'] ?? '';

// If ISBN is not provided, redirect back to search page
if (empty($isbn)) {
    header("Location: search.php");
    exit();
}

// Check if the book is already reserved
$sql = "SELECT * FROM reservedbooks WHERE ISBN = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $isbn);
$stmt->execute();
$result = $stmt->get_result();

// If the book is already reserved, show an error message
if ($result->num_rows > 0) {
    $errorMessage = "This book has already been reserved.";
} else {
    // Reserve the book for the user
    $username = $_SESSION['username'];
    $dateOfReservation = date('Y-m-d H:i:s'); // Current date and time

    $insertSql = "INSERT INTO reservedbooks (username, ISBN, DateOfReservation) VALUES (?, ?, ?)";
    $insertStmt = $conn->prepare($insertSql);
    $insertStmt->bind_param("sss", $username, $isbn, $dateOfReservation);

    if ($insertStmt->execute()) {
        $updateSql = "UPDATE books SET Rese = 'Y' WHERE ISBN = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("s", $isbn);
        $updateStmt->execute(); // Execute the update query


        $successMessage = "Book reserved successfully!";
    } else {
        $errorMessage = "Failed to reserve the book. Please try again later.";
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
    <title>After Reservation</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="after_reserved_body">

<header>
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

<h2>Reservation Status</h2>

<?php if (isset($successMessage)): ?>
    <div class="success-message"><?php echo htmlspecialchars($successMessage); ?></div>
<?php elseif (isset($errorMessage)): ?>
    <div class="error-message"><?php echo htmlspecialchars($errorMessage); ?></div>
<?php endif; ?>

<br>
<a href="search.php" class="btn">Back to Search</a>
<br>
<a href="reserved.php">See your reserved Books</a>

<!--common footer -->
<div class = "footer">
        <footer>Jason Gaynor's Library Website</footer>
    </div>


</body>
</html>
