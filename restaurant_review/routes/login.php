<?php
include __DIR__ . '/../db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;

    if (!$email || !$password) {
        echo '<div class="alert alert-danger">Please provide both email and password!</div>';
        exit;
    }

    $sql = "SELECT * FROM Users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $user = $result->fetch_assoc()) {
        if (!isset($user['password'])) {
            echo '<div class="alert alert-danger">Password field missing in the database record!</div>';
            exit;
        }
        
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            echo '<div class="alert alert-success">Login successful!</div>';
        } else {
            echo '<div class="alert alert-danger">Invalid password!</div>';
        }
    } else {
        echo '<div class="alert alert-danger">User not found!</div>';
    }

    $stmt->close();
    $conn->close();
}
?>
<div class="container mt-5">
    <h2>Login</h2>
    <form method="POST" class="needs-validation" novalidate>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
</div>