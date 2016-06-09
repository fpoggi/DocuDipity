<?php
	session_start();
	if(isset($_SESSION['user'])){
		header("location:gestione_regole_logged.php");
	}else {
	  $access = $_GET['access'];
	}
	include "php/config_db.php";
	$host=$_SESSION["host"]; // Host name 
	$username=$_SESSION["username"]; // Mysql username 
	$password=$_SESSION["password"]; // Mysql password 
	$db_name=$_SESSION["db_name"]; 

	$dir    = 'data/xml';
	$files1 = scandir($dir);
	$files2 = scandir($dir, 1);

	// Connect to server and select databse.
	$conn = new mysqli("$host", "$username", "$password", "$db_name");

	// Check connection to database
	if ($mysqli->connect_errno) {
	  // header('refresh: 3; url = /gestione_regole.php');
	  echo "Failed to connect";
	}

	$sql = "SELECT id, author, title, status, description FROM rules";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
	    // output data of each row
	    $j=0;
	    while($row = $result->fetch_assoc()) {
	        $vettore_regole[$j]["id"] = $row["id"];
	        $vettore_regole[$j]["author"] = $row["author"];
	        $vettore_regole[$j]["title"] = $row["title"];
	        $vettore_regole[$j]["status"] = $row["status"];
	        $vettore_regole[$j]["description"] = $row["description"];
	        $j++;
	    }
	} else {
	    echo "0 results";
	}
	$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Docudipity - Management</title>
	<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="css/gestione_regole.css">
	<script src="lib/d3.min.js" charset="utf-8"></script>
	<script type='text/javascript' src='lib/jquery-2.1.1.min.js'></script>
	<script type='text/javascript' src='lib/jquery.scrollTo.min.js'></script>
	<script type='text/javascript' src='bootstrap/js/bootstrap.js'></script>
	<script type='text/javascript' src='js/gestione_regole.js'></script>
	<script type="text/javascript">var vettore_regole = <?php echo json_encode($vettore_regole); ?></script>
	<script type="text/javascript">var docs = <?php echo json_encode($files1); ?></script>
	<script src="lib/codemirror-4.7/lib/codemirror.js"></script>
	<script src="lib/codemirror-4.7/mode/javascript/javascript.js"></script>
	<script src="lib/codemirror-4.7/mode/css/css.js"></script>
	<script type='text/javascript' src='lib/list_function.js'></script>

	<link rel="stylesheet" type="text/css" href="lib/codemirror-4.7/lib/codemirror.css">
	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Open+Sans:400,600">
</head>
<body>
	<script type="text/javascript">
  $(window).load(function(){
    var access = "<?php echo $access; ?>";
    if (access == "deny") {
      $('#loginModal').modal('show');
      $("#logmail").addClass("has-error");
      $("#logpassword").addClass("has-error");
    }
  });
