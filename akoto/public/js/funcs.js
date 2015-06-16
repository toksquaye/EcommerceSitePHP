$(document).ready(function(){
            $('#slider2').plusSlider({
                autoPlay: true,
                displayTime: 3000, // The amount of time the slide waits before automatically moving on to the next one. This requires 'autoPlay: true'
                sliderType: 'slider', // Choose whether the carousel is a 'slider' or a 'fader'
                pauseOnHover: false});
            
            $('img.gopp').click(function(){
                window.location.href="productpage.php?pid=" + this.id});
            
            $('#mini_image').click(function(){
                        swap_pics();})
            
            $('#shipto_state_id').show(function(){
                        pop_states('shipto_state_id');
                        });
            
            $('#policies_page').show(function(){
                        document.getElementById("policies_menu").style.color = "red";
            });
            
            $('#aboutus_page').show(function(){
                        document.getElementById("aboutus_menu").style.color = "red";
            });
            
            $('#terms_page').show(function(){
                        document.getElementById("terms_menu").style.color = "red";
            });
            
            $('#contactus_page').show(function(){
                        document.getElementById("contactus_menu").style.color = "red";
            });
            
            $('#cart_page').show(function(){
                        document.getElementById("cart_menu").style.color = "red";
            });
            
            $('#newarrivals_page').show(function(){
                        document.getElementById("newarrivals_menu").style.color = "red";
            });
            
            $('#handbags_page').show(function(){
                        document.getElementById("handbags_menu").style.color = "red";
            });
            
            $('#clutches_page').show(function(){
                        document.getElementById("clutches_menu").style.color = "red";
            });
            
            $('#homeacc_page').show(function(){
                        document.getElementById("homeacc_menu").style.color = "red";
            });
            
            $('#specoff_page').show(function(){
                        document.getElementById("specoff_menu").style.color = "red";
            });
            
            $('#sale_page').show(function(){
                        document.getElementById("sale_menu").style.color = "red";
            });
            
            $('#slider2').show(function(){
                        document.getElementById("home_menu").style.color = "red";
            });
   });
$(window).load(function(){
            // listen for click 
    $("#ship_same_bill").click(function(event) {
        hideDiv();
    });
});

function hideDiv() {
            //code
            var ckbox = document.getElementById("ship_same_bill");
            if (ckbox.checked)
                        document.getElementById("get_billing_add").style.display = "none";
            else
                        document.getElementById("get_billing_add").style.display = "block";
}

function pop_shipping()
{
    var dropdown = document.getElementById("ship_method_id");
    for(var i=0; i < SHIPPING.length; i++)
    {
       var opt = SHIPPING[i].name;
       var el = document.createElement("option");
       el.textContent = opt;
       el.value = opt;
       dropdown.appendChild(el);
    }
}

function pop_states(field)
{
            
    var dropdown = document.getElementById(field);
    //var dropdown = document.getElementById("state_id");
    for(var i=0; i < STATES.length; i++)
    {
       var el = document.createElement("option");
       el.textContent = STATES[i].name;
       el.value = STATES[i].abbr;
       dropdown.appendChild(el);
    }
}

function swap_pics()
{
 var main_image = document.getElementById("main_image").src;
 document.getElementById("main_image").src = document.getElementById("mini_image").src;
 document.getElementById("mini_image").src = main_image;
 
            
}