<?php

include_once("model/Tag.php");
include_once("model/Post.php");
include_once("model/dbConn.php");
include_once("lib/class.uuid.php");

class PostModel {
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
        public function findTagNameListByTagStringIds($tIds){
        	// find tag names by tag id list in string format
            $collection_tag = $this->db->tag;
            $tagNames = array();
            if(count($tIds) != 0){
            	foreach($tIds as $tId){
            		$tag = $collection_tag->findOne(array('_id' => new MongoId($tId)));
            		array_push($tagNames, $tag['name']);
            	}
            	return $tagNames;
            }
        }

        // mongo
        public function commentsFormating($comments){

        	function date_compare($a, $b)
        	{
        		$t1 = strtotime($a['submit date']);
        		$t2 = strtotime($b['submit date']);
        		return $t2 - $t1;
        	}
        	$formatedComments = array();
        	if(count($comments) != 0){
        		foreach($comments as $comment){
        			$comment['author'] = $this->findAuthorNameByAuthorStringId($comment['author']);
        			$comment['submit date'] = $this->convertMongoDateToRegularDateFormat($comment['submit date']);
        			array_push($formatedComments, $comment);
        		}
        		usort($formatedComments, 'date_compare');
        		return $formatedComments;
        	}
        }
        
        // mongo
        public function convertMongoDateToRegularDateFormat($mongoDate){
        	// Convert the date format
        	return $date = date('Y-m-d H:i:s', $mongoDate->sec);
        }
        
        //cassandra
        public function convertTimestampToDate($timestamp){
        	return date('Y-m-d H:i:s', $timestamp);
        }
        
        // mongo
        public function getSinglePost($_id){
        	$collection = $this->db->post;
        	$p = $collection->findOne(array('_id' => new MongoId($_id)));
        	$p['author'] = $this->findAuthorNameByAuthorStringId($p['author']);
        	$p['publish date'] = $this->convertMongoDateToRegularDateFormat($p['publish date']);
        	$p['tags'] = $this->findTagNameListByTagStringIds($p['tags']);
        	$p['comments'] = $this->commentsFormating($p['comments']);
        	$singlePostObject = new Post($p['_id'], $p['title'], $p['body'], $p['author'], $p['publish date'], $p['tags'], $p['comments']); // convert into a post object
        	return $singlePostObject; 
        }
        
        // cassandra
        public function getSinglePost_Cassandra($_id){
        	function date_compare($a, $b)
        	{
        		$t1 = strtotime($a['submit date']);
        		$t2 = strtotime($b['submit date']);
        		return $t2 - $t1  ;
        	}
        	$post = $this->cassandra->get('post.' . $_id);
        	// get tags
        	$tags = $this->cassandra->get('tagsByPost.' . $_id);
        	$tagArray = array();
        	foreach(array_keys($tags) as $paramName){
        		array_push($tagArray, $paramName);
        	}
        	// get comments
        	$commentIds = $this->cassandra->get('commentsByPost.' . $_id);
        	$commentArray = array();
        	if(count($commentIds) > 0 ){
        		foreach($commentIds as $cId){
        			$comment = $this->cassandra->get('comment.' . $cId);
        			$comment['submit date'] = $this->convertTimestampToDate($comment['submit_date']);
        			array_push($commentArray, $comment);
        			usort($commentArray, 'date_compare');
        		}
        	}
        	$post['publish date'] = $this->convertTimestampToDate($post['publish_date']);
        	$singlePostObject = new Post($_id, $post['title'], $post['body'], $post['author'], $post['publish date'], $tagArray, $commentArray); // convert into a post object
        	return $singlePostObject;
        }
        
