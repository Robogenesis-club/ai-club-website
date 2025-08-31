<?php
include 'config.php'; // assumes $conn is your mysqli connection

$error = '';
$success = '';

if (!isset($conn) || !$conn) {
    $error = 'Main database not connected. Cannot create admin.';
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        $error = 'Email and password are required.';
    } else {
        // Check if email already exists
        $stmt_check = $conn->prepare('SELECT id FROM core WHERE email = ?');
        $stmt_check->bind_param('s', $email);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            $error = 'An admin with this email already exists.';
        } else {
            // Hash the password
            $hash = password_hash($password, PASSWORD_DEFAULT);

            // Insert new admin
            $stmt = $conn->prepare('INSERT INTO core (email, password) VALUES (?, ?)');
            if (!$stmt) {
                $error = 'Prepare failed: ' . htmlspecialchars($conn->error);
            } else {
                $stmt->bind_param('ss', $email, $hash);
                if ($stmt->execute()) {
                    $success = 'Admin created successfully. Please delete create_admin.php for security.';
                } else {
                    $error = 'Execute failed: ' . htmlspecialchars($stmt->error);
                }
                $stmt->close();
            }
        }
        $stmt_check->close();
    }
}
?>

<!-- Your HTML form and messages here -->
<!DOCTYPE html>
<html>
<head>
    <title>Create Admin</title>
</head>
<body>
    <h2>Create Admin User</h2>
    <?php if ($error): ?>
        <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
    <?php elseif ($success): ?>
        <p style="color:green;"><?php echo htmlspecialchars($success); ?></p>
    <?php endif; ?>
    <form method="post">
        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>
        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>
        <button type="submit">Create Admin</button>
    </form>
</body>
</html>
