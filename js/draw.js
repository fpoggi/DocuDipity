var editor_xmlclassed = "http://localhost/vis/DoCoFromPatterns/data/xmlclassed/";

// Dimensions of sunburst.
var width = 1920;
var height = 900;
var radius = Math.min(width, height) / 2;

var mainChartWidth = 900;

// Breadcrumb dimensions: width, height, spacing, width of tip/tail.
var b = {
	w : 90,
	h : 40,
	s : 3,
	t : 10
};

var delayDraw = 2000;

/**
 * FPOGGI - START
 */
var x = d3.scale.linear()
	.range([0, 2 * Math.PI]);

var y = d3.scale.sqrt()
	.range([0, radius]);
/**
 * FPOGGI - END
 */


d3.select("#page").style({
	"width" : width + "px"
});

// Total size of all segments; we set this later, after loading the data.
var totalSize = 0;

var svg_focus;

var mainChart = d3.select("#chart").append("svg:svg")
										.attr("id", "mainChart")
										.attr("width", mainChartWidth) //width * .75)
										.attr("height", height); // * 1.1);

var vis = mainChart
			.append("svg:g")
			.attr("id", "container")
			.attr("transform", "translate(470,410)"); // + width / 2 + "," + ((height/2)-40)  + ")");

var partition = d3.layout.partition()
					//.size([ 2 * Math.PI, radius * radius ])
					.value(function(d) {
						return d.text;
					}).sort(null);



/**
 * FPOGGI - START
 */
/*
var arc = d3.svg.arc()
					.startAngle(function(d) { return d.x; })
					.endAngle(function(d) { return d.x + d.dx; })
					.innerRadius(function(d) { return Math.sqrt(d.y); })
					.outerRadius(function(d) { return Math.sqrt(d.y + d.dy); });
*/

var arc = d3.svg.arc()
			.startAngle(function(d) { return Math.max(0, Math.min(2 * Math.PI, x(d.x))); })
			.endAngle(function(d) { return Math.max(0, Math.min(2 * Math.PI, x(d.x + d.dx))); })
			.innerRadius(function(d) { return Math.max(0, y(d.y)); })
			.outerRadius(function(d) { return Math.max(0, y(d.y + d.dy)); });
/**
 * FPOGGI - END
 */

$("#docSelection").change(function() {
	updateDocument( $(this) );
	
	setTimeout(function() { evaluateJs(); }, delayDraw);
});



/**
 * SunBurst reset
 ******************** 
 * TODO: it should delete (or at least manage) unused html classes
 */
$("#doco_reset").click(function(){
	updateDocument( $("#docSelection") );
});

function updateDocument(el) {
	var urlFile = el.val();
	d3.json(urlFile, function(json) {
		console.log(json);
		drawVisualization(json[0], false);
	});
	
	$("#document").load(urlFile.replace('/json/', '/htmlsubtree/').replace('.json', '.xml'), updateText);
	
	// carico xmlclassed per editor patternfinder
	// new FPOGGI
	$("#xmlclassed").load( urlFile.replace('/json/', '/xmlclassed/').replace('.json', '.xml') );
	//$("#xmlclassed").load( urlFile.replace('/json/', '/xmlclassed/').replace('.json', '.xml'), evaluateJs());
	
}


$("#openxml").click(function() {
	var file = $('#docSelection option:selected').text();
	var win = window.open("data/xml/"+file, '_blank');
	win.focus();
});


d3.json($("#docSelection").val(), function(json) {
	drawVisualization(json[0], true);
	var urlFile = $("#docSelection").val();
	$("#document").load(urlFile.replace('/json/', '/htmlsubtree/').replace('.json', '.xml'), updateText);

	// carico xmlclassed per editor patternfinder
	$( "body" ).append("<div id='xmlclassed'/>");
	$("#xmlclassed").load( urlFile.replace('/json/', '/xmlclassed/').replace('.json', '.xml') );
});

