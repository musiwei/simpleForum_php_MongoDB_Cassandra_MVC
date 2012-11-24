<?php

include_once("Author.php");
include_once("dbConn.php");

class AuthorModel {
        public $db;
        public $cassandra;
        function __construct(){
            $this->db=$GLOBALS['db'];
            $this->cassandra=$GLOBALS['cassandra'];
        }
        // mongo
        public function getSingleAuthor($name){
        	$collection = $this->db->author;
        	$author = $collection->findOne(array('name' => $name));
        	$singleAuthorObject = new Author($author['_id'], $author['name'], $author['post address'], $author['phone'], $author['email']); // convert into an author object
        	return $singleAuthorObject;
        }
        // cassandra
        public function getSingleAuthor_Cassandra($name){
        	$author = $this->cassandra->get('author.' . $name);
        	$singleAuthorObject = new Author(null, $name, $author['address'], $author['phone'], $author['email']); // convert into an author object
        	return $singleAuthorObject;
        }
       // mongo
        public function updateAuthor($name, $address, $phone, $email){
            $collection = $this->db->author;
            $newdata = array('name' => $name, 'email' => $email, 'post address' => $address, 'phone' => $phone);
            $collection->update(array("name" => $name), array('$set' => $newdata));
        }
        // cassandra
        public function updateAuthor_Cassandra($name, $address, $phone, $email){
        	$this->cassandra->set(
        			'author.' . $name,
        			array(
        					'email' => $email,
        					'address' => $address,
        					'phone' => $phone
        			)
        	);
        }
}