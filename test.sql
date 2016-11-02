<?php
  /*
   * 360 - School Empowerment System.
   * Developer: Ankur Mishra (amishra@ebizneeds.com.au) | www.ebizneeds.com.au 
   * Page details here: Master for fees head and related processing 
   * Updates here: 
   */

    $loadSelectize = rtrim(basename($_SERVER['PHP_SELF']), '.php');  

    /* Selectize load bool ends */
    
  require_once "../../config/config.php";
  require_once DIR_FUNCTIONS;
  require_once VIEW_HEADER;
  $response = actionDelete();
?>

<script type="text/javascript">

    var rowNum = 0;
    function addRow() {
        rowNum++;
        var row = '<div class="clearfix">&nbsp;</div><div id="rowNum' + rowNum + '" >'.concat(
                '<div class="col-lg-3"> <label for="classid' + rowNum + '">Select Classes</label><br>',
                '<select id="classid' + rowNum + '" multiple class="demo-default" required="true"></div>',
                '<div class="col-lg-3"> <label for="amount"> Amount </label> ',
                '<input type="text" name="amount[' + rowNum + ']" id="amount' + rowNum + '" class="form-control" required="true"> <div class="hidden" id="divamount' + rowNum + '">',
                '<code>Admission amount is required.</code></div> </div><div class="col-lg-3"> <label for="duedate' + rowNum + '"> Due Date(s) </label>',
                '<input type="text" name="duedate[' + rowNum + ']" id="duedate' + rowNum + '">',
                '<div class="hidden" id="divduedate' + rowNum + '"><code>Due date of payment is required.</code></div> </div>',
                '<div class="col-lg-3"> <label>Remove</label><br><button type="button" class="btn btn-danger" id="remove" onclick="removeRow(' + rowNum + ');">',
                '<span class="glyphicon glyphicon-minus"></span> </button> </div> </div>');

        jQuery('#itemRows').append(row);

        addSelectize('#classid' + rowNum);
        var fld = '#amount' + rowNum + ', '.concat('#duedate' + rowNum);
        addInputize(fld);
    }

    function removeRow(rnum) {
        jQuery('#rowNum' + rnum).remove();
    }

    function displayErrorJS(err) {
        var errMsg = [];
        errMsg[0] = "You are about to delete this particular fee component for class <?php echo $response ?> ?  click Yes to confirm ...!";
        errMsg[1] = "Do you want to delete this particular fee component ?  click Yes to confirm ...!";

        var strModal = '<div id="jsErrorAlert" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">'.concat(
                '<div class="modal-dialog"><div class="modal-content"><div class="modal-header"></strong>Attention..!</strong></div>',
                '<div class="modal-body"><div class="alert alert-danger alert-dismissible fade in" role="alert">',
                errMsg[err] + '</div></div>',
                '<div class="modal-footer"><button type="button" class="btn btn-success" data-dismiss="modal" onClick="Javascript: confirmDelete();">Yes</button><button type="button" class="btn btn-danger" data-dismiss="modal">Close</button></div></div></div></div>');

        $(strModal).appendTo('body');
        $('#jsErrorAlert').modal('toggle');
    }


    function getParameterByName(name) {
        name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
        var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
                results = regex.exec(location.search);
        return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
    }

    function confirmDelete() {
        var id = getParameterByName('delid');
        var classid = getParameterByName('classid');
        var feestructureid = getParameterByName('feestructureid');

        var url = "feeMaster.php?c=" + id + "&classid=" + classid + "&feestructureid=" + feestructureid;
        window.location.replace(url);

    }

    function showHideDiv(divName) {
        $('#' + divName).toggle();

    }
</script>


<?php
  if (!empty($response)) {
      echo('<script type="text/javascript"> displayErrorJS(0);</script>');
  } elseif (!empty($_GET['delid'])) {
      echo('<script type="text/javascript"> displayErrorJS(1);</script>');
  }
