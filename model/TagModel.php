<?php

include_once("model/Tag.php");
include_once("model/Post.php");
include_once("model/dbConn.php");

class TagModel {
        public $db;
        public $cassandra;
        function __construct(){
            $this->db=$GLOBALS['db'];
            $this->cassandra=$GLOBALS['cassandra'];
        }
        // mongo
        public function findAuthorNameByAuthorStringId($aId){
        	// find author name by author id in string format
        	$collection_author = $this->db->author;
        	$author = $collection_author->findOne(array('_id' => new MongoId($aId)));
        	return $author['name'];
        }
        // mongo
        public function convertMongoDateToRegularDateFormat($mongoDate){
        	// Convert the date format
        	return $date = date('Y-m-d H:i:s', $mongoDate->sec);
        }
        // mongo
        public function getAllPostsByTagName($tagName){
        	// get post ids of a specific tag
        	$collection = $this->db->tag;
        	$tag = $collection->findOne(array('name' => $tagName));
        	// find all the posts, and save as post objects.
        	$collection_post = $this->db->post;
        	$PostMongoObjects = array();
        	foreach ($tag['posts'] as $pId){
        		array_push($PostMongoObjects, $collection_post->findOne(array('_id' => new MongoId($pId))));
        	}
        	$PostObjects = array();
            foreach ($PostMongoObjects as $p){
            	$p['author'] = $this->findAuthorNameByAuthorStringId($p['author']);
            	$p['publish date'] = $this->convertMongoDateToRegularDateFormat($p['publish date']);
            	array_push($PostObjects, new Post($p['_id'], $p['title'], $p['body'], $p['author'], $p['publish date'], $p['tags'], $p['comments']));
            }
        	return $PostObjects;
        }
        // cassandra
        public function getAllPostsByTagName_Cassandra($tagName){
        	$postIds = $this->cassandra->get('postsByTag.' . $tagName);
        	$PostObjects = array();
        	foreach(array_keys($postIds) as $paramName){
        		$p = $this->cassandra->get('post.' . $paramName);
        		$publishDate = $this->convertTimestampToDate($p['publish_date']);
        		array_push($PostObjects, new Post($paramName, $p['title'], $p['body'], $p['author'], $publishDate, null, null));
        	}
        	return $PostObjects;
        }
        
        public function convertTimestampToDate($timestamp){
        	return date('Y-m-d H:i:s', $timestamp);
        }
        
}