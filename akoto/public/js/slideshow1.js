var image1 = new Image();
image1.src = "img/bag1.jpg";
var image2 = new Image();
image2.src = "img/bag2.jpg";
var image3 = new Image();
image3.src = "img/bag3.jpg";

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
   $('homeimg').attr('src','img/bag2.jpg');
   document.getElementById("homeimg").src = "img/bag" + step + ".png";
   setTimeout("slideit()", 3500)
}
