<?php
$title = "Book Details";
function get_content(){
require_once '../controllers/connection.php';
$book_id = $_GET['id'];
$query1 = "SELECT * FROM books WHERE id = $book_id";
$book = mysqli_fetch_assoc(mysqli_query($cn,$query1));

$genre_id = $_GET['genre'];
$query2 = "SELECT * FROM category1 WHERE id = $genre_id";
$genre = mysqli_fetch_assoc(mysqli_query($cn,$query2));

$language_id = $_GET['language'];
$query3 = "SELECT * FROM category2 WHERE id = $language_id";
$language = mysqli_fetch_assoc(mysqli_query($cn,$query3));

$query4 = "SELECT reviews.id, reviews.reader_id, reviews.book_id,reviews.date, reviews.reviews, reader.username  FROM reviews JOIN reader ON reader.id = reviews.reader_id WHERE reviews.book_id = $book_id";
$reviews = mysqli_fetch_all(mysqli_query($cn, $query4), MYSQLI_ASSOC);
?>

<div class="container margin">
    <div class="row">
        <div>
            <div class="card horizontal">
                <div class="card-image col l4 m6 s6">
                    <img src="<?php echo $book['image']?>" style="height:100%;">
                </div>
                <div class="card-stacked">
                    <div class="card-content">
                        <h5><?php echo $book['title'];?></h5>
                        <h6>Author: <?php echo $book['author'];?></h6>
                        <h6>Publisher: <?php echo $book['publisher'];?></h6>
                        <h6>Year Published: <?php echo $book['year'];?></h6>
                        <h6>Genre: <?php echo $genre['genre']?></h6>
                        <h6>Language: <?php echo $language['language'];?></h6>
                        <h6>Rating:</h6>
                    </div>
                    <div class="card-action">
                        <h6>Description: <br>
                            <?php echo $book['description'];?>
                        </h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row ">
        <div class="col l10 m12 s12 offset-l1">
            <div class="card-panel">
                <h4>Reviews</h4>
                <?php if(isset($_SESSION['user_data'])):
                    $user_id = $_SESSION['user_data']['id'];
                    $query5 = "SELECT * FROM reader WHERE id = $user_id";
                    $user = mysqli_fetch_assoc(mysqli_query($cn, $query5));
                    ?>
                <form action="../web.php" method="POST" class="col l12 m12 s12 ">
                    <input type="hidden" name="action" value="add_review">
                    <input type="hidden" name="user_id" value="<?php echo $user['id'];?>">
                    <input type="hidden" name="book_id" value="<?php echo $book['id'];?>">
                    <div class="input-field col l10 m10 s10">
                        <input type="text" name="review" id="review" placeholder="Add a review...">
                    </div>
                    <div class="input-field col l2 m2 s2">
                        <button class="btn btn-floating" style="display:inline-block;">Add</button>
                    </div>
                </form>
                <?php endif;?>

                <ul class="collection">
                    <?php if(!empty($reviews)):?>
                    <?php foreach($reviews as $review):?>
                        <?php if($review['reviews'] != ""):?>
                        <li class="collection-item avatar">
                            <i class="material-icons left medium">account_circle</i>
                            <span class="title"><?php echo $review['username']?></span>
                            <p>
                                <?php echo $review['date']?> <br>
                                <?php echo $review['reviews'];?>
                            </p>
                            <?php if(isset($_SESSION['user_data']) && $_SESSION['user_data']['username'] == $review['username']):?>
                                <form action="../web.php?id=<?php echo $review['id'];?>" class="secondary-content" method="POST">
                                    <input type="hidden" name="action" value="delete_review">
                                    <input type="hidden" name="review_id" value="<?php echo $review['id'];?>">
                                    <button class="btn-small red darken-2">Delete</button>
                                </form>
                            <?php endif;?>
                        </li>
                        <?php endif; ?>
                    <?php endforeach;?>
                    <?php else:?>
                        <li class="collection-item">
                            <i class="material-icons left small">cancel</i>
                            <span class="title valign-wrapper">No review</span>
                        </li>
                    <?php endif;?>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php 
}
require_once 'layout.php';
?>