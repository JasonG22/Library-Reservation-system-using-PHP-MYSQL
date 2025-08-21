    <?php
    session_start();
    

    // database connection details
    $servername = "localhost"; 
    $dbUsername = "root";
    $dbPassword = "";
    $dbName = "website"; 

    // Connect to  database
    $conn = new mysqli($servername, $dbUsername, $dbPassword, $dbName);

    // check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $error = ""; // initialize error message variable

    // handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // check if username and password fields are set
        if (isset($_POST['username'], $_POST['password'])) {
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);
            // Prepare sql query
            $stmt = $conn->prepare("SELECT username FROM users WHERE username = ? AND password = ?");
            //bind $username and $passowrd to ??, ss indicating theyre strings
            $stmt->bind_param("ss", $username, $password); 
            $stmt->execute(); // execute statement  
            $result = $stmt->get_result();

            
            //check if result matches 1 row ie only one user record matches from database
            if ($result->num_rows == 1) {
                $_SESSION['username'] = $username; // store username in session
                header("Location: registered.php"); 
                exit();

            
            } else {
                $error = "Invalid username or password."; // set $error vairable initialised earlier
            }
            $stmt->close();
        }
    }
    $conn->close();
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <link rel="stylesheet" href="style.css"> 
    </head>
    <body class="login_body">
        <!-- comon header section with nav links -->
        <header>
            <nav>
                <ul> <!--nav links-->
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                    <li><a href="search.php">Search Books</a></li>
                    <li><a href="reserved.php">Reserved Books</a></li>
                </ul>
            </nav>
        </header>


        
        <h2>Login</h2>
        <!-- checks if form was submitted and that the error variable isnt empty-->
        <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <!-- create form-->
        <form method="POST" action="login.php">

            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
        <p>Don't have an account? <br><a href="register.php">Register here</a>.</p>
        <!--common footer -->
        <div class = "footer">
            <footer>Jason Gaynor's Library Website</footer>
        </div>

    </body>
        
        


    </body>
    </html>
