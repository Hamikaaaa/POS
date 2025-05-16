<?php
session_start();
require __DIR__ . '/db.php';

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if username exists
    $checkUser = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $checkUser->bind_param('s', $username);
    $checkUser->execute();
    $checkUser->store_result();
    if ($checkUser->num_rows > 0) {
        $errors[] = "Username already exists. Please choose another.";
    }

    // Check password match
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    // File upload
    $profilePicPath = '';
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    if (!empty($_FILES['profile_picture']['name']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $fileInfo = pathinfo($_FILES['profile_picture']['name']);
        $extension = strtolower($fileInfo['extension']);

        if (!in_array($extension, $allowedExtensions)) {
            $errors[] = "Only JPG, JPEG, PNG, and GIF files are allowed.";
        } else {
            $uploadDir = __DIR__ . '/Super_Admin/uploads/profilepic/';
            $filename = uniqid() . "." . $extension;
            $profilePicPath = $uploadDir . $filename;

            if (!move_uploaded_file($_FILES['profile_picture']['tmp_name'], $profilePicPath)) {
                $errors[] = "Failed to upload profile picture.";
            }
        }
    }

    // If no errors, insert into database
    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $relativePath = 'Super_Admin/uploads/profilepic/' . $filename;

        $stmt = $conn->prepare("INSERT INTO users (username, email, phone, password, profile_pic) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('sssss', $username, $email, $phone, $hashedPassword, $relativePath);

        if ($stmt->execute()) {
            $success = "Registration successful! You can now <a href='index.php'>sign in</a>.";
        } else {
            $errors[] = "An error occurred. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - POS System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('indexpic/register4.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            margin: 0;
            padding: 0;
        }

         /* Navbar Styling */
         .navbar {
            position: fixed;
            top: 0px;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 20px;
            z-index: 1000; /* Ensures it's always above other elements */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5); /* Transparent black shadow */
            background-color: 0 4px 6px rgba(0, 0, 0, 0.8);
        }

        .navbar img {
            height: 40px;
            width: auto;
            padding-left: 20px;
        }

        .nav-links {
            display: flex;
            gap: 20px;
            padding-right: 75px;
        }

        .nav-links a {
            text-decoration: none;
            font-size: 18px;
            color: white;
            transition: color 0.3s ease-in-out;
            font-weight: bold;
        }

        .nav-links a:hover {
            color: #ec6d10;
        }


        .signup-form {
            position: absolute;
            right: 30px;
            top: 50%;
            transform: translateY(-50%);
            width: 400px;
            padding: 30px;
            border-radius: 15px;
        }

        .signup-form h1 {
            color: rgba(186, 60, 2);
            font-size: 30px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 15px;
        }

        .signup-form input,
        .signup-form button,
        .signup-form .form-group {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid rgba(186, 60, 2);
            background-color: #F9F9F9;
            font-size: 16px;
            transition: 0.3s ease-in-out;
            box-sizing: border-box;
        }

        .signup-form .form-group {
            display: flex;
            flex-direction: column;
        }

        .signup-form input:focus {
            outline: none;
            border-color: rgba(186, 60, 2);
            box-shadow: 0 0 5px rgba(186, 60, 2, 0.5);
        }

        .signup-form button {
            background-color: rgba(186, 60, 2);
            color: white;
            border: none;
            cursor: pointer;
            transition: 0.3s ease-in-out;
        }

        .signup-form button:hover {
            background-color: #EC6D10;
            transform: scale(1.05);
        }

        .login-link {
            text-align: left;
            margin-top: 10px;
        }

        .login-link p {
            color: white;
        }

        .login-link a {
            color: white;
            font-weight: bold;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .password-container {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #DBA979;
        }


        input[type="file"] {
            padding: 12px;
            border: 1px solid rgba(186, 60, 2);
            border-radius: 5px;
            background-color: white;
            margin: 10px 0;
        }

        /* Error and Success Messages */
.error, .success {
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    padding: 12px 24px;
    border-radius: 10px;
    font-size: 16px;
    z-index: 1000;
    box-shadow: 0 4px 10px rgba(0,0,0,0.3);
    text-align: center;
    max-width: 90%;
}

.error {
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    background-color: rgb(248, 240, 215);
    color:  #ec6d10;
    padding: 12px 24px;
    border-radius: 4px;
    font-size: 16px;
    font-weight: 500;
    z-index: 1000;
    box-shadow: 0 6px 12px rgba(0,0,0,0.25);
    display: flex;
    align-items: center;
    gap: 10px;
    transition: opacity 0.5s ease-out, transform 0.3s ease-in-out;
    border: 1px solid #fff;
}

/* Optional Icon Styling (emoji-based) */
.error::before {
    content: "⚠️";
    font-size: 20px;
}

/* Success message style */
.success {
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    background-color: rgba(236, 109, 16, 0.9); /* #2db347 in RGBA with 90% opacity */
    color: white;
    padding: 12px 24px;
    border-radius: 12px;
    font-size: 16px;
    font-weight: 500;
    z-index: 1000;
    box-shadow: 0 6px 12px rgba(0,0,0,0.25);
    display: flex;
    align-items: center;
    gap: 10px;
    transition: opacity 0.5s ease-out, transform 0.3s ease-in-out;
    border: 1px solid #fff;
}

/* Optional Icon Styling (emoji-based) */
.success::before {
    font-size: 20px;
}

.fade-message {
    opacity: 1;
    transition: opacity 0.5s ease-in-out;
}

.fade-out {
    opacity: 0;
}

#fade-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7); /* black with transparency */
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.1s;
        z-index: 9999;
    }

    #fade-overlay.active {
        opacity: 1;
        pointer-events: auto;
    }




    </style>
