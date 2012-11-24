<?php
include_once("model/PostModel.php");
include_once("model/AuthorModel.php");
include_once("model/TagModel.php");


class CassandraModelController {
	public $PostModel;
	public $AuthorModel;
	public $TagModel;

	public function __construct()
	{
		$this->PostModel = new PostModel();
		$this->AuthorModel = new AuthorModel();
		$this->TagModel = new TagModel();
	}

	public function invoke()
	{
		if (isset($_GET['postId']))
		{
			$post = $this->PostModel->getSinglePost_Cassandra($_GET['postId']);
			$locationHint = "Post";
			include 'view/viewSinglePost.php';
		}else if (isset($_GET['edit'])){
			$post = $this->PostModel->getSinglePost_Cassandra($_GET['edit']);
			$locationHint = "Edit post";
			include 'view/editPost.php';
		}
		else if (isset($_GET['allPostsFor']))
		{
			$posts = $this->PostModel->getAllPostsByAuthorName_Cassandra($_GET['allPostsFor'], 1);
			$locationHint = "Posts posted by <b>" . $_GET['allPostsFor'] . "</b>";
			include 'view/viewPosts.php';
		}
		else if (isset($_GET['date']))
		{
			$posts = $this->PostModel->getAllPostsByDate_Cassandra($_GET['postId']);
			include 'view/viewPosts.php';
		}
		else if (isset($_GET['tag']))
		{
			$posts = $this->TagModel->getAllPostsByTagName_Cassandra($_GET['tag']);
			$locationHint = "Posts with <b>" . $_GET['tag'] . "</b> tag";
			include 'view/viewPosts.php';
		}
		else if (isset($_GET['author']))
		{
			$author = $this->AuthorModel->getSingleAuthor_Cassandra($_GET['author']);
			include 'view/viewAuthorProfile.php';
		}
		else if (isset($_POST['search_date']))
		{
			$posts = $this->PostModel->getAllPostsByDate_Cassandra(1, $_POST['search_date']);
			$locationHint = "Search results for posts published after \"" . $_POST['search_date']. "\"";
			include 'view/viewPosts.php';
		}
		else{
			$posts = $this->PostModel->getAllPosts_Cassandra(1);
			$locationHint = "";
			include 'view/viewPosts.php';
		}
		
		if (isset($_POST['comment_postId'])){
			$indicator = $this->PostModel->addCommentsToPaticularPost_Cassandra($_POST['comment_postId'], $_POST['comment_author'], $_POST['comment_body']);
			$locationHint = $indicator;
		}
		else if(isset($_POST['commentDelete_postId'])){
			$this->PostModel->removeCommentsFromPaticularPost_Cassandra($_POST['commentDelete_postId'], $_POST['comment_author'], $_POST['comment_submitDate'], $_POST['comment_body']);
		}
		else if(isset($_POST['editPost_postId'])){
			$this->PostModel->updatePost_Cassandra($_POST['editPost_postId'], $_POST['editPost_title'], $_POST['editPost_tags'], $_POST['editPost_body']);
		}
		else if(isset($_POST['author_name'])){
			$author = $this->AuthorModel->updateAuthor_Cassandra($_POST['author_name'], $_POST['author_address'], $_POST['author_phone'], $_POST['author_email']);
		}
		else if(isset($_POST['post_title'])){
			$author = $this->PostModel->publishPost_Cassandra($_POST['post_title'], $_POST['post_author'], $_POST['post_tags'], $_POST['post_body']);
		}
	}
}

?>