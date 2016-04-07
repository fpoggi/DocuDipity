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
        
		<link rel="stylesheet" type="text/css" href="lib/codemirror-4.7/lib/codemirror.css">
		<link rel="stylesheet" type="text/css" href="css/patternsHTML.css">
		<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Open+Sans:400,600">
		<link rel="stylesheet" type="text/css" href="css/draw.css"/>
		<link rel="stylesheet" type="text/css" href="css/rec.css"/>
	</head>
	<body>
		<div>
	      <h1>Docudipity</h1>
	    </div>
	    <div id="intro" style="text-align: center;">
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
		<script type="text/javascript">
		// Hack to make this example display correctly in an iframe on bl.ocks.org
      		d3.select(self.frameElement).style("height", "700px");
		</script>
  </body>
</html>
