<?php 
require_once 'connection.php';

function add_book($request) {
    global $cn;

    $title = $request['title'];
    $author = $request['author'];
    $publisher = $request['publisher'];
    $year = $request['year'];
    $quantity = $request['quantity'];
    $genre = $request['genre_id'];
    $language = $request['language_id'];
    $description = $request['description'];

    $img_name = $_FILES['fileUpload']['name'];
    $img_size = $_FILES['fileUpload']['size'];
    $img_tmpname = $_FILES['fileUpload']['tmp_name'];
    $img_type = pathinfo($img_name, PATHINFO_EXTENSION);


    $extensions = ['jpg', 'png', 'jpeg','gif', 'jfif'];
    $upload_dir = "/public/";
    $image; 

    $errors = 0;

    if(strlen($title) < 1) {
        $errors++;
    }

    if(strlen($author) < 1) {
        $errors++;
    }

    if(strlen($publisher) < 1){
        $errors++;
    }

    if((intval($year) < 1 && intval($year) > 5) || intval($quantity) < 1){
        $errors++;
    }

    if(!isset($genre) || !isset($language)) {
        $errors++;
    }

    if(strlen($description) < 1){
        $errors++;
    }

    if($_FILES['fileUpload']['name'] == "" || !in_array($img_type, $extensions)) {
        $errors++;
    } else {
        $image = $upload_dir.$_FILES['fileUpload']['name'];
        move_uploaded_file($img_tmpname, $_SERVER['DOCUMENT_ROOT'].$upload_dir.$img_name);
    }

    

    if($errors === 0) {
        $query = "INSERT INTO books(title, image, author, publisher, year, quantity, genre, language, description) VALUES ('$title', '$image', '$author', '$publisher', '$year', '$quantity', '$genre', '$language', '$description')";

        mysqli_query($cn, $query);
        mysqli_close($cn);

        session_start();
        $_SESSION['message'] = "Book Added";
        $_SESSION['class'] = "teal darken-2";
        header('Location: /');

    }else{
        session_start();
        $_SESSION['message'] = "Fail to add book";
        $_SESSION['class'] = "red darken-2";
        header('Location: /');
    }


}

function edit($request) {
    global $cn;

    $id = $request['id'];
    $title = $request['title'];
    $author = $request['author'];
    $publisher = $request['publisher'];
    $year = $request['year'];
    $quantity = $request['quantity'];
    $genre = $request['genre_id'];
    $language = $request['language_id'];
    $description = $request['description'];

    $extensions = ['jpg', 'png', 'jpeg','gif', 'jfif'];
    $upload_dir = "/public/";

    $img_name = $_FILES['fileUpload']['name'];
    $img_size = $_FILES['fileUpload']['size'];
    $img_tmpname = $_FILES['fileUpload']['tmp_name'];
    $img_type = pathinfo($img_name, PATHINFO_EXTENSION);
    $image = $upload_dir.$_FILES['fileUpload']['name']; 

    $errors = 0;

    if(strlen($title) < 1) {
        $errors++;
    }

    if(strlen($author) < 1) {
        $errors++;
    }

    if(strlen($publisher) < 1){
        $errors++;
    }

    if((intval($year) < 1 && intval($year) > 5) || intval($quantity) < 1){
        $errors++;
    }

    if(!isset($genre) || !isset($language)) {
        $errors++;
    }

    if(strlen($description) < 1){
        $errors++;
    }

    if(in_array($img_type, $extensions)) {
        move_uploaded_file($img_tmpname, $_SERVER['DOCUMENT_ROOT'].$upload_dir.$img_name);
    } else {
        $image = $request['image'];
    }

    if($errors === 0) {
        $query = "UPDATE books SET
                title = '$title',
                image = '$image',
                author = '$author',
                publisher = '$publisher',
                year = '$year',
                quantity = '$quantity',
                genre = '$genre',
                language = '$language',
                description = '$description'
                WHERE id = $id";

        mysqli_query($cn, $query);
        mysqli_close($cn);

        session_start();
        $_SESSION['message'] = "Update Successfully";
        $_SESSION['class'] = "teal darken-2";
        header('Location: /');

    } else {
        session_start();
        $_SESSION['message'] = "Fail to Update";
        $_SESSION['class'] = "red darken-2";
        header('Location: /');
    }    
}

function delete_book($request) {
    global $cn;

    $id = $request['id'];
    $query = "UPDATE books SET isActive = 0 WHERE id = $id";
    mysqli_query($cn, $query);
    mysqli_close($cn);
    header('Location: /');

}

function add_review($request) {
    global $cn;

    $id = $request['user_id'];
    $book_id = $request['book_id'];
    $review = $request['review'];

    
    date_default_timezone_set('Asia/Kuala_Lumpur');
    $date = date('Y-m-d H:i');

    $query = "INSERT INTO reviews(reader_id, book_id, date, reviews) VALUES ('$id', '$book_id', '$date', '$review')";
    
    mysqli_query($cn, $query);
    mysqli_close($cn);
    header('Location:' . $_SERVER['HTTP_REFERER']);
}

function delete_review($request) {
    global $cn;

    $id = $_GET['id'];
    $review_id = $request['review_id'];

    $query = "DELETE FROM reviews WHERE id = $id";

    mysqli_query($cn, $query);
    mysqli_close($cn);
    
    header('Location:' . $_SERVER['HTTP_REFERER']);  
}

