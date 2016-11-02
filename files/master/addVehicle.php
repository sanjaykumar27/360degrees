<?php
/*
 * 360 - School Empowerment System.
 * Developer: Ankur Mishra (amishra@ebizneeds.com.au) | www.ebizneeds.com.au
 * Page details here: Page to add new subjects
 * Updates here:
 */

/*bread crumb page variables ends */
//call the main config file, functions file and header
require_once "../../config/config.php";
require_once DIR_FUNCTIONS;
require_once VIEW_HEADER;
?>

<form action="<?php echo PROCESS_FORM; ?>" method="post"  name="imForm">

<div class="container">
    <div class="span10">
    <?php renderMsg(); ?>
    <h1>Add Vehicle</h1>
    <span class="clearfix"><p>&nbsp;</p></span>            

        <div class="col-lg-6">
            <label for="buscode">Vehicle Code</label>
             <input type="text" id="buscode" class="form-control" name="buscode" required="true"
              value ="<?php  echo submitFailFieldValue("buscode");  ?>">
        </div>
    
        <div class="col-lg-6">
            <label for="busnumber">Vehicle Number</label>
             <input type="text" id="busnumber" class="form-control" name="busnumber" required="true"
              value ="<?php  echo submitFailFieldValue("busnumber");  ?>">
        </div>

    <span class="clearfix"><p>&nbsp;</p></span>
	    <div class="col-lg-6">
            <label for="capacity">Seating Capacity</label>
             <input type="text" id="capacity" class="form-control" name="capacity" required="true"
              value ="<?php  echo submitFailFieldValue("capacity");  ?>">
        </div>

	    <div class="col-lg-6">
	        <label for="status">Status</label>
            <select name="status" class="form-control">
                <option value="">-Select One-</option>
                <option value="0"> Inactive </option>
                <option value="1"> Active </option>
            </select>
	    </div>
	    
	<span class="clearfix"><p>&nbsp;</p></span>    
	    
	    <div class="col-lg-6">
            <label for="description">Additional Information</label>
             <textarea id="description" class="form-control" name="description" 
              value ="<?php  echo submitFailFieldValue("description");  ?>" 
              placeholder=" eg: busregistration or insurance number etc"></textarea>
        </div>
	
	<span class="clearfix"><p>&nbsp;</p></span>
	<span class="clearfix"><p>&nbsp;</p></span>
	
	    <div class="controls" align="center">
            <input id="clearDiv" type="button"  value="Cancel" class="btn">
            <input type="submit" id="save" name="save" value="SAVE" class="btn btn-success">
        </div>
        
    </div><!--------span10 div closed--------->
</div><!--------container div closed--------->
</form>

<?php
require VIEW_FOOTER;
