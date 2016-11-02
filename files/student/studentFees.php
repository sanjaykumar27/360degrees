<?php
  /*
   * 360 - School Empowerment System.
   * Developer: Sanjay Chaurasia (schaurasia@ebizneeds.com) | www.ebizneeds.com.au
   * Page details here: Page to add new students
   * Updates here:
   */

  //call the main config file, functions file and header
  require_once "../../config/config.php";
  require_once DIR_FUNCTIONS;
  require_once VIEW_HEADER;

  /* Tab Class Defined here  For disabling and enabling tabs */
 $class = "";
  if (!isset($_REQUEST['sid']) && $_REQUEST['sid'] <= 0) {
      $class = "class='disabled disabledTab' data-toggle='tab' ";
  } 

  //calling the fee rule and students details function in an arrary,
  // to be used later. 

  $feeRuleArray = getFeeRules();
  $studentFeeDetail = getStudentFeeDetails();
  //initital feeruleallid, $studentFeeArray with a 0
   
  $feeRuleAllId = $studentFeeArray = array();
  $mode = 'add';

  if (!empty($studentFeeDetail) && $studentFeeDetail > 0) {
     
      foreach ($studentFeeDetail as $studentFeeKey => $studentFeeValue) {
          $studentFeeArray = $studentFeeValue['feeruleid'];
      }
       $mode = 'edit';
  }

  if (!empty($feeRuleArray) && $feeRuleArray > 0) {
      
      foreach ($feeRuleArray as $feeKey => $feeRuleId) {
          $feeRuleAllId = $feeRuleId['feeruleid'];
      }
  }

?>
<script type="text/javascript">

    $(document).ready(function ($)
    {
        // for displaying modal for confirming before updating  record...//
        $('#save,#next').click(function ()
        {
            $('#myModal').modal('show');
            return false;
        });
        $('#submitForm').click(function ()
        {
            $('#imform').submit();
            $('#myModal').modal('hide');
        });
    });

    function showInstallments(objectID)
    {
        $idArray = objectID.split('-');
        if (document.getElementById(objectID).checked)
            document.getElementById('feeinst-' + $idArray[1]).style.visibility = 'visible';
        else
            document.getElementById('feeinst-' + $idArray[1]).style.visibility = 'hidden';
    }
</script>
<div class="container"> 	
    <ul class="nav nav-tabs" role="tablist">
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
                <li class="active"><a href="studentFees.php<?php echo isEditable(); ?>">Fees Rule</a></li>
                <li ><a href="studentDocument.php<?php echo isEditable(); ?>">Documents</a></li>
                <li><a href="studentFeeDetails.php<?php echo isEditable(); ?>">Fee Details</a></li>
            </ul>
        </div>

        <span class="clearfix">&nbsp;<br></span>	
        <form action="<?php echo PROCESS_FORM; ?>" method="post" id="imform" >

            <input type="hidden" name="sid"  id="sid"  value="<?php echo cleanVar($_GET['sid']); ?>">
            <input type="hidden" name="mode" id="mode" value="<?= $mode ?>">

            <div class="container">
                <div class="span11">
                    <div class="row">
                        <label for="feeruleid"> Fee Rule </label><br>
<?php echo populateFeeRuleCheckBox("feerule", "feerule[]", $studentFeeArray); ?>
                    </div> <!--Class Row Closed-->
                </div><!--span 11 Closed-->
            </div> <!--//Container Closed-->

            <span class="clearfix"><p>&nbsp;</p></span>
            <div class="row">
                <div class="controls" align="center">
                    <input id="clearDiv" type="button" value="Cancel" class="btn">
                    <!-- Button trigger modal -->
                    <input type="submit" id="submit1" name="submit" value="SAVE" class="btn btn-success">
                    <input type="submit" id="submit" name="submit" value="NEXT" class="btn btn-success">
                </div>
            </div>
            <span class="clearfix"><p>&nbsp;</p></span> 

        </form>
    </ul>
</div>


<?php
  require_once VIEW_FOOTER;

  function getStudentFeeDetails() {
      if (isset($_GET['sid']) && is_numeric($_GET['sid'])) {
          $sqlSelect = "SELECT studfeeruleassocid,feeruleid 
                   FROM tblstudfeeruleassoc
                    WHERE studentid=" . cleanVar($_GET['sid']) . "
                    AND associationstatus=1 
                    ORDER BY feeruleid";

          $result = dbSelect($sqlSelect);
          if (mysqli_num_rows($result) > 0) {
              while ($row = mysqli_fetch_assoc($result)) {
                  $studentFeeRules[] = $row;
              }
          } else {
              $studentFeeRules = 0;
          }
      }
      return $studentFeeRules;
  }

  function getFeeRules() {
      $sqlFeeRule = " SELECT feeruleid,feerulename 
                FROM tblfeerule  
                WHERE feerulestatus = 1  
                AND deleted!=1 
                ORDER by feeruleid ";

      $sqlResult = dbSelect($sqlFeeRule);
      if (mysqli_num_rows($sqlResult) > 0) {
          while ($sqlRow = mysqli_fetch_assoc($sqlResult)) {
              $feeRuleArray[] = $sqlRow;
          }
      } else {
          $feeRuleArray = 0;
      }
      return $feeRuleArray;
  }

  // check is edit mode is enabled, if so return true.
  // e = edit
  function isEditable() {
      $str = substr($_SERVER['REQUEST_URI'], strpos($_SERVER['REQUEST_URI'], '?'));
      if (isset($_GET['sid']) && is_numeric($_GET['sid']) && $_GET['sid'] > 0) {
          return $str;
      } else {
          return false;
      }
  }
?>