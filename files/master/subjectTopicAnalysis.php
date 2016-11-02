<?php
/*
 * 360 - School Empowerment System.
 * Developer: Ankur Mishra (amishra@ebizneeds.com.au) | www.ebizneeds.com.au
 * Page details here: Page to add new subjects
 * Updates here:
 */
/* assign if selectize needs to be loaded for this page */
$loadSelectize = rtrim(basename($_SERVER['PHP_SELF']), '.php');
/* Selectize load bool ends */

//call the main config file, functions file and header
require_once "../../config/config.php";
require_once DIR_FUNCTIONS;
require_once VIEW_HEADER;
?>

<script type="text/javascript">

 $(document).ready(function() { 
              
    $('#sectionid').selectize({
        hideSelected: 'true'
    });
});

    var rowNum = 0;
    function addRow(frm) {
        rowNum++;
var row = '<div id="rowNum' + rowNum + '" class="row-fluid">'.concat(
'<p><div class="col-lg-3"> <label for="topicname">Topic Name</label>',
'<input type="text" class="form-control" id="topicname[]" name="topicname[]" required="true">',
'</div>  <div class="col-lg-3"> <label for="expectedstartdate">Start Date</label>',
'<input type="date" class="form-control"  id="expectedstartdate[]" name="expectedstartdate[]">',
'</div>  <div class="col-lg-3"> <label for="expectedenddate">End date</label>',
'<input type="date" class="form-control"  id="expectedenddate[]" name="expectedenddate[]">',
' </div><div class="col-lg-2"><label>Click to remove this Row</label> <button type="button" class="btn btn-danger" id="remove" onclick="removeRow(' + rowNum + ');">',
'<span class="glyphicon glyphicon-minus"></span>  </button></div>  </div>');
    
        jQuery('#itemRows').append(row);
        
    }

    function removeRow(rnum) {
        jQuery('#rowNum' + rnum).remove();
    }

  </script>

<form action="<?php echo PROCESS_FORM; ?>" method="post" name="imform" enctype="multipart/form-data">
<div class="container">
<div class="span12">
      
	<?php renderMsg(); ?>
	
    <h1>Subject Topic Association </h1>
	
<div class="row">
   
    <div class="col-lg-3" >
      <label for="classid"> Class </label>
       <select  class="form-control" id="classid" name="classid[]" required="true" >
         <?php echo populateSelect("classname", submitFailFieldValue("classid")); ?>
       </select>
    
    </div>
  
    <div class="col-lg-3">
        <label for="sectionid"> Sections </label> 
        <select multiple="multiple" class="form-control" id="sectionid" name="sectionid[]" required="true" >
         <?php echo populateSelect("sectionname", submitFailFieldValue("sectionid")); ?>
       </select>
    </div>
 
    <div class="col-lg-3">
        <label>Subject</label>
        <select  id="subjectid" class="form-control" name="subjectid" required="true">
         <?php echo populateSelect("subjectname", submitFailFieldValue("subjectid")); ?>
        </select>
    </div>       
	
	<div class="col-lg-3">
        <label>Teacher</label>
        <select  id="employeeid" class="form-control" name="employeeid" required="true">
			<?php echo populateSelect("employee", submitFailFieldValue("employeeid")); ?>
	    </select>
    </div>
</div>

<span class="clearfix"><p>&nbsp;</p></span>
 
				 
<div  class="row" id="itemRows">
    <div class="col-lg-3">
		<label for="topicname">Topic name</label>
		<input type="text"	class="form-control" name="topicname[]" id="topicname[]"/>
    </div>	
			
	<div class="col-lg-3">
		<label for="expectedstartdate">Start Date</label>
		<input type="date"	class="form-control" name="expectedstartdate[]" id="expectedstartdate[]"/>
	</div>
		
	<div class="col-lg-3">
		<label for="expectedenddate">End Date</label>
		<input type="date"	class="form-control" name="expectedenddate[]" id="expectedenddate[]"/>
	</div>
		
	<div class="col-lg-2">
	<label>Click to add more Rows</label>
        <button type="button" class="btn btn-success" id="add" onclick="addRow(this.form);"><span class="glyphicon glyphicon-plus"></span></button> 
	</div>               
</div>
            	    
<span class="clearfix"><p>&nbsp;</p></span>
                               
<div class="row">
    <div class="col-md-8">
        <div class="controls" align="center">
            <button value="reset" type="reset" class="btn">Cancel</button>
            <button type="submit" value="submit" class="btn btn-success">Save</button>
        </div>
    </div>
</div>

<span class="clearfix"><p>&nbsp;</p></span>


    </div> <!---span12 closed--->     
  
    </div><!---container closed--->

</form>
<?php
require VIEW_FOOTER;
?>