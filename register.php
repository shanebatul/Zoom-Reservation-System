<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: url(images/img_background.jpg) no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .blur-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            backdrop-filter: blur(8px);
        }

        .container {
            position: relative;
            z-index: 1;
            width: 437.5px;
            text-align: center;
        }

        .card {
            background-color: white;
            border-radius: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
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

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            font-size: 16px;
            padding: 5px;
            width: 100%;
            box-sizing: border-box;
        }

        .signup-button {
            background-color: green;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .signup-button:hover {
            background-color: darkgreen;
        }

        .error-message {
            color: red;
            margin-top: 5px;
            margin-bottom: 10px;
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

            $sql = "INSERT INTO tbl_user (u_fname, u_lname, u_usrname, u_division, u_password) 
                    VALUES ('$u_usrname', '$hashed_password')";

            if ($conn->query($sql) === TRUE) {
                header("Location: login.php");
                echo "New record created successfully";
                exit();
            } else {
                echo "Error: " . $conn->error;
            }

            $conn->close();
        }
        ?>
        <div class="card">
            <h2 class="heading">Register</h2>
            <form action="register.php" method="post" onsubmit="return validateForm()">
                <div class="input-container">
                    <input type="text" name="username" placeholder="Username" required><br><br>
                </div>
                <div class="input-container">
                    <input type="password" name="password" id="password" placeholder="Password" required><br><br>
                </div>
                <div class="input-container">
                    <input type="password" name="confirm_password" id="confirmPassword" placeholder="Confirm Password" required><br><br>
                    <input type="checkbox" id="showPassword" onclick="togglePasswordVisibility('password', 'confirmPassword')"> Show Password <br> <br>
                </div>
                <div id="passwordMatchError" class="error-message"></div>
                <label><input type="checkbox" name="agree" required> I agree to all the statements in <a href="terms_of_service.html" class="blue">Terms of Service</a></label><br><br>
                <button type="submit" class="signup-button">Sign Up</button>
            </form>
            <p>Already have an account? <a href="login.php" class="blue">Login</a></p>
        </div>
    </div>
    <script>
        function togglePasswordVisibility(inputId, confirmInputId) {
            var input = document.getElementById(inputId);
            var confirmInput = document.getElementById(confirmInputId);
            if (input.type === "password") {
                input.type = "text";
                confirmInput.type = "text";
            } else {
                input.type = "password";
                confirmInput.type = "password";
            }
        }

        function validateForm() {
            var password = document.getElementById("password").value;
            var confirmPassword = document.getElementById("confirmPassword").value;
            var errorElement = document.getElementById("passwordMatchError");

            if (password !== confirmPassword) {
                errorElement.innerText = "Passwords do not match";
                return false;
            } else {
                errorElement.innerText = "";
                return true;
            }
        }
    </script>
</body>
</html>