function add_cart($request) {

    global $cn;

    session_start();
    $book_id = $request['book_id'];
    $reader_id = $_SESSION['user_data']['id'];

    $search = "SELECT * FROM my_books WHERE reader_id = $reader_id AND book_id = $book_id AND status < 4";
    $existing = mysqli_fetch_all(mysqli_query($cn, $search), MYSQLI_ASSOC);

    if($existing) {
        session_start();
        $_SESSION['message'] = "Already added";
        $_SESSION['class'] = "light-green lighter-2";
        header('Location: /');

    } else {
        $query = "INSERT INTO my_books(reader_id, book_id) VALUES ('$reader_id', '$book_id')";  
        mysqli_query($cn, $query);
        
        $query2 = "UPDATE books SET quantity = quantity - 1 WHERE book_id = $book_id";
        mysqli_query($cn, $query2);
    
        mysqli_close($cn);

        session_start();
        $_SESSION['message'] = "Added to MyBooks";
        $_SESSION['class'] = "teal darken-2";
        header('Location: /');
    }
}

function pending($request) {

    global $cn;

    session_start();
    $id = $request['mybooks_id'];
    $reader_id = $_SESSION['user_data']['id'];
    $book_id = $request['book_id'];

    $query = "UPDATE my_books SET status = 1 WHERE book_id = $book_id AND reader_id = $reader_id";
    mysqli_query($cn, $query);

    $query2 = "INSERT INTO record(mybooks_id) VALUES ('$id')";
    mysqli_query($cn, $query2);

    mysqli_close($cn);
    header('Location:' . $_SERVER['HTTP_REFERER']);
}

function delete_cart($request) {
    global $cn;

    session_start();
    $book_id = $request['book_id'];
    $id = $request['mybooks_id'];
    $reader_id = $_SESSION['user_data']['id'];

    $query = "DELETE FROM my_books WHERE id = $id";
    
    mysqli_query($cn, $query);
    mysqli_close($cn);
    
    header('Location:' . $_SERVER['HTTP_REFERER']);  
}

function update_profile($request) {
    global $cn;

    $id = $request['id'];
    $fullname = $request['fullname'];
    $username = $request['username'];
    $email = $request['email'];
    $contactno = $request['contactno'];
    $genre = $request['genre_id'];
    $language = $request['language_id'];
    

    $errors = 0;

    if(!isset($genre) && !isset($language)) {
        $errors++;
    }
    
    if(strlen($username) < 8) {
        $errors++;
    }

    if(strlen($fullname) < 1) {
        $errors++;
    }

    if($errors === 0) {
        $query = "UPDATE reader SET
                fullname = '$fullname',
                username = '$username',
                email = '$email',
                contactno = '$contactno',
                genre = '$genre',
                language = '$language'
                WHERE id = $id";

        mysqli_query($cn, $query);
        mysqli_close($cn);

        session_start();
        $_SESSION['message'] = "Update Successfully";
        $_SESSION['class'] = "teal darken-2";
        header('Location:' . $_SERVER['HTTP_REFERER']);

    } else {
        session_start();
        $_SESSION['message'] = "Fail to Update";
        $_SESSION['class'] = "red darken-2";
        header('Location:' .$_SERVER['HTTP_REFERER']);
    }    
}

function approve($request) {
    global $cn;

    $id = $request['id'];
    $book_id = $request['book_id'];
    $reader_id = $request['reader_id'];
    $lend_date = $request['lend'];
    $return_date = $request['return'];

    if(strtotime($lend_date) >= strtotime($return_date)) {
        session_start();
        $_SESSION['message'] = "Lent date should not be greater than Return date";
        $_SESSION['class'] = "red darken-2";
        header('Location:' .$_SERVER['HTTP_REFERER']);
    }

    $query = "UPDATE my_books SET status = 2 WHERE book_id = $book_id AND reader_id = $reader_id";
    mysqli_query($cn, $query);


    $query3 = "UPDATE record SET lend_date = CAST('$lend_date' AS DATE)  WHERE id = $id";
    mysqli_query($cn, $query3);
    
    $query4 = "UPDATE record SET return_date = CAST('$return_date' AS DATE) WHERE id = $id";
    mysqli_query($cn, $query4);

    mysqli_close($cn);
    header('Location:' . $_SERVER['HTTP_REFERER']);
}

function renew($request){
    global $cn;

    $id = $request['mybooks_id'];
    $book_id = $request['book_id'];
    $reader_id = $request['reader_id'];

    $date = "SELECT return_date from record WHERE record.mybooks_id = $id";
    $return_date = implode(mysqli_fetch_assoc(mysqli_query($cn, $date)));
    
    $query = "UPDATE record SET return_date = CAST(DATE_ADD('$return_date', INTERVAL 7 DAY) AS DATE) WHERE mybooks_id = $id";
    mysqli_query($cn, $query);


    $query2 = "UPDATE my_books SET status = 3 WHERE reader_id = $reader_id AND book_id = $book_id";
    mysqli_query($cn, $query2);

    mysqli_close($cn);

    session_start();
    $_SESSION['message'] = "Renewed";
    $_SESSION['class'] = "teal darken-2";
    header('Location:' . $_SERVER['HTTP_REFERER']);
    
}

function return_book($request) {
    global $cn;

    $book_id = $request['book_id'];
    $id = $request['mybooks_id'];
    
    $query = "UPDATE my_books SET status = 4 WHERE id = $id";
    mysqli_query($cn, $query);

    $query2= "UPDATE books SET quantity = quantity + 1 WHERE book_id = $book_id";
    mysqli_query($cn, $query2);

    mysqli_close($cn);
    header('Location:' . $_SERVER['HTTP_REFERER']);
}
?>