window.onpopstate = function(event) {
	loadpage("/view"+document.location.pathname);
};
function loadpage(url,type,data,container){
	if(typeof type == "undefined"){ type="GET"; }
	if(typeof data == "undefined"){ data=""; }
	if(typeof container == "undefined"){ container="#content"; }

	var req = $.ajax({
		url: url,
		type: type,
		data: data,
		error: function (jqXHR, exception) {
			//todo
			$(container).html("что-то пошло не так. "+jqXHR.status);
		}
	}).done(function(html){
		$(container).html(html);
	});
}
function apianswer(data){
	$("#loader-progress").css("width","0%");
	var answer = JSON.parse(data);
	if(typeof answer.rediect != "undefined"){
		document.location.href = answer.rediect;
	}
	if(typeof answer.message != "undefined"){
		$("#server-answer").show();
		$("#server-answer").html(answer.message);
	}
	if(typeof answer.html != "undefined"){
		$("#content").html(answer.html);
	}
}
function navgo(e){
	var href = e.attributes.href.value;
	loadpage("/view"+href);
	window.history.pushState("", "", href);
	if($("body").width() <= 480){
		$("#menu").hide();
		$("#topmenu > .hamburger").toggleClass("active",false);
	}
	return false;
}
function collectform(form){
	var data = new FormData();
	$(form).find("input,textarea,select").each(function(c,elem){
		if(elem.name != "" && elem.disabled === false){
			if(elem.type == "checkbox"){
				if(elem.checked){
					data.append(elem.name, elem.value);
				}
			} else if(elem.type == "file"){
				for (var i = 0; i < elem.files.length; i++) {
					data.append(elem.name, elem.files[i]);
				}
			} else {
				data.append(elem.name, elem.value);
			}
		}
	});
	return data;
}
function updtickets(e,page){
	var data = collectform(e);
	data.append("page", page);
	loadpage("/view/tickets.php","GET",new URLSearchParams(data).toString(),".main-block");
	return false;
}
function submitform(e,evnt,callback){
	evnt.preventDefault();
	var url = "";
	var form = $(e);
	var data = collectform(form);
	if(form.attr("action") != undefined){
		url = form.attr("action");
	}
	if(typeof evnt.submitter != "undefined"){
		data.append(evnt.submitter.name, "");
	}
	$.ajax({
		type: "POST",
		url: url,
		data: data,
		processData: false,
		contentType: false,
		statusCode: {
			429: function() {
				$("#server-answer").show();
				$("#server-answer").html("Произошла ошибка, попробуйте ещё раз.");
			}
		}
	}).done(apianswer,callback);
	return false;
}
function addcomment(html){
	var data = JSON.parse(html);
	$("#logmsgs").append(data.newmsg);
	$("textarea[name=\"comment\"]").val("");
	$("#logmsgs > .ghosth").remove();
}
function modalimg(elem){
	$("body").append('<div class="modal-container modalpic" id="modal"><div><span class="crossbtn" onclick="$(\'#modal\').remove();">X</span><img src="'+elem.src+'"></div></div>');
}
function markdel(e){
	var targetrow = $(e).parent().parent();
	if(targetrow.hasClass("for-deletion")){
		e.innerText = "X";
		targetrow.find(".flagsinput").val("");
	} else {
		e.innerText = "+";
		targetrow.find(".flagsinput").val("del");
	}
	targetrow.toggleClass("for-deletion");
}
var newcategoryid = 0;
function newcategory(){
	newcategoryid--;
	$("#categories").append("<tr><td><input type='hidden' name='cat_flag["+newcategoryid+"]' value='new' class='flagsinput'><input placeholder='Новая категория' name=\"cat_name["+newcategoryid+"]\" value=''/></td><td><input name=\"cat_color["+newcategoryid+"]\" value='#000000' type='color'/></td><td><button type='button' class='redbtn' onclick='$(this).parent().parent().remove();'>X</button></td></tr>");
}
