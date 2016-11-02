<?php
/*
 * 360 - School Empowerment System.
 * Developer: Ankur Mishra (amishra@ebizneeds.com.au) | www.ebizneeds.com.au
 * Page details here: Page to add new subjects
 * Updates here:
 * Assign the breadcrumb page name for current page*/
/*bread crumb page variables ends */

//call the main config file, functions file and header

require_once "../../config/config.php";
require_once DIR_FUNCTIONS;
require_once VIEW_HEADER;
?>

<script type="text/javascript">
$(function() {
    $('#infoemail').hide(); 
    $('#notificationtype').change(function(){
        if($('#notificationtype').val() == 'Email') {
            $('#infosms').hide();
            $('#infoemail').show(); 
             }   
        else{
           $('#infosms').show();
            $('#infoemail').hide(); 
        }
    });
});
    </script>


<form action="<?php echo PROCESS_FORM; ?>" method="post" name="testform">

<div class="container">
    <div class="span10">
       
       <?php renderMsg(); ?>
       
       <div class="col-lg-4">
         <label for="notificationtype">Notification Type</label>
         <select name="notificationtype" id="notificationtype" class="form-control">
            
            <option value="">-Select One-</option>
            <option value="SMS">SMS Alert</option>
            <option value="Email">Email Alert</option>
            <option value="System Messages">System Messages</option>
            <option value="Other Alerts">Other Alerts</option>
         </select>
       </div>
       
    <div id="infosms">   
       <div class="col-lg-4">
          <label for="sendernumber">Enter Sender Number </label>
          <input type="text" name="sendernumber" id="sendernumber" class="form-control">
    
       </div>
       
       <div class="col-lg-4">
          <label for="recievernumber">Enter Reciever Number </label>
          <input type="text" name="recievernumber" id="recievernumber" class="form-control">
          
       </div>
    </div>   
       
       
    <div id="infoemail">   
       <div class="col-lg-4">
          <label for="senderemail">Enter Sender Email </label>
          <input type="text" name="senderemail" id="senderemail" class="form-control">
          
       </div>
       
       <div class="col-lg-4">
          <label for="recieveremail">Enter Reciever Email </label>
          <input type="text" name="recieveremail" id="recieveremail" class="form-control">
          
       </div>
    </div>    
       
    <span class="clearfix"><p>&nbsp;</p></span>
     
       <div class="col-lg-6">
          <label for="subjectinfo">Message's  Subject</label>
          <textarea  name="subjectinfo" id="subjectinfo" class="form-control"  col=5 rows=5></textarea>
       </div>
     
       <div class="col-lg-6">
          <label for="message"> Enter Message </label>
          <textarea name="message" id="message" class="form-control" col=5 rows=5></textarea>
       </div>
   
    </div>
     
    <span class="clearfix"><p>&nbsp;</p></span>
    <span class="clearfix"><p>&nbsp;</p></span>
   
     <div class="row">  
       <div class="controls" align="center">
          <label for="submit"></label>
           <input type="submit"  value="SEND" class="btn btn-success">
       </div>
    </div>
 
</div>

</form>

<?php
require VIEW_FOOTER;
