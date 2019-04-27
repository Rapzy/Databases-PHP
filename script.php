<?php 
if(isset($_GET['getDatabases']))
{
	$address = $_GET['address'];
	$pass = $_GET['pass'];
	$login = $_GET['login'];

	$conn = @new mysqli($address, $login, $pass);
	if ($conn->connect_error) 
	{
	    die("Connection failed: " . $conn->connect_error);
	} 

	$sql = "show databases";
	$tables = $conn->query($sql);

	printHtmlTableHeader(['№','Database','Option']);
	for ($i=0; $i < $tables->num_rows; $i++) { 
		$row = $tables->fetch_assoc();
		$dbName = $row['Database'];
		echo "<tr><td>".($i+1)."</td><td style='text-align:left'>$dbName</td><td><a href='script.php?getTable=$dbName'></a></td></tr>";
	}

	$conn->close();
}

function printHtmlTableHeader($fields)
{
	echo "<table width='600px' style='text-align:center'>
			<tr bgcolor='#CCCCCC'>";
	foreach ($fields as $field) {
		echo "<th>$field</th>";
	}
	echo"</tr>";
}