<?php
include __DIR__ . '/../db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? null;
    $category = $_POST['category'] ?? null;
    $location = $_POST['location'] ?? null;
    $description = $_POST['description'] ?? null;

    if (!$name || !$category || !$location || !$description) {
        echo '<div class="alert alert-danger">All fields are required!</div>';
    } else {
        $sql = "INSERT INTO Restaurants (name, category, location, description) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $name, $category, $location, $description);

        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Restaurant added successfully!</div>';
        } else {
            echo '<div class="alert alert-danger">Error: ' . $stmt->error . '</div>';
        }

        $stmt->close();
    }
}

$conn->close();
?>

<div class="container mt-5">
    <h2>Add a New Restaurant</h2>
    <form method="POST" class="needs-validation" novalidate>
        <div class="mb-3">
            <label for="name" class="form-label">Restaurant Name</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="category" class="form-label">Category</label>
            <input type="text" name="category" id="category" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="location" class="form-label">Location</label>
            <input type="text" name="location" id="location" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control" rows="4" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Add Restaurant</button>
    </form>
</div>