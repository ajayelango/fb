<?php 
session_start();
$session_username=(isset($_SESSION['login_username']))?$_SESSION['login_username']:'';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fbdb";
$userid = $_GET["userid"];
$subcommentid = $_GET["subcommentid"];

$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($userid == $session_username) {
	$stmt = $conn->prepare("Delete from subcomment where subcommentid = '$subcommentid'");
	$stmt->execute();
}
  
header('location: wall.php');
	
?>