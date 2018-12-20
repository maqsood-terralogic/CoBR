<?php

	$host="sjc-dbdl-mysql4"; // Host name
	$db_name = "irs";
	$user="irs"; // Mysql username
	$password="irs"; // Mysql password
	$conn =mysql_connect("$host", "$user", "$password")or die("cannot connect");
	mysql_select_db("$db_name")or die("cannot select DB");
	//Temporary Initialization

	$username = "satkommu";
?>
