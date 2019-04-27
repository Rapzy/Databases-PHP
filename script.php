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
	if($databases->num_rows > 0)
		echo "<h2>DATABASES</h2>";
	else
		echo "<h2>DATABASES AREN'T CREATED/h2>";

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
	if($tables->num_rows > 0)
		echo "<h2>TABLES</h2>";
	else 
		echo "<h2>TABLES AREN'T CREATED</h2>";
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
elseif (isset($_POST['openTable'])) {
	$data = json_decode($_POST['data'], true);
	$conn = @new mysqli($data['addres'], $data['login'], $data['pass'], $data['dbName']);
	if ($conn->connect_error) 
	{
	    die("Connection failed: " . $conn->connect_error);
	} 
	$table = $_POST['tableName'];
	$sql = "DESCRIBE $table";
	$columns = $conn->query($sql);
	$row = $columns->fetch_assoc();
	printHtmlTableHeader(array_keys($row));
	$columns->data_seek(0);

	echo "<h2>TABLE STRUCTURE</h2>";
	for ($i=0; $i < $columns->num_rows; $i++) 
	{ 
		$row = $columns->fetch_assoc();
		echo "<tr>";
		foreach ($row as $value) 
		{
			echo "<td>$value</td>";
		}
		echo "</tr>";
	}
	echo "</table>";

	$sql = "SELECT * from $table";
	$result = $conn->query($sql);
	if($result->num_rows > 0)
	{
		$row = $result->fetch_assoc();
		printHtmlTableHeader(array_keys($row));
		$result->data_seek(0);
		echo "<h2>TABLE DATA</h2>";
	}
	else
		echo "<h2>TABLE IS EMTY</h2>";

	for ($i=0; $i < $result->num_rows; $i++) 
	{ 
		$row = $result->fetch_assoc();
		echo "<tr>";
		foreach ($row as $value) 
		{
			echo "<td>$value</td>";
		}
		echo "</tr>";
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