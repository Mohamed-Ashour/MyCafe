<?php 

session_start();

if(!isset($_SESSION['user']) && !isset($_COOKIE['user'])){
    header("Location: Login.php");
}

if(isset($_SESSION['user'])){
    if($_SESSION['user']!="admin") {
        header("Location: UserOrder.php");
    }
    else {
        header("Location: AdminHome.php");
    }
}

if(isset($_COOKIE['user'])){
    if($_COOKIE['user']!="admin") {
		header("Location: UserOrder.php");
    }
    else {
        header("Location: AdminHome.php");
    }
}
