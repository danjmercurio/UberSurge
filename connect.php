<?php

if (mysql_connect('localhost','root')) {
	if (mysql_select_db('uber')) {

	} else {
		echo '<h1>Connected but no application database. Check mySQL.</h1>';
		die();
	}


} else {
	echo '<h1>Could not connect to database.</h1>';
	die();
}

?>