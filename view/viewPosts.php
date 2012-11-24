<html>
<head></head>

<body>

<table>
	<tr><td>Title</td><td>Author</td><td>Publish date</td></tr>
	<?php 
		echo '<a href="./index.php">Home</a> >> ' . $locationHint . '<br><br>';
		?>
		
	Published after
	<form method="post">
	<input type="text" name="search_date"/>(example: 2012-10-28)
	<input type="submit" value="Search" /><br><br>
	</form>
	<?php
	if (count($posts) != 0){
		foreach ($posts as $title => $post)
		{
			echo '<tr><td><a href="index.php?postId='.$post->id.'">'.$post->title.'</a></td><td><a href="index.php?author='.$post->author.'">'.$post->author.'</td><td>'.$post->publishDate.'</td></tr>';
		}
	}
	?>
</table>
	<br><br><b>New post:</b><br>
	<form method="post">
	Title: <input type="text" name="post_title"/><br>
	Author: <input type="text" name="post_author"/><br>
	Tags: <input type="text" name="post_tags"/>(seperated by "," without space.)<br>
	Content: <TEXTAREA name="post_body" COLS=50 ROWS=10/></TEXTAREA><br>
	<input type="submit" value="Post" />
	</form>

</body>
</html>