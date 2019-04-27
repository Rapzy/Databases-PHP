<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Working with Databases in PHP</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
</body>
</html>

<?php 
if(isset($_POST['getDatabases']))
{
	$address = $_POST['address'];
	$pass = $_POST['pass'];
	$login = $_POST['login'];

	$conn = @new mysqli($address, $login, $pass);
	if ($conn->connect_error) 
	{
	    die("Connection failed: " . $conn->connect_error);
	} 

	$sql = "show databases";
	$databases = $conn->query($sql);

	printHtmlTableHeader(['№','Database','Option']);
	for ($i=0; $i < $databases->num_rows; $i++) { 
		$row = $databases->fetch_assoc();
		$dbName = $row['Database'];
		$data = json_encode(['address' => $address,'pass' => $pass, 'login' => $login]);
		echo "<tr>
				<td>".($i+1)."</td>
				<td style='text-align:left;padding-left:5px;'>$dbName</td>
				<td>
					<form class='openForm' action='script.php' method='POST'>
						<input type='hidden' name='getTable'>
						<input type='hidden' name='dbName' value='$dbName'>
						<input type='hidden' name='data' value='$data'>
						<input type='submit' class='openBtn' value='Open'>
					</form>
				</td>
			</tr>";
	}
	echo '</table><a href="index.php">Home</a>';
	$conn->close();
}

elseif (isset($_POST['getTable'])) {
	$data = json_decode($_POST['data'], true);
	$data['dbName'] = $_POST['dbName'];
	$conn = @new mysqli($data['addres'], $data['login'], $data['pass'], $data['dbName']);
	if ($conn->connect_error) 
	{
	    die("Connection failed: " . $conn->connect_error);
	} 
	$sql = "show tables";
	$tables = $conn->query($sql);
	$data = json_encode($data);
	printHtmlTableHeader(['№','Table','Option']);
	for ($i=0; $i < $tables->num_rows; $i++) { 
		$row = $tables->fetch_array();
		$tableName = $row[0];
		echo "<tr>
				<td>".($i+1)."</td>
				<td style='text-align:left;padding-left:5px;'>$tableName</td>
				<td>
					<form class='openForm' action='script.php' method='POST'>
						<input type='hidden' name='openTable'>
						<input type='hidden' name='tableName' value='$tableName'>
						<input type='hidden' name='data' value='$data'>
						<input type='submit' class='openBtn' value='Open'>
					</form>
				</td>
			</tr>";
	}
	echo '</table><a href="index.php">Home</a>';
	$conn->close();
}


function printHtmlTableHeader($fields)
{
	echo "<table border='1'>
			<tr bgcolor='#CCCCCC'>";
	foreach ($fields as $field) {
		echo "<th>$field</th>";
	}
	echo"</tr>";
}
?>