        // mongo
        public function getAllPosts($order){
        	$collection = $this->db->post;
        	$posts = $collection->find();
        	$PostObjects = array();
        	foreach ($posts as $p){
        		$p['author'] = $this->findAuthorNameByAuthorStringId($p['author']);
        		$p['publish date'] = $this->convertMongoDateToRegularDateFormat($p['publish date']);
        		// create post object, and save in an array
        		array_push($PostObjects, new Post($p['_id'], $p['title'], $p['body'], $p['author'], $p['publish date'], $p['tags'], $p['comments']));
        	}
        	return $PostObjects;
        }
        
        // cassandra
        public function getAllPosts_Cassandra($order){
        	$posts = $this->cassandra->cf('post')->getKeyRange(100);
        	$posts = $posts->getAll();
        	$PostObjects = array();
        	// get id
        	$_id = array_keys($posts);
        	$n = 0;
        	foreach ($posts as $p){
        		//***at this stage there is no need to get tags and comments because this function returns value to viewPosts.php
//         		// get tags
//         		$tags = $this->cassandra->get('tagsByPost.' . $_id[$n]);
//         		$tagArray = array();
//         		foreach(array_keys($tags) as $paramName){
//         			array_push($tagArray, $paramName);
//         		}
//         		// get comments
//         		$commentIds = $this->cassandra->get('commentsByPost.' . $_id[$n]);
//         		$commentArray = array();
//         		foreach($commentIds as $cId){
//         			$comment = $this->cassandra->get('comment.' . $cId);
//         			array_push($commentArray, $comment);
//         		}
        		$p['publish date'] = $this->convertTimestampToDate($p['publish_date']);
        		array_push($PostObjects, new Post($_id[$n], $p['title'], $p['body'], $p['author'], $p['publish date'], $p['tags'], $p['comments']));
        		$n++;
        	}
        	return $PostObjects;
        }
        // mongo
        public function getAllPostsByDate($order, $date){
            if(isset($date)){
            	$dateMongo = new MongoDate(strtotime($date));
            	
            	$collection = $this->db->post;
            	$posts = $collection->find(array("publish date" => array('$gt' => $dateMongo)));
            	$PostObjects = array();
            	foreach ($posts as $p){
            		$p['author'] = $this->findAuthorNameByAuthorStringId($p['author']);
            		$p['publish date'] = $this->convertMongoDateToRegularDateFormat($p['publish date']);
            		array_push($PostObjects, new Post($p['_id'], $p['title'], $p['body'], $p['author'], $p['publish date'], $p['tags'], $p['comments']));
            	}
            	return $PostObjects;
            }
        }
        // cassandra
        public function getAllPostsByDate_Cassandra($order, $date){
        	if(isset($date)){
        		$posts = $this->cassandra->cf('post')->getKeyRange(100);
        		$posts = $posts->getAll();
        		$postIdArray = array();
        		foreach(array_keys($posts) as $paramName){
        			array_push($postIdArray, $paramName);
        		}
        		$n = 0;
        		$searchDate = new DateTime($date);
        		$searchDate = $searchDate->getTimestamp();
        		$validPostIdArray = array();
        		foreach($posts as $p){
        			if ($p['publish_date'] > $searchDate)
        				array_push($validPostIdArray, $postIdArray[$n]);
        			$n++;
        		}
        		$PostObjects = array();
        		foreach ($validPostIdArray as $pId){
        			$post = $this->cassandra->get('post.' . $pId);
        			$post['publish date'] = $this->convertTimestampToDate($post['publish_date']);
        			array_push($PostObjects, new Post($pId, $post['title'], $post['body'], $post['author'], $post['publish date'], null, null));
        		}
        		return $PostObjects;
        	}
        }
        
        // mongo
        public function getTagsOfSinglePost($_id){
        	// get tags of a specific post
        	$collection = $this->db->post;
        	$post = $collection->findOne(array('_id' => new MongoId($_id)));
        	// find all the tag names, and save as tag objects.
        	$collection = $this->db->tag;
        	foreach ($post['tagIds'] as $tId){
        		array_push($TagObjects, $collection_post->find(array('_id' => new MongoId($pId))));
        	}
        	return $TagObjects;
        }
        // cassandra
        public function getTagsOfSinglePost_Cassandra($_id){
            // get tags
        	$tags = $this->cassandra->get('tagsByPost.' . $_id);
        	$tagArray = array();
        	foreach(array_keys($tags) as $paramName){
        		array_push($tagArray, $paramName);
        	}
        	return $tagArray;
        }
        
