<?php 
	session_start();
	$_SESSION["id"] = SID;
    include "config_db.php";
	$host=$_SESSION["host"]; // Host name 
    $username=$_SESSION["username"]; // Mysql username 
    $password=$_SESSION["password"]; // Mysql password 
    $db_name=$_SESSION["db_name"]; 

    // Connect to server and select databse.
    $mysqli = new mysqli("$host", "$username", "$password", "$db_name");

    // Check connection to database
    if ($mysqli->connect_errno) {
      header('refresh: 3; url = /gestione_regole.php');
      echo "Failed to connect";
    }
    $stmt = $mysqli->prepare("SELECT * FROM signin WHERE email= ? && password= ?");
    if ( false===$stmt ) {
      header('refresh: 3; url = ../index.php');
      echo "Failed to prepare the query";    
    }
	$stmt->bind_param('ss', $myusername, $mypasswordencrypted);
    $myusername = (string)$_POST['logmail'];
    //$mypassword=$_POST['password'];
    $mypasswordencrypted = md5($_POST['logpassword'][$raw_output=false]);

    // Execute query, store the result and count the rows (only one email-pwd couple should exists)
    $stmt->execute();

    $result = $stmt->get_result();
    $count = 0;
    $count=mysqli_num_rows($result);

    if($count==1){
    	while ($row = $result->fetch_assoc()) {
         	$myname = $row['name'];
			$email= $row['email'];
		}
		$_SESSION["user"] = $myname;
		$_SESSION["email"] = $myusername;

		header("location:../index_logged.php");
	}
	else {
	    $acc = "deny";
	    header("refresh: 3; url=../index.php?access=".$acc);
	    echo "<h1 align='center'>Wrong Username or Password</h1>";
    }
?>