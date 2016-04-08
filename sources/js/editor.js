/**
 * js_editor
 */
var js_textarea = document.getElementById("js_editor");
var js_editor = CodeMirror.fromTextArea(js_textarea, {
	mode: "javascript",
	lineNumbers: true,
	theme: "default",
	readOnly: false
});
js_editor.setSize(1000, 400);



$("#nextdoc").on("click", function() {
	var selectedEl = $('select#docSelection').find(":selected").next();
	var selectedVal = $('select#docSelection').find(":selected").next().val();
	//console.log( selectedVal );
	
	selectedEl.prop('selected', true); //val(selectedVal);
	updateDocument(selectedEl);
	
	setTimeout(function() { evaluateJs(); }, delayDraw);

});

$("#doco_go").on("click", function() {
	evaluateJs();
});


$("#js_example").change(function() {
        //updateDocument( $(this) );
        //setTimeout(function() { evaluateJs(); }, delayDraw);
	var example = $(this).val();
	var js_code = code_examples[example].js;
	var css_code = code_examples[example].css;
	js_editor.setValue(js_code);
	css_editor.setValue(css_code);
	setTimeout(function() { evaluateJs(); }, delayDraw);	
});


function evaluateJs() {
	
	$("#css_included").remove();
	var css_text = css_editor.getValue();
	$('head').append('<style type="text/css" id="css_included">' + css_text + '</style>');
	
	var doco_text = js_editor.getValue();
	eval("function draw_doco() {"+ doco_text + "} draw_doco();	");

	//$('head').append('<style type="text/css">path.doco-paragraph {fill: blue !important;}</style>');
		
	var docosel = $("classeddocument [class*=' doco-']"); //.find("[class|='doco']"); //.attr("class").split(' ');
		
	docosel.each(function() {
		addClassToPath( $(this) );
	});
}

function addClassToPath(currEl) {
		
	//var classesString = $(this).attr("class");
	var classesString = currEl.attr("class");
		
	var eid = getStructuralClass( classesString );
	var docoClassesArr = getDocoClass( classesString );
	var pathEl = $("path."+eid);

	for (var i=0; i<docoClassesArr.length; i++) {
		// Ho fatto un accrocchio perché $.hasClass() non funziona
		//if ( ! pathEl.hasClass(docoClassesArr[i]) ) {
		if ( ! (pathEl.attr("class").indexOf(docoClassesArr[i]) > -1) ) {
			var pathElClass = pathEl.attr("class");
			pathEl.attr("class", pathElClass +" "+ docoClassesArr[i]);
		}
		pathEl.html("<title>" + docoClassesArr[i] + "</title>");
	}
}

/**
 * css_editor
 */
var css_textarea = document.getElementById("css_editor");
var css_editor = CodeMirror.fromTextArea(css_textarea, {
	mode: "text/css",
	lineNumbers: true,
	theme: "default",
	readOnly: false
});
css_editor.setSize(600, 400);



/**
 * INIT - populates css and js editors with code
 */

function load_js_code() {
        var example = $("#js_example").val();
        var js_code = code_examples[example].js;
        var css_code = code_examples[example].css;
        js_editor.setValue(js_code);
        css_editor.setValue(css_code);
        //setTimeout(function() { evaluateJs(); }, delayDraw);
}
load_js_code();
