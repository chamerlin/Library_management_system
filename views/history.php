<?php 
$title = "history";
function get_content() {
    require_once '../controllers/connection.php';
    $id = $_SESSION['user_data']['id'];
    $query = "SELECT books.*, my_books.status, record.return_date, category1.genre, category2.language FROM books 
            JOIN my_books ON books.id = my_books.book_id 
            JOIN record ON my_books.id = record.mybooks_id
            JOIN category1 ON books.genre = category1.id
            JOIN category2 ON books.language = category2.id
            WHERE my_books.status = 4 AND my_books.reader_id = $id ORDER BY record.return_date DESC";
    $histories = mysqli_fetch_all(mysqli_query($cn, $query), MYSQLI_ASSOC);
   
?>

<div class="container margin">
    <div class="row">   
    <h3>Histories</h3>
    <?php if($histories  == NULL):?>
            <h4>No Books Read Yet</h4>
        <?php endif;?>
    <?php foreach($histories as $history):?>
        <div>
            <div class="card horizontal col l10 m10 s12 offset-l1 offset-m1">
                <div class="card-image col l4 m6 s6">
                    <img src="<?php echo $history['image']?>" style="height:100%;">
                </div>
                <div class="card-stacked">
                    <div class="card-content">
                        <h5><?php echo $history['title'];?></h5>
                        <h6>Author: <?php echo $history['author'];?></h6>
                        <h6>Publisher: <?php echo $history['publisher'];?></h6>
                        <h6>Year Published: <?php echo $history['year'];?></h6>
                        <h6>Genre: <?php echo $history['genre']?></h6>
                        <h6>Language: <?php echo $history['language'];?></h6>
                    </div>
                    <div class="card-action">
                        <h6>Description: <br>
                            <?php echo $history['description'];?>
                        </h6>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach;?>
    </div>
</div>

<?php 
}
require_once 'layout.php';
?>