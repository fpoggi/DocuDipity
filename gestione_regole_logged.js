$(document).ready(function(){
    pre_create();
    loadtable_altre();
    loadtable_pub();
    loadtable_priv();
    getfilename();
    loadgroup();
});

var flag_altre=0;
var flag_pub=0;
var flag_priv=0;
var flag_docs=[];
flag_docs[0]=0;
flag_docs[1]=0;
var titledocs=[];
var groupdocs=[];
var newgroupdocs=[];

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

function pre_create(){
	$("#createrules").on("click",function(){
		$(".td").removeClass("red");
		$("#createrules").addClass("red");
		$(".table-striped").addClass("hide");
		$("#prerules").removeClass("hide");
		$(".spantab").addClass("hide");
	});
}

function prerules(){
	var title1=$("#title").val();
	var author1=$("#author").val();
	var description1=$("#description").val();
	$("#maincontent").empty();
	create(title1,author1,description1);
}

function create(tit, auth, des){
	$("#maincontent").append('<form method="post" action="php/create.php" class="form-horizontal"><div class="form-group"><label for="spantitle" class="col-sm-2 control-label">Title:</label><div class="col-sm-10"><span class="" id="spantitle">'+tit+'</span></div></div><div class="form-group"><label for="spanauth" class="col-sm-2 control-label">Author:</label><div class="col-sm-10"><span class="" id="spanauth">'+auth+'</span></div></div><div class="form-group"><label for="spandesc" class="col-sm-2 control-label">Description:</label><div class="col-sm-10"><span class="" id="spandesc">'+des+'</span></div></div><div class="form-group"><div class="col-sm-offset-2 col-sm-10"><button type="submit" class="btn btn-default">Create</button></div></div></form>')
    $("#rultitle").attr("value",tit);
	$("#rulauth").attr("value",auth);
	$("#ruldes").attr("value",des);
}
function loadtable_altre(){
	$("#altrerules").on("click", function(){
		$(".td").removeClass("red");
		$(this).addClass("red");
		$("#prerules").addClass("hide");
		$(".table-striped").addClass("hide");
		$("#spanpub").addClass("hide");
		$("#spanpriv").addClass("hide");
		$("#spandoc").addClass("hide");
		$("#spanaltre").removeClass("hide");
		$("#altre").removeClass("hide");
		if(flag_altre==0){
			for(var j=0; j<vettore_regole.length; j++){
				$("#altre").append('<tr><td id="tit'+j+'"></td><td id="auth'+j+'"></td><td id="desc'+j+'"><td id="stat'+j+'"></td></td><td id="edit'+j+'"><button class="btn btn-default">edit</button></td><td id="dup'+j+'"><button class="btn btn-default">duplicate</button></td><td id="del'+j+'"></td></tr>');
			}
			for(var i=0; i<vettore_regole.length; i++){
				$("#tit"+i).append(vettore_regole[i]["title"]);
				$("#auth"+i).append(vettore_regole[i]["author"]);
				$("#desc"+i).append(vettore_regole[i]["description"]);
				$("#stat"+i).append(vettore_regole[i]["status"]);
			}
			flag_altre=1;
		}
	});
}

function loadtable_pub(){
	$("#pubrules").on("click",function(){
		$(".td").removeClass("red");
		$(this).addClass("red");
		$("#prerules").addClass("hide");
		$(".table-striped").addClass("hide");
		$("#spanpub").removeClass("hide");
		$("#spanpriv").addClass("hide");
		$("#spandoc").addClass("hide");
		$("#spanaltre").addClass("hide");
		$("#miepub").removeClass("hide");
		if(flag_pub==0){
			for(var j=0; j<vettore_regole.length; j++){
				if(user==vettore_regole[j]["author"]){
					$("#miepub").append('<tr><td id="titp'+j+'"></td><td id="authp'+j+'"></td><td id="descp'+j+'"><td id="statp'+j+'"></td></td><td id="editp'+j+'"><button class="btn btn-default">edit</button></td><td id="dupp'+j+'"><button class="btn btn-default">duplicate</button></td><td id="delp'+j+'"><button class="btn btn-default">delete</button></td></tr>');
				}		
			}
			for(var i=0; i<vettore_regole.length; i++){
				if(user==vettore_regole[i]["author"]){
					$("#titp"+i).append(vettore_regole[i]["title"]);
					$("#authp"+i).append(vettore_regole[i]["author"]);
					$("#descp"+i).append(vettore_regole[i]["description"]);
					$("#statp"+i).append(vettore_regole[i]["status"]);
				}
			}
			flag_pub=1;
		}
	});
	
}
function loadtable_priv(){
	$("#privrules").on("click", function(){
		$(".td").removeClass("red");
		$(this).addClass("red");
		$("#prerules").addClass("hide");
		$(".table-striped").addClass("hide");
		$("#spanpub").addClass("hide");
		$("#spanpriv").removeClass("hide");
		$("#spandoc").addClass("hide");
		$("#spanaltre").addClass("hide");
		$("#miepriv").removeClass("hide");
		if(flag_priv==0){
			for(var j=0; j<vettore_regole.length; j++){
				if((user==vettore_regole[j]["author"])&&(vettore_regole[j]["status"]==0)){
					$("#miepriv").append('<tr><td id="titpp'+j+'"></td><td id="authpp'+j+'"></td><td id="descpp'+j+'"><td id="statpp'+j+'"></td></td><td id="editpp'+j+'"><button class="btn btn-default">edit</button></td><td id="duppp'+j+'"><button class="btn btn-default">duplicate</button></td><td id="delpp'+j+'"><button class="btn btn-default">delete</button></td></tr>');
				}		
			}
			for(var i=0; i<vettore_regole.length; i++){
				if((user==vettore_regole[i]["author"])&&(vettore_regole[i]["status"]==0)){
					$("#titpp"+i).append(vettore_regole[i]["title"]);
					$("#authpp"+i).append(vettore_regole[i]["author"]);
					$("#descpp"+i).append(vettore_regole[i]["description"]);
					$("#statpp"+i).append(vettore_regole[i]["status"]);
				}
			}
			flag_priv=1;
		}
	});
	
}


function getfilename(){
	for(var i=2; i<docs.length; i++){
		getXml(docs[i],i);
	}
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
			$("#spanpub").addClass("hide");
			$("#spanpriv").addClass("hide");
			$("#spandoc").removeClass("hide");
			$("#spanaltre").addClass("hide");
			$("#tabdocs").removeClass("hide");
			$("#tabdocs").empty();
			$("#tabdocs").append("<tr><th>Title</th><th>Author</th><th>Group</th></tr>")
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
