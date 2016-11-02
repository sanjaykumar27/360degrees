<?php

       /**
        * 360 - School Empowerment System.
        * Developer: Ankur Mishra (amishra@ebizneeds.com.au) | www.ebizneeds.com.au
        * Page details here: Page to add new students
        * Updates here:
        **/
    //call the main config file, functions file and header
    require_once "../../config/config.php";
    require_once DIR_FUNCTIONS;
    require_once VIEW_HEADER;

    if (!isset($_REQUEST['sid']) && $_REQUEST['sid']<=0) {
        $class="class='disabled disabledTab' data-toggle='tab' ";
    } else {
        $class="";
    }

    
    if (isset($_GET['mode']) && $_GET['mode']=='delete' && is_numeric($_GET['did'])) {
        $sqlDeleteString="UPDATE tbluserdocument SET `status` = 0  WHERE documentid='".cleanVar($_GET['did'])."'";
        $resultDelete = dbUpdate($sqlDeleteString);
        if (in_array('1', $resultDelete)) {
            echo "<script>window.location='studentDocument.php?s=51&sid=".cleanVar($_GET['sid'])."&mode=edit'</script>";
        }
    }
    
    if (isset($_GET['sid'])) {
        $studentDocumentArray = getStudentDocumentDetails();
    }
    
    $sno=1;
?>
    <script type="text/javascript">
    $(document).ready(function ($) 
    {
        // for displaying modal for confirming before updating  record...//

        $('#save,#next').click(function() 
        {
            $('#myModal').modal('show');
                return false;

        });

        $('#submitForm').click(function()
        {
            $('#imform').submit();
            $('#myModal').modal('hide');
        });
        
        
    });        
    
    var rowNum = 0;
                            
    function addRow(frm) 
    {
        rowNum++;
      
        var row = '<span class="clearfix">&nbsp;<br></span><div id="rowNum' + rowNum + '"><span class="clearfix">&nbsp;</span><div class="col-md-3">'.concat(
'<input type="file"  value =""  name = "document[]" id = "document" class="form-control" required="true">',
'</div><div class="col-md-3"> <select class="form-control" name="documenttype[]" id="doctype" tabindex="3"  required="true" > \n',
'<?php echo PopulateSelect("document");?>','</select>\n\
</div><div class="col-md-1"> <button type="button" class="btn btn-danger" id="remove" onclick="removeRow(' + rowNum + ');">',
'<span class="glyphicon glyphicon-minus"></span></button></div></div>');
      
        jQuery('#itemRows').append(row);
    }

    function removeRow(rnum) { jQuery('#rowNum' + rnum).remove(); }
    
    </script>
    <form action="<?php echo PROCESS_FORM; ?>" method="post" id="imform"  name="imform" enctype="multipart/form-data">
    <div class="container"> 	
       
            <!-- Modal -->
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Update Record&hellip; !</h4>
                        </div>
                        <div class="modal-body">
                            <p class="alert-danger"> Do You Want To Update Record as  action once done , couldn't be reverted back.</p> 
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="submitForm">Save changes</button>
                      </div>
                    </div>
                </div>
            </div>
            <ul class="nav nav-tabs" role="tablist">
                <li><a href="studentPersonal.php<?php echo isEditable(); ?>">Personal</a></li>
                <li><a href="studentParent.php<?php echo isEditable(); ?>">Parents</a></li>
                <li><a href="studentMedical.php<?php echo isEditable(); ?>">Medical</a></li>
                <li><a href="studentFees.php<?php echo isEditable(); ?>">Fees Rule</a></li>
                <li class="active"><a href="studentDocument.php<?php echo isEditable(); ?>">Documents</a></li>
                <li><a href="studentFeeDetails.php<?php echo isEditable(); ?>">Fee Details</a></li>
            </ul>
            
        </div>
    
        <span class="clearfix">&nbsp;<br></span>
        
       
        <div class="container">
             <?php  renderMsg(); //echoThis($errorArray); die;?>
            <?php if ($studentDocumentArray['rowcount'] > 0) {
    ?>
             <div class="alert alert-success">
                <span class="text-info">The document details attached with your profile is as follows :<br/><br/>
                    <ul class="list-group">
                        <?php foreach ($studentDocumentArray['records'] as $documentKey) {
        ?>
                        <li class="list-group-item"> 
                        <span class="glyphicon glyphicon-chevron-right">  </span>&nbsp;&nbsp;
                        <a href="studentDocument.php?sid=<?=cleanVar($_GET['sid'])?>&mode=delete&did=<?=$documentKey['documentid']?>">
                            <span class="glyphicon glyphicon-trash" style="color:red">  </span></a>&nbsp;&nbsp;
                        
                        <a href="#"> <?php echo $documentKey['collectionname']; ?> &nbsp;-&nbsp;  </a>
                        
                        </li>
                        <?php $sno++;
    } ?>
                    </ul>
                </span> 
             </div>    
                    <span class="clearfix">&nbsp;<br></span>
        
            <?php 
} else {
    ?>
                <div class="alert alert-warning">
                    <span class="text-info"> 
                        There is no document submitted with this student.
                        Please upload the required documents mentioned in the admission form.
                    </span> 
                </div>
        </div>   
            <?php 
} ?>
        
            <div class="container">
                
                    
                        <input type="hidden" name="sid" value="<?=$_GET['sid']?>" >
                        <?php 
                        if (isset($_GET['sid']) && !empty($_GET['sid'])) {
                            ?>
                            <input type="hidden" name="mode" value="edit" >

                         <?php 
                        } else {
                            ?>
                             <input type="hidden" name="mode" value="add" >   
                          <?php 
                        }
                         ?> 
                            
                        <div class="row" id="itemRows">
                            
                            <div class="col-lg-3">
                                <label for="Select Document"> Document File </label>
                                <input type="file"  value =""  name = "document[]" id = "document" class="form-control" required="true">
                            </div>

                            <div class="col-lg-3">
                                <label for="Document Type"> Document Type:</label>
                                <select class="form-control"   name="documenttype[]" id="doctype" tabindex="3"  required="true" >
                                <?php   echo PopulateSelect("document", submitFailFieldValue("document"));?>
                                </select> 
                            </div>
                            <div class="col-md-1">
                                <label for=""> Add</label><br>
                                <button type="button" class="btn btn-success" id="add" onclick="addRow(this.form);">
                                <span class="glyphicon glyphicon-plus"></span></button> 
                            </div>
                             
                            <div class="controls" >
                                <label for="Submit"></label> <br/> 
                                <input type="submit" id="save"  name="save" tabindex="35" value="SAVE" class="btn btn-success">                         
                            </div>
                        </div>
                </div>
                 </div>
    
           </form>
 <?php

