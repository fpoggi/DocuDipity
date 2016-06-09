<?php
	// Inizio la sessione
  	session_start();
  	if (!isset($_SESSION['user'])){
      	header("location:/index.php");
  	}else{
  		$user = $_SESSION["user"];
  	}
  	$dir    = 'data/xml';
	$files1 = scandir($dir);
	$files2 = scandir($dir, 1);
	include "php/config_db.php";
	$host=$_SESSION["host"]; // Host name 
	$username=$_SESSION["username"]; // Mysql username 
	$password=$_SESSION["password"]; // Mysql password 
	$db_name=$_SESSION["db_name"]; // Database name 

	// Connect to server and select databse.
	$conn = new mysqli("$host", "$username", "$password", "$db_name");

	// Check connection to database
	if ($mysqli->connect_errno) {
	  // header('refresh: 3; url = /gestione_regole.php');
	  echo "Failed to connect";
	}

	$sql = "SELECT id, author, title, status, description, js, css FROM rules";
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
	        $vettore_regole[$j]["js"] = $row["js"];
	        $vettore_regole[$j]["css"] = $row["css"];
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
		<title>DoCoVis - Sunburst View</title>
<!--	<script src="http://d3js.org/d3.v3.min.js"></script>  -->
		<script src="lib/d3.min.js" charset="utf-8"></script>
<!--
	<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js'></script>
	<script type='text/javascript' src='http://demos.flesler.com/jquery/scrollTo/js/jquery.scrollTo-min.js?1.4.11'></script>