function updateText() {
		
	$('#document div').mouseover(function(event) {
		
		event.stopPropagation();
		if ($(this).attr("class")) {
			$(this).addClass("hover");
			var eClass = getStructuralClass( $(this).attr("class"));
			burstDatum = getParentInBurst(d3.select(this), eClass);				
			mouseover(burstDatum);
		}
			
	});
		
	function getParentInBurst(currEl, eClass) {
		sel = d3.select("path."+eClass);
		if (!sel.empty()) {
			return sel.datum();
		} else {
			parent = d3.select(currEl.node().parentNode);
			parentEClass = getStructuralClass(parent.attr("class"));
			return getParentInBurst(parent, parentEClass);
		} 
	}
		
	$('#document div').mouseout( function(event) {
			
		event.stopPropagation();
		if ($(this).attr("class")) {
			$(this).removeClass("hover");
			var eClass = getStructuralClass( $(this).attr("class"));
			burstDatum = getParentInBurst(d3.select(this), eClass);				
				
		}
			
	});

	$('#document div').click(function(event) {
		event.stopPropagation();
	});
		
	$('#document div.article').mouseleave(function() {
		mouseleave(d3.select("#container").datum());
	});
		
	$('#document').mouseleave(function() {
		mouseleave(d3.select("#container").datum());
	});
};


function getStructuralClass(classString) {
	
	//console.log("SClass: "+classString);
	var classString_split = classString.split(" ");
	for (var i=0; i< classString_split.length; i++) {
		var attr = classString_split[i];
		if (/e[0-9]+$/.test(attr)) {
			return attr;
		}
	}
}


function getDocoClass(classString) {
	
	var docoClassesArr = [];
	var classString_split = classString.split(" ");
	for (var i=0; i< classString_split.length; i++) {
		var attr = classString_split[i];
		if (/doco-.+$/.test(attr)) {
			docoClassesArr.push(attr);
		}
	}
	return docoClassesArr;
}







// Main function to draw (and set up, if needed) the visualization, once we have
// the data.
function drawVisualization(json, doInitialization) {

	svg_focus = json.xmlId;
	
	if (doInitialization) {
		// Basic setup of page elements.
		//initializeBreadcrumbTrail();
		drawLegend();
		d3.select("#togglelegend").on("click", toggleLegend);
		d3.select("#legend svg g rect").on("click", function(d) {
			alert("click!");
		}).attr("id", function(d, i) {
			return i;
		});

	} else {
		// Empty the main vis element
		vis.html("");

		// Resetto per zoom, al più esterno
		x = d3.scale.linear()
			.range([0, 2 * Math.PI]);

		y = d3.scale.sqrt()
			.range([0, radius]);
		arc = d3.svg.arc()
			.startAngle(function(d) { return Math.max(0, Math.min(2 * Math.PI, x(d.x))); })
			.endAngle(function(d) { return Math.max(0, Math.min(2 * Math.PI, x(d.x + d.dx))); })
			.innerRadius(function(d) { return Math.max(0, y(d.y)); })
			.outerRadius(function(d) { return Math.max(0, y(d.y + d.dy)); });

	}

	// Bounding circle underneath the sunburst, to make it easier to detect
	// when the mouse leaves the parent g.
	// FPOGGI - commented
	vis.append("svg:circle")
		.attr("r", radius)
		.style("opacity", 0);
		//.attr("fill", "red");

	// For efficiency, filter nodes to keep only those large enough to see.
	// FPOGGI - commented
//	var nodes = partition.nodes(json).filter(function(d) {
//		return (d.dx > 0.00); // 0.005 radians = 0.29 degrees
//	});

	
	function reset_o(){//before each animation, reset to (0,0), skip this.
		$("body").stop().stop();
		$("body").scrollTop = 0;
	};
	
	var path = vis.selectAll("path")
						//.data(nodes)
						.data(partition.nodes(json).filter(function(d) {
							return (d.dx >= 0.00); // 0.005 radians = 0.29 degrees
						}))
						.enter()
						.append("path")
							//.attr("display", function(d) {
							//	return d.depth ? null : "none";
							//})
							.attr("d", arc)
							.attr("fill-rule", "evenodd")
							.attr("class", function(d) {
								return d.pattern + " " + d.xmlId;
							})
							.style("fill", function(d) {
								return colorsPattern[d.pattern].color;
							})
							.style("opacity", 1)
							.on("mouseover", mouseover)
							.on("click", click);
	
	function click(d) {
		
		svg_focus = d.xmlId;
		
		//if (d3.select(this).attr)
		zoom(d);
		scrollText(d, this);
	}
	
	function zoom(d) {

		// Hide not zoomed elements 
		d3.selectAll("path").classed("invisible", true);
		if (d.parent) {
			parent = d.parent.xmlId;
		}
		d3.selectAll("path."+parent).classed("invisible", false);
		var descendants = getDescendants(d, []);
		for (var i=0; i<descendants.length; i++) {
			d3.selectAll("path."+descendants[i]).classed("invisible", false);
		}

		/*********************************************
		 ** FPOGGI -PROBLEMA ZOOM			**
		 *********************************************/
		path
			.on("mouseover", null) // PROBLEMA ZOOM - A
			.transition()
			.duration(650)
			.attrTween("d", arcTween(d))
			.each("end", function() { d3.select(this).on("mouseover", mouseover); });

	}

	function getStructuralClass(classString) {
		
		var classString_split = classString.split(" ");
		for (var i=0; i< classString_split.length; i++) {
			var attr = classString_split[i];
			if (/e[0-9]+$/.test(attr)) {
				return attr;
			}
		}
	}
	
	function scrollText(d, el) {
		var attrClasses = d3.select(el).attr('class');
		var elId = getStructuralClass(attrClasses);
		$("#document div").removeClass("hover");
		$("#document").stop().scrollTo( "."+elId, { duration: 800 } );
		$("#document ."+elId).addClass("hover");
	}
	
	if (doInitialization)
		initializeBreadcrumbTrail();
	
	// Add the mouseleave handler to the bounding circle.
	//d3.select("#container").on("mouseleave", mouseleave);
	
	// FPOGGI: 	path sono sopra a circle, per becco evento "uscita dai path" quando
	// 			entro "entro nel cerchio" col mouse.
	d3.select("#mainChart circle").on("mouseover", mouseleave);

	// Get total size of the tree = value of root node from partition.
	totalSize = path.node().__data__.value;

};











