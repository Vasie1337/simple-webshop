<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
include '../header.php';

$sql = "SELECT * FROM reviews";
$result = $db->query($sql);
$reviews = $result->fetch_all(MYSQLI_ASSOC);

?>

<div class="reviews-container">
        <h1 class="reviews-header">Customer Reviews</h1>
        
        <?php foreach($reviews as $review): ?>
            <div class="review">
                <div class="review-header">
                    <div class="review-stars">
                        <?php 
                        $stars = intval($review['stars']);
                        echo str_repeat('★', $stars) . str_repeat('☆', 5 - $stars); 
                        ?>
                    </div>
                    <div class="review-author">
                        <?php echo htmlspecialchars($review['user_name']); ?>
                    </div>
                </div>
                <p class="review-comment">
                    <?php echo htmlspecialchars($review['comment']); ?>
                </p>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if(is_logged_in()): ?>
        <form class="add-review-form" action="add.php" method="post">
            <div class="form-group">
                <label for="stars">Rating (1-5 Stars)</label>
                <input 
                    type="number" 
                    id="stars" 
                    name="stars" 
                    min="1" 
                    max="5" 
                    class="form-control" 
                    required
                >
            </div>
            <div class="form-group">
                <label for="comment">Your Review</label>
                <textarea 
                    id="comment" 
                    name="comment" 
                    class="form-control" 
                    rows="4" 
                    required
                ></textarea>
            </div>
            <button type="submit" class="btn">Submit Review</button>
        </form>
    <?php endif; ?>

<?php include '../footer.php'; ?>