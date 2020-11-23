<?php 
session_start();
$session_username=(isset($_SESSION['login_username']))?$_SESSION['login_username']:'';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fbdb";
$postid = $_GET["postid"];
$commentid = $_GET["commentid"];
$subcommentid = $_GET["subcommentid"];

$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (isset($_GET['like']) || isset($_GET['dislike'])) {
	$stmt1 = $conn->prepare("select count(*) from reaction where postid = '$postid' and commentid = '$commentid' and subcommentid = '$subcommentid' and userid = '$session_username'");	
	$stmt1->execute();
	$number_of_rows = $stmt1->fetchColumn(); 
	if ($number_of_rows > 0) {
		header('location: wall.php');
		return;
	}
}

$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
$charactersLength = strlen($characters);
$randomString = '';
for ($i = 0; $i < 10; $i++) {
    $randomString .= $characters[rand(0, $charactersLength - 1)];
}

if (isset($_GET['like'])) {
	$stmt = $conn->prepare("INSERT INTO reaction (reactionid, userid, postid, commentid, subcommentid, liker) VALUES ('$randomString','$session_username','$postid', '$commentid', '$subcommentid', TRUE)");	
	$stmt->execute();
} else {
	$stmt = $conn->prepare("INSERT INTO reaction (reactionid, userid, postid, commentid, subcommentid, dislike) VALUES ('$randomString','$session_username','$postid','$commentid', '$subcommentid', TRUE)");
	
	$stmt->execute();
}

header('location: wall.php');
	
?>