//Interpolate the scales!
function arcTween(d) {
  var xd = d3.interpolate(x.domain(), [d.x, d.x + d.dx]),
      yd = d3.interpolate(y.domain(), [d.y, 1]),
      yr = d3.interpolate(y.range(), [d.y ? 20 : 0, radius]);
  return function(d, i) {
    return i
        ? function(t) { return arc(d); }
        : function(t) { x.domain(xd(t)); y.domain(yd(t)).range(yr(t)); return arc(d); };
  };
}

function getDescendants(d, resArr) {
	resArr.push(d.xmlId);
	if (!d.children) {
		return resArr;
	} else {
		for (var i=0; i<d.children.length; i++) {
			getDescendants(d.children[i], resArr);
		}
		return resArr;
	}
}


// Fade all but the current sequence, and show it in the breadcrumb trail.
function mouseover(d) {

	updateBreadcrumbs(d);

	// Fade all the segments.
	d3.selectAll("path").style("opacity", 0.3);
	
	// Then highlight only those that are an ancestor of the current segment.
	vis.selectAll("path").filter(function(node) {
		var sequenceArray = getAncestors(d);
		return (sequenceArray.indexOf(node) >= 0);
	}).style("opacity", 1);
 	
}

// Restore everything to full opacity when moving off the visualization.
function mouseleave(d) {

	updateBreadcrumbs(d3.select("path."+svg_focus).datum());
	
	// Hide the breadcrumb trail
	// d3.select("#trail").style("visibility", "hidden");

	// Deactivate all segments during transition.
	d3.selectAll("path").on("mouseover", null);

	
	
	
	// ***********************************************************************
	//  ** FPOGGI - PROBLEMA QUI						 **
	//  ********************************************************************* /

/*
	//  ** TRANSIZIONE RIMOSSA: andava in conflitto con transizione dello zoom		 **
	//  Transition each segment to full opacity and then reactivate it.
	d3.selectAll("path").transition().duration(700).style("opacity", 1).each(
			"end", function() {
				d3.select(this).on("mouseover", mouseover);
			});
*/
	d3.selectAll("path").style("opacity", 1).each(
			function() {
				d3.select(this).on("mouseover", mouseover);
			});

	//d3.select("#explanation").style("visibility", "hidden");
}

// Given a node in a partition layout, return an array of all of its ancestor
// nodes, highest first, but excluding the root.
function getAncestors(node) {

	var path = [];
	var current = node;
	while (current.parent) {
		path.unshift(current);
		current = current.parent;
	}
	// FPOGGI - START
	path.unshift(current);
	// FPOGGI - END

	return path;
}

