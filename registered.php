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


// database connection settings
$servername = "localhost";   
$username = "root";          
$password = "";              
$database = "website";       

// create  connection to the database
$conn = new mysqli($servername, $username, $password, $database);

// check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body class="registered_body">
    <!-- header section with nav links -->
    <header>
        <nav>
            <ul> 
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Register</a></li>
                <li><a href="search.php">Search Books</a></li>
                <li><a href="reserved.php">Reserved Books</a></li>
                <li><a href="?action=logout" class="btn">Sign Out</a></li> <!-- sign Out link -->
            </ul>
        </nav>
    </header>

    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!<br><br> Choose an option:</h2>
    
    <div class="registered_links">
        <a href="search.php" class="btn">Search Books</a>
        <a href="reserved.php" class="btn">See Your Reserved Books</a>
        <br><br>
        <a href="?action=logout" class="btn">Sign Out</a>
    </div>

    <!-- Footer -->
    <div class="footer">
        <footer>Jason Gaynor</footer>
    </div>
</body>
</html>
