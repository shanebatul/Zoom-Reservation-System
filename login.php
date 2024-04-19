<!DOCTYPE html>
<html>
<head>
    <title>ZOOM RESERVATION SYSTEM</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
       body {
            font-family: Arial, sans-serif;
            background: url(images/zoom.jpg) no-repeat center center fixed;
            background-size: cover;
            position: relative;
            display: flex;
            align-items: left;
            min-height: 100vh;
        }

        .blur-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            backdrop-filter: blur(8px); /* Adjust the blur radius as needed */
        }
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .card {
            background-color: white;
            border-radius: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Add a shadow to the card */
            padding: 20px;
            text-align: center;
            max-width: 350px; /* Set a maximum width for the card */
            width: 80%; /* Adjust the width as needed */
            margin: auto; /* Center the card horizontally */
            height: auto; /* Allow the height to adjust based on content */
            max-height: 85vh; /* Set a maximum height for the card */
            position: relative; /* Position relative to allow absolute positioning */
        }

        .heading {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .blue {
            color: blue;
            text-decoration: underline;
        }

        .input-container {
            text-align: left;
            margin-bottom: 10px;
        }

        input[type="username"],
        input[type="password"] {
            font-size: 16px;
            padding: 5px;
            width: 100%;
            box-sizing: border-box;
        }
        .zoom-logo {
            display: inline-block;
            position: absolute;
            top: 10px;
            left: 10px;
            width: 75px; /* Adjust the width of the logo */
        }

        .zoom-reservation {
            display: inline-block;
            position: absolute;
            top: 10px;
            left: calc(10px + 80px); /* Adjust the position as needed */
            font-size: 24px; /* Adjust the font size as needed */
            margin-top: 15px; /* Remove margin-top */
        }

        .login-button {
            background-color: green;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .login-button:hover {
            background-color: darkgreen;
        }
    </style>
</head>
<body>
    <div class="blur-overlay"></div>
    <div class="container">
    <?php
        include('db.php');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $u_usrname = $_POST['username'] ?? '';
            $u_password = $_POST['password'] ?? '';

            $hashed_password = password_hash($u_password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO tbl_user (u_usrname, u_password) 
                    VALUES ('$u_usrname', '$hashed_password')";

            if ($conn->query($sql) === TRUE) {
                header("Location: calendar.php");
                echo "New record created successfully";
                exit();
            } else {
                echo "Error: " . $conn->error;
            }

            $conn->close();
        }
        ?>
        <div class="card">
            <div class="zoom-logo">
                <img src="images/zoom_logo.jpg" alt="logo" style="width: 100%;">
            </div>
            <div class="zoom-reservation">
                <h2 class="heading">ZOOM RESERVATION</h2>
            </div>
            <form action="login.php" method="post" style="position: relative; margin-top: 70px;"> <!-- Adjusted position and margin-top -->
                <div class="input-container">
                    <input type="username" name="username" id="username" placeholder="Username" required><br><br>
                </div>
                <div class="input-container">
                    <div class="input-group">
                        <input type="password" name="password" id="password" placeholder="Password" required class="form-control">
                        <div class="input-group-append">
                            <span class="input-group-text toggle-password">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <br>
                <button type="login" class="login-button">LOGIN</button><br><br>
            </form>
            <p>Don't have an account? <a href="register.php" class="blue">Sign up</a></p>
        </div>
    </div>

    <!-- Bootstrap JS and Font Awesome JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
    <script>
        $(document).ready(function() {
            // Show/hide password functionality
            $('.toggle-password').click(function() {
                var passwordField = $('#password');
                var passwordFieldType = passwordField.attr('type');
                if (passwordFieldType === 'password') {
                    passwordField.attr('type', 'text');
                    $(this).html('<i class="fas fa-eye-slash"></i>');
                } else {
                    passwordField.attr('type', 'password');
                    $(this).html('<i class="fas fa-eye"></i>');
                }
            });
        });
    </script>
</body>
</html>
