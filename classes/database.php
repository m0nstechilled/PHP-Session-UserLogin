<?php

class database {
	
	public function __construct() {

		return new PDO(
		    'mysql:host=localhost;dbname=DATABASE',
		    "USERNAME",
		    "PASSWORD",
		    array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

	}

}

?>
