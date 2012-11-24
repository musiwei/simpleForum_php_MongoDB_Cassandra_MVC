<?php

class Post {
	public $id;
	public $title;
	public $body;
	public $author;
    public $publishDate;
    public $tags;
    public $comments;
	
	function __construct($id, $title, $body, $author, $publishDate, $tags, $comments) {
            $this->id = $id;
            $this->title = $title;
            $this->body = $body;
            $this->author = $author;
            $this->publishDate = $publishDate;
            $this->tags = $tags;
            $this->comments = $comments;
        }

}

?>