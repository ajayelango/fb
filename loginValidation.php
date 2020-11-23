<html>
<body>

<?php

include_once './config/database.php';
require "./vendor/autoload.php";
use \Firebase\JWT\JWT;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fbdb";
$logged_in = false;

$login_username = $_POST["username"];
$login_password = $_POST["password"];

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $stmt = $conn->prepare("SELECT * FROM user where username='$login_username'");
  $stmt->execute();
	
  // set the resulting array to associative
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
  
  if ($stmt->rowCount() == 0) {
	printf("Login Denied as username or password is incorrect");
  }
  
	if (!password_verify($login_password, $result[0]['password'])) {
		printf("Login Denied as username or password is incorrect");
	} else {
		
		// Storing username in session variable 
        $_SESSION['login_username'] = $login_username; 
		
        // Welcome message 
        $_SESSION['success'] = "You have logged in!";
		
		printf("Login successfull");
		
		$firstname = $result[0]['first_name'];
		$lastname = $result[0]['last_name'];
		
		$secret_key = "eyJ1c2VyX2lkIjoxLCJyb2xlIjoiYWRtaW4iLCJleHAiOjE1OTM4MjgyMjJ9";
        $issuer_claim = "THE_ISSUER"; 
        $audience_claim = "THE_AUDIENCE";
        $issuedat_claim = time(); // issued at
        $notbefore_claim = $issuedat_claim + 10;
        $expire_claim = $issuedat_claim + 60;
        $token = array(
            "iss" => $issuer_claim,
            "aud" => $audience_claim,
            "iat" => $issuedat_claim,
            "nbf" => $notbefore_claim,
            "exp" => $expire_claim,
            "data" => array(
                "id" => $id,
                "firstname" => $firstname,
                "lastname" => $lastname,
                "username" => $login_username
        ));

        http_response_code(200);
		header('location: profile.php');
        $jwt = JWT::encode($token, $secret_key);
        echo json_encode(
            array(
                "message" => "Successful login.",
                "jwt" => $jwt,
                "email" => $email,
                "expireAt" => $expire_claim
            ));
	}
 
}
catch(PDOException $e) {
  echo "Error: " . $e->getMessage();
}
$conn = null;
?>

</body>
</html>