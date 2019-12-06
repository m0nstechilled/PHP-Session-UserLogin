<?php

class database {
	
	public function __construct() {

		return new PDO(
		    'mysql:host=localhost;dbname=trundle',
		    "root",
		    "worktroll911",
		    array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

	}

}

?>