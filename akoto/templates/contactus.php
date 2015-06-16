<div class="display_page">
    <div class="prod_list_row">
        <h1 class="prod_list_heading" id="contactus_page">CONTACT US</h1>
        
        <?php if(isset($message))
                   echo '<p id="contactus_msg">'. $message. '</p>';
        ?>
        <div id="contact_form">
        <form method="post" action="contactus.php">
        <p class = "contact_form_entry"> <label>Name: (required)</label> </p>
        <p class="contact_form_entry"><input type="text" name="contact_name" placeholder="" autocomplete="off" maxlength="30"/></p>
        </br>
         <p class = "contact_form_entry"> <label>Email: (required)</label></p>   
         <p class = "contact_form_entry"><input type="email" name="contact_email" placeholder="" autocomplete="off" maxlength="30"/></p>
        </br>
         <p class = "contact_form_entry"> <label>Phone:</label> </p>
         <p class = "contact_form_entry"  ><input type="text" name="contact_phone" placeholder="" autocomplete="off" maxlength="30"/></p>
         </br>
         <p class = "contact_form_entry"> <label>Message: (required)</label></p>
         <p class ="contact_form_entry"><textarea name="contact_message" placeholder="" rows="10" cols="40"></textarea></p>
        
         <p class ="contact_form_entry1"><input type="submit" class="add_to_cart" value="Submit" /></p>
</form>
    </div>
    </div>
</div