        // mongo
        public function getAllPostsByAuthorName($author_name, $order){
        	function date_compare($a, $b)
        	{
        		$t1 = strtotime($a->publishDate);
        		$t2 = strtotime($b->publishDate);
        		return $t2 - $t1;
        	}
        	// find author id
        	$collection_author = $this->db->author;
        	$author = $collection_author->findOne(array('name' => $author_name));
        	// find all the posts of this author
        	$collection_post = $this->db->post;
        	$posts = $collection_post->find(array('author' => (string)$author['_id']));
        	$PostObjects = array();
            foreach ($posts as $p){
            	$p['author'] = $this->findAuthorNameByAuthorStringId($p['author']);
            	$p['publish date'] = $this->convertMongoDateToRegularDateFormat($p['publish date']);
            	array_push($PostObjects, new Post($p['_id'], $p['title'], $p['body'], $p['author'], $p['publish date'], $p['tags'], $p['comments']));
            }
            usort($PostObjects, 'date_compare');
            return $PostObjects;
        }
        
        // cassandra
        public function getAllPostsByAuthorName_Cassandra($author_name, $order){
        	function date_compare($a, $b)
        	{
        		$t1 = strtotime($a->publishDate);
        		$t2 = strtotime($b->publishDate);
        		return $t2 - $t1;
        	}
        	
        	$posts = $this->cassandra->get('postsByAuthor.' . $author_name);
        	$PostObjects = array();
        	foreach($posts as $p){
        		$post = $this->cassandra->get('post.' . $p);
        		$post['publish date'] = $this->convertTimestampToDate($post['publish_date']);
        		array_push($PostObjects, new Post($p, $post['title'], $post['body'], $post['author'], $post['publish date'], null, null));
        	}
        	usort($PostObjects, 'date_compare');
        	return $PostObjects;
        }
        
