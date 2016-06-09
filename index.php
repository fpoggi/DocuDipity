<?php
	// Inizio la sessione
  	session_start();
  	if (isset($_SESSION['user'])){
      	header("location: index_logged.php");
  	}	
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
	</head>
	<body>
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
		      <form method="post" name="signup" action="php/checklogin_index.php">
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

		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 5px; border:1px solid black; border-radius:4px; padding:15px;">
			<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 text-left">
				<span style="cursor:pointer; font-size:130%;" class='glyphicon glyphicon-chevron-left'><a style="color:black; margin-left:5px;" href="gestione_regole.php">Management</a></span>
			</div>
			<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
	      		<h1>Docudipity</h1>
			</div>
			<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 text-right right">
				<button type="button" class="btn btn-default" id="login" data-toggle="modal" data-target="#loginModal">Login</button>
				<button class="text-left btn btn-default" style="margin-left: 5px;" data-toggle="modal" data-target="#signupModal">Sign up</button>
			</div>
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
				<div id="sequence"></div>
				<div id="chart"></div>
			</div>
			<div id="sidebar">
		      	<input type="checkbox" id="togglelegend" checked="checked"> Legend<br/>
				<div id="legend"></div>
			</div>
	    	<div id="document"></div>
		</div>
	    <div id="doco_container">
	    	<div style="width: 1000px;">
			<input id="doco_go" type="submit" value="evaluate">
	    		<input id="doco_reset" type="reset" value="reset">
			<div style="display: inline; float: right;">
				<label for="js_example">Load an example: </label><select name="js_example" id="js_example">
					<option value="doco_code" id="doco_code">DoCO structures</option>
					<option value="biblioref_code" id="biblioref_code" selected="selected">Bilbiographic references</option>
					<option value="para_length_code" id="para_length_code">Paragraph length</option>
					<option value="uk_vs_usa_code" id="uk_vs_usa_code">UK vs. USA English</option>
				</select>
			</div>
		</div>
		<div id="editor_container">
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
				<textarea id="css_editor" rows="40" cols="50"></textarea>
		</div>
	</div>
		<script type="text/javascript" src="js/init_param.js"></script> 
    	<script type="text/javascript" src="js/draw.js"></script> 
    	<script type="text/javascript" src="js/editor.js"></script>
    	<script type='text/javascript' src='bootstrap/js/bootstrap.js'></script>
		<script type="text/javascript">
		// Hack to make this example display correctly in an iframe on bl.ocks.org
      		d3.select(self.frameElement).style("height", "700px");
		</script>
		<script type="text/javascript">
			$(document).ready(function(){
				console.log(d3.selectAll("path"));
			});
		</script>
		<script type="text/javascript">
		  $(window).load(function(){
		    var access = "<?php echo $access; ?>";
		    if (access == "deny") {
		    	alert("erroe");
		      $('#loginModal').modal('show');
		      $("#logmail").addClass("has-error");
		      $("#logpassword").addClass("has-error");
		    }
		  });
		</script>
  </body>
</html>
