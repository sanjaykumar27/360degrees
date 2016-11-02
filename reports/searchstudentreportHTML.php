<?php
    /*
     * 360 - School Empowerment System.
     * Developer: Prateek Mathur (pmathur@ezizneeds.com.com) | www.ebizneeds.com.au
     * Page details here: HTML page for all reports relted pages
     * Updates here:
     */
    //call the main config file, functions file and header
    require_once "../config/config.php";
    require_once DIR_FUNCTIONS;
    require_once VIEW_HEADER;
   
?>
<script type="text/javascript">
    // This  code is used  for sorting the data inside the table using TSORT API...//
    var TSort_Data = new Array ('displaytable', 'h', 'h', 'h', 'h','h');
    tsRegister();
    function PrintElem(elem)
    {
        Popup($(elem).html());
    }

function Popup(data) 
{
    var mywindow = window.open('', 'Fee Due Report', 'height=400,width=600');
    mywindow.document.write('<html><head><title>Fee Due Report</title>');

    mywindow.document.write('</head><body >');
    mywindow.document.write(data);
    mywindow.document.write('</body></html>');

    mywindow.document.close(); // necessary for IE >= 10
    mywindow.focus(); // necessary for IE >= 10

    mywindow.print();
    mywindow.close();

    return true;
}

</script>
<?php 
 getSessionStartEndDate();
?>

<div class="container">
    <?php renderMsg();?>
    <div class="span10">
        <form action="" method="GET" id="imform" name="myForm"  > 
           <div class="row">
           <div class="col-lg-4 col-md-4">
                <div class="input-group">
                    <span class="input-group-addon">Scholar No</span> 
                        <input type="text" class="form-control" name="scholarnumber" id="scholarnumber" tabindex="1"
                               value ="<?php echo submitFailFieldValue("scholarnumber"); ?>" >
                    <span class="input-group-btn">
                        <button class="btn btn-default"  name="search" id="search">
                    <span class="glyphicon glyphicon-search" name="search" value='Search' > </span></a> 
                   </span>   
                 </div><!-- /input-group -->
            </div><!-- /.col-lg-4 col-md-4 -->

            <div class="col-lg-4 col-md-4">
                <div class="input-group">
                    <span class="input-group-addon">Student First Name</span> 
                        <input type="text" class="form-control" name="studentname" id="studentname" tabindex="2" 
                        value ="<?php echo submitFailFieldValue("studentname"); ?>">

                 </div><!-- /input-group -->
                
            </div><!-- /.col-lg-4 col-md-4 -->
            
            <div class="col-lg-4 col-md-4">
                <div class="input-group">
                    <span class="input-group-addon">Parent First Name</span> 
                        <input type="text" class="form-control" name="parentname" id="parentname" tabindex="3"
                        value ="<?php echo submitFailFieldValue("parentname"); ?>">

                 </div><!-- /input-group -->
            </div><!-- /.col-lg-4 col-md-4 -->
        </div>     
        
        <span class='clearfix'>&nbsp;<br></span>
        
        <div class="row">
            <div class="col-lg-4 col-md-4">
                <div class="input-group">
                    <span class="input-group-addon">Class</span>
                        <select name="classid" id="classid"  class="form-control" tabindex="4"  >
                            <?php echo populateSelect("classname", submitFailFieldValue("class")); ?>
                        </select>
                </div>
            </div> 

            <div class="col-lg-4 col-md-4">
                <div class="input-group">
                    <span class="input-group-addon">Section</span>
                        <select name="sectionid" id="sectionid"  class="form-control" tabindex="5">
                            <?php echo populateSelect("sectionname", submitFailFieldValue("section")); ?>
                        </select>
                </div>
            </div>
            
 
             <div class="col-lg-4 col-md-4">
                <div class="input-group">
                    <span class="input-group-addon">Payment Mode</span>
                        <select name="paymentmode" id="paymentmode"  class="form-control" tabindex="6">
                            <?php echo populateSelect("feecollectionmode", submitFailFieldValue("paymentmode")); ?>
                        </select>
                </div>
            </div>
        </div>
        <span class="clearfix">&nbsp;</span>
        
            <div class="row">  
                <div class="col-lg-6 col-md-6">
                    <div class="input-group">
                        <span class="input-group-addon">Date From</span>
                        <input type="date" name="monthstart" id="monthstart" class="form-control" tabindex="7" 
                               max="<?php echo $_SESSION['sessionenddate']?>" min="<?php echo $_SESSION['sessionstartdate']  ?>" >
                    </div>
                </div>  
                <div class="col-lg-6 col-md-6">
                    <div class="input-group">
                        <span class="input-group-addon">Date To</span>
                        <input type="date" name="monthend" id="monthend" class="form-control" tabindex="8" 
                               max="<?php echo $_SESSION['sessionenddate']?>" min="<?php echo $_SESSION['sessionstartdate']  ?>">
                    </div>
                </div> 
            </div> 
        <span class='clearfix'>&nbsp;<br></span>
        
        <div class="row"> 
            <div class="controls" align="right">
                <div class='col-lg-6 col-md-6'>
                   <button type='reset' value="Reset" class="btn " tabindex="6">Cancel</button>
                   <button name='search' value="search" class="btn btn-success" tabindex="7">Search</button>
                </div>
            </div>
        </div>
        </form>
    </div>
</div>

   <?php 
     function getSessionStartEndDate()
     {
         $sql = "SELECT  sessionstartdate, sessionenddate 
        
            FROM tblacademicsession as t1, 
            tblinstsessassoc as t2 
            
            WHERE t1.academicsessionid = t2.academicsessionid 
            AND t2.instsessassocid = $_SESSION[instsessassocid]";

         $result = dbSelect($sql);
         $row = mysqli_fetch_assoc($result);

         if (!isset($_SESSION['sessionstartdate']) && empty($_SESSION['sessionstartdate'])) {
             $_SESSION['sessionstartdate'] = $row['sessionstartdate'];
             $_SESSION['sessionenddate'] = $row['sessionenddate'];
         }
     }
     ?>