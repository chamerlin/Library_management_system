<?php 
$title = "My Books";
function get_content() {
    require_once '../controllers/connection.php';
    date_default_timezone_set('Asia/Kuala_Lumpur');

    $id = $_SESSION['user_data']['id'];

    $query1 = "SELECT * FROM category1";
    $genres = mysqli_fetch_all(mysqli_query($cn, $query1), MYSQLI_ASSOC);

    $query2 = "SELECT * FROM category2";
    $languages = mysqli_fetch_all(mysqli_query($cn, $query2), MYSQLI_ASSOC);

    $query3 = "SELECT my_books.*, books.title, books.image, books.author,books.publisher, books.genre, books.language, books.year, books.description FROM my_books 
    JOIN books ON my_books.book_id = books.id 
    WHERE my_books.reader_id = $id";
    $books = mysqli_fetch_all(mysqli_query($cn, $query3), MYSQLI_ASSOC);

    
    $date = date('Y-m-d');
?>

<div class="container margin">
    <?php if(isset($_SESSION['message'])):?>
        <div class="card-panel white-text center-align <?php echo $_SESSION['class'];?>">
            <?php echo $_SESSION['message'];?>
        </div>
    <?php endif;?>
    <div class="row">
        <h3>My Books</h3>
        <?php if($books == NULL):?>
            <h4>No Books Yet</h4>
            <a href="/">Go to Add Books</a>
        <?php endif;?>
        <?php foreach($books as $book):?>
            <?php if($book['status'] != 4):?>
                <div class="book_container col l4 m6 s12">
                    <div class="book">
                        <div class="cover">
                           <figure class="front">
                                <img src="<?php echo $book['image'];?>" >
                           </figure>
                           <figure class="back"></figure>
                        </div>
                        <div class="details">
                            <?php echo $book['description']?>
                        </div>
                    </div>
                    <div class="card z-depth-3">
                        <div class="card-content">
                            <span class="card-title activator grey-text text-darken-4">
                                <a href="book_details.php?id=<?php  echo $book['book_id'];?>&genre=<?php echo $book['genre'];?>&language=<?php echo $book['language'];?>" class="black-text"><?php echo $book['title'];?></a>
                                <i class="material-icons right">more_vert</i>
                            </span>

                            <?php if(isset($_SESSION['user_data']) && $book['status'] < 2 ):?>
                                <div class="valign-wrapper">
                                    <?php if($book['status'] == 0):?>
                                    <form action="../web.php" method="POST">
                                        <input type="hidden" name="action" value="pending">
                                        <input type="hidden" name="book_id" value="<?php echo $book['book_id'];?>">
                                        <input type="hidden" name="mybooks_id" value="<?php echo $book['id'];?>">
                                        <button class="btn btn-floating left-align">Lend</button>
                                    </form>
                                    
                                    <!-- <button class="btn red lighten-2 modal-trigger right-align" data-target="modal2" style="margin-left:30px;">Delete</button>
                                        <div class="modal" id="modal2">
                                            <div class="modal-content">
                                                <h4>Delete</h4>
                                                <p>Are you sure you want to delete this book from MyBooks?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <form action="../web.php" method="POST">
                                                    <input type="hidden" name="action" value="delete_book_cart">
                                                    <input type="hidden" name="book_id" value="<?php echo $book['book_id'];?>">
                                                    <button class="btn waves-green waves-effect btn-flat">Confirm</button>
                                                </form>
                                            </div>
                                        </div> -->

                                        <form action="../web.php" method="POST">
                                            <input type="hidden" name="action" value="delete_book_cart">
                                            <input type="hidden" name="book_id" value="<?php echo $book['book_id'];?>">
                                            <input type="hidden" name="mybooks_id" value="<?php echo $book['id'];?>">
                                            <button class="btn red lighten-2  right-align" style="margin-left:30px;">Delete</button>
                                        </form>

                                    <?php else:?> 
                                        <span class="pending"> Pending</span>
                                    <?php endif;?>
                                    
                                </div>
                            
                            <?php else:?>

                            <div class="valign-wrapper">
                                <?php if($book['status'] != 3):?>
                                <form action="/web.php" method="POST" class="left-align">
                                    <input type="hidden" name="action" value="renew">
                                    <input type="hidden" name="mybooks_id" value="<?php echo $book['id'];?>">
                                    <input type="hidden" name="reader_id" value="<?php echo $book['reader_id'];?>">
                                    <input type="hidden" name="book_id" value="<?php echo $book['book_id'];?>">
                                    <button class="btn left-align orange accent-4">Renew</button>
                                </form>
                                <?php endif;?>
                                    
                                 <form action="/web.php" method="POST" class="right-align" style="margin-left:20px;" >
                                    <input type="hidden" name="action" value="return">
                                    <input type="hidden" name="reader_id" value="<?php echo $book['reader_id'];?>">
                                    <input type="hidden" name="book_id" value="<?php echo $book['book_id'];?>">
                                    <input type="hidden" name="mybooks_id" value="<?php echo $book['id'];?>">
                                    <button class="btn right-align red accent-2">Return</button>
                                </form>
                            </div>
                                    
                            <?php endif;?>
                        </div>

                        <div class="card-reveal">
                            <span class="card-title grey-text darken-4">Details<i class="material-icons right">close</i></span>
                            <p>
                                Author:
                                <?php echo $book['author'];?><br>

                                Publisher:
                                <?php echo $book['publisher'];?><br>

                                Year Published:
                                <?php echo $book['year'];?>
                            </p>
                        </div>
                    </div>
                </div>
             <?php endif;?>       
    <?php endforeach;?>
    </div>
</div>

<?php 
}
require_once 'layout.php';
?>
<script>
    document.addEventListener('DOMContentLoaded', ()=>{
        let card = document.querySelector('.card-panel')
        setTimeout(() => {
            <?php unset($_SESSION['message']);?>
            <?php unset($_SESSION['class']);?>
            card.classList.toggle('hide');
        }, 3000);
    });

    document.addEventListener('DOMContentLoaded', function(){
        var elems = document.querySelectorAll('.modal');
        var instances = M.Modal.init(elems, {});
    })
</script>