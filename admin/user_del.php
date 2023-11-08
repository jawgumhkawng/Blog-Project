<?php 

session_start();

require '../config/config.php';

if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {

  header('Location: login.php');

}

$id = $_GET['id'];

$stmtU = $pdo->prepare("DELETE FROM users WHERE id=".$_GET['id']);
$stmtU->execute();


$stmtC = $pdo->prepare("DELETE FROM comments WHERE author_id=".$_GET['id']);
$stmtC->execute();

$stmtP = $pdo->prepare("DELETE FROM posts WHERE author_id=".$_GET['id']);
$stmtP->execute();


header("location: users_list.php");

?>