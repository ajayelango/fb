<html>
<body>

<?php
session_start();

include_once './config/database.php';

header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fbdb";
$logged_in = false;
$first_name = $_POST["firstname"];
$last_name = $_POST["lastname"];
$login_username = $_POST["username"];
$login_password = $_POST["password"];
$login_repassword = $_POST["repassword"];

try {
   	
  if ($login_password != $login_repassword) {
	printf("Passwords do not match");
	return;
	// header('location: register.php');
  }
	
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $stmt = $conn->prepare("SELECT * FROM user where username='$login_username'");
  $stmt->execute();
	
  // set the resulting array to associative
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
  
  if ($stmt->rowCount() > 0) {
	http_response_code(400); 
 echo json_encode(array("message" => "Username already exists in the database"));	
  } else {
	  
	$login_password_hash = password_hash($login_password, PASSWORD_BCRYPT);  
	  
	$stmt2 = $conn->prepare("INSERT INTO user (first_name, last_name, username, password) VALUES ('$first_name','$last_name','$login_username', '$login_password_hash')");
	$stmt2->execute();
	echo "Registration successfull";
	// Storing username in session variable 
    $_SESSION['login_username'] = $login_username; 
	http_response_code(200);
    header('location: profile.php');
  }
}
catch(PDOException $e) {
  echo "Error: " . $e->getMessage();
}
$conn = null;
?>

</body>
</html>