</script>
	<!-- Modal Sign up-->
	<div class="modal fade" id="signupModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel">Sign up</h4>
	      </div>
	      <form method="post" name="signup" action="php/signup.php">
		      <div class="modal-body">
		      	<div class="form-group">
					<label for="signname">Name</label>
					<input type="text" class="form-control" name="signname" id="signname" placeholder="Name">
				</div>
		      	<div class="form-group">
					<label for="signmail">Email address</label>
					<input type="email" class="form-control" name="signmail" id="signmail" placeholder="Email">
				</div>
				<div class="form-group">
					<label for="signpassword">Password</label>
					<input type="password" class="form-control" name="signpassword" id="signpassword" placeholder="Password">
				</div>
				<div class="form-group">
					<label for="signrepassword">Re-password</label>
					<input type="password" class="form-control" name="signrepassword" id="signrepassword" placeholder="Re-Password">
				</div>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		        <button type="submit" name="Submit" value="signup" class="btn btn-primary">Sing up</button>
		      </div>
	      </form>
	    </div>
	  </div>
	</div>
	
	<!-- Modal  Login-->
	<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel">Login</h4>
	      </div>
	      <form method="post" name="signup" action="php/checklogin.php">
		      <div class="modal-body">
		      	<div class="form-group">
					<label for="logmail">Email</label>
					<input type="email" class="form-control" name="logmail" id="logmail" placeholder="Email">
				</div>
				<div class="form-group">
					<label for="logpassword">Password</label>
					<input type="password" class="form-control" name="logpassword" id="logpassword" placeholder="Password">
				</div>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		        <button type="submit" name="Submit" value="login" class="btn btn-primary">Login</button>
		      </div>
	      </form>
	    </div>
	  </div>
	</div>
	
	<div class="loader"></div>
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top: 10px;">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 5px;">
			<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 text-left">
				<span class='glyphicon glyphicon-chevron-left'><a style="color:black; margin-left:5px;" href="index.php">Home</a></span>
			</div>
			<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
				<h2>Docudipity - Management</h2>
			</div>
			<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 text-right right">
				<button type="button" class="btn btn-default" id="login" data-toggle="modal" data-target="#loginModal">Login</button>
				<button class="text-left btn btn-default" style="margin-left: 5px;" data-toggle="modal" data-target="#signupModal">Sign up</button>
			</div>
		</div>
		<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" id="features" style="border-right: 1px solid black; margin-top:10%;">
			<div>
			  <!-- Nav tabs -->
			  <ul class="nav nav-tabs" role="tablist">
			    <li role="presentation" class="active"><a href="#rules" aria-controls="rules" role="tab" data-toggle="tab">Rules</a></li>
			    <li role="presentation"><a href="#docs" aria-controls="docs" role="tab" data-toggle="tab" id="tabDocuments">Docs</a></li>
			  </ul>
			
			  <!-- Tab panes -->
			  <div class="tab-content">
			    <div role="tabpanel" class="tab-pane active" id="rules">
			    	<br>
			    	<table class="table">
						<tr>
							<th>Regole</th>
						</tr>
						<tr>
							<td class="td" onclick="login()">mie pubbliche</td>
						</tr>
						<tr>
							<td class="td" onclick="login()">mie private</td>
						</tr>
						<tr>
							<td class="td" onclick="loadtable()">altre</td>
						</tr>
						<tr>
							<th>Operazioni</th>
						</tr>
						<tr>
							<td class="td" id="createrules" onclick="login()">
								Crea nuova regola
							</td>
						</tr>
					</table>
			    </div>
			    <div role="tabpanel" class="tab-pane" id="docs">
			    	<br>
			    	<table class="table" id="tablegroups">
					</table>
			    </div>
			  </div>
			
			</div>
			<!--<tr>
				<td id="TotRegPub">
					<a role="button" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample" style="text-decoration: none; color: black;">Regole pubbliche</a>
					<div class="collapse" id="collapseExample">
						<div class="well">	
							<div id="listrules"></div>
					  	</div>
					</div>
				</td>
			</tr> -->
		</div>
		<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8" id="main" style="margin-top:10%;">
			<div id="mainheader">
				<button id='editrules' num_css="0" num_js="0" class='btn btn-default hide' style='float:right;'>edit</button>
				<form action="prova.php" method="post" enctype="multipart/form-data">
					<input type="text" name="ruljs" id="ruljs" value="" class="hide"/>
					<input type="text" name="rulcss" id="rulcss" value="" class="hide"/>
					<input type="text" name="rultitle" id="rultitle" value="" class="hide"/>
					<input type="text" name="rulauth" id="rulauth" value="" class="hide"/>
					<input type="text" name="ruldes" id="ruldes" value="" class="hide"/>
					<input type="text" name="rulprivpub" id="rulprivpub" value="" class="hide"/>
					<button id='saverules' num="0" class='btn btn-default hide' style='float:right; margin-right:20px;' type="submit" value="Upload" name="submit">save</button>
				</form>
				<button id='css_rules' num="0" class='btn btn-default hide' style='float:left;'>css</button>
				<button id='js_rules' num="0" class='btn btn-default hide' style='float:left; margin-left:20px;'>jscript</button>
				<span id='namerules' num="0" class='hide' style='float:left; margin-left:60px; margin-top:10px; font-weight:bold;'></span>
			</div>
			<div id="maincontent">
				<form method="post" action="php/loadrules.php">
					<table id="tablerules" class="table  table-striped hide">
						<tr>
							<th>Title</th>
							<th>Author</th>
							<th>Description</th>
							<th>Edit</th>
							<th>Duplicate</th>
						</tr>
					</table>
				</form>
				<form method="post" action="php/loadocs.php">
					<table id="tabdocs" class="table table-striped hide">
						<tr>
							<th>Title</th>
							<th>Author</th>
							<th>Group</th>
						</tr>
					</table>
				</form>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$(document).ready(function(){
			$(".loader").fadeOut(1500);
		});
	</script>
</body>
</html>