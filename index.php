<?php
// Start the session only if it hasn't started yet
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// If the user is already logged in, redirect them based on their role
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] == 'super_admin') {
        header('Location: /POS/Super_Admin/super_admin_dashboard.php');
        exit();
    } elseif ($_SESSION['role'] == 'admin') {
        header('Location: /POS/Admin/cashier.php');
        exit();
    } else {
        header('Location: /POS/Users/cusView.php');
        exit();
    }
}

// Handle regular login (username and password)
$error = ""; // Initialize error message
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require 'db.php';
    
    // Sanitize the user inputs
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    // Check credentials in the database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    if (!$user) {
        $error = "This username doesn't exist. Please sign up.";
    } else {
        // Check password based on role
        if ($user['role'] == 'super_admin' || $user['role'] == 'admin') {
            if ($password !== $user['password']) {
                $error = "Incorrect password. Please try again.";
            }
        } else {
            if (!password_verify($password, $user['password'])) {
                $error = "Incorrect password. Please try again.";
            }
        }
    
    

        // If no errors, log in the user
        if (empty($error)) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['username'] = $user['username'];
            
            // Redirect based on role
            if ($user['role'] == 'super_admin') {
                header('Location: /POS/Super_Admin/super_admin_dashboard.php');
            } elseif ($user['role'] == 'admin') {
                header('Location: /POS/Admin/cashier.php');
            } else {
                header('Location: /POS/Users/cusView.php');
            }
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS System Login</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    background-image: url('indexpic/login4.jpg'); /* Replace with the actual image filename */
    background-size: cover; /* Ensures the image covers the entire screen */
    background-position: center; /* Centers the image */
    background-repeat: no-repeat; /* Prevents repeating */
    background-attachment: fixed; /* Keeps the background fixed when scrolling */
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
            padding-right: 70px;
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

        
/* Centered Login Box */
.container {
    display: flex;
    padding-right: 100px;
    justify-content: right;
    align-items: center;
    height: 100vh;
}


/* Login Box */
.login-box {
    text-align: center;
    padding: 30px;
    padding-right: 10px;
    /* background-color: rgba(255, 202, 148, 0.493); */
    border-radius: 10px;
    /* box-shadow: 0 4px 8px rgba(0, 0, 0, 0.373); */
    width: 350px;
    height: 400px;
    
}

/* Login Heading */

.login-box h1 {
    margin-bottom: -2px;
    color: rgba(186, 60, 2);
    font-size: 40px; /* Adjust the font size as needed */
    font-weight: bold; /* Optional: Make it bolder */
    
    
}



/* Input Fields */
.login-box input[type="text"], 
.login-box input[type="password"] {
    width: 400px;
    padding: 10px;
    margin: 10px 0;
    border-radius: 5px;
    border: 1px solid rgba(186, 60, 2); 
    background-color: #F0F4F8;
    box-sizing: border-box; /* Ensures width includes padding and border */
}

/* Submit Button */
.login-box button {
    width: 400px;
    padding: 10px;
    background-color: rgba(186, 60, 2); /* Dark teal */
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin-top: 10px;
    box-sizing: border-box; /* Ensures width includes padding and border */
}

/* Hover Effect for Button */
.login-box button:hover {
    background-color: #EC6D10; /* Lighter teal */
}

/* Social Login Buttons */
.social-login {
    margin-top: 20px;
}

.social-btn {
    padding: 10px 10px;
    background-color: #EC6D10; /* Match card gradient */
    color: white;
    border-radius: 5px;
    margin: 5px;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
}

.social-btn:hover {
    background-color: rgba(186, 60, 2);
}

/* Signup Link */
.signup-link {
    margin-top: 20px;
    display: block;
    text-align: left;
    color: white;
}

.signup-link a {
    text-decoration: none;
    color: white;
    font-weight: bold;
}

/* Enhanced Error Message with RGBA Background */
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

    


    <div class="container">
    <?php if (!empty($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <div class="login-box">
    <h1>Login</h1>
    <br>

    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <br>
        <button type="submit">Login</button>
    </form>

    <div class="signup-link">
        <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
    </div>
</div>
</div>

<script>
    // Wait for the DOM to load
    document.addEventListener("DOMContentLoaded", function () {
        const errorBox = document.querySelector('.error');
        if (errorBox) {
            setTimeout(() => {
    errorBox.style.opacity = '0';
    setTimeout(() => {
        errorBox.style.display = 'none';
    }, 500); // Wait for the fade to finish before hiding
}, 2000);
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