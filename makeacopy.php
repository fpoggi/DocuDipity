<?php
	session_start();
  	if (!isset($_SESSION['user'])){
      	header("location:/index.php");
  	}else{
  		$user = $_SESSION["user"];
  	}
  	include "config_db.php";
	$host=$_SESSION["host"]; // Host name 
	$username=$_SESSION["username"]; // Mysql username 
	$password=$_SESSION["password"]; // Mysql password 
	$db_name=$_SESSION["db_name"]; // Database name 

	$js=$_POST["jscpy"];
	$css=$_POST["csscpy"];
	$title=$_POST["titlecpy"]."_copy";
	$author=$_POST["authcpy"];
	// Connect to server and select databse.
	$conn = new mysqli("$host", "$username", "$password", "$db_name");

	// Check connection to database
	if ($conn->connect_errno) {
	  // header('refresh: 3; url = /gestione_regole.php');
	  echo "Failed to connect";
	}

	$stmt = $conn->prepare("INSERT INTO rules (js, css, title, author) VALUES (?, ?, ?, ?)");
    if ( false===$stmt ) {
      header('refresh: 3; url = ../index.php');
      echo "Failed to prepare the query";    
    }
	$stmt->bind_param('ssss', $js, $css, $title, $author);
    if (!$stmt->execute()) {
		header("refresh: 2; url=../index.php");
		echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	header('refresh: 2; url = ../index.php');
    echo "New record created successfully";

    $conn->close();
?>