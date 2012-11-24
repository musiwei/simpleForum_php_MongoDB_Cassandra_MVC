<?php

class Tag {
	public $id;
	public $name;
	public $posts;
	
	public function __construct($id, $name, $posts)  
    {  
        $this->id = $id;
        $this->name = $name;
        $this->posts = $posts;
    }
}

?>