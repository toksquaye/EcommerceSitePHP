
step = 1;
$(window).load(function(){
   slideit();
});
function slideit(){
   var sshow = document.getElementById("slideshow");
   
   if (step < 3) {
      step++;
   }
   else
      step = 1;
   document.getElementById("homeimg").src = "img/bag" + step + ".png";
   setTimeout("slideit()", 4500)
}