        // mongo
        public function publishPost($title, $author, $tags, $body){
        	$today = date("Y-m-d H:i:s");
        	$tagArray = explode(',', $tags);
        	
        	$collection_author = $this->db->author;
        	$au =  $collection_author->findOne(array('name' => $author));
        	if(!isset($au) || empty($au)){
        		echo "Failed. No such author name. \"" . $author . "\"";
        		exit;
        	}
        	
        	$collection_tag = $this->db->tag;
        	$tagIdArray = array();
        	foreach( $tagArray as $t ){
        		$tag = $collection_tag->findOne(array('name' => $t));
        		if (!isset($tag) || empty($tag)){
        			echo "No such tag called. \"" . $t . "\", deleted automatically. ";
        		} else{
        			array_push($tagIdArray, (string)$tag['_id']);
        		}
        	}
        	echo count($tagIdArray) . " tag(s) are valid for this post. ";
        	
        	$collection = $this->db->post;
        	$post = array('title' => $title,
        				'body' => $body,
        				'publish date' => new MongoDate(strtotime($today)),
        				'tags' => $tagIdArray,
        				'author' => (string)$au['_id'],
        	);
        	$collection->insert($post);
        	// get the post just added
        	$newPost = $collection->findOne(array('publish date' => new MongoDate(strtotime($today)), 'title' => $title, 'author' => (string)$au['_id']));
        	// update the tag
        	if(count($tagIdArray) != 0){
        		foreach($tagIdArray as $tId){
        			$collection_tag->update(array('_id' => new MongoId($tId)), array('$push' => array("posts" => (string)$newPost['_id'])));
        		}
        	}
        }
        // problems here left: 1. when put only 1 tag, won't trigger the postsByTag updating. (some problem with Author CHECK)
        // cassandra
        public function publishPost_Cassandra($title, $author, $tags, $body){
        	if ($tags != "" && isset($tags) && $tags != null){
        		// break the $tags string by comma, and store in an array
        		$tagArray = explode(',', $tags);
        	}
        	// generate a pseudo random string as post id
        	$str = UUID::generate(UUID::UUID_RANDOM, UUID::FMT_STRING);
        	// get current date
        	$today = new DateTime();
        	// check if the author name exists
        	$authorCheck = $this->cassandra->get('author.' . $author);
        	$authorCheckFlag = false;
        	if (!empty($authorCheck)||isset($authorCheck)){
        		$authorCheckFlag = true;
        		// store the author information
        		$this->cassandra->set(
        				'postsByAuthor.' . $author,
        				array(
        						$today->getTimestamp() => $str
        				)
        		);
        	}
        	
        	// no need to update tag information for posts if no tag is added or author doesn't exist
        	if(count($tagArray) != 0 && $authorCheckFlag){
        		// find all the tags and store them in an array
        		$tags_valid = array();
        		foreach($tagArray as $t_new){
        			$tagCheck = $this->cassandra->get('postsByTag.' . $t_new);
        			if (!empty($tagCheck)||isset($tagCheck)){
        				array_push($tags_valid, $t_new);
        			}
        		}
        		// store the tag information for this post
        		// if no tag is valid, updating will not happen.
        		if(count($tags_valid) != 0){
        			foreach($tags_valid as $validTag){
        				$this->cassandra->set(
        						'tagsByPost.' . $str,
        						array(
        								$validTag => ''
        						)
        				);
        			}
        		}
        	}
				if($authorCheckFlag){
					// store the new post with random id.
					$this->cassandra->set(
							'post.' . $str,
							array(
									'author' => $author,
									'title' => $title,
									'body' => $body,
									'publish_date' => $today->getTimestamp() // convert date format to timestamp
							)
					);
				}
        }
        
        // mongo
        public function addCommentsToPaticularPost($postId, $author, $body){
        	$today = date("Y-m-d H:i:s");
        	// find author id
        	$collection_author = $this->db->author;
        	$au =  $collection_author->findOne(array('name' => (string)$author));
            if(!isset($au) || empty($au)){
        		echo "Failed. No such author name. \"" . $author . "\"";
        		exit;
        	}

        	try{
         		$collection_post = $this->db->post;
        		$newComment = array('author' => (string)$au['_id'],
        				'submit date' => new MongoDate(strtotime($today)),
        				'body' => $body);
        		$collection_post->update(array('_id' => new MongoId($postId)), array('$push' => array("comments" => $newComment)));
        		echo "Successfully commented!";
        		return true;
        	}catch(Exception $e){
        		throw new Exception( 'Failed', 0, $e);
        		echo "Failed!";
        		return false;
        	}
        } 
        
        // cassandra
        public function addCommentsToPaticularPost_Cassandra($postId, $author, $body){
        	$authorCheck = $this->cassandra->get('author.' . $author);
        	// generate a pseudo random string as comment id
        	$str = UUID::generate(UUID::UUID_RANDOM, UUID::FMT_STRING);
        	// get current date
        	$today = new DateTime();
        	if (!empty($authorCheck)||isset($authorCheck)){
        		$this->cassandra->set(
        			'commentsByPost.' . $postId,
        			array(
        					$today->getTimestamp() => $str,
        			)
        		);
        		$this->cassandra->set(
        				'comment.' . $str,
        				array(
        						'author' => $author,
        						'body' => $body,
        						'submit_date' => $today->getTimestamp()
        				)
        		);
        	}
        }
        // mongo
		public function removeCommentsFromPaticularPost($postId, $author, $submitDate, $body){
			// find author id
			$collection_author = $this->db->author;
			$au =  $collection_author->findOne(array('name' => $author));
			if(!isset($au) || empty($au)){
				echo "Failed. No such author name. \"" . $author . "\"";
				exit;
			}
			
			$collection_post = $this->db->post;
			$oldComment = array('author' => (string)$au['_id'],
								'submit date' => new MongoDate(strtotime($submitDate)),
								'body' => $body);
			$collection_post->update(array('_id' => new MongoId($postId)), array('$pull' => array("comments" => $oldComment)));
		}
		
