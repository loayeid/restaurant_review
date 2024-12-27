<?php
include __DIR__ . '/../db_connection.php';

$restaurant_id = $_GET['id'] ?? null;
if (!$restaurant_id) {
    echo '<div class="alert alert-danger">Invalid restaurant ID!</div>';
    exit;
}

$sql = "SELECT * FROM Restaurants WHERE restaurant_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $restaurant_id);
$stmt->execute();
$result = $stmt->get_result();
$restaurant = $result->fetch_assoc();

if (!$restaurant) {
    echo '<div class="alert alert-danger">Restaurant not found!</div>';
    exit;
}

$sql_reviews = "SELECT r.comment, r.rating, r.review_date, u.username 
                FROM Reviews r 
                JOIN Users u ON r.user_id = u.user_id 
                WHERE r.restaurant_id = ?";
$stmt_reviews = $conn->prepare($sql_reviews);
$stmt_reviews->bind_param("i", $restaurant_id);
$stmt_reviews->execute();
$result_reviews = $stmt_reviews->get_result();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'] ?? null;
    $rating = $_POST['rating'] ?? null;
    $comment = $_POST['comment'] ?? null;

    if (!$user_id || !$rating || !$comment) {
        echo '<div class="alert alert-danger">All fields are required to submit a review.</div>';
    } else {
        $sql_add_review = "INSERT INTO Reviews (user_id, restaurant_id, rating, comment, review_date) VALUES (?, ?, ?, ?, NOW())";
        $stmt_add_review = $conn->prepare($sql_add_review);
        $stmt_add_review->bind_param("iiis", $user_id, $restaurant_id, $rating, $comment);

        if ($stmt_add_review->execute()) {
            echo '<div class="alert alert-success">Review added successfully!</div>';
        } else {
            echo '<div class="alert alert-danger">Error: ' . $stmt_add_review->error . '</div>';
        }

        $stmt_add_review->close();
    }
}

$stmt->close();
$stmt_reviews->close();
?>

<div class="container mt-5">
    <h2><?php echo htmlspecialchars($restaurant['name']); ?></h2>
    <p><strong>Category:</strong> <?php echo htmlspecialchars($restaurant['category']); ?></p>
    <p><strong>Location:</strong> <?php echo htmlspecialchars($restaurant['location']); ?></p>
    <p><strong>Description:</strong> <?php echo htmlspecialchars($restaurant['description']); ?></p>

    <h3 class="mt-4">Reviews</h3>
    <?php if ($result_reviews->num_rows > 0): ?>
    <ul class="list-group">
        <?php while ($review = $result_reviews->fetch_assoc()): ?>
        <li class="list-group-item">
            <h5><?php echo htmlspecialchars($review['username']); ?> <span class="badge bg-primary">Rating:
                    <?php echo htmlspecialchars($review['rating']); ?>/5</span></h5>
            <p><?php echo htmlspecialchars($review['comment']); ?></p>
            <small class="text-muted">Reviewed on: <?php echo htmlspecialchars($review['review_date']); ?></small>
        </li>
        <?php endwhile; ?>
    </ul>
    <?php else: ?>
    <p class="text-muted">No reviews yet for this restaurant.</p>
    <?php endif; ?>

    <h3 class="mt-4">Add Your Review</h3>
    <form method="POST" class="needs-validation">
        <div class="mb-3">
            <label for="rating" class="form-label">Rating (1-5)</label>
            <select name="rating" id="rating" class="form-select" required>
                <option value="">Choose...</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="comment" class="form-label">Comment</label>
            <textarea name="comment" id="comment" class="form-control" rows="4" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit Review</button>
    </form>

    <a href="index.php" class="btn btn-secondary mt-3">Back to Listings</a>
</div>