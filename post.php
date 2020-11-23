<?php 
session_start();
$session_username=(isset($_SESSION['login_username']))?$_SESSION['login_username']:'';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fbdb";
$postComment = $_POST["postComment"];

$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
$charactersLength = strlen($characters);
$randomString = '';
for ($i = 0; $i < 10; $i++) {
    $randomString .= $characters[rand(0, $charactersLength - 1)];
}

$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$stmt = $conn->prepare("INSERT INTO post (userid, postid, post_message) VALUES ('$session_username','$randomString','$postComment')");
$stmt->execute();
  
header('location: wall.php');
	
?>