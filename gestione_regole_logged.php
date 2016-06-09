<?php 
  // Inizio la sessione
  session_start();
  if (!isset($_SESSION['user'])){
      header("location:gestione_regole.php");
  }else{
     // Recupero lo username (mail) che poi userò nell'html
    $user = $_SESSION["user"];
  }
  $dir    = 'data/xml';
  $files1 = scandir($dir);
  $files2 = scandir($dir, 1);
  include "php/config_db.php";
  	$host=$_SESSION["host"]; // Host name 
	$username=$_SESSION["username"]; // Mysql username 
	$password=$_SESSION["password"]; // Mysql password 
	$db_name=$_SESSION["db_name"]; 

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
	<script type='text/javascript' src='js/gestione_regole_logged.js'></script>
	<script type="text/javascript">var vettore_regole = <?php echo json_encode($vettore_regole); ?></script>
	<script type="text/javascript">var user = <?php echo json_encode($user); ?></script>
	<script type="text/javascript">var docs = <?php echo json_encode($files1); ?></script>
	<script src="lib/codemirror-4.7/lib/codemirror.js"></script>
	<script src="lib/codemirror-4.7/mode/javascript/javascript.js"></script>
	<script src="lib/codemirror-4.7/mode/css/css.js"></script>

	<link rel="stylesheet" type="text/css" href="lib/codemirror-4.7/lib/codemirror.css">
	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Open+Sans:400,600">
</head>
<body>
	<script type="text/javascript">
		$(window).load(function(){
		var access = "<?php echo $access; ?>";
		if (access == "deny") {
		  $('#loginModal').modal('show');
		  $("#email-login").addClass("has-error");
		  $("#password-login").addClass("has-error");
		}
		});
	</script>
	
	<div class="loader"></div>
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top: 10px;">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 5px;">
			<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 text-left">
				<span class='glyphicon glyphicon-chevron-left'><a style="color:black; margin-left:5px;" href="http://127.0.0.1/DocuDipity/sources/index.php">Home</a></span>
			</div>
			<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
				<h2>Docudipity - Management</h2>
			</div>
			<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 text-right right">
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1"> <!-- Collect the content for toggling into 	the button when the size is too small-->
		            <ul id="nav-destra" class="nav navbar-nav navbar-right">
		                    <li class="dropdown"><a class="dropdown-toggle" href="#" data-toggle="dropdown" role="button" aria-expanded="true"> Benvenuto, <b> <?php echo "$user" ?></b>! <span class="glyphicon glyphicon-menu-down"></span> </a><ul class="dropdown-menu" role="menu"> <li> <a href="about.html"> <span class=" glyphicon glyphicon-info-sign"></span> About</a> <li> <a href="php/logout.php"> <span class="glyphicon glyphicon-log-out"></span> Logout </a></ul></li>
		            </ul>
		        </div><!-- /.navbar-collapse -->
			</div>
		</div>
		<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" id="features" style="border-right: 1px solid black; margin-top:10%;">
			<div>
			  <!-- Nav tabs -->
			  <ul class="nav nav-tabs" role="tablist">
			    <li role="presentation" class="active"><a href="#rules" aria-controls="rules" role="tab" data-toggle="tab">Rules</a></li>
			    <li role="presentation"><a href="#docs" id="tabDocuments" aria-controls="docs" role="tab" data-toggle="tab">Docs</a></li>
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
							<td id="pubrules"  class="td">mie pubbliche</td>
						</tr>
						<tr>
							<td id="privrules" class="td">mie private</td>
						</tr>
						<tr>
							<td id="altrerules" class="td">altre</td>
						</tr>
						
						<tr>
							<th>Operazioni</th>
						</tr>
						<tr>
							<td class="td" id="createrules">
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
					<span class="spantab hide" id="spanaltre">Altre</span>
					<table id="altre" class="table  table-striped hide">
						<tr>
							<th>Title</th>
							<th>Author</th>
							<th>Description</th>
							<th>Status</th>
							<th>Edit</th>
							<th>Duplicate</th>
						</tr>
						
					</table>
				</form>
				<form method="post" action="php/loadrules.php">
					<span class="spantab hide" id="spanpub">Mie Pubbliche</span>
					<table id="miepub" class="table  table-striped hide">
						<tr>
							<th>Title</th>
							<th>Author</th>
							<th>Description</th>
							<th>Status</th>
							<th>Edit</th>
							<th>Duplicate</th>
							<th>Delete</th>
						</tr>
						
					</table>
				</form>
				<form method="post" action="php/loadrules.php">
					<span class="spantab hide" id="spanpriv">Mie Private</span>
					<table id="miepriv" class="table  table-striped hide">
						<tr>
							<th>Title</th>
							<th>Author</th>
							<th>Description</th>
							<th>Status</th>
							<th>Edit</th>
							<th>Duplicate</th>
							<th>Delete</th>
						</tr>
					</table>
				</form>
				<form method="post" action="php/loadocs.php">
					<span class="spantab hide" id="spandoc">Documents</span>
					<table id="tabdocs" class="table table-striped hide">
						<tr>
							<th>Title</th>
							<th>Author</th>
							<th>Group</th>
						</tr>
					</table>
				</form>
				<div class="form-horizontal hide" id="prerules" name="prerules"> 
					<span class="spantab1">Crea Regola</span>
					<div class="form-group"> 
						<label for="title" class="col-sm-2 control-label">Title</label> 
						<div class="col-sm-7"> 
							<input type="title" class="form-control long" id="title" placeholder="title.."> 
						</div> 
					</div> 
					<div class="form-group"> 
						<label for="author" class="col-sm-2 control-label">Author</label> 
						<div class="col-sm-7"> 
							<input type="author" class="form-control long" id="author" placeholder="author.."> 
						</div> 
					</div> 
					<div class="form-group"> 
						<label for="description" class="col-sm-2 control-label">Description</label> 
						<div class="col-sm-7"> 
							<textarea class="form-control long" rows="3" type="description" class="form-control" id="description" placeholder="description.."></textarea> 
						</div> 
					</div> 
					<div class="form-group"> 
						<div class=" col-sm-offset-2 col-md-10">
							<label class="radio-inline">
								<input type="radio" name="status" value="public" autocomplete="off">Public
							</label>
							<label class="radio-inline">
								<input type="radio" name="status" value="private" autocomplete="off">Private
							</label>
						</div><br><br>
						<div class="col-sm-offset-2 col-sm-10"> 
							<button type="button" onclick="prerules()" class="btn btn-default">Sign in</button> 
						</div> 
					</div> 
				</div>
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