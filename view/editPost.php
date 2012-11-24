<html>
<head></head>

<body>
	<?php
	echo '<a href="./index.php">Home</a> >> ' . $locationHint . '<br><br>';
	?>
	<form method="post">
	<input type=hidden name="editPost_postId" value = "<?php echo $_REQUEST['edit']; ?>">
	Title: <input type="text" name="editPost_title" value="<?php echo $post->title?>"/><br>
	Date: <?php echo $post->publishDate?><input type=hidden name="editPost_publishDate" value="<?php echo $post->publishDate?>"/><br>
	Author: <?php echo $post->author?><input type=hidden name="editPost_author" value="<?php echo $post->author?>"/><br>
	<?php
	$n = count($post->tags);
	if (count($post->tags) != 0){
		foreach($post->tags as $t) {
			$n--;
			$string = $string . $t;
			if( $n > 0)
				$string = $string . ',';
		}
		?>
		Tag: <input type="text" name="editPost_tags" value="<?php echo $string?>" /><br>
		<?php
	}else{
		?>
		Tag: <input type="text" name="editPost_tags"/><br>
		<?php
	}
	?>
	Body: <TEXTAREA name="editPost_body" COLS=50 ROWS=10 /><?php echo $post->body?></TEXTAREA>
	<input type="submit" value="Confirm modification" />
	</form>
</body>
</html>