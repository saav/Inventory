<?php
require_once 'config.php';
$db = new mysqli(db_host, db_uid, db_pwd, db_name);
//Building Inventory Table
if ($db->connect_errno) 
	echo("Failed to connect to MySQL: (" . $db->connect_errno . ") " . $db->connect_error);
$sql = "CREATE TABLE Inventory ( id INT PRIMARY KEY AUTO_INCREMENT,
                           		 name VARCHAR(300),
                            	 amount INT,
                            	 price FLOAT(11,2),
                            	 description VARCHAR(1000),
                            	 category VARCHAR(100));";
if (mysqli_query($db, $sql)) {} 
    else echo("Error creating table: " . $db->error);
$sql = "CREATE TABLE Images ( id INT PRIMARY KEY AUTO_INCREMENT, 
                           		 filename VARCHAR(300),
                            	 objectname VARCHAR(100),
                            	 category VARCHAR(100));";
if (mysqli_query($db, $sql)) {} 
    else echo("Error creating table: " . $db->error);

$directory = "/images";
$scanned_directory = array_diff(scandir($directory), array('..', '.'));
print_r($scanned_directory);

for($i = 0; $i < count($scanned_directory);$i++){
	$temp = explode($scanned_directory[i],"/");
	$category = $temp[0];
	$name = $temp[1];
	$filename = $scanned_directory[i];
	$sql = "INSERT INTO Inventory(filename,objectname,category) VALUES (\""+$filename+"\",\""+$name+"\",\""+$category+"\");";
	if ($db->query($sql) === TRUE) {
    
		} else {
    	echo( "Error: " . $sql . "<br>" . $db->error);
		}
}

$jsonurl = "data.json";
$json = file_get_contents($jsonurl,0,null,null);
$json_output = json_decode($json,true);

for($i = 0; $i<count($json_output);$i++) {
	$sql = "INSERT INTO Inventory (id,name,amount,price,description,category) VALUES (".($i+1).",\"".$json_output[$i]['name']."\",".$json_output[$i]['amount'].",
						".$json_output[$i]['price'].",\"".$json_output[$i]['description']."\",\"".$json_output[$i]['category']."\")";

	if ($db->query($sql) === TRUE) {
    
	} else {
    echo( "Error: " . $sql . "<br>" . $db->error);
	}
}

?>
