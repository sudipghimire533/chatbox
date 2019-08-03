//------this for toast-----
var elem;
window.addEventListener('DOMContentLoaded', function(){
	elem = document.getElementById("Toast");
});
function Toast(txt,type) {
	elem.textContent=txt;
	if(type=='sucess'){elem.style.backgroundColor="#89DA30";}
	else if(type=='error'){elem.style.backgroundColor="#D42B17";}
	else if(type=='warning'){elem.style.backgroundColor="#F2DD28";}
	else if(type=='warning'){elem.style.backgroundColor="#333333";}
	else{elem.style.backgroundColor=type;}
	elem.className = "show";
	setTimeout(function(){elem.className=elem.className.replace("show", ""); },3500);
}
//------------------------
function Makesmall(elem){
	elem.value=elem.value.toLowerCase();
}
function fillPrev(Email, Password, UserName, FullName){
	document.getElementById('Email').value = Email;
	document.getElementById('Password').value = Password;
	document.getElementById('UserName').value = UserName;
	document.getElementById('FullName').value = FullName;
}
function focuson(elem){
	if(elem == 'Email'){
		document.getElementById('Email').focus();
		document.getElementById('Email').style.borderColor = 'red';
	}
	else if(elem == 'Password'){
		document.getElementById('Password').focus();
		document.getElementById('Password').style.borderColor = 'red';
	}
	else if(elem == 'UserName'){
		document.getElementById('UserName').focus();
		document.getElementById('UserName').style.borderColor = 'red';
	}
	else if(elem == 'FullName'){
		document.getElementById('FullName').focus();
		document.getElementById('FullName').style.borderColor = 'red';
	}
}
function showLog(text, type){
	let logViewer = document.getElementById("ErrorLogger");
	logViewer.textContent = text;
	if(type == 'error'){ bg="#FF5555"; }
	else if(type == 'sucess'){ bg="#55FF55"; }
	else if(type == 'info'){ bg="#26c6da"; }
	logViewer.style.backgroundColor = bg;
}
