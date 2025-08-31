<?php
include 'config.php'; // This now handles session_start() and both $conn and $conn_member

if (!isset($conn) || !$conn) {
    error_log('robophp: $conn is null in login.php');
    echo '<p>Database not connected. Check config.php and import the SQL.</p>'; exit();
}
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Query table 'core'
    $sql = "SELECT * FROM core WHERE email = ?"; // Use prepared statement for security
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            // Verify hashed password
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_email'] = $user['email']; // Store email for admin access
                header("Location: secret_file.php");
                exit();
            } else {
                $message = 'Invalid email or password';
            }
        } else {
            $message = 'Invalid email or password';
        }
        $stmt->close();
    } else {
        $message = 'Database query failed: ' . $conn->error;
        error_log('robophp: login prepare statement failed: ' . $conn->error);
    }
}

include 'includes/header.php';
?>

<!-- HTML content remains the same -->
<div class="login-container">
    <div class="login-header">
        <div class="logo">
            <img src="assests/images/club-logo.png" alt="RoboGenesis Logo" style="width: 80px; height: auto;"
                 onerror="this.style.display='none'; this.parentNode.innerHTML='<i class=\'fas fa-robot\' style=\'font-size: 40px; color: #4ca1af;\'></i>';">
        </div>
        <h1>Welcome to RoboGenesis Club</h1>
        <p>Sign in to access your account</p>
    </div>

    <form class="login-form" method="POST">
        <div class="form-group">
            <label for="email">Email</label>
            <div class="input-with-icon">
                <i class="fas fa-envelope"></i>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <div class="input-with-icon">
                <i class="fas fa-lock"></i>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
        </div>

        <?php if ($message): ?>
            <p style="color: red; text-align: center;"><?php echo $message; ?></p>
        <?php endif; ?>

        <button type="submit" class="login-button">
            <i class="fas fa-sign-in-alt"></i> Login
        </button>

        <div class="register-link">
            Don't have an account? <a href="#">Register Now</a>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>

<style>
    body {
        background-image: url('https://images.unsplash.com/photo-1535223289827-42f1e9919769?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1287&q=80');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-attachment: fixed;
        height: 100vh;
        min-height: 100%;
        margin: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .login-container {
        background: rgba(10, 11, 16, 0.9); /* Semi-transparent overlay */
        padding: 20px;
        border-radius: 10px;
        width: 100%;
        max-width: 400px;
        text-align: center;
    }

    .login-header {
        margin-bottom: 20px;
    }

    .logo {
        margin-bottom: 15px;
        text-align: center;
    }

    .login-form {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .form-group {
        margin-bottom: 15px;
        width: 100%;
    }

    .input-with-icon {
        position: relative;
    }

    .input-with-icon i {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #666;
    }

    .input-with-icon input {
        width: 100%;
        padding: 10px 10px 10px 35px;
        border: 1px solid #ddd;
        border-radius: 5px;
        box-sizing: border-box;
    }

    .login-button {
        width: 100%;
        padding: 10px;
        background-color: #4ca1af;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
    }

    .login-button:hover {
        background-color: #3d7f8c;
    }

    .register-link {
        margin-top: 15px;
        text-align: center;
    }

    .footer {
        text-align: center;
        padding: 20px 0;
        color: #888;
        font-size: 14px;
    }
</style>