-->
		<script type='text/javascript' src='lib/jquery-2.1.1.min.js'></script>
		<script type='text/javascript' src='lib/jquery.scrollTo.min.js'></script>
		
		<script src="lib/codemirror-4.7/lib/codemirror.js"></script>
		<script src="lib/codemirror-4.7/mode/javascript/javascript.js"></script>
		<script src="lib/codemirror-4.7/mode/css/css.js"></script>
        
        <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="lib/codemirror-4.7/lib/codemirror.css">
		<link rel="stylesheet" type="text/css" href="css/patternsHTML.css">
		<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Open+Sans:400,600">
		<link rel="stylesheet" type="text/css" href="css/draw.css"/>
		<link rel="stylesheet" type="text/css" href="css/rec.css"/>
		<script type="text/javascript">var user = <?php echo json_encode($user); ?></script>
		<script type="text/javascript">var vettore_regole = <?php echo json_encode($vettore_regole); ?></script>
		<script type="text/javascript">var docs = <?php echo json_encode($files1); ?></script>
	</head>
	<body>
	<!-- Modal Sign up-->
		<div class="modal fade" id="copyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        <h4 class="modal-title" id="myModalLabel">Sign up</h4>
		      </div>
		      <form method="post" name="copy" action="php/makeacopy.php">
			      <div class="modal-body">
			      	<div class="form-group">
						<label for="titlecpy">Title</label>
						<input id="titlecpy" value="" name="titlecpy"></input>
					</div>
			      	<div class="form-group">
						<label for="jscpy">Js</label>
						<input id="jscpy" value="" name="jscpy"></input>
					</div>
					<div class="form-group">
						<label for="csscpy">Css</label>
						<input id="csscpy" value="" name="csscpy"></input>
					</div>
					<div class="form-group">
						<label for="authcpy">Author</label>
						<input id="authcpy" value="" name="authcpy"></input>
					</div>
			      </div>
			      <div class="modal-footer">
			        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			        <button type="submit" name="Submit" value="signup" class="btn btn-primary">Make a copy</button>
			      </div>
		      </form>
		    </div>
		  </div>
		</div>
		
	<div style="padding: 5px; border-bottom:1px solid black; border-radius:4px;" class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 5px;">
			<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 text-left">
				<span style="cursor:pointer; font-size:130%;" class='glyphicon glyphicon-chevron-left'><a style="color:black; margin-left:5px;" href="http://127.0.0.1/DocuDipity/sources/gestione_regole.php">Management</a></span>
			</div>
			<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
				<h1>Docudipity</h1>
			</div>
			<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 text-right right">
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1"> <!-- Collect the content for toggling into 	the button when the size is too small-->
		            <ul id="nav-destra" class="nav navbar-nav navbar-right">
		                    <li class="dropdown"><a style="color:gray; text-decoration:none; font-size:120%;" class="dropdown-toggle" href="#" data-toggle="dropdown" role="button" aria-expanded="true"> Benvenuto, <b> <?php echo "$user" ?></b>! <span class="glyphicon glyphicon-menu-down"></span> </a><ul class="dropdown-menu" role="menu"> <li> <a href="about.html"> <span class=" glyphicon glyphicon-info-sign"></span> About</a> <li> <a href="php/logout_index.php"> <span class="glyphicon glyphicon-log-out"></span> Logout </a></ul></li>
		            </ul>
		        </div><!-- /.navbar-collapse -->
			</div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center" style="padding: 5px; border-bottom:1px solid black; border-radius:4px; padding:15px;">
			<h2>Sezione documenti</h2>
		</div>
	    <div id="intro" style="text-align: center; margin-top:10%;">
	      <p>Pick a document: 
			<select id='docSelection'>
			  <?php
			    $files = glob("data/json/*.json");
			    foreach ($files as $file) {
			      $file_name = str_replace("data/json/", "", str_replace(".json", ".xml", $file));
			      echo "<option value='$file'>$file_name</option>\n";
			    }
			  ?>
			</select>
			<input id="nextdoc" type='submit' value='next' />
			<input id="openxml" type='submit' value='open XML' />
	      </p>
	    </div>
	    <div id="page">
			<div id="main">

				<div id="chart"></div>
			</div>
			<div id="sidebar">
			</div>
	    	<div id="document"></div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading" role="tab" id="headingTwo">
				<h4 class="panel-title">
					<a id="collapse_nr" class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" >
					Navigate Rule
					</a>
				</h4>
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="nr_top">
					<div style="margin-top:10px;" class="col-xs-6 col-sm-6 col-md-6 col-lg-6" ><span class="glyphicon glyphicon-chevron-left col-md-1 col-lg-1"></span><div id="navigate_rule_close_top" class="col-md-2 col-lg-2"></div><span class="glyphicon glyphicon-chevron-right text-right col-md-2 col-lg-2"></span></div>
					<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right"><button class="btn btn-default" onclick="load_rules()">load</button></div>
				</div>
			</div>
			<div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
				<div class="panel-body">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="nr_bot">
						<div style="margin-top:10px;" class="col-xs-6 col-sm-6 col-md-6 col-lg-6" ><span class="glyphicon glyphicon-chevron-left col-md-1 col-lg-1"></span><div id="navigate_rule_close_bot" class="col-md-3 col-lg-3"><select id="seltit"></select></div><span class="glyphicon glyphicon-chevron-right text-right col-md-2 col-lg-2"></span></div>
						<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right"><button id="id_rules" onclick="load_rules()" class="btn btn-default">load</button></div>
					</div><br>
					<div id="info_rules" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="margin-top:2%; padding:15px; border:1px solid black; border-radius:5px;">
							<div class="form-group col-xs-8 col-sm-8 col-md-8 col-lg-8">
								<label class="col-md-4 col-lg-4 control-label" for="spantitle">Title:</label>
								<div class="col-md-8 col-lg-8">
									<span name ="spantitle" id="spantitle" class=""></span>
								</div>
							</div><div class="col-md-4 col-lg-4"></div>
							<div class="form-group col-xs-8 col-sm-8 col-md-8 col-lg-8">
								<label class="col-md-4 col-lg-4 control-label" for="spanauth">Author:</label>
								<div class="col-md-8 col-lg-8">
									<span name="spanauth" id="spanauth" class=""></span>
								</div>
							</div><div class="col-md-4 col-lg-4"></div>
							<div class="form-group col-xs-8 col-sm-8 col-md-8 col-lg-8">
								<label class="col-md-4 col-lg-4  control-label" for="spandesc">Description:</label>
								<div class="col-md-8 col-lg-8">
									<span name="spandesc" id="spandesc" class=""></span>
								</div>
								<div class="col-md-8 col-lg-8 hide">
									<span name="spanjs" id="spanjs" class=""></span>
								</div>
								<div class="col-md-8 col-lg-8 hide">
									<span name="spancss" id="spancss" class=""></span>
								</div>
							</div>
						</div>
						<div class="col-md-6 col-lg-6 text-right">
							<button id="mc" class="btn btn-default" value="copy" style="margin-top:24%;" data-toggle="modal" data-target="#copyModal">Make a copy</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="panel panel-default">
		    <div class="panel-heading" role="tab" id="headingOne">
		      <h4 class="panel-title">
		        <a id="collapse_er" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
		          Rule Editor
		        </a>
		      </h4>
		    </div>
		    <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
		      <div class="panel-body">
		      	<div id="doco_container">
			    	<div style="width: 1000px;">
						<input id="doco_go" type="submit" value="evaluate">
			    		<input id="doco_reset" type="reset" value="reset">
						<div style="display: inline; float: right;">
							<label for="js_example">Load an example: </label>
							<select name="js_example" id="js_example">
								<option value="doco_code" id="doco_code">DoCO structures</option>
								<option value="biblioref_code" id="biblioref_code" selected="selected">Bilbiographic references</option>
								<option value="para_length_code" id="para_length_code">Paragraph length</option>
								<option value="uk_vs_usa_code" id="uk_vs_usa_code">UK vs. USA English</option>
							</select>
						</div>
						<div id="editor_container" clss="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<textarea id="js_editor" rows="40" cols="30"></textarea>
						<!-- </div> -->

		<!--
							var sections = $("div.section");
							var filtered = sections.filter(function() {
								return ( $(this).children(".title").length != 0);
							});
							filtered.each(function() {
							  var eid = getStructuralClass( $(this).attr("class") );
							  $("path."+eid).attr("style", "fill: red");
							});
		-->
							<textarea id="css_editor" rows="40" cols="50" class="text-right"></textarea>
						</div>
					</div>
				</div>
		      </div>
		    </div>
	  	</div>
		<script type="text/javascript" src="js/init_param.js"></script> 
    	<script type="text/javascript" src="js/draw.js"></script> 
    	<script type="text/javascript" src="js/editor.js"></script>
    	<script type='text/javascript' src='bootstrap/js/bootstrap.js'></script>
    	<script type="text/javascript" src="js/index.js"></script>
		<script type="text/javascript">
		// Hack to make this example display correctly in an iframe on bl.ocks.org
      		d3.select(self.frameElement).style("height", "700px");
		</script>
		<script type="text/javascript">
			$(document).ready(function(){
				console.log(d3.selectAll("path"));
			});
		</script>
  </body>
</html>
