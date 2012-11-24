<?php
include_once("lib/cassandra/Cassandra.php");

//Mongo DB Connection
$conn = new Mongo("mongodb://172.24.24.91:27017"); //60.240.205.199
$db = $conn->blog;
//Cassandra Connection
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
// start using the created keyspace
$cassandra->useKeyspace('blog');