require_once VIEW_FOOTER ;


function getStudentDocumentDetails()
{
    if (isset($_GET['sid']) && is_numeric($_GET['sid'])) {
        $studentid=  cleanVar($_GET['sid']);
        $sqlString="SELECT documentid,documentname,collectionname
                        FROM tbluserdocument AS T1 
                        LEFT JOIN tbluserdetailsassoc AS T2 ON T1.userid = T2.userid 
                        LEFT JOIN tblmastercollection as T3 ON T1.documenttype = T3.mastercollectionid
                        WHERE T1.instsessassocid = '".$_SESSION['instsessassocid']."'
                        AND T2.studentid = '$studentid' ";
        
        $resultSql=  dbSelect($sqlString);
        if (mysqli_num_rows($resultSql)>0) {
            while ($dataRow=mysqli_fetch_assoc($resultSql)) {
                $dataArray['records'][] = $dataRow;
            }
            $dataArray['rowcount'] = mysqli_num_rows($resultSql);
            return $dataArray;
        } else {
            return 0;
        }
    }
    return 0;
}

// check is edit mode is enabled, if so return true.
// e = edit

function isEditable()
{
    $str=  substr($_SERVER['REQUEST_URI'], strpos($_SERVER['REQUEST_URI'], '?'));
    
    if (isset($_GET['sid']) && is_numeric($_GET['sid']) && $_GET['sid']>0) {
        return $str;
    } else {
        return false;
    }
}
?>               
                
                
                