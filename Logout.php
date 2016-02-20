
<?php

session_start();

if($_POST) {

    if( isset($_SESSION["user"]) ) {
        $_SESSION = array();
        session_unset();
        session_destroy();
    }

    if( isset($_COOKIE["user"]) ) {
        setcookie('user', '', time() - (86400 * 30));
        setcookie('user_id', '', time() - (86400 * 30));
    }
    header("Location: Login.php");
}

?>
