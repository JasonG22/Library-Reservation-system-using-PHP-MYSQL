        <?php
        
        // database connection settings
        $servername = "localhost";   
        $username = "root";         
        $password = "";              
        $database = "website";       

        // create a connection to the database
        $conn = new mysqli($servername, $username, $password, $database);

        // check the connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // error variable empty initially
        $error = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // get user inputs from the form
            $username = $conn->real_escape_string(trim($_POST['username']));
            $password = $conn->real_escape_string(trim($_POST['password']));
            $confirm_password = $conn->real_escape_string(trim($_POST['confirm_password']));
            $first_name = $conn->real_escape_string(trim($_POST['first_name']));
            $last_name = $conn->real_escape_string(trim($_POST['last_name']));
            $address_line1 = $conn->real_escape_string(trim($_POST['address_line1']));
            $address_line2 = $conn->real_escape_string(trim($_POST['address_line2']));
            $city = $conn->real_escape_string(trim($_POST['city']));
            $telephone = $conn->real_escape_string(trim($_POST['telephone']));
            $mobile = $conn->real_escape_string(trim($_POST['mobile']));

            // validate inputs to meet criteria using multipel ifs
            if (empty($username) || empty($password) || empty($confirm_password) || empty($first_name) ||
                empty($last_name) || empty($address_line1) || empty($city) || empty($telephone) || empty($mobile)) 
                {
                    $error = "All fields must be entered.";
                } 
                //check if password confirmation = password
            elseif ($password !== $confirm_password)  
                {
                $error = "Passwords do not match!";
                }  
                //check mobile number is numeric 
            elseif (!is_numeric($mobile)) 
                {
                    $error = "Mobile number must be numeric ";
                }
                //check mobile number is 10 digits
            elseif( strlen($mobile) != 10)
                {
                    $error = "Mobile Number must be 10 digits";
                }
                //check password is at least 6 characters
            elseif (strlen($password) < 6) 
                {
                    $error = "Password must be at least 6 characters long.";
                }
                
                // check for duplicate username  
            else 
                {
                    $check_user_sql = "SELECT * FROM users WHERE username = '$username'";
                    $result = $conn->query($check_user_sql);
                
                    
                if ($result->num_rows > 0) 
                    {
                        $error = "Username already exists!";
                    } 
                else {
                        // prepare & execute sql query
                        $sql = "INSERT INTO users (username, password, first_name, last_name, address_line1, address_line2, city, telephone, mobile) 
                            VALUES ('$username', '$password', '$first_name', '$last_name', '$address_line1', '$address_line2', '$city', '$telephone', '$mobile')";
                        //display success with inline style
                    if ($conn->query($sql) === TRUE) 
                    {
                        echo "<p style='color:green;'>New user registered successfully!</p>";
                    } 
                    else {
                            $error = "Error: " . $conn->error;
                        }
                }
            }
        }

        // close  database connection
        $conn->close();
        ?>

        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>User Registration</title>
            <link rel="stylesheet" href="style.css"> 
        </head>
        <body class="register_body">
            <!-- header section with nav links -->
            <header>
                <nav>
                    <ul> 
                        <li><a href="login.php">Login</a></li>
                        <li><a href="register.php">Register</a></li>
                        <li><a href="search.php">Search Books</a></li>
                        <li><a href="reserved.php">Reserved Books</a></li>
                    </ul>
                </nav>
            </header>
            <!--user registration form-->
            <h2>User Registration</h2>
            <?php if (!empty($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
            <form action="register.php" method="post">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" ><br><br>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" ><br><br>

                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" ><br><br>

                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name" ><br><br>

                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name" ><br><br>

                <label for="address1">Address Line 1:</label>
                <input type="text" id="address1" name="address_line1" ><br><br>

                <label for="address2">Address Line 2:</label>
                <input type="text" id="address2" name="address_line2"><br><br>

                <label for="city">City:</label>
                <input type="text" id="city" name="city" ><br><br>

                <label for="telephone">Telephone:</label>
                <input type="text" id="telephone" name="telephone"><br><br>

                <label for="mobile">Mobile:</label>
                <input type="text" id="mobile" name="mobile" ><br><br>

            <input type="submit" value="Register">
        </form>
        <p>Already have an account? <br><a href="login.php">Login here</a>.</p>
        <br><br>

        <!--common footer -->
    <div class = "footer">
        <footer>Jason Gaynor's Library Website</footer>
    </div>

    
    </body>
    </html>
