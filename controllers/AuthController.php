<?php 
require_once 'connection.php';

function register($request) {
    global $cn;

    $errors = 0;
    $fullname = $request['fullname'];
    $email = $request['email'];
    $contactno = $request['contactno'];
    $username = $request['username'];
    $password = $request['password'];
    $password2 = $request['password2'];
    $genre =  $request['genre_id'];
    $language = $request['language_id'];
    
    if(!isset($genre) && !isset($language)) {
        $errors++;
    }
    
    if(strlen($username) < 8) {
        $errors++;
    }

    if(strlen($fullname) < 1) {
        $errors++;
    }

    if(strlen($password) < 8) {
        $errors++;
    }

    if($password != $password2) {
        $errors++;
    }

    if($username || $email ||$contactno) {
        $query = "SELECT  username,email,contactno FROM reader WHERE username = '$username' OR  email ='$email' OR contactno ='$contactno'";
        $result = mysqli_fetch_assoc(mysqli_query($cn, $query));
        if($result) {
            $errors++;
            mysqli_close($cn);
        }
    }


    if($errors === 0) {
        session_start();
        $password = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO reader(fullname, email, contactno, username, password, genre, language) VALUES ('$fullname', '$email', '$contactno', '$username', '$password','$genre', '$language')";
        mysqli_query($cn, $query);
        mysqli_close($cn);
        header('Location: /');
        $_SESSION['message'] = "Register Successfully";
        $_SESSION['class'] = "teal darken-2";
    } else {
        session_start();
        $_SESSION['message'] = "Register Failed(Credentials might already exists)";
        $_SESSION['class'] = "red darken-2";
        header('Location:' . $_SERVER['HTTP_REFERER']);
    }


    
}

function login($request) {
    global $cn;

    $username = $request['username'];
    $password = $request['password'];

    $query = "SELECT * FROM reader WHERE username = '$username'";
    $reader = mysqli_fetch_assoc(mysqli_query($cn, $query));

    if($reader && password_verify($password, $reader['password'])) {
        session_start();
        $_SESSION['user_data'] = $reader;
        $_SESSION['message'] = "Logged in successfully";
        $_SESSION['class'] = "teal darken-2";
        mysqli_close($cn);
        header('Location: /');
    } else {
        session_start();
        $_SESSION['message'] = "Invalid Credentials";
        $_SESSION['class'] = "red darken-3";
        header('Location:' . $_SERVER['HTTP_REFERER']);
    }
}

function logout() {
    session_start();

    session_unset();

    session_destroy();

    header('Location: /');
}
?>

