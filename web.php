<?php 
require_once 'controllers/AuthController.php';
require_once 'controllers/BookRecord.php';

if($_SERVER['REQUEST_METHOD'] == "GET") {
    $uri = basename($_SERVER['REQUEST_URI']);
    switch($uri) {
        case "logout":
            logout();
            break;
    }
}

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $action = $_POST['action'];

    switch($action){
        case "register":
            register($_POST);
            break;
        case "login":
            login($_POST);
            break;
        case "add_book":
            add_book($_POST);
            break;
        case "edit":
            edit($_POST);
            break;
        case "delete_book":
            delete_book($_POST);
            break;
        case "add_review":
            add_review($_POST);
            break;
        case "delete_review":
            delete_review($_POST);
            break;
        case "add_cart":
            add_cart($_POST);
            break;
        case "pending":
            pending($_POST);
            break;
        case "delete_book_cart":
            delete_cart($_POST);
            break;
        case "update_profile":
            update_profile($_POST);
            break;
        case "approve":
            approve($_POST);
            break;
        case "renew":
            renew($_POST);
            break;
        case "return":
            return_book($_POST);
            break;
    }
}
?>