		// cassandra
		public function removeCommentsFromPaticularPost_Cassandra($postId, $author, $submitDate, $body){
			$submitTimestamp = new DateTime($submitDate);
			$submitTimestamp = $submitTimestamp->getTimestamp();
			// get comment id
			$commentId = $this->cassandra->cf('commentsByPost')->getColumns($postId, array($submitTimestamp));
			$this->cassandra->remove('commentsByPost.' . $postId . ':' . $submitTimestamp);
			$this->cassandra->remove('comment.' . $commentId[$submitTimestamp]);
		}
		
		// mongo
		public function updatePost($postId, $title, $tags, $body){
			$tagArray = explode(',', $tags);
			
			$collection_tag = $this->db->tag;
			$tagIdArray = array();
			foreach( $tagArray as $t ){
				$tag = $collection_tag->findOne(array('name' => $t));
				if (!isset($tag) || empty($tag)){
					echo "No such tag called. \"" . $t . "\", deleted automatically. ";
				} else{
					array_push($tagIdArray, (string)$tag['_id']);
				}
			}
			echo count($tagIdArray) . " tag(s) are valid for this post. ";
			
            $collection_post = $this->db->post;
            // get old tags
            $unmodifiedPost = $collection_post->findOne(array('_id' => new MongoId($postId)));
            $oldTagIds = $unmodifiedPost['tags'];
            // delete the old links on the tag
            if(count($oldTagIds) != 0){
            	foreach($oldTagIds as $oldTId){
            		$collection_tag->update(array('_id' => new MongoId($oldTId)), array('$pull' => array("posts" => $postId)));
            	}
            }
            //update the post
            $newdata = array('title' => $title, 'tags' => $tagIdArray, 'body' => $body);
            $collection_post->update(array("_id" => new MongoId($postId)), array('$set' => $newdata));   
            // put the new links on the tag
            if(count($tagIdArray) != 0){
            	foreach($tagIdArray as $tId){
            		$collection_tag->update(array('_id' => new MongoId($tId)), array('$push' => array("posts" => $postId)));
            	}
            }
        }
        
        // cassandra
        public function updatePost_Cassandra($postId, $title, $tags, $body){
        	if ($tags != "" && isset($tags) && $tags != null){
        		// break the $tags string by comma, and store in an array
        		$tagArray = explode(',', $tags);
        	}
        	// no need to update tag information for posts if no tag is added
        	if(count($tagArray) != 0){
        		// remove all the tags on this post
        		$this->cassandra->remove('tagsByPost.' . $postId);
        		// find all the tags and store them in an array
        		$tags_valid = array();
        		foreach($tagArray as $t_new){
        			$tagCheck = $this->cassandra->get('postsByTag.' . $t_new);
        			if (!empty($tagCheck)||isset($tagCheck)){
        				array_push($tags_valid, $t_new);
        			}
        		}
        		// store the tag information for this post
        		// if no tag is valid, updating will not happen.
        		if(count($tags_valid) != 0){
        			foreach($tags_valid as $validTag){
        				$this->cassandra->set(
        						'tagsByPost.' . $postId,
        						array(
        								$validTag => ''
        						)
        				);
        			}
        		}
        	}
        	// update the post.
        	$this->cassandra->set(
        			'post.' . $postId,
        			array(
        					'title' => $title,
        					'body' => $body,
        			)
        	);
        }
}