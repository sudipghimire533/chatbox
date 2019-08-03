
window.addEventListener('DOMContentLoaded', function(){
	window.onscroll = function() {myFunction()};
	var navbar = document.getElementById("MenuBar");
	var sticky = navbar.offsetTop+1;
	function myFunction() {
	  	if (window.pageYOffset > sticky) {
	  		navbar.style.position="fixed";
	    	navbar.classList.add("sticky");
	  	} else {
	  		navbar.style.position="absolute";
	    	navbar.classList.remove("sticky");
	  	}
	} 
});
function hamburgerAnimate(x) {
  	x.classList.toggle("change");
  	let elem=document.querySelector('#RightMenu');
  	if(elem.style.display!='inline-block'){elem.style.display='inline-block';}
  	else if(elem.style.display=='inline-block'){;elem.style.display="none";}
}
