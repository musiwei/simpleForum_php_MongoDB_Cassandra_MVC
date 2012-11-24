<?php
	include_once("lib/cassandra/Cassandra.php");
	include_once("lib/class.uuid.php");
	
	$servers = array(
			array(
					'host' => '127.0.0.1',
					'port' => 9160,
					'use-framed-transport' => true,
					'send-timeout-ms' => 1000,
					'receive-timeout-ms' => 1000
			)
	);
	$cassandra = Cassandra::createInstance($servers);
	
// 	try {
// 		$cassandra->dropKeyspace('blog');
// 	} catch (Exception $e) {}

	// create a new keyspace, accepts extra parameters for replication options
	// normally you don't do it every time
//   	$cassandra->createKeyspace('blog');
	
	// start using the created keyspace
	$cassandra->useKeyspace('blog');
/*
	// create a standard column family with given column metadata
	$cassandra->createStandardColumnFamily(
			'blog', // keyspace name
			'author',             // the column-family name
			array(              // list of columns with metadata
					array(
							'name' => 'email',
							'type' => Cassandra::TYPE_UTF8
					),
					array(
							'name' => 'address',
							'type' => Cassandra::TYPE_UTF8
					),
					array(
							'name' => 'phone',
							'type' => Cassandra::TYPE_UTF8
					),
			),
			Cassandra::TYPE_UTF8
			// actually accepts more parameters with reasonable defaults
	);
	
	$cassandra->createStandardColumnFamily(
			'blog', // keyspace name
			'comment',             // the column-family name
			array(              // list of columns with metadata
					array(
							'name' => 'author',
							'type' => Cassandra::TYPE_UTF8
					),
					array(
							'name' => 'body',
							'type' => Cassandra::TYPE_UTF8
					),
					array(
							'name' => 'submit date',
							'type' => Cassandra::TYPE_LONG
					),
			)
			// actually accepts more parameters with reasonable defaults
	);
	
	// create a super column family
	$cassandra->createStandardColumnFamily(
			'blog',
			'post',
			array(              // list of columns with metadata
					array(
							'name' => 'author',
							'type' => Cassandra::TYPE_UTF8
					),
					array(
							'name' => 'title',
							'type' => Cassandra::TYPE_UTF8
					),
					array(
							'name' => 'body',
							'type' => Cassandra::TYPE_UTF8
					),
					array(
							'name' => 'publish date',
							'type' => Cassandra::TYPE_LONG,
							'index-type' => Cassandra::INDEX_KEYS, // create secondary index
							'index-name' => 'publish date'
					),
			)
			// see the definition for these additional optional parameters
	);
	
	$cassandra->createStandardColumnFamily(
			'blog', // keyspace name
			'postsByAuthor',             // the column-family name
			array(              // list of columns with metadata
			),
			// actually accepts more parameters with reasonable defaults
			Cassandra::TYPE_LONG,
			Cassandra::TYPE_INTEGER
	);
	
	$cassandra->createStandardColumnFamily(
			'blog', // keyspace name
			'commentsByPost',             // the column-family name
			array(              // list of columns with metadata
			),
			// actually accepts more parameters with reasonable defaults
			Cassandra::TYPE_LONG,
			Cassandra::TYPE_INTEGER
	);
	
	$cassandra->createStandardColumnFamily(
			'blog', // keyspace name
			'postsByTag',             // the column-family name
			array(              // list of columns with metadata
			),
			// actually accepts more parameters with reasonable defaults
			Cassandra::TYPE_INTEGER
	);
	
	$cassandra->createStandardColumnFamily(
			'blog', // keyspace name
			'tagsByPost',             // the column-family name
			array(              // list of columns with metadata
					array(
							'name' => 'postId',
							'type' => Cassandra::TYPE_INTEGER,
							'index-type' => Cassandra::INDEX_KEYS, // create secondary index
							'index-name' => 'NameIdx'
					),
					array(
							'name' => 'tag_name',
							'type' => Cassandra::TYPE_UTF8
					),
			),
			// actually accepts more parameters with reasonable defaults
			Cassandra::TYPE_UTF8
	);
	
	
	// lets fetch and display the schema of created keyspace
	//$schema = $cassandra->getKeyspaceSchema('CassandraExample');
	//echo 'Schema: <pre>'.print_r($schema, true).'</pre><hr/>';
	
	// lets insert some test data using the convinience method "set" of Cassandra
	// the syntax is COLUMN_FAMILY_NAME.KEY_NAME
	$cassandra->set(
			'author.Siwei',
			array(
					'email' => 'siwei@admin.com',
					'address' => '1 Stanley St, Burwood, NSW 2134',
					'phone' => '0450000000'
			)
	);
	
	$cassandra->set(
			'author.Vincent',
			array(
					'email' => 'vincent@admin.com',
					'address' => '2 Stanley St, Burwood, NSW 2134',
					'phone' => '0450111111'
			)
	);
	
	$today = new DateTime();
	$cassandra->set(
			'post.1',
			array(
					'author' => 'Vincent',
					'title' => 'Sample Title',
					'body' => 'Sample Body',
					'publish_date' => $today->getTimestamp(),
			)
	);
	
	$cassandra->set(
			'post.2',
			array(
					'author' => 'Siwei',
					'title' => 'Only me',
					'body' => 'There is no one giving comment...',
					'publish_date' => $today->getTimestamp(),
			)
	);
	
	$cassandra->set(
			'postsByAuthor.Vincent',
			array(
					$today->getTimestamp() => '1'
			)
	);
	
	$cassandra->set(
			'postsByAuthor.Siwei',
			array(
					$today->getTimestamp() => '2'
			)
	);
	
	$cassandra->set(
			'tagsByPost.1',
			array(
					"happy" => '',
					"interesting" => ''
			)
	);
	
	$cassandra->set(
			'postsByTag.happy',
			array(
					1 => '',
			)
	);
	
	$cassandra->set(
			'postsByTag.interesting',
			array(
					1 => '',
			)
	);
	
	$cassandra->set(
			'comment.1',
			array(
					 'author' => 'Siwei',
					 'body' => 'nice!',
					'submit_date' => $today->getTimestamp()
			)
	);
	
	$cassandra->set(
			'commentsByPost.1',
			array(
					$today->getTimestamp() => 1,
			)
	);
	*/

	
	$today = new DateTime();
	
	//$posts = $cassandra->cf('comment')->getWhere(array('submit_date' => $today->getTimestamp()));
	//$posts = $cassandra->cf('post')->getWhere(array(array('publish date', Cassandra::OP_GT, '123')));
	//echo 'Users at age 24: <pre>'.print_r($posts->getAll(), true).'</pre><hr/>';
	$id = $cassandra->cf('commentsByPost')->getColumns('1', array('1351509161'));
	echo 'Users at age 24: <pre>'.print_r($id, true).'</pre><hr/>';
	
    $posts = $cassandra->cf('post')->getKeyRange(100);
    $posts = $posts->getAll();
    $postIdArray = array();
    foreach(array_keys($posts) as $paramName){
    	array_push($postIdArray, $paramName);
    }
    $n = 0;
    $searchDate = new DateTime('2012-10-28');
    $searchDate = $searchDate->getTimestamp();
    $validPostIdArray = array();
    echo $searchDate . "<br><br>";
    foreach($posts as $p){
    	echo $p['publish_date'] . "<br>";
    	if ($p['publish_date'] > $searchDate)
    		array_push($validPostIdArray, $postIdArray[$n]);
    	$n++;
    }
    echo 'Users at age 24: <pre>'.print_r($validPostIdArray, true).'</pre><hr/>';
	/*
	$column = new cassandra_Column();
	$column->name = '2012-10-29';
	$column->value = 1;
	$columns = array();
	array_push($columns, $column);
	$cassandra->columnFamily("postsByAuthor")->createColumns($columns);


	/*$cassandra->set(
			'postsByAuthor.Vincent',
			array(
					$str3 => '1',
			)
	)*/