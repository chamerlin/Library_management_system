<?php 
    $title = "Home";
    function get_content(){
        require_once 'controllers/connection.php';
        $query1 = "SELECT * FROM category1";
        $genres = mysqli_fetch_all(mysqli_query($cn, $query1), MYSQLI_ASSOC);

        $query2 = "SELECT * FROM category2";
        $languages = mysqli_fetch_all(mysqli_query($cn, $query2), MYSQLI_ASSOC);

        $query3 = "SELECT * FROM books ORDER BY id DESC";
        $books = mysqli_fetch_all(mysqli_query($cn, $query3), MYSQLI_ASSOC);
    
?>

<div class="container margin">
    <?php if(isset($_SESSION['message'])):?>
        <div class="card-panel white-text center-align <?php echo $_SESSION['class'];?>">
            <?php echo $_SESSION['message'];?>
        </div>
    <?php endif;?>

    <?php if(isset($_SESSION['user_data']) && $_SESSION['user_data']['isAdmin']):?>
    <div class="row " id="authnav">
        <a href="views/book_record.php">
            <div class="col l4 m6 s6 offset-l2 ">
                <div class="card hoverable offset-4">
                    <div class="card-image">
                        <img src="assets/img/book_record.jpg" class="img-responsive">
                        <span class="card-title black-text">BOOK RECORDS</span>
                    </div>
                </div>
            </div>
        </a>
        <a href="views/readers.php">
            <div class="col l4 m6 s6">
                <div class="card hoverable">
                    <div class="card-image" style="background-color:white;">
                        <img src="assets/img/reader1.png" class="img-responsive" >
                        <span class="card-title grey-text">READERS</span>
                    </div>
                </div>
            </div>
        </a>
    </div>


    <div class="row">
        <ul class="collapsible col l10 m12 offset-l1">
            <li>
                <div class="collapsible-header">
                        <i class="medium material-icons">add_circle</i>
                        ADD NEW BOOKS
                </div>

                <div class="collapsible-body" style="height:350px;">
                    <form method="POST" action="web.php" enctype="multipart/form-data">
                        <h5>Add Book</h5>
                        <input type="hidden" name="action" value="add_book">

                        <div class="file-field input-field col l12 m12 s12">
                            <div class="btn lime darken-1">
                                <span >File</span>
                                <input type="file" name="fileUpload" id="book_image">
                            </div>
                            <div class="file-path-wrapper">
                                <input type="text" class="file-path validate" placeholder="Input book cover picture">
                                <span></span>
                            </div>
                        </div>

                        <div class="input-field col l6 m6 s6">
                            <label for="title">Title</label>
                            <input type="text" name="title" id="title">
                            <span class="err_msg"></span>
                        </div>

                        <div class="input-field col l6 m6 s6">
                            <label for="author">Author</label>
                            <input type="text" name="author" id="author">
                            <span class="err_msg"></span>
                        </div>


                        <div class="input-field col l8 m12 s12">
                            <label for="publisher">Publisher</label>
                            <input type="text" name="publisher" id="publisher">
                            <span class="err_msg"></span>
                        </div>
                        
                        <div class="input-field col l2 m6 s6">
                            <label for="year">Year</label>
                            <input type="text" name="year" id="year">
                            <span class="err_msg"></span>
                        </div>

                        <div class="input-field col l2 m6 s6">
                            <label for="quantity">quantity</label>
                            <input type="number" name="quantity" id="quantity">
                            <span class="err_msg"></span>
                        </div>

                        <div class="input-field col l6 m12">
                            <select name="genre_id">
                                <option disabled>Genre</option>
                                <?php foreach($genres as $genre):?>
                                    <option value="<?php echo $genre['id'];?>" class="left" style="color:black;">
                                        <?php echo $genre['genre'];?>
                                    </option>
                                <?php endforeach;?>
                                <label for="genre">Genre Preferred</label>
                            </select>
                        </div>

                        <div class="input-field col l6 m12">
                            <select name="language_id">
                                <option disabled>Language</option>
                                <?php foreach($languages as $language):?>
                                    <option value="<?php echo $language['id'];?>" class="left" style="color:black;">
                                        <?php echo $language['language'];?>
                                    </option>
                                <?php endforeach;?>
                                <label for="genre">Language Preferred</label>
                            </select>
                        </div>

                        <div class="input-field  col l12 m12 s12">
                            <label for="description">Description</label>
                            <input type="text" name="description" id="description">
                            <span class="err_msg"></span>
                        </div>

                        <button class="btn">
                            Add Book
                            <i class="material-icons right">add</i>
                        </button>

                    </form>
                </div>
            </li>
        </ul>
    </div>
    <?php endif;?>

    <div class="row">
    <?php if(isset($_SESSION['user_data']) && !$_SESSION['user_data']['isAdmin']):?>
    <?php
        $id = $_SESSION['user_data']['id'];
        $query4 = "SELECT books.*, reader.genre, reader.language FROM books 
                    JOIN reader ON books.genre = reader.genre AND books.language = reader.language
                    WHERE reader.id = $id ORDER BY books.id DESC LIMIT 6";
        $recommends = mysqli_fetch_all(mysqli_query($cn, $query4), MYSQLI_ASSOC);
    ?>
        <?php if($recommends != NULL):?>
            <div class="carousel">
                <h4 class="center-align">Recommendations</h4>
                <?php foreach($recommends as $recommend):?>
                    <?php if($recommend['isActive']):?>
                        <a class="carousel-item" href="views/book_details.php?id=<?php  echo $recommend['id'];?>&genre=<?php echo $recommend['genre'];?>&language=<?php echo $recommend['language'];?>">
                            <div class="card">
                                <div class="card-image">
                                    <img src="<?php echo $recommend['image'];?>">
                                </div>
                                <div class="card-title black-text center-align">
                                    <?php echo $recommend['title'];?>
                                </div>
                            </div>
                        </a>
                    <?php endif;?>
                <?php endforeach;?>
            </div>
        <?php endif;?>
    <?php endif;?>
    </div>

    <div class="row">
    <h4>BOOKS</h4>
    <?php 
        if(isset($_POST['search'])) {
            $keyword = $_POST['search'];
            $search_query = "SELECT * FROM books WHERE title LIKE '%$keyword%' ORDER BY id DESC";
            $books = mysqli_fetch_all(mysqli_query($cn, $search_query), MYSQLI_ASSOC);
        }
    ?>
    <?php if($books == NULL):?>
        <h4>No Books Found...</h4>
    <?php endif;?>
    <?php foreach($books as $book):?>
            <?php if($book['isActive']):?>
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
                                <a href="views/book_details.php?id=<?php  echo $book['id'];?>&genre=<?php echo $book['genre'];?>&language=<?php echo $book['language'];?>" class="black-text"><?php echo $book['title'];?></a>
                                <i class="material-icons right">more_vert</i>
                            </span>

                            <div>
                                <?php if(isset($_SESSION['user_data']) && !$_SESSION['user_data']['isAdmin']):?>
                                    <?php if($book['quantity'] > 0):?>
                                        <form action="web.php" method="POST">
                                            <input type="hidden" name="action" value="add_cart">
                                            <input type="hidden" name="book_id" value="<?php echo $book['id'];?>">
                                            <button class="btn blue darken-3">Add To MyBook</button>
                                        </form>
                                    <?php else:?> 
                                        <span class="grey" style="font-size:20px;">All lended out</span>
                                    <?php endif;?>
                                <?php endif;?>
                            </div>


                            <div>
                                <?php if(isset($_SESSION['user_data']) && $_SESSION['user_data']['isAdmin']):?>
                                <button data-target="modal1" class="btn cyan lighten-1 modal-trigger">Edit</button>
                                <div class="modal" id="modal1">
                                    <div class="modal-content">
                                        <form action="web.php" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="action" value="edit">
                                        <input type="hidden" name="id" value="<?php echo $book['id'];?>">
                                        
                                        <div class="file-field input-field col l12 m12 s12">
                                            <div class="btn lime darken-1">
                                                <span >File</span>
                                                <input type="file" name="fileUpload" id="book_image2">
                                            </div>
                                            <div class="file-path-wrapper">
                                                <input type="text" class="file-path validate"  name="image" value="<?php echo $book['image'];?>">
                                            </div>
                                        </div>

                                        <div class="input-field col l6 m6 s6">
                                            <label for="title">Title</label>
                                            <input type="text" name="title" id="title2" value="<?php echo $book['title'];?>">
                                            <span class="err_msg"></span>
                                        </div>

                                        <div class="input-field col l6 m6 s6">
                                            <label for="author">Author</label>
                                            <input type="text" name="author" id="author2" value="<?php echo $book['author']?>">
                                            <span class="err_msg"></span>
                                        </div>


                                        <div class="input-field col l8 m12 s12">
                                            <label for="publisher">Publisher</label>
                                            <input type="text" name="publisher" id="publisher2" value="<?php echo $book['publisher']?>">
                                            <span class="err_msg"></span>
                                        </div>
                        
                                        <div class="input-field col l2 m6 s6">
                                            <label for="year">Year</label>
                                            <input type="text" name="year" id="year2" value="<?php echo $book['year']?>">
                                            <span class="err_msg"></span>
                                        </div>

                                        <div class="input-field col l2 m6 s6">
                                            <label for="quantity">quantity</label>
                                            <input type="number" name="quantity" id="quantity2" value="<?php echo $book['quantity']?>">
                                            <span class="err_msg"></span>
                                        </div>

                                        <div class="input-field col l6 m12">
                                            <select name="genre_id">
                                                <option disabled>Genre</option>
                                                <?php foreach($genres as $genre):?>
                                                    <option value="<?php echo $genre['id'];?>" class="left" style="color:black;">
                                                        <?php echo $genre['genre'];?>
                                                    </option>
                                                <?php endforeach;?>
                                                <label for="genre">Genre Preferred</label>
                                            </select>
                                        </div>

                                        <div class="input-field col l6 m12">
                                            <select name="language_id">
                                                <option disabled>Language</option>
                                                <?php foreach($languages as $language):?>
                                                    <option value="<?php echo $language['id'];?>" class="left" style="color:black;">
                                                        <?php echo $language['language'];?>
                                                    </option>
                                                <?php endforeach;?>
                                                <label for="genre">Language Preferred</label>
                                            </select>
                                        </div>

                                        <div class="input-field  col l12 m12 s12">
                                            <label for="description">Description</label>
                                            <input type="text" name="description" id="description" value="<?php echo $book['description'];?>">
                                            <span class="err_msg"></span>
                                        </div>

                                        <button class="btn">
                                            Update
                                            <i class="material-icons right">create</i>
                                        </button>

                                        </form>
                                    </div>
                                </div>

                                <div style="display:inline-block;">
                                    <button class="btn red lighten-2 modal-trigger" data-target="modal2">Delete</button>
                                    <div class="modal" id="modal2">
                                        <div class="modal-content">
                                            <h4>Delete</h4>
                                            <p>Are you sure you want to delete this book?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <form action="web.php" method="POST">
                                                <input type="hidden" name="id" value="<?php echo $book['id'];?>">
                                                <input type="hidden" name="action" value="delete_book">
                                                <button class="btn waves-green waves-effect btn-flat">Confirm</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <?php endif;?>

                               
                            </div>
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
require_once 'views/layout.php';
?>

<script>
    document.addEventListener('DOMContentLoaded', ()=>{
        let card = document.querySelector('.card-panel')
        setTimeout(() => {
            <?php unset($_SESSION['message']);?>
            <?php unset($_SESSION['class']);?>
            card.classList.toggle('hide');
        }, 3000);
    })

    let elems = document.querySelectorAll('select');
    let instances = M.FormSelect.init(elems, {});

    document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.collapsible');
    var instances = M.Collapsible.init(elems, {});
    });   

    document.addEventListener('DOMContentLoaded', function(){
        var elems = document.querySelectorAll('.modal');
        var instances = M.Modal.init(elems, {});
    });

    document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.carousel');
    var instances = M.Carousel.init(elems, {});
    });


    let year = document.getElementById('year');
    let title = document.getElementById('title');
    let author = document.getElementById('author');
    let publisher = document.getElementById('publisher');
    let quantity = document.getElementById('quantity');
    let description = document.getElementById('description');

    title.addEventListener('keyup', function(e) {
        if(title.value.length < 1){
            title.nextElementSibling.innerHTML = "Please input a title";
        }else{
            title.nextElementSibling.innerHTML = ""
        }
    })

    author.addEventListener('keyup', function(e) {
        if(author.value.length < 1){
            author.nextElementSibling.innerHTML = "Please input a author";
        }else{
            author.nextElementSibling.innerHTML = ""
        }
    })

    publisher.addEventListener('keyup', function(e) {
        if(publisher.value.length < 1){
            publisher.nextElementSibling.innerHTML = "Please input a publisher";
        }else{
            publisher.nextElementSibling.innerHTML = ""
        }
    })

    year.addEventListener('keyup', function(e) {
        if(year.value.length < 1 || year.value.length > 4){
            year.nextElementSibling.innerHTML = "Please input a year";
        }else{
            year.nextElementSibling.innerHTML = ""
        }
    })

    quantity.addEventListener('keyup', function(e) {
        if(quantity.value < 1){
            quantity.nextElementSibling.innerHTML = "Please input more than 1";
        }else{
            quantity.nextElementSibling.innerHTML = ""
        }
    })

    description.addEventListener('keyup', function(e) {
        if(description.value.length < 1){
            description.nextElementSibling.innerHTML = "Please input a description";
        }else{
            description.nextElementSibling.innerHTML = ""
        }
    }) 

    let year2 = document.getElementById('year2');
    let title2 = document.getElementById('title2');
    let author2 = document.getElementById('author2');
    let publisher2 = document.getElementById('publisher2');
    let quantity2 = document.getElementById('quantity2');
    let description2 = document.getElementById('description2');


    title2.addEventListener('keyup', function(e) {
        if(title2.value.length < 1){
            title2.nextElementSibling.innerHTML = "Please input a title";
        }else{
            title2.nextElementSibling.innerHTML = ""
        }
    })

    author2.addEventListener('keyup', function(e) {
        if(author2.value.length < 1){
            author2.nextElementSibling.innerHTML = "Please input a author";
        }else{
            author2.nextElementSibling.innerHTML = ""
        }
    })

    publisher2.addEventListener('keyup', function(e) {
        if(publisher2.value.length < 1){
            publisher2.nextElementSibling.innerHTML = "Please input a publisher";
        }else{
            publisher2.nextElementSibling.innerHTML = ""
        }
    })

    year2.addEventListener('keyup', function(e) {
        if(year2.value.length < 1 || year.value.length > 4){
            year2.nextElementSibling.innerHTML = "Please input a year";
        }else{
            year2.nextElementSibling.innerHTML = ""
        }
    })

    quantity2.addEventListener('keyup', function(e) {
        if(quantity2.value < 1){
            quantity2.nextElementSibling.innerHTML = "Please input more than 1";
        }else{
            quantity2.nextElementSibling.innerHTML = ""
        }
    })

    description2.addEventListener('keyup', function(e) {
        if(description2.value.length < 1){
            description2.nextElementSibling.innerHTML = "Please input a description";
        }else{
            description2.nextElementSibling.innerHTML = ""
        }
    }) 
    

</script>