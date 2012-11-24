<html>
<head></head>

<body>
	<?php
	echo '<a href="./index.php">Home</a> >> ' . $locationHint . '<br><br>';
	?>
	<form method="post">
	Name: <?php echo $author->name?><input type=hidden name="author_name" value="<?php echo $author->name?>"/><br>
	Email: <input type="text" name="author_email" value="<?php echo $author->email?>"/><br>
	Address: <input type="text" name="author_address" value="<?php echo $author->address?>" /><br>
	phone: <input type="text" name="author_phone" value="<?php echo $author->phone?>" />
	<input type="submit" value="Submit" />
	</form>
	<?php
		echo '<a href="index.php?allPostsFor='.$author->name.'">' . 'View all the posts by this author' . '</a><br/>';
	?>
</body>
</html>