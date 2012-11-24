<html>
<head></head>

<body>

<?php 
	echo '<a href="./index.php">Home</a> >> ' . $locationHint . '<br><br>';

	echo '<a href="index.php?edit='.$_REQUEST['postId'].'">Edit post</a>';
	echo '<br/>';
	echo 'Title:' . $post->title . '<br/>';
	echo 'Date:' . $post->publishDate . '<br/>';
	echo 'Author:<a href="index.php?author='.$post->author.'">' . $post->author . '</a><br/>';
	echo 'Tag:';
	if (count($post->tags) != 0){
	foreach($post->tags as $tagName) {echo '<a href="index.php?tag='.$tagName.'">'.$tagName . '</a>'; echo ' ';}}
	echo '<br/>';
	echo 'Body:' . $post->body . '<br/>';
	echo '<br/><br/>';
	echo 'Comment: ';echo '<br/>';
	if (count($post->comments) != 0){
	foreach($post->comments as $comment) {echo 'Author: <a href="index.php?author='.$comment['author'].'">' .$comment['author'] . '</a><br/>'; 
										  echo 'Submit date: ' .$comment['submit date'] . '<br/>'; 
										  echo 'Comment body:' .$comment['body']. '<br/><br/>';
?>
	<form method="post">
	<input type=hidden name="commentDelete_postId" value = "<?php echo $_REQUEST['postId']; ?>">
	<input type=hidden name="comment_author" value = "<?php echo $comment['author'] ?>">
	<input type=hidden name="comment_submitDate" value = "<?php echo $comment['submit date']; ?>">
	<input type=hidden name="comment_body" value = "<?php echo $comment['body']; ?>">
	<input type="submit" value="Delete" onClick="confirm( 'Do you want to delete this comment?' )"/>
	</form>							  
<?php
	}
	}
?>
	<form method="post">
	<input type=hidden name="comment_postId" value = "<?php echo $_REQUEST['postId']; ?>">
	Author: <input type="text" name="comment_author"/>
	Comment: <input type="text" name="comment_body"/>
	<input type="submit" value="Submit" />
	</form>
</body>
</html>