function initializeBreadcrumbTrail() {
	
	// Add the svg area.
	var trail = d3.select("#sequence").append("svg:svg").attr("width", mainChartWidth)
			.attr("height", 50).attr("id", "trail");
	// Add the label at the end, for the percentage.
	trail.append("svg:text").attr("id", "endlabel").style("fill", "#000");
	
	// FPOGGI - last
	updateBreadcrumbs(d3.select("path."+svg_focus).datum());
}

// Generate a string that describes the points of a breadcrumb polygon.
function breadcrumbPoints(d, i) {
	var points = [];
	points.push("0,0");
	points.push(b.w + ",0");
	points.push(b.w + b.t + "," + (b.h / 2));
	points.push(b.w + "," + b.h);
	points.push("0," + b.h);
	if (i > 0) { // Leftmost breadcrumb; don't include 6th vertex.
		points.push(b.t + "," + (b.h / 2));
	}
	return points.join(" ");
}

// Update the breadcrumb trail to show the current sequence and elementInfo.
function updateBreadcrumbs(d) {

	
	var nodeArray = getAncestors( d );
	var elementInfo = d.text;
	
	// Data join; key function combines pattern and depth (= position in
	// sequence).
	var g = d3.select("#trail").selectAll("g")
									.data(nodeArray, function(d) {
										//console.log("select trail");
										return d.pattern + d.gid + d.depth;
									});

	// Add breadcrumb and label for entering nodes.
	var entering = g.enter().append("svg:g");
	// Remove exiting nodes.
	g.exit().remove();
	g.attr("id", function(d) { return d.xmlId; });

	entering.append("svg:polygon")
				.attr("points", breadcrumbPoints)
				.style("fill", function(d) {
					return colorsPattern[d.pattern].color;
				});

	entering.append("svg:text")
				.attr("x", (b.w + b.t) / 2)
				.attr("y", b.h / 2)
				.attr("dy", "0.35em")
				.attr("text-anchor", "middle")
				.text(function(d) {
					return d.gid;
				});

	// Set position for entering and updating nodes.
	g.attr("transform", function(d, i) {
		return "translate(" + i * (b.w + b.s) + ", 0)";
	});

	// Remove exiting nodes.
	//g.exit().remove();

	// Now move and update the elementInfo at the end.
	d3.select("#trail").select("#endlabel").attr("x",
			(nodeArray.length + 0.5) * (b.w + b.s)).attr("y", b.h / 2).attr(
			"dy", "0.35em").attr("text-anchor", "middle").text(elementInfo);

	// Make the breadcrumb trail visible, if it's hidden.
	d3.select("#trail").style("visibility", "");

}

function drawLegend() {

	// Dimensions of legend item: width, height, spacing, radius of rounded
	// rect.
	var li = {
		w : 75,
		h : 30,
		s : 3,
		r : 3
	};

	var legend = d3.select("#legend")
						.append("svg:svg")
						.attr("width", li.w)
						.attr("height", d3.keys(colorsPattern).length * (li.h + li.s));

	var g = legend.selectAll("g")
					.data(d3.entries(colorsPattern))
					.enter()
					.append("svg:g")
						.on("mouseover", mouseoverBreadcrumb)
						.on("mouseout", mouseoutBreadcrumb)
						.attr("transform", function(d, i) {
							return "translate(0," + i * (li.h + li.s) + ")";
						});
	
	g.append("title")
		.text(function (d) { return d.value.description; });



	// legend.selectAll("g").each(function(d) {console.log(d3.select(this));
	// d3.select(this).on("mouseover", function() { alert("ciao!");} );} );

	g.append("svg:rect")
		.attr("rx", li.r)
		.attr("ry", li.r)
		.attr("width", li.w)
		.attr("height", li.h).style("fill", function(d) {
			return d.value.color;
		});
	
	g.append("svg:text")
		.attr("x", li.w / 2)
		.attr("y", li.h / 2)
		.attr("dy", "0.35em")
		.attr("text-anchor", "middle")
		.text(function(d) {
			return d.key;
		});
}

function mouseoverBreadcrumb(d) {
	d3.selectAll("path").style("opacity", 0.3);
	d3.selectAll("." + d.key).style("opacity", 1);
}

function mouseoutBreadcrumb(d) {
	d3.selectAll("#container path").style("opacity", 1);
}

function toggleLegend() {
	var legend = d3.select("#legend");
	if (legend.style("visibility") == "hidden") {
		legend.style("visibility", "");
	} else {
		legend.style("visibility", "hidden");
	}
}
