<?php

class Author {
	public $id;
	public $name;
	public $address;
    public $phone;
    public $email;
	function __construct($id, $name, $address, $phone,$email) {
            $this->id = $id;
            $this->name = $name;
            $this->address = $address;
            $this->phone = $phone;
            $this->email = $email;
        }

}

?>