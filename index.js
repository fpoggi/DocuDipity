$(document).ready(function(){
	change_idx_rul(); // navigate rule closed
	load_title_nc_top();  // navigate rule closed
	hide_cont();
	load_title_nc_bot();
	load_rules();
	change_sel();
	makecopy();
});
var idx=0;

function change_idx_rul(){
	$(".glyphicon-chevron-right").on("click",function(){
		var selectedTit = $('select#seltit').find(":selected").next();
		var selectedTitVal = $('select#seltit').find(":selected").next().val();
		//console.log( selectedVal );
		selectedTit.prop('selected', true); //val(selectedVal);

		if(idx==(vettore_regole.length-1)){
			idx=0;
			$(".first-child").prop('selected', true);
			load_title_nc_top();
		}
		else{
			idx++;
			load_title_nc_top();
		}
	});
	$(".glyphicon-chevron-left").on("click",function(){
		var selectedTit = $('select#seltit').find(":selected").prev();
		var selectedTitVal = $('select#seltit').find(":selected").prev().val();
		//console.log( selectedVal );
		selectedTit.prop('selected', true); //val(selectedVal);
		if(idx==0){
			idx=(vettore_regole.length-1);
			$(".last-child").prop('selected', true);
			load_title_nc_top();
		}
		else{
			idx--;
			load_title_nc_top();
		}
	});
}

function load_title_nc_top(){
	$("#spantitle").empty();
	$("#spanauth").empty();
	$("#spandesc").empty();
	$("#spanjs").empty();
	$("#spancss").empty();

	$("#spantitle").append(vettore_regole[idx]["title"]);
	$("#spanauth").append(vettore_regole[idx]["author"]);
	$("#spandesc").append(vettore_regole[idx]["description"]);

	//invisible
	$("#spanjs").append(vettore_regole[idx]["js"]);
	$("#spancss").append(vettore_regole[idx]["css"]);

	$("#navigate_rule_close_top").empty();
	$("#navigate_rule_close_top").append('<span idx="'+idx+'" class="span_nc">'+vettore_regole[idx]["title"]+'</span>');
}

function load_title_nc_bot(){
	$("#seltit").append('<option class="first-child" idx="'+vettore_regole[0]["id"]+'" id="'+vettore_regole[0]["title"]+'" value="'+vettore_regole[0]["title"]+'">'+vettore_regole[0]["title"]+'</option>');
	for(var i=1; i<vettore_regole.length-1; i++){
		$("#seltit").append('<option idx="'+vettore_regole[i]["id"]+'" id="'+vettore_regole[i]["title"]+'" value="'+vettore_regole[i]["title"]+'">'+vettore_regole[i]["title"]+'</option>');
	}
	var j=vettore_regole.length-1;
	$("#seltit").append('<option class="last-child" idx="'+vettore_regole[j]["id"]+'" id="'+vettore_regole[j]["title"]+'" value="'+vettore_regole[j]["title"]+'">'+vettore_regole[j]["title"]+'</option>');
}

function hide_cont(){
	$("#collapse_nr").on("click", function(){
		var a_e=$(this).attr("aria-expanded");
		if(a_e=="false"){
			$("#nr_top").addClass("hide");
		}
		else{
			$("#nr_top").removeClass("hide");
		}
	});

}
function load_rules_base(){
	var js_code = vettore_regole[idx]["js"];
	var css_code = vettore_regole[idx]["css"];
	js_editor.setValue(js_code);
	css_editor.setValue(css_code);
	if(($("#collapse_er").attr("aria-expanded"))=="false"){
		$("#collapse_er").attr("aria-expanded",true);
	}

	// setTimeout(function() { evaluateJs(); }, delayDraw);
}
function load_rules(){
	var js_code = vettore_regole[idx]["js"];
	var css_code = vettore_regole[idx]["css"];
	js_editor.setValue(js_code);
	css_editor.setValue(css_code);
	// setTimeout(function() { evaluateJs(); }, delayDraw);

}
function change_sel(){
	$("option").on("click",function(){
		idx=$(this).attr("idx");
		load_title_nc_top();
	});
}
function makecopy(){
	$("#mc").on("click",function(){
		var tit=$("#spantitle").text();
		var js=$("#spanjs").text();
		var css=$("#spancss").text();
		var author=$("#spanauth").text();

		$("#titlecpy").attr("value",tit);
		$("#jscpy").attr("value",js);
		$("#csscpy").attr("value",css);
		$("#authcpy").attr("value",author);
	});
}