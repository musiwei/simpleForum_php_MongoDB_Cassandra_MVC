<?php 
	include_once("controller/FrontControllerInterface.php");
	include_once("controller/MongoModelController.php");
	include_once("controller/CassandraModelController.php");
	include_once("lib/cassandra/Cassandra.php");

	//$options["controller"] = "MongoModelController";
 	$options["controller"] = "CassandraModelController";
	$options["action"] = "invoke";
	$controller = new FrontController($options);
	$controller->run();
	
	
	/*Below is how you add a sample new tag.
	$conn = new Mongo("mongodb://192.168.1.6:27017"); //60.240.205.199
	$db = $conn->blog;
	$collection = $db->tag;
	$posts = array('508ccdbca625a24c9200004b');
	$posts2 = array('508cb43aa625a24c92000010');
	$tag1 = array('name' => 'happy', 'posts' => $posts);
	$tag2 = array('name' => 'sad', 'posts' => $posts2);
	$tag3 = array('name' => 'normal');
	$tag4 = array('name' => 'interesting', 'posts' => $posts);
	$collection->insert($tag1);
	//$collection->insert($tag2);
	//$collection->insert($tag3);
	$collection->insert($tag4);
	*/
	
	/*Below is how you add a sample new post.
	$conn = new Mongo("mongodb://192.168.1.6:27017"); //60.240.205.199
	$db = $conn->blog;
	$collection = $db->post;
	$tags = array('508cb19da625a24c9200000a', '508cb19da625a24c9200000d');//tags are linked by tag id
	$comments = array(array('author' => '508b8a65a625a2b07e000008',// comments are embedded into post
					  'submit date' => new MongoDate(strtotime("2012-10-27 20:25:20")),
					  'body' => 'You are awesome! '));
	$post = array('title' => 'I am happy!', 
				  'body' => 'When an individual becomes happy, the network effect can be measured up to three degrees. One person\'s happiness triggers a chain reaction that benefits not only his friends, but his friends\' friends, and his friends\' friends\' friends. The effect lasts for up to one year.', 
			      'publish date' => new MongoDate(strtotime("2012-10-27 20:20:20")), 
				  'tags' => $tags,
				  'author' => '508b8a65a625a2b07e000009',
				  'comments' => $comments,
			);
	$collection->insert($post);
	*/
	
	/*
	$conn = new Mongo("mongodb://192.168.1.6:27017"); //60.240.205.199
	$db = $conn->blog;
	$collection = $db->post;
	$tags = array('508cb19da625a24c9200000b');//tags are linked by tag id
	$comments = array(array('author' => '508b8a65a625a2b07e00000a',// comments are embedded into post
			'submit date' => new MongoDate(strtotime("2012-10-28 20:25:20")),
			'body' => 'Don\'t worry, everything will be fine. '));
	$post = array('title' => 'I am sad!',
			'body' => 'Here are some ideas to help kick a rotten day to kingdom come...
 Do something good for someone else, even though you may not want to. Do a favour, help them find something, give them an item which will help them in some way. Start the flow of positive energy.
 Eat the best chocolate you can get your hands on. In bed. Or in the bath.
 Turn up music you really love. Play it so loudly that it soaks in through your skin. Dance in your pyjamas. Feel the pain lift.
 Have a romance in your head.',
			'publish date' => new MongoDate(strtotime("2012-10-28 20:20:20")),
			'tags' => $tags,
			'author' => '508b8a65a625a2b07e000008',
			'comments' => $comments,
	);
	$collection->insert($post);
	*/
	
	/*Below is how you add sample new authors.
	$conn = new Mongo("mongodb://192.168.1.6:27017"); //60.240.205.199
	$db = $conn->blog;
	$collection = $db->author;
	$author1 = array('name' => 'Jason', 'email' => 'Jason@admin.com', 'post address' => '2 Stanley st, Burwood, NSW 2134', 'phone' => '0450222222');
	$author2 = array('name' => 'Siwei', 'email' => 'Siwei@admin.com', 'post address' => '3 Stanley st, Burwood, NSW 2134', 'phone' => '0450333333');
	$author3 = array('name' => 'Ying', 'email' => 'Ying@admin.com', 'post address' => '4 Stanley st, Burwood, NSW 2134', 'phone' => '0450444444');
	$collection->insert($author1);
	$collection->insert($author2);
	$collection->insert($author3);
	*/
?>