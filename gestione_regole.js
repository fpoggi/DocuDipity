$(document).ready(function(){
    getfilename();
    loadgroup();
});
var flag=0;
var titledocs=[];
var groupdocs=[];

function login(){
	alert("devi prima effettuare il login");
	$('#loginModal').modal('show');
}
function loadtable(){
	$("#tablerules").removeClass("hide");
	if(flag==0){
		for(var j=0; j<vettore_regole.length; j++){
			$("#tablerules").append('<tr><td id="tit'+j+'"></td><td id="auth'+j+'"></td><td id="desc'+j+'"></td><td id="edit'+j+'"><button class="btn btn-default">edit</button></td><td id="dup'+j+'"><button class="btn btn-default">duplicate</button></td></tr>');
		}
		for(var i=0; i<vettore_regole.length; i++){
			$("#tit"+i).append(vettore_regole[i]["title"]);
			$("#auth"+i).append(vettore_regole[i]["author"]);
			$("#desc"+i).append(vettore_regole[i]["description"]);
		}
		flag=1;
	}
}
function getfilename(){
	for(var i=2; i<docs.length; i++){
		getXml(docs[i],i);
	}
}
function getXml(data, i){
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
	    if (xhttp.readyState == 4 && xhttp.status == 200) {
	        getTilte(xhttp);
	    }
	};
	xhttp.open("GET", "data/xml/"+data+"", true);
	xhttp.send();

	function getTilte(xml) {
	    var xmlDoc = xml.responseXML;
	    var x = xmlDoc.getElementsByTagName('title')[0];
	    var h = xmlDoc.getElementsByTagName('conftitle')[0];
	    var t = h.childNodes[0];
	    var y = x.childNodes[0];
	    loadtitle(y.nodeValue, t.nodeValue, i);
	}
}
function loadtitle(titolo,gruppo, i){
	titledocs[i]=titolo;
	groupdocs[i]=gruppo;
}
function loadgroup(){
	$("#tabDocuments").on("click", function(){
		for(var i=2; i<groupdocs.length-1; i++){
			if(groupdocs[i]!=groupdocs[i+1]){
				$("#tablegroups").append('<tr><td class="td group" gr="'+groupdocs[i]+'">'+groupdocs[i]+'</td></tr>');
			}
		}
		loadtable_doc();
	});
	function loadtable_doc(){
		$(".group").on("click", function(){
			var g=$(this).attr("gr");
			$(".td").removeClass("red");
			$(this).addClass("red");
			$("#prerules").addClass("hide");
			$(".table-striped").addClass("hide");
			$("#tabdocs").removeClass("hide");
			$("#tabdocs").empty();
				for(var j=2; j<titledocs.length; j++){
					if(g==groupdocs[j]){
						$("#tabdocs").append('<tr><td id="titd'+j+'"></td><td id="authd'+j+'"></td><td id="groupd'+j+'"></td></tr>');
						$("#titd"+j).append(titledocs[j]);
						$("#groupd"+j).append(groupdocs[j]);
					}
				}
		});
	}
}

