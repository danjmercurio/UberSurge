
<?php
include('header.php');
if ($_POST) {
	$userName = $_POST['userName'];
	$userPassword = $_POST['userPassword'];

	$sql = "SELECT * FROM users WHERE name='{$userName}' AND password='{$userPassword}'"; //potentially vulnerable to SQL injection
	$result = mysql_query($sql);

	if (!$result) {
    echo "Could not successfully run query ($sql) from DB: " . mysql_error();
    exit;
}
	if (mysql_num_rows($result) == 1) {
		$_SESSION['logged_in'] = true;
		//logged in. now let's check if they are an admin.
		$sql = "SELECT * FROM users WHERE name='{$userName}' AND password='{$userPassword}'"; //potentially vulnerable to SQL injection
		$result = mysql_query($sql);
		$assoc = mysql_fetch_assoc($result);
		if ($assoc['role'] == 'admin') {
			$_SESSION['admin'] = true;
		} else {
			$_SESSION['admin'] = false;
		}
		//redirect
		header('Location: index.php');

	} else {
		echo '<h1>User not found or invalid password.</h1>';
		}

} else {

	echo 'error';

}


?>