?>
<div class="container">
    <form action="<?php echo PROCESS_FORM ?>" method="post" name="imform">
        <?php renderMsg(); ?>
        <div class="btn-group btn-group-justified">
            <a href="feeMaster.php" class="btn btn-primary btn-lg">Create Fee Structure</a>
            <a href="feeRule.php" class="btn btn-success btn-lg">Create Fee Rules</a>
            <a href="otherFees.php" class="btn btn-info btn-lg">Create Other Fees</a>
        </div> 
        <h3>Create Fee Structure </h3><span class="clearfix">&nbsp;</span>
        <div class="clearfix"></div>
        <div class="col-lg-4">
            <label for="feecomponents">Component Name</label><div id="forFeeComponentTour"></div>
            <select  class="form-control" id="feecomponents" name="feecomponents" required="true" >
                <?php
                  if (isset($_GET['feecomponentid'])) {
                      echo populateSelect("feecomponent", $_GET['feecomponentid']);
                  } else {
                      echo populateSelect("feecomponent", submitFailFieldValue("feecomponents"));
                  }
                ?>
            </select>	   
            <div class="hidden" id="divfeecomponents"><code>Component name, in text, is required.</code></div>
        </div>

        <div class="col-lg-6">
            <label for="frequency"> Frequency</label><div id="forFeeFrequencyTour"></div>
            <select name="frequency" id="frequency" class="form-control" required="true">
                <option value="">- Select One-</option>
                <option value="1">Once Only(per session)</option>
                <option value="2">Periodic </option> 
                <option value="3">Once only(At time of admission) </option> 
            </select>
            <small class="alert-danger">Payment Frequency is required.</small>
        </div>

        <div class="col-lg-1">  
            <label for="isrefundable">Refundable</label><div id="forFeeRefundableTour"></div>
            <input type="checkbox" name="isrefundable" id="isrefundable"  value="true">
        </div>

        <span class="clearfix">&nbsp;<br></span>
        <script>
            $('#classid').selectize({
                maxItems: 10
            });

            $('#amount,#duedate').selectize({
                persist: false,
                createOnBlur: true,
                create: true
            });

        </script>
        <?php
          if (isset($_GET['classid']) && isset($_GET['feecomponentid'])) {
              ShowStructure();
          } else {
              ?>  
              <br><div id="itemRows" >
                  <div class="demo">
                      <div class="control-group col-lg-3">
                          <label for="classid">Select Class:</label>
                          <select id="classid" name="classid[0]"  required="true">
                              <?php echo populateSelect("classname", submitFailFieldValue("classid")); ?>
                          </select>
                          <small>Select all the classes with same structure.</small>
                      </div>
                      <script>
                          $('#classid').selectize({
                              maxItems: 10
                          });
                      </script>
                  </div>
                  <div class="demo">
                      <div class="control-group col-lg-3">
                          <label for="Amount">Amount:</label>
                          <input type="text" id="amount" name="amount[0]"  required="true"
                                 value="<?php echo submitFailFieldValue("amount[0]"); ?>">
                      <small>Amount must have matching date in next column. </small>
                      <div class="hidden" id="divamount"><code>Amount per date in Rupees is required.</code></div>
                      </div>
                      <script>
                          $('#amount').selectize({
                              persist: false,
                              createOnBlur: true,
                              create: true
                          });
                      </script>
                  </div>
                  <div class="demo">
                      <div class="control-group col-lg-4">
                          <label for="Due Date">Due Date(s)</label>
                          <input type="text" id="duedate"  name="duedate[0]" required="true"
                                 value="<?php echo submitFailFieldValue("duedate[0]"); ?>">
                     <small>Enter the date in  when the assigned amount is due.  </small>
                    <div class="hidden" id="divduedate"><code>Due date of payment is required.</code></div>
                      </div>
                      <script>
                          $('#duedate').selectize({
                              persist: false,
                              createOnBlur: true,
                              create: true
                          });
                      </script>
                  </div>
                  <div class="col-lg-2">
                      <label>Add Row</label><br>
                      <button type="button" class="btn btn-success" id="add" onclick="addRow();" title="Click here to add more rows!">
                          <span class="glyphicon glyphicon-plus"></span>
                      </button> 
                  </div>
              </div>

          <?php } ?>   

        <span class="clearfix"><p>&nbsp;</p></span><br>
        <div class="controls" align="center">
            <input type="button" id="showstructure" name="showstructure" value="Show Structure" class="btn btn-primary">
            <button id="cancel" type="reset" class="btn"> Cancel</button>
            <input type="submit" id="save" name="save" value="SAVE" class="btn btn-success">
        </div>
        <span class="clearfix"> <p>&nbsp;</p> </span> 
    </form>
