<?php

//include_once './config/database.php';
require "./vendor/autoload.php";
use \Firebase\JWT\JWT;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$secret_key = "eyJ1c2VyX2lkIjoxLCJyb2xlIjoiYWRtaW4iLCJleHAiOjE1OTM4MjgyMjJ9";
$jwt = null;

$authHeader = $_SERVER['HTTP_AUTHORIZATION'];

$arr = explode(" ", $authHeader);

$jwt = $arr[1];

if($jwt){

    try {
	
        $decoded = JWT::decode($jwt, $secret_key, array('HS256'));

        echo json_encode(array(
            "message" => "Access granted:",
            "error" => $e->getMessage()
        ));
	
    }catch (Exception $e){

    http_response_code(401);

    echo json_encode(array(
        "message" => "Access denied.",
        "error" => $e->getMessage()
    ));
}

}
?>

<?php 
session_start();
$session_username=(isset($_SESSION['login_username']))?$_SESSION['login_username']:'';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fbdb";

$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $stmt2 = $conn->prepare("SELECT * FROM post order by posted_date desc");
  $stmt2->execute();
	
  // set the resulting array to associative
  $postCommentresult = $stmt2->fetchAll(PDO::FETCH_ASSOC);
?>
<HTML>
<body>
<?php foreach($postCommentresult as $row) { ?> 
	<br><br>
	<h3><?php echo $row['userid'], ": ", $row['post_message'], " ",
		$row['posted_date'] ?></h3>
	
	<form action="comment.php" method="get">
	 <input type="hidden" name="postid" value="<?php echo $row['postid']; ?>">	
	<textarea id="comment" name="comment" rows="3" cols="50" placeholder="Write your comments here..."></textarea>
	<br><br>
	<input type="submit" value="comment">
	</form>
	
		
	<form action="deletePost.php" method="get">
	 <input type="hidden" name="postid" value="<?php echo $row['postid']; ?>">
	 <input type="hidden" name="userid" value="<?php echo $row['userid']; ?>">
	<input type="submit" value="delete">
	</form>
		
	<?php
		$postid = $row['postid'];
		$stmt1 = $conn->prepare("select count(*) from reaction where postid = '$postid' and commentid is NULL and subcommentid is NULL and liker = 1");	
		$stmt1->execute();
		$number_of_rows_like = $stmt1->fetchColumn(); 
		
		$stmt2 = $conn->prepare("select count(*) from reaction where postid = '$postid'  and commentid is NULL and subcommentid is NULL and dislike = 1");	
		$stmt2->execute();
		$number_of_rows_dislike = $stmt2->fetchColumn(); 
	?>
	
	
	<form action="reaction.php" method="get">
	<input type="hidden" name="postid" value="<?php echo $row['postid']; ?>">	
	<?php echo $number_of_rows_like; ?> <input type="submit" name="like" value="Like">
	<?php echo $number_of_rows_dislike; ?> <input type="submit" name="dislike" value="Dislike">
	</form>
	
	<?php
		 $var_postid = $row['postid'];
		 $stmt3 = $conn->prepare("SELECT * FROM comment where postid = '$var_postid' order by commented_on desc");
		 $stmt3->execute();
	
		// set the resulting array to associative
		$commentresult = $stmt3->fetchAll(PDO::FETCH_ASSOC);
	?>

	<?php foreach($commentresult as $row2) { ?> 
		<blockquote id="commentPosted"><?php echo $row2['userid'], ": ", $row2['comment_message'], " ", $row2['commented_on']; ?>
		<form action="subcomment.php" method="get">
			<input type="hidden" name="postid" value="<?php echo $row['postid']; ?>">	
			<input type="hidden" name="commentid" value="<?php echo $row2['commentid']; ?>">	
			<textarea id="subcomment" name="subcomment" rows="3" cols="50" placeholder="Write your comments here..."></textarea>
			<br><br>
			<input type="submit" value="comment">
		</form>
		
		<form action="deleteComment.php" method="get">
			<input type="hidden" name="commentid" value="<?php echo $row2['commentid']; ?>">
			<input type="hidden" name="userid" value="<?php echo $row2['userid']; ?>">
			<input type="submit" value="delete">
		</form>
		
		<?php
			$postid = $row['postid'];
			$commentid = $row2['commentid'];
			$stmt4 = $conn->prepare("select count(*) from reaction where postid = '$postid' and commentid = '$commentid' and subcommentid is NULL and liker = 1");	
			$stmt4->execute();
			$number_of_comment_rows_like = $stmt4->fetchColumn(); 
		
			$stmt5 = $conn->prepare("select count(*) from reaction where postid = '$postid' and commentid = '$commentid' and subcommentid is NULL and dislike = 1");	
			$stmt5->execute();
			$number_of_comment_rows_dislike = $stmt5->fetchColumn(); 
		?>
		
		<form action="commentreaction.php" method="get">
			<input type="hidden" name="postid" value="<?php echo $row['postid']; ?>">	
			<input type="hidden" name="commentid" value="<?php echo $row2['commentid']; ?>">	
			<?php echo $number_of_comment_rows_like; ?> <input type="submit" name="like" value="Like">
			<?php echo $number_of_comment_rows_dislike; ?> <input type="submit" name="dislike" value="Dislike">
		</form>
		
		<?php
			$var_postid = $row['postid'];
			$var_commentid = $row2['commentid'];
			$stmt4 = $conn->prepare("SELECT * FROM subcomment where postid = '$var_postid' and commentid = '$var_commentid' order by subcommented_on desc");
			$stmt4->execute();
	
			// set the resulting array to associative
			$subcommentresult = $stmt4->fetchAll(PDO::FETCH_ASSOC);
		?>
			
		<?php foreach($subcommentresult as $row3) { ?>
			<blockquote id="subcommentPosted"><?php echo $row3['userid'], ": ", $row3['subcomment_message'], " ", $row3['subcommented_on']; ?>
		
			<?php
				$postid = $row['postid'];
				$commentid = $row2['commentid'];
				$subcommentid = $row3['subcommentid'];
				$stmt4 = $conn->prepare("select count(*) from reaction where postid = '$postid' and commentid = '$commentid' and subcommentid = '$subcommentid' and liker = 1");	
				$stmt4->execute();
				$number_of_subcomment_rows_like = $stmt4->fetchColumn(); 
		
				$stmt5 = $conn->prepare("select count(*) from reaction where postid = '$postid' and commentid = '$commentid' and subcommentid = '$subcommentid' and dislike = 1");	
				$stmt5->execute();
				$number_of_subcomment_rows_dislike = $stmt5->fetchColumn(); 
			?>
		
			<form action="deleteSubComment.php" method="get">
				<input type="hidden" name="subcommentid" value="<?php echo $row3['subcommentid']; ?>">
				<input type="hidden" name="userid" value="<?php echo $row3['userid']; ?>">
				<input type="submit" value="delete">
			</form>
		
			<form action="subcommentreaction.php" method="get">
				<input type="hidden" name="postid" value="<?php echo $row['postid']; ?>">	
				<input type="hidden" name="commentid" value="<?php echo $row2['commentid']; ?>">
				<input type="hidden" name="subcommentid" value="<?php echo $row3['subcommentid']; ?>">
				<?php echo $number_of_subcomment_rows_like; ?> <input type="submit" name="like" value="Like">
				<?php echo $number_of_subcomment_rows_dislike; ?> <input type="submit" name="dislike" value="Dislike">
			</form>	
			</blockquote>
		<?php } ?>
		
		</blockquote>
	<?php } ?>
	
<?php } ?>

</body>
</HTML>