<?php
session_start();

// Check if user is already logged in
if(isset($_SESSION['user_id'])) {
    // User is logged in, show the main content
    $showMainContent = true;
} else {
    // User is not logged in, show login form
    $showMainContent = false;
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "eventwale";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error_message = "";
$success_message = "";

// Handle Login
if(isset($_POST['login'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    
    if(empty($email) || empty($password)) {
        $error_message = "All fields are required";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if(password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['first_name'] = $user['first_name'];
                $showMainContent = true;
            } else {
                $error_message = "Invalid email or password";
            }
        } else {
            $error_message = "Invalid email or password";
        }
        
        $stmt->close();
    }
}

// Handle Registration
if(isset($_POST['register'])) {
    $firstName = filter_var($_POST['firstName'], FILTER_SANITIZE_STRING);
    $lastName = filter_var($_POST['lastName'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    
    if(empty($firstName) || empty($lastName) || empty($email) || empty($phone) || empty($password) || empty($confirmPassword)) {
        $error_message = "All fields are required";
    } elseif($password !== $confirmPassword) {
        $error_message = "Passwords do not match";
    } elseif(strlen($password) < 8) {
        $error_message = "Password must be at least 8 characters long";
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format";
    } elseif(!preg_match("/^[0-9]{10}$/", $phone)) {
        $error_message = "Invalid phone number format";
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        if($stmt->get_result()->num_rows > 0) {
            $error_message = "Email already exists";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, phone, password) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $firstName, $lastName, $email, $phone, $hashed_password);
            
            if($stmt->execute()) {
                $success_message = "Registration successful! Please login.";
            } else {
                $error_message = "Error creating account. Please try again.";
            }
        }
        $stmt->close();
    }
}

if(!$showMainContent):
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management - Login/Register</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-image: url('images/background.jpg');
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
            overflow: hidden;
            width: 100%;
            max-width: 900px;
            display: flex;
        }

        .form-container {
            flex: 1;
            padding: 40px;
        }

        .form-container h2 {
            color: #333;
            margin-bottom: 30px;
            font-size: 24px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #555;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .form-group input:focus {
            border-color: #FFDAB9;
            outline: none;
        }

        .form-group .toggle-password {
            position: absolute;
            right: 10px;
            top: 38px;
            cursor: pointer;
            color: #666;
            padding: 5px;
        }

        .form-group .toggle-password:hover {
            color: #333;
        }

        .password-requirements {
            margin-top: 5px;
            font-size: 12px;
            color: #666;
            padding: 10px;
            border-radius: 5px;
            background-color: #f8f8f8;
        }

        .password-requirements p {
            margin: 3px 0;
            display: flex;
            align-items: center;
        }

        .requirement-icon {
            margin-right: 5px;
            font-size: 12px;
        }

        .invalid {
            color: #ff4444;
        }

        .valid {
            color: #00C851;
        }

        .btn {
            width: 100%;
            padding: 12px;
            background: #FFDAB9;
            border: none;
            border-radius: 8px;
            color: #333;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn:hover {
            background: #fdcb9f;
        }

        .divider {
            width: 1px;
            background: #eee;
            margin: 20px 0;
        }

        .error-message {
            color: #ff4444;
            margin-bottom: 20px;
            text-align: center;
            padding: 10px;
            background-color: #ffe6e6;
            border-radius: 5px;
        }

        .success-message {
            color: #00C851;
            margin-bottom: 20px;
            text-align: center;
            padding: 10px;
            background-color: #e6ffe6;
            border-radius: 5px;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }
            .divider {
                width: 100%;
                height: 1px;
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Login Form -->
        <div class="form-container">
            <h2>Login to Event Management</h2>
            <?php if($error_message): ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>
            <?php if($success_message): ?>
                <div class="success-message"><?php echo $success_message; ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="login-email">Email</label>
                    <input type="email" id="login-email" name="email" required placeholder="Enter your email">
                </div>
                <div class="form-group">
                    <label for="login-password">Password</label>
                    <input type="password" id="login-password" name="password" required placeholder="Enter your password">
                    <i class="toggle-password fas fa-eye-slash" onclick="togglePassword('login-password', this)"></i>
                </div>
                <button type="submit" name="login" class="btn">Login</button>
            </form>
        </div>

        <div class="divider"></div>

        <!-- Registration Form -->
        <div class="form-container">
            <h2>Create New Account</h2>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="firstName">First Name</label>
                    <input type="text" id="firstName" name="firstName" required placeholder="Enter your first name">
                </div>
                <div class="form-group">
                    <label for="lastName">Last Name</label>
                    <input type="text" id="lastName" name="lastName" required placeholder="Enter your last name">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required placeholder="Enter your email">
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" required placeholder="Enter 10-digit phone number">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required placeholder="Minimum 8 characters" oninput="checkPasswordStrength(this.value)">
                    <i class="toggle-password fas fa-eye-slash" onclick="togglePassword('password', this)"></i>
                    <div class="password-requirements">
                        <p id="length"><i class="requirement-icon fas fa-circle"></i>Minimum 8 characters</p>
                        <p id="capital"><i class="requirement-icon fas fa-circle"></i>At least one capital letter</p>
                        <p id="number"><i class="requirement-icon fas fa-circle"></i>At least one number</p>
                        <p id="symbol"><i class="requirement-icon fas fa-circle"></i>At least one special character (!@#$%^&*)</p>
                    </div>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Confirm Password</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" required placeholder="Re-enter your password" oninput="validateConfirmPassword()">
                    <i class="toggle-password fas fa-eye-slash" onclick="togglePassword('confirmPassword', this)"></i>
                </div>
                <button type="submit" name="register" class="btn">Register</button>
            </form>
        </div>
    </div>

    <script>
        function togglePassword(inputId, icon) {
            const input = document.getElementById(inputId);
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            } else {
                input.type = "password";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            }
        }

        function checkPasswordStrength(password) {
            // Check length
            const lengthValid = password.length >= 8;
            updateRequirement('length', lengthValid);

            // Check capital letter
            const capitalValid = /[A-Z]/.test(password);
            updateRequirement('capital', capitalValid);

            // Check number
            const numberValid = /[0-9]/.test(password);
            updateRequirement('number', numberValid);

            // Check special character
            const symbolValid = /[!@#$%^&*]/.test(password);
            updateRequirement('symbol', symbolValid);

            // Update confirm password validation if it exists
            const confirmPassword = document.getElementById('confirmPassword');
            if (confirmPassword && confirmPassword.value) {
                validateConfirmPassword();
            }
        }

        function updateRequirement(requirementId, isValid) {
            const requirement = document.getElementById(requirementId);
            const icon = requirement.querySelector('.requirement-icon');
            
            if (isValid) {
                requirement.classList.remove('invalid');
                requirement.classList.add('valid');
                icon.classList.remove('fa-circle');
                icon.classList.add('fa-check-circle');
            } else {
                requirement.classList.remove('valid');
                requirement.classList.add('invalid');
                icon.classList.remove('fa-check-circle');
                icon.classList.add('fa-circle');
            }
        }

        function validateConfirmPassword() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const confirmInput = document.getElementById('confirmPassword');
            
            if (password === confirmPassword) {
                confirmInput.style.borderColor = '#00C851';
            } else {
                confirmInput.style.borderColor = '#ff4444';
            }
        }

        // Add event listener for confirm password
        document.getElementById('confirmPassword').addEventListener('input', validateConfirmPassword);
    </script>
</body>
</html>
<?php 
else:
    // Database connection settings
    $servername = "localhost";
    $username = "root";       // Default XAMPP username
    $password = "";           // Default XAMPP password is empty
    $database = "eventwale"; // Replace with your database name

    // Create connection
    $conn = new mysqli($servername, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch events from the database
    $sql = "SELECT * FROM mainpage";
    $result = $conn->query($sql);

    $sql2 = "SELECT * FROM wedding";
    $result2 = $conn->query($sql2);

    $sql3 = "SELECT * FROM birthday";
    $result3 = $conn->query($sql3);

    $sql4 = "SELECT * FROM aniversery";
    $result4 = $conn->query($sql4);

    $sql5 = "SELECT * FROM freshers";
    $result5 = $conn->query($sql5);

    $sql6 = "SELECT * FROM pooja";
    $result6 = $conn->query($sql6);

    $sql7 = "SELECT * FROM naming";
    $result7 = $conn->query($sql7);

    $sql8 = "SELECT * FROM grihprivesh";
    $result8 = $conn->query($sql8);

    $sql9 = "SELECT * FROM exhibition";
    $result9 = $conn->query($sql9);

    $sql10 = "SELECT * FROM babyshower";
    $result10 = $conn->query($sql10);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        
        /* Navbar styles */
        .navbar {
            background-color: #FFDAB9;
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            padding: 1rem 2rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            z-index: 1000;
        }

        .nav-links {
            display: flex;
            justify-content: space-between;
            align-items: center;
            list-style: none;
            gap: 3rem;
            padding: 0;
        }

        .nav-links .main-links {
            display: flex;
            gap: 2rem;
        }

        .nav-links  {
            display: flex;
            gap: 1rem;
        }

        .nav-links a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            font-size: 1.1rem;
            transition: color 0.3s ease;
            position: relative;
        }

        .nav-links a:hover {
            color: #000;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -4px;
            left: 0;
            background-color: #333;
            transition: width 0.3s ease;
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        .nav-links .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .nav-links .user-name {
            color: #333;
            font-weight: 600;
        }

        .nav-links .logout-btn {
            background: #ff4444;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .nav-links .logout-btn:hover {
            background: #cc0000;
        }

        /* Adjust hero section to account for navbar */
        .hero-section {
            min-height: 100vh; /* Changed from height to min-height */
            width: 100%;
            background-image: linear-gradient(rgba(255, 192, 203, 0.192), rgba(255, 255, 255, 0.205)), url('images/background.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed; /* Makes background fixed while scrolling */
            display: flex;
            align-items: center;
            justify-content: center;
            color: #333;
            text-align: center;
            padding-top: 60px;
        }

        .hero-content {
            max-width: 800px;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.562); /* Semi-transparent white background */
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .hero-content h1 {
            font-size: 3.5rem;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: black; /* Changed to black */
        }

        .hero-content p {
            font-size: 1.2rem;
            margin-bottom: 30px;
            line-height: 1.6;
            color: black; /* Changed to black */
            font-weight: 500; /* Added for better visibility */
        }

        .cta-button {
            display: inline-block;
            padding: 15px 30px;
            background-color: #FFDAB9; /* Hot pink button */
            color: rgb(0, 0, 0);
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .cta-button:hover {
            background-color: #fdcb9f; /* Deeper pink on hover */
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.4s ease;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transform-style: preserve-3d;
            perspective: 1000px;
        }

        .card:hover {
            transform: translateY(-10px) rotateX(5deg);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }

        .card img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .card:hover img {
            transform: scale(1.1);
        }

        .card-content {
            padding: 20px;
            background: rgba(255, 255, 255, 0.95);
            transform: translateY(0);
            transition: transform 0.3s ease;
        }

        .card:hover .card-content {
            transform: translateY(-5px);
        }

        .card-content h3 {
            color: #333;
            margin-bottom: 10px;
            font-size: 1.5rem;
            opacity: 1;
            transform: translateY(0);
            transition: all 0.3s ease;
        }

        .card-content p {
            color: #666;
            font-size: 1rem;
            opacity: 1;
            transform: translateY(0);
            transition: all 0.3s ease;
            line-height: 1.5;
        }

        /* Additional content sections */
        .content-section {
            min-height: 100vh;
            padding: 80px 20px;
            background-image: linear-gradient(rgba(255, 192, 203, 0.192), rgba(255, 255, 255, 0.205)), url('images/bg5.jpg');
            position: relative;
            z-index: 1;
        }

        .content-section:nth-child(even) {
            background-color: #FFF5EE;
        }

        .section-container {
            max-width: 1200px;
            margin: 0 auto;
            text-align: center;
        }

        .section-title {
            color: black;
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.5rem;
        }

        .section-content {
            color: black; /* Changed to black */
            line-height: 1.6;
            font-size: 1.1rem;
            font-weight: 500; /* Added for better visibility */
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .section-content p {
            margin-bottom: 20px;
            text-align: justify;
        }

        .section-content .highlight {
            font-size: 1.2rem;
            color: #FF69B4;
            font-weight: 600;
            margin-bottom: 30px;
            text-align: center;
        }

        .ceremony-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Wedding section specific styles */
        .wedding-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        @media (max-width: 992px) {
            .wedding-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .wedding-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Section separator styles */
        .section-separator {
            height: 200px;
            background-image: url('images/background.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            position: relative;
        }

        .section-separator::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 218, 185, 0.3); /* Light peach overlay */
        }

        /* Smooth Scroll Behavior */
        html {
            scroll-behavior: smooth;
        }

        /* Contact form styles */
        .contact-container {
            display: flex;
            justify-content: space-between;
            gap: 60px;
            max-width: 1400px;
            margin: 0 auto;
            padding: 30px;
        }

        .contact-left {
            flex: 1;
        }

        .contact-right {
            flex: 1;
            background: rgb(250, 249, 248);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .contact-numbers {
            list-style: none;
        }

        .contact-numbers li {
            margin-bottom: 20px;
            padding: 15px;
            background: rgba(255, 218, 185, 0.3);
            border-radius: 10px;
            transition: transform 0.3s ease;
        }

        .contact-numbers li:hover {
            transform: translateX(10px);
        }

        .contact-numbers h3 {
            color: #333;
            margin-bottom: 5px;
            font-size: 1.2rem;
        }

        .contact-numbers p {
            color: #666;
            font-size: 1.1rem;
        }

        .contact-form {
            max-width: 600px;
            margin: 0 auto;
            padding: 30px;
            background: rgb(250, 249, 248);
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
            font-size: 1.1rem;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #FFDAB9;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #fdcb9f;
            box-shadow: 0 0 8px rgba(253, 203, 159, 0.5);
        }

        .form-group textarea {
            height: 150px;
            resize: vertical;
        }

        .contact-form .cta-button {
            width: 100%;
            padding: 15px;
            font-size: 1.1rem;
            font-weight: 500;
            margin-top: 10px;
            cursor: pointer;
            border: none;
        }

        .contact-form .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(253, 203, 159, 0.3);
        }

        /* Back button styles */
        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            padding: 10px 20px;
            background-color: #FFDAB9;
            color: #333;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: all 0.3s ease;
            font-weight: 500;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .back-button:hover {
            background-color: #fdcb9f;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        .back-button::before {
            content: '←';
            margin-right: 8px;
            font-size: 1.2em;
        }

        .content-section {
            position: relative;
        }
    </style>
    <script>
        
        document.addEventListener('DOMContentLoaded', function() {
    // Get all buttons with class 'scroll-btn'
    const scrollButtons = document.querySelectorAll('.scroll-btn');
    
    // Smooth scrolling function with adjustable speed
    function smoothScroll(targetElement) {
        const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset;
        const startPosition = window.pageYOffset;
        const distance = targetPosition - startPosition;
        const duration = 1000; // Duration in milliseconds (increase for slower scrolling)
        let startTime = null;

        function animationScroll(currentTime) {
            if (startTime === null) startTime = currentTime;
            const timeElapsed = currentTime - startTime;
            const run = easeInOutQuad(timeElapsed, startPosition, distance, duration);
            window.scrollTo(0, run);
            if (timeElapsed < duration) requestAnimationFrame(animationScroll);
        }

        // Ease in-out function for smooth effect
        function easeInOutQuad(t, b, c, d) {
            t /= d / 2;
            if (t < 1) return c / 2 * t * t + b;
            t--;
            return -c / 2 * (t * (t - 2) - 1) + b;
        }

        requestAnimationFrame(animationScroll);
    }

    // Add click event to each button
    scrollButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            const targetSection = document.getElementById(targetId);
            
            if (targetSection) {
                smoothScroll(targetSection);
            }
        });
    });

    // Add parallax effect to sections with background images
    window.addEventListener('scroll', function() {
        const parallaxSections = document.querySelectorAll('.parallax');
        
        parallaxSections.forEach(section => {
            const scrolled = window.pageYOffset;
            const rate = scrolled * -0.3;
            section.style.backgroundPositionY = rate + 'px';
        });
    });

    // Handle ceremony card clicks
    const ceremonyCards = document.querySelectorAll('#ceremonies .card');
    ceremonyCards.forEach(card => {
        card.addEventListener('click', function() {
            const cardTitle = this.querySelector('h3').textContent.toLowerCase();
            let targetSection;
            
            switch(cardTitle) {
                case 'wedding ceremony':
                    targetSection = 'wedding';
                    break;
                case 'birthday celebration':
                    targetSection = 'birthday';
                    break;
                case 'anniversary party':
                    targetSection = 'anniversary';
                    break;
                case 'baby shower':
                    targetSection = 'babyshower';
                    break;
                case 'cultural pooja':
                    targetSection = 'pooja';
                    break;
                case 'naming ceremony':
                    targetSection = 'naming';
                    break;
                case 'exhibition event':
                    targetSection = 'exhibition';
                    break;
                case 'grihprivesh ceremony':
                    targetSection = 'grihprivesh';
                    break;
                case 'freshers party':
                    targetSection = 'freshers';
                    break;
                case 'graduation ceremony':
                    targetSection = 'graduation';
                    break;
                case 'munj ceremony':
                    targetSection = 'munj';
                    break;
                case 'retirement party':
                    targetSection = 'retirement';
                    break;
            }
            
            if (targetSection) {
                const targetElement = document.getElementById(targetSection);
                if (targetElement) {
                    smoothScroll(targetElement);
                }
            }
        });
    });

    // Store login status in localStorage when PHP session confirms user is logged in
    <?php if(isset($_SESSION['user_id'])) { ?>
        localStorage.setItem('isLoggedIn', 'true');
    <?php } ?>

    // Add click handler for Explore Events button
    document.querySelector('.cta-button').addEventListener('click', function(e) {
        e.preventDefault();
        // Check login status from localStorage
        if (localStorage.getItem('isLoggedIn') !== 'true') {
            window.location.href = 'login.html';
        } else {
            document.getElementById('ceremonies').scrollIntoView({ behavior: 'smooth' });
        }
    });
});

    </script>
</head>
<body>
    <nav class="navbar">
        <ul class="nav-links">
            <div class="main-links">
                <li><a href="#home" class="scroll-btn">Home</a></li>
                <li><a href="#about" class="scroll-btn">About Us</a></li>
                <li><a href="#contact" class="scroll-btn">Contact us</a></li>
            </div>
            <?php if(isset($_SESSION['user_id'])): ?>
            <div class="user-info">
                <span class="user-name">Welcome, <?php echo htmlspecialchars($_SESSION['first_name']); ?></span>
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
            <?php endif; ?>
        </ul>
    </nav>
    <section id="home" class="hero-section">
        <div class="hero-content">
            <h1>Event Waale</h1>
            <p>Your Premier Event Planning Partner</p>
            <a href="#ceremonies" class="cta-button scroll-btn">Explore Events</a>
        </div>
    </section>

    <section id="ceremonies" class="content-section">
        <h2 class="section-title">Our Ceremonies</h2>
        <div class="ceremony-grid">
            
        <?php
        if ($result->num_rows > 0) {
            // Output each event as a card
            while ($row = $result->fetch_assoc()) {
                ?>

            <div class="card" onclick="window.location.href='<?php echo $row['onclick']; ?>'">
                <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                <div class="card-content">
                    <h3><?php echo $row['name']; ?></h3>
                    <p><?php echo $row['des']; ?></p>
                </div>
            </div>
            <?php
            }
        } else {
            echo "<p>No events found.</p>";
        }
        
        ?>
    </div>
        <a href="#home" class="back-button">Back to Home</a>
    </section>

    <div class="section-separator"></div>

    <section id="wedding" class="content-section">
        <a href="#ceremonies" class="back-button">Back to Ceremonies</a>
        <div class="section-container">
            <h2 class="section-title">Wedding Planning</h2>
            <div class="section-content">
                <div class="wedding-grid">
            
        <?php
        if ($result2->num_rows > 0) {
            // Output each event as a card
            while ($row2 = $result2->fetch_assoc()) {
                ?>

            <div class="card" onclick="<?php echo $row2['onclick']; ?>">
                <img src="<?php echo $row2['image']; ?>" alt="<?php echo $row2['name']; ?>">
                <div class="card-content">
                    <h3><?php echo $row2['name']; ?></h3>
                </div>
            </div>
            <?php
            }
        } else {
            echo "<p>No events found.</p>";
        }
        ?>
                </div>
            </div>
        </div>
    </section>

    <div class="section-separator"></div>

    <section id="birthday" class="content-section">
        <a href="#ceremonies" class="back-button">Back to Ceremonies</a>
        <div class="section-container">
            <h2 class="section-title">Birthday Themes</h2>
            <div class="section-content">
                <div class="wedding-grid">
            
        <?php
        if ($result3->num_rows > 0) {
            // Output each event as a card
            while ($row3 = $result3->fetch_assoc()) {
                ?>

            <div class="card" onclick="<?php echo $row3['onclick']; ?>">
                <img src="<?php echo $row3['image']; ?>" alt="<?php echo $row3['name']; ?>">
                <div class="card-content">
                    <h3><?php echo $row3['name']; ?></h3>
                </div>
            </div>
            <?php
            }
        } else {
            echo "<p>No events found.</p>";
        }
        ?>
                </div>
            </div>
        </div>
    </section>

    <div class="section-separator"></div>

    <section id="anniversary" class="content-section">
        <a href="#ceremonies" class="back-button">Back to Ceremonies</a>
        <div class="section-container">
            <h2 class="section-title">Anniversary Themes</h2>
            <div class="section-content">
                <div class="wedding-grid">
            
        <?php
        if ($result4->num_rows > 0) {
            // Output each event as a card
            while ($row4 = $result4->fetch_assoc()) {
                ?>

            <div class="card" onclick="<?php echo $row4['onclick']; ?>">
                <img src="<?php echo $row4['image']; ?>" alt="<?php echo $row4['name']; ?>">
                <div class="card-content">
                    <h3><?php echo $row4['name']; ?></h3>
                </div>
            </div>
            <?php
            }
        } else {
            echo "<p>No events found.</p>";
        }
        ?>
                </div>
            </div>
        </div>
    </section>

  

    <div class="section-separator"></div>

    
    <section id="freshers" class="content-section">
        <a href="#ceremonies" class="back-button">Back to Ceremonies</a>
        <div class="section-container">
            <h2 class="section-title">Freshers Party Themes</h2>
            <div class="section-content">
                <div class="wedding-grid">
            
        <?php
        if ($result5->num_rows > 0) {
            // Output each event as a card
            while ($row5 = $result5->fetch_assoc()) {
                ?>

            <div class="card" onclick="<?php echo $row5['onclick']; ?>">
                <img src="<?php echo $row5['image']; ?>" alt="<?php echo $row5['name']; ?>">
                <div class="card-content">
                    <h3><?php echo $row5['name']; ?></h3>
                </div>
            </div>
            <?php
            }
        } else {
            echo "<p>No events found.</p>";
        }
        ?>
                </div>
            </div>
        </div>
    </section>


    <div class="section-separator"></div>

    <section id="pooja" class="content-section">
        <a href="#ceremonies" class="back-button">Back to Ceremonies</a>
        <div class="section-container">
            <h2 class="section-title">Religious Ceremonies</h2>
            <div class="section-content">
                <div class="wedding-grid">
            
        <?php
        if ($result6->num_rows > 0) {
            // Output each event as a card
            while ($row6 = $result6->fetch_assoc()) {
                ?>

            <div class="card" onclick="<?php echo $row6['onclick']; ?>">
                <img src="<?php echo $row6['image']; ?>" alt="<?php echo $row6['name']; ?>">
                <div class="card-content">
                    <h3><?php echo $row6['name']; ?></h3>
                </div>
            </div>
            <?php
            }
        } else {
            echo "<p>No events found.</p>";
        }
        ?>
                </div>
            </div>
        </div>
    </section>


    <div class="section-separator"></div>

    <section id="naming" class="content-section">
        <a href="#ceremonies" class="back-button">Back to Ceremonies</a>
        <div class="section-container">
            <h2 class="section-title">Naming Ceremony Themes</h2>
            <div class="section-content">
               <div class="wedding-grid">
            
        <?php
        if ($result7->num_rows > 0) {
            // Output each event as a card
            while ($row7 = $result7->fetch_assoc()) {
                ?>

            <div class="card" onclick="<?php echo $row3['onclick']; ?>">
                <img src="<?php echo $row7['image']; ?>" alt="<?php echo $row7['name']; ?>">
                <div class="card-content">
                    <h3><?php echo $row7['name']; ?></h3>
                </div>
            </div>
            <?php
            }
        } else {
            echo "<p>No events found.</p>";
        }
        ?>
                </div>
            </div>
        </div>
    </section>


    <div class="section-separator"></div>

    <section id="exhibition" class="content-section">
        <a href="#ceremonies" class="back-button">Back to Ceremonies</a>
        <div class="section-container">
            <h2 class="section-title">Exhibition Types</h2>
            <div class="section-content">
                <div class="wedding-grid">
            
        <?php
        if ($result9->num_rows > 0) {
            // Output each event as a card
            while ($row9 = $result9->fetch_assoc()) {
                ?>

            <div class="card" onclick="<?php echo $row9['onclick']; ?>">
                <img src="<?php echo $row9['image']; ?>" alt="<?php echo $row9['name']; ?>">
                <div class="card-content">
                    <h3><?php echo $row9['name']; ?></h3>
                </div>
            </div>
            <?php
            }
        } else {
            echo "<p>No events found.</p>";
        }
        ?>
                </div>
            </div>
        </div>
    </section>

    <div class="section-separator"></div>


    <section id="babyshower" class="content-section">
        <a href="#ceremonies" class="back-button">Back to Ceremonies</a>
        <div class="section-container">
            <h2 class="section-title">Baby Shower Themes</h2>
            <div class="section-content">
                <div class="wedding-grid">
            
        <?php
        if ($result10->num_rows > 0) {
            // Output each event as a card
            while ($row10 = $result10->fetch_assoc()) {
                ?>

            <div class="card" onclick="<?php echo $row10['onclick']; ?>">
                <img src="<?php echo $row10['image']; ?>" alt="<?php echo $row10['name']; ?>">
                <div class="card-content">
                    <h3><?php echo $row10['name']; ?></h3>
                </div>
            </div>
            <?php
            }
        } else {
            echo "<p>No events found.</p>";
        }
        ?>
                </div>
            </div>
        </div>
    </section>


    <div class="section-separator"></div>

     <section id="about" class="content-section">
        <a href="#home" class="back-button">Back to Home</a>
        <h2 class="section-title">About Us</h2>
        <div class="section-content">
            <p>Founded with a passion for creating extraordinary experiences, Event Walee has emerged as a leading event management company in India. Our journey began with a simple yet powerful vision: to transform every celebration into a masterpiece of memories. With years of expertise and dedication, we've successfully orchestrated thousands of events, each unique and memorable in its own way.</p>

            <p>At Event Walee, we understand that every event tells a story. Whether it's a grand wedding celebration, an intimate baby shower, or a solemn traditional ceremony, we approach each event with the same level of dedication and attention to detail. Our team of experienced event planners, creative designers, and logistics experts work in perfect harmony to ensure that every aspect of your event exceeds expectations.</p>

            <p>What sets us apart is our commitment to preserving and celebrating Indian traditions while incorporating modern elements that reflect your personal style. We pride ourselves on our ability to blend cultural authenticity with contemporary elegance, creating events that are both meaningful and memorable. From traditional ceremonies like Munj and Naming ceremonies to modern celebrations like Freshers Parties and Exhibitions, we handle each event with cultural sensitivity and professional excellence.</p>

            <p>Our comprehensive services include:</p>
            <ul style="margin-left: 40px; margin-bottom: 20px;">
                <li>Complete event planning and coordination</li>
                <li>Venue selection and decoration</li>
                <li>Catering and menu planning</li>
                <li>Entertainment and cultural programs</li>
                <li>Photography and videography</li>
                <li>Guest management and logistics</li>
            </ul>

            <p>We believe in building lasting relationships with our clients, understanding their vision, and turning their dreams into reality. Our dedicated team is available round the clock to ensure that every detail is perfect and every moment is special. With Karyakram.com, you're not just planning an event; you're creating memories that will last a lifetime.</p>
        </div>
    </section>

    

    <div class="section-separator"></div>

    <section id="contact" class="content-section">
        <a href="#home" class="back-button">Back to Home</a>
        <h2 class="section-title">Contact Us</h2>
        <div class="contact-container">
            <div class="contact-left">
                <form class="contact-form">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone:</label>
                        <input type="tel" id="phone" name="phone" required>
                    </div>
                    <div class="form-group">
                        <label for="message">Message:</label>
                        <textarea id="message" name="message" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="cta-button">Send Message</button>
                </form>
            </div>
            <div class="contact-right">
                <h2>Our Services Contact Numbers</h2>
                <br>
                <ul class="contact-numbers">
                    <li>
                        <h3>Decoration Services</h3>
                        <p>+91 98765 43210</p>
                    </li>
                    <li>
                        <h3>Mehendi Artist</h3>
                        <p>+91 98765 43211</p>
                    </li>
                    <li>
                        <h3>Catering Services</h3>
                        <p>+91 98765 43212</p>
                    </li>
                    <li>
                        <h3>Panditji</h3>
                        <p>+91 98765 54232</p>
                    </li>
                    <li>
                        <h3>Photography</h3>
                        <p>+91 98765 43213</p>
                    </li>
                    <li>
                        <h3>Event Coordinator</h3>
                        <p>+91 98765 43214</p>
                    </li>
                </ul>
            </div>
        </div>
    </section>


</body>
</html>
<?php endif; ?>
