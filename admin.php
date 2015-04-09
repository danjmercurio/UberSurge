<?php
include('header.php');

if (!isset($_SESSION['logged_in']) or $_SESSION['logged_in'] != 'true') {
	header('Location: login.php');
}
if (!$_SESSION['admin'] == true) {
	echo "<h1>Forbidden - you do not have sufficient privileges to view this page.";
	die();
}


?>
<div class="container">
	<div id="main">
		<h1>Admin Panel</h1>
		<div>
			<p>
				<a href="index.php">Back</a>
				<a href="logout.php">Logout</a>
			</p>
		<div>
			<form action="do_admin.php" method="POST">
			<fieldset>
		<div class="form-group">
			<p><strong>Set admin password</strong></p>
			<p><input type="text" class="form-control" name="admin_pass_change"></p>
			<p><strong>Set guest1 password</strong></p>
			<p><input type="text" class="form-control" name="guest1_pass_change"></p>
			<p><strong>Set guest2 password</strong></p>
			<p><input type="text" class="form-control" name="guest2_pass_change"></p>
		</div>
		<div class="form-group">
			<p><strong>Add Locations</strong></p>
			<?php
			
				echo "<p>";
				echo "<input type=\"text\" placeholder=\"Name\" class=\"form-control\" name=\"name\"></p>";
				echo "<input type=\"text\" placeholder=\"Latitude\" class=\"form-control\" name=\"lat\"></p>";
				echo "<input type=\"text\" placeholder=\"Longitude (enter western hemisphere longitudes as negative e.g. 122 deg W = -122)\" class=\"form-control\" name=\"lon\"></p>";
				echo "</p>";
				echo "<br />";
				
			?>
			<input type="submit" value="Update" class="btn btn-large btn-success">
		</fieldset>
		</form>
		</div>
		
	</div>
</div>