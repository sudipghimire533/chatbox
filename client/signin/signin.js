//------this for toast-----
function Toast(txt,type) {
	elem = document.getElementById("Toast");
	elem.textContent=txt;
	if(type=='sucess'){elem.style.backgroundColor="#89DA30";}
	else if(type=='error'){elem.style.backgroundColor="#D42B17";}
	else if(type=='warning'){elem.style.backgroundColor="#F2DD28";}
	else if(type=='info'){elem.style.backgroundColor="#333333";}
	else{elem.style.backgroundColor=type;}
	elem.className = "show";
	setTimeout(function(){elem.className=elem.className.replace("show", ""); },3500);
}
function Invalid(){
	document.getElementById('Email').style.borderColor = "red";
	document.getElementById('Password').style.borderColor = "red";
	document.getElementById('Email').focus();
	document.getElementById('ErrorLogger').textContent = "Invalid Login...";
	document.getElementById('ErrorLogger').style.backgroundColor = "#FF5555";
}
function sucess(){
	document.getElementById('ErrorLogger').textContent = "Getting things Ready...";
	document.getElementById('ErrorLogger').style.backgroundColor = "#55FF55";
	document.getElementById('Password').style.borderColor = "#26c6da";
	document.getElementById('Email').style.borderColor = "#26c6da";
}
function info(text){
	document.getElementById('showMe').textContent = text;
}

//------------------------