</div>


<?php
  require_once VIEW_FOOTER;

  function classMaster() {
      $instsessassocid = $_SESSION['instsessassocid'];
      if (!isset($_GET['page'])) {
          $startpage = 0;
      } else {
          $startpage = ($_GET['page'] - 1) * 10;
      }

      global $countRows;

      $countRows = mysqli_num_rows(dbSelect("SELECT `classid` FROM `tblclassmaster` WHERE `instsessassocid` = $instsessassocid "));

      $sql = "SELECT `classid`,`classname` FROM `tblclassmaster` WHERE `instsessassocid` = $instsessassocid LIMIT $startpage ," . ROW_PER_PAGE;
      //echoThis($sql);
      $result = dbSelect($sql);
      if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result))
              $classArray[$row['classname']] = $row['classid'];
      } else
          $classArray = 0;


      return $classArray;
  }

  function feeMasterShowSelect($classid) {
      global $totalAmount;
      $totalAmount = '';
      $sql = "SELECT  t1.feestructureid, t1.feecomponentid, t1.classid, SUM(t2.amount) As totalAmount,count(t4.feecomponent) AS installments, t2.duedate,t3.classname,t4.feecomponent
                    FROM `tblfeestructure` AS t1,
                    `tblfeestructuredetails` AS t2,
                    `tblclassmaster` AS t3, 
                    `tblfeecomponent` AS t4
			 
                    WHERE t1.classid = $classid
                    AND t2.feestructureid = t1.feestructureid
                    AND t1.classid = t3.classid 
                    AND t1.feecomponentid =  t4.feecomponentid
                    AND t1.status = 1
                    AND t1.deleted = 0
                    GROUP BY t4.feecomponent";

      $result = dbSelect($sql);
      if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
              $feedetail[] = $row;
              $totalAmount += $row['totalAmount'];
          }
      } else {
          $feedetail = 0;
          $totalAmount = "0.00";
      }

      return $feedetail;
  }

  function getCurrentSessionId() {
      $intsessassocid = $_SESSION['instsessassocid'];
      $sql = "SELECT t2.academicsessionid  FROM `tblinstsessassoc` AS t1, `tblacademicsession` as t2
            WHERE t1.instsessassocid = $intsessassocid AND t2.deleted!=1 AND t2.status=1";


      $result = dbSelect($sql);
      $row = mysqli_fetch_assoc($result);
      return $row['academicsessionid'];
  }

  function ShowStructure() {
      $classid = cleanVar($_GET['classid']);
      $feecomponentid = cleanVar($_GET['feecomponentid']);

      $sql = "SELECT t3.feecomponentid, t1.feestructureid, t2.amount, t2.duedate, t1.classid, t4.classname,
        t2.frequency, t2.feestructuredetailsid
            FROM `tblfeestructure` As t1,
            `tblfeestructuredetails` AS t2,
            `tblfeecomponent` AS t3,
            `tblclassmaster` AS t4

            WHERE t1.classid = $classid
            AND t1.classid = t4.classid
            AND t1.feecomponentid = $feecomponentid
            AND t1.feestructureid  = t2.feestructureid
            AND t3.feecomponentid = t1.feecomponentid
            AND t1.status = '1'
            AND t1.deleted = '0'
            ";

      $result = dbSelect($sql);
      $i = 0;


      while ($row = mysqli_fetch_assoc($result)) {
          $strHTML = "
           <input type=\"hidden\"  name=\"edid\"   value =\" $row[feestructureid]\">
               <input type=\"hidden\" name=\"mode\" value=\" $_GET[mode]\"> 
            <input type=\"hidden\"  name=\"feestructureid\" id=\"feestructureid\"  value =\" $row[feestructureid]\">
                
            <div class=\"row\">
                <div class=\"col-lg-3\">
                    <label for=\"classname\">Class</label>
                    <input type=\"text\" class=\"form-control\" name=\"classname[]\" id=\"classname[$i]\" required value =\" $row[classname]\">
                     <input type=\"hidden\"  name=\"classid[]\" id=\"classid[$i]\"  value =\" $row[classid]\">
                 </div>
                 
                 
                 
                <div class=\"col-lg-3\">
                    <label for=\"amount\"> Amount </label>
                    <input type=\"text\" class=\"form-control\" name=\"amount[]\" id=\"amount[$i]\" required value =\"$row[amount]\">
                </div>
                
                

                <div class=\"col-lg-3\">
                    <label for=\"duedate\"> Due Date </label>
                    <input type=\"date\" class=\"form-control\" name=\"duedate[]\" id=\"duedate[$i]\" required=\"true\" value =\"$row[duedate]\">
                </div>
            
            <input type=\"hidden\" class=\"form-control\" name=\"feestructuredetailsid[]\" id=\"feestructuredetailsid[$i]\" required value =\"$row[feestructuredetailsid]\">
            
            </div>";
          $i++;
          echo $strHTML;
      }
  }

  function actionDelete() {
      if (isset($_GET['delid'])) {
          $feecomponentid = cleanVar($_GET['delid']);
          $sql = "SELECT t2.classname
				FROM `tblfeestructure` AS t1,
				`tblclassmaster`  AS t2
			
				WHERE t1.feecomponentid = $feecomponentid
				AND t1.classid = t2.classid
				";

          $result = dbSelect($sql);
          $row = mysqli_fetch_assoc($result);
          $classname = $row['classname'];
          return $classname;
      } elseif (isset($_GET['c'])) {

          $feecomponentid = cleanVar($_GET['c']);
          $classid = cleanVar($_GET['classid']);
          $feestructureid = cleanVar($_GET['feestructureid']);
          $sql = "UPDATE `tblfeestructure`,`tblfeestructuredetails`
                        
                        SET `tblfeestructure`.`status`= 0 , 
                        `tblfeestructure`.`deleted` = 1 ,
                        `tblfeestructuredetails`.`status`= 0 , 
                        `tblfeestructuredetails`.`deleted` = 1 
                        
                        WHERE `tblfeestructure`.feecomponentid= $feecomponentid
                        AND `tblfeestructure`.classid = $classid
                        AND `tblfeestructure`.feestructureid = $feestructureid
                        AND `tblfeestructure`.feestructureid =  `tblfeestructuredetails`.feestructureid 
                        ";

          $result = dbUpdate($sql);
      }
  }

  function feerulemode($modename) {
      $sql = "SELECT t1.mastercollectionid
            FROM `tblmastercollection` AS t1,
            `tblmastercollectiontype` AS t2
        
            WHERE t1.mastercollectiontypeid = t2.mastercollectiontypeid
            AND t2.mastercollectiontype = 'Feerulemode'
            AND t1.collectionname = '$modename'";
      $result = dbSelect($sql);
      $row = mysqli_fetch_assoc($result);


      return $row['mastercollectionid'];
  }

  function feeruletype($type) {
      $sql = "SELECT t1.mastercollectionid
            FROM `tblmastercollection` AS t1,
            `tblmastercollectiontype` AS t2
        
            WHERE t1.mastercollectiontypeid = t2.mastercollectiontypeid
            AND t2.mastercollectiontype = 'Feeruletype'
            AND t1.collectionname = '$type'";

      $result = dbSelect($sql);
      $row = mysqli_fetch_assoc($result);

      return $row['mastercollectionid'];
  }
?>
	