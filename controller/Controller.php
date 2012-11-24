<?php
include_once("../model/AuthorModel.php");
include_once("model/PostModel.php");
include_once("model/TagModel.php");
include_once("model/Model.php");

class Controller {
	public $AuthorModel;
	public $model;
        public $PostModel;
        public $TagModel;
	
	public function __construct()  
        {  
            $this->AuthorModel = new AuthorModel();
            $this->PostModel = new PostModel();
            $this->TagModel = new TagModel();
            $this->model = new Model();
        }
	
	public function invoke()
	{
		if (!isset($_GET['book']))
		{
			// no special book is requested, we'll show a list of all available books
			$books = $this->model->getBookList();
			include 'view/booklist.php';
		}
		else
		{
			// show the requested book
			$book = $this->model->getBook($_GET['book']);
			include 'view/viewbook.php';
		}
	}
}

?>