<?php

/**
 * This was written to quickly move a database from mysql to postgresql
 * By: Scott Sanders <magorath1@gmail.com>
 * Date: Feb. 8, 2023
 * 
 */

/**
 * MySQL Server Connection information
 */
$MYSQL_HOST = '192.168.0.3';
$MYSQL_PORT = 3306;
$MYSQL_USER = 'root';
$MYSQL_PASSWORD = 'Fionna2cute';
$MYSQL_DATABASE = 'achievo';

/** 
 * POSTGRES Server Connection Information
 */
$POSTGRES_HOST = '192.168.0.3';
$POSTGRES_PORT = 5432;
$POSTGRES_USER = 'dev';
$POSTGRES_PASSWORD = 'Fionna2cute';
$POSTGRES_DATABASE = 'testDB';

/**
 * Connecting to remote MySQL Server
 */
$conn = new mysqli($MYSQL_HOST, $MYSQL_USER, $MYSQL_PASSWORD, $MYSQL_DATABASE);

function getDataType($x) {
	$DataTypes = [
		"BIGINT" => "BIGINT",
		"BINARY" => "BYTEA",
		"BIT" => "BOOLEAN",
		"CHAR" => "CHAR",
		"CHARACTER" => "CHARACTER",
		"DATE" => "DATE",
		"DATETIME" => "TIMESTAMP",
		"DECIMAL" => "DECIMAL",
		"DEC" => "DEC",
		"DOUBLE" => "DOUBLE PRECISION",
		"FLOAT" => "REAL",
		"INT" => "INT",
		"INTEGER" => "INTEGER",
		"MEDIUMINT" => "INTEGER",
		"NUMERIC" => "NUMERIC",
		"SMALLINT" => "SMALLINT",
		"TINYBLOB" => "BYTEA",
		"BLOB" => "BYTEA",
		"MEDIUMBLOB" => "BYTEA",
		"LONGBLOB" => "BYTEA",
		"TINYINT" => "SMALLINT",
		"TINYTEXT" => "TEXT",
		"TEXT" => "TEXT",
		"MEDIUMTEXT" => "TEXT",
		"LONGTEXT" => "TEXT",
		"TIME" => "TIME",
		"TIMESTAMP" => "TIMESTAMP",
		"VARBINARY" => "BYTEA",
		"VARCHAR" => "VARCHAR"
	];

	$split = strtoupper(explode("(", $x)[0]);
	print_r($split);
}

if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

$results = $conn->query('SHOW TABLES');
if ($results->num_rows > 0) {
	echo "Scanning tables .... found " . $results->num_rows . PHP_EOL;
	while ($row = $results->fetch_assoc()) {
		$describe_table = $conn->query("DESCRIBE ".$row["Tables_in_".$MYSQL_DATABASE]);
		$pg_table = "CREATE TABLE IF NOT EXISTS ". $row["Tables_in_".$MYSQL_DATABASE] . "(".PHP_EOL;
		while ($row = $describe_table->fetch_assoc()) {
			die($row['Type'].PHP_EOL.getDataType($row['Type']));

			$pg_table .= $row['Field'] . " ".$DataTypes[$row['Type']] . ($row['Null'] !== "Null" ? " NOT NULL" : ""). ",".PHP_EOL;
			
			// $fields[] = [
			// 	$Field => $row['Field'],
			// 	$Type => $row['Type'],
			// 	$isNull => $row['Null'],
			// 	$Key => $row['Key'],
			// 	$Default => $row['Default'],
			// 	$Extra => $row['Extra']
			// ];
		}
		// print_r($pg_table);
	}
	// print_r($fields);

}