</head>
<body>
<div class="navbar">
    <a href="landing.php">
        <img src="imgmain/logo1.1.png" alt="SukariKo Logo">
    </a>
    <div class="nav-links">
        <a href="landing.php">Home</a>
        <a href="aboutUs.php">About Us</a>
        <a href="contactUs.php">Contact Us</a>
        <a href="index.php">Signin</a>
        <a href="signup.php">Signup</a>
    </div>
</div>

    <?php if (!empty($errors)): ?>
    <div class="error fade-message">
        <?php foreach ($errors as $error): ?>
            <p><?= htmlspecialchars($error) ?></p>
        <?php endforeach; ?>
    </div>
<?php elseif (!empty($success)): ?>
    <div class="success fade-message"><?= $success ?></div>
<?php endif; ?>


    
    <div class="signup-form">
        <h1>Create an Account!</h1>

        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="tel" name="phone" placeholder="Phone" required>

            <div class="password-container">
                <input type="password" id="password" name="password" placeholder="Password" required>
                <span class="toggle-password" onclick="togglePassword('password')">
                    <i id="password-icon" class="fas fa-eye"></i>
                </span>
            </div>

            <div class="password-container">
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
                <span class="toggle-password" onclick="togglePassword('confirm_password')">
                    <i id="confirm-password-icon" class="fas fa-eye"></i>
                </span>
            </div>

            <div class="form-group">
                <label>Upload Profile Picture:</label>
                <input type="file" name="profile_picture" accept="image/*" required>
            </div>

            <button type="submit">Create Account</button>
        </form>

        <div class="login-link">
            <p>Already have an account? <a href="index.php">Sign In</a></p>
        </div>
    </div>

    <script>
    function togglePassword(id) {
        var passwordField = document.getElementById(id);
        var icon = document.getElementById(id + '-icon');

        if (passwordField.type === "password") {
            passwordField.type = "text";
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            passwordField.type = "password";
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }

    // Auto-fade error/success messages after 2 seconds
    window.addEventListener('DOMContentLoaded', () => {
        const fadeMessage = document.querySelector('.fade-message');

        if (fadeMessage) {
            setTimeout(() => {
                fadeMessage.classList.add('fade-out');
            }, 2000);

            // Optional: remove the element entirely after fade-out
            setTimeout(() => {
                fadeMessage.style.display = 'none';
            }, 2500); // Wait until after fade completes (0.5s fade + 2s delay)
        }
    });

    //fade for page
    document.addEventListener("DOMContentLoaded", function () {
        const links = document.querySelectorAll(".nav-links a, .navbar a[href='landing.php']");
        const overlay = document.getElementById("fade-overlay");

        links.forEach(link => {
            link.addEventListener("click", function (e) {
                const href = this.getAttribute("href");
                if (href && href !== "#") {
                    e.preventDefault(); // Prevent immediate navigation
                    overlay.classList.add("active"); // Trigger fade to black
                    setTimeout(() => {
                        window.location.href = href;
                    }, 100); // Match the CSS transition duration
                }
            });
        });
    });
</script>
<div id="fade-overlay"></div>

</body>
</html>
