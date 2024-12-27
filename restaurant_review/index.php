<?php
session_start();
include 'header.php';
include 'db_connection.php';

$page = isset($_GET['page']) ? $_GET['page'] : 'home';

switch ($page) {
    case 'register':
        include 'routes/register.php';
        break;
    case 'login':
        include 'routes/login.php';
        break;
    case 'add-restaurant':
        include 'routes/add_restaurant.php';
        break;
    case 'add-review':
        include 'routes/add_review.php';
        break;
    case 'search-restaurants':
        include 'routes/search_restaurants.php';
        break;
    case 'restaurant-reviews':
        include 'routes/restaurant_reviews.php';
        break;
    case 'details':
        include 'routes/details.php';
        break;
    default:
        $sql = "SELECT * FROM Restaurants";
        $result = $conn->query($sql);
        ?>
<div class="container mt-5">
    <h2>Restaurant Listings</h2>
    <div class="row">
        <?php while ($restaurant = $result->fetch_assoc()): ?>
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($restaurant['name']); ?></h5>
                    <p class="card-text">Category: <?php echo htmlspecialchars($restaurant['category']); ?></p>
                    <p class="card-text">Location: <?php echo htmlspecialchars($restaurant['location']); ?></p>
                    <a href="index.php?page=details&id=<?php echo $restaurant['restaurant_id']; ?>"
                        class="btn btn-primary">View Details</a>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>
<?php
        break;
}

$conn->close();

?>