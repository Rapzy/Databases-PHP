<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Working with Databases in PHP</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<div>
		<h2 id="title">Connect to database</h3>
		<form method="POST" action="script.php">
			<input type="hidden" name="getDatabases">
			<label>Address</label>
			<input type="text" name="address" value="localhost">
			<label>Login</label>
			<input type="text" name="login">
			<label>Password</label>
			<input type="password" name="pass">
			<input type="submit" value="Connect"><br>
		</form>
	</div>
</body>
</html>