<?php
include_once("model/PostModel.php");
include_once("model/AuthorModel.php");
include_once("model/TagModel.php");


class MongoModelController {
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
			$post = $this->PostModel->getSinglePost($_GET['postId']);
			$locationHint = "Post";
			include 'view/viewSinglePost.php';
		}else if (isset($_GET['edit'])){
			$post = $this->PostModel->getSinglePost($_GET['edit']);
			$locationHint = "Edit post";
			include 'view/editPost.php';
		}
		else if (isset($_GET['allPostsFor']))
		{
			$posts = $this->PostModel->getAllPostsByAuthorName($_GET['allPostsFor'], 1);
			$locationHint = "Posts posted by <b>" . $_GET['allPostsFor'] . "</b>";
			include 'view/viewPosts.php';
		}
		else if (isset($_GET['date']))
		{
			$posts = $this->PostModel->getAllPostsByDate($_GET['postId']);
			include 'view/viewPosts.php';
		}
		else if (isset($_GET['tag']))
		{
			$posts = $this->TagModel->getAllPostsByTagName($_GET['tag']);
			$locationHint = "Posts with <b>" . $_GET['tag'] . "</b> tag";
			include 'view/viewPosts.php';
		}
		else if (isset($_GET['author']))
		{
			$author = $this->AuthorModel->getSingleAuthor($_GET['author']);
			include 'view/viewAuthorProfile.php';
		}
		else if (isset($_POST['search_date']))
		{
			$posts = $this->PostModel->getAllPostsByDate(1, $_POST['search_date']);
			$locationHint = "Search results for posts published after \"" . $_POST['search_date']. "\"";
			include 'view/viewPosts.php';
		}
		else{
			$posts = $this->PostModel->getAllPosts(1);
			$locationHint = "";
			include 'view/viewPosts.php';
		}
		
		if (isset($_POST['comment_postId'])){
			$indicator = $this->PostModel->addCommentsToPaticularPost($_POST['comment_postId'], $_POST['comment_author'], $_POST['comment_body']);
			$locationHint = $indicator;
		}
		else if(isset($_POST['commentDelete_postId'])){
			$this->PostModel->removeCommentsFromPaticularPost($_POST['commentDelete_postId'], $_POST['comment_author'], $_POST['comment_submitDate'], $_POST['comment_body']);
		}
		else if(isset($_POST['editPost_postId'])){
			$this->PostModel->updatePost($_POST['editPost_postId'], $_POST['editPost_title'], $_POST['editPost_tags'], $_POST['editPost_body']);
		}
		else if(isset($_POST['author_name'])){
			$author = $this->AuthorModel->updateAuthor($_POST['author_name'], $_POST['author_address'], $_POST['author_phone'], $_POST['author_email']);
		}
		else if(isset($_POST['post_title'])){
			$author = $this->PostModel->publishPost($_POST['post_title'], $_POST['post_author'], $_POST['post_tags'], $_POST['post_body']);
		}
	}
}

?>