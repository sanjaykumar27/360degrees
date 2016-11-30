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
    $(document).ready(function ()
    {
        // Instance the tour
        $("#tourhelp").click(function ()
        {
            var tour = new Tour({
                steps: [
                    {
                        element: "#forFeeComponentTour",
                        title: " Select Fee Component",
                        content: "Select the fee component from given dropdown",
                        placement: "right"
                    },
                    {
                        element: "#forSessionTour",
                        title: "Select Academic Session",
                        content: "Select academic session  ",
                        placement: "right"
                    },
                    {
                        element: "#forFeeFrequencyTour",
                        title: "Select Fee charging frequency ",
                        content: "Select frequency for the selected fee component.",
                        placement: "right"
                    },
                    {
                        element: "#forFeeRefundableTour",
                        title: "Fee Rule Refundable Status",
                        content: "Check only if fee component is refundable in any case",
                        placement: "left"
                    },
                    {
                        element: "#forClassesTour",
                        title: "Select Classes",
                        content: "Select classes which tends to have same fee structure ",
                        placement: "right"
                    },
                    {
                        element: "#forFeeAmountTour",
                        title: "Enter Amount(s)",
                        content: "Enter amount for the given fee component. Enteries for both amount and due dates must match with eachother  ",
                        placement: "right"
                    },
                    {
                        element: "#forDuedateTour",
                        title: "Enter Due Date(s).",
                        content: "Enter due dates for the given fee component. Enteries for both amount and due dates must match with eachother  ",
                        placement: "right"
                    }

                ]
            });

            tour.init();
            tour.restart();
        });
    });

    $(function () {

<?php if (isset($_GET['classid']) && !(isset($_GET['mode']) === 'edit')) { ?>

              $("#addFeesStructure").show();
              $("#showStructure").hide();

              $('#addstructure, #showstructure').click(function () {
                  $('#addFeesStructure').toggle(200);
                  $('#showStructure').toggle(200);
              });
      <?php
  } else {
      ?>
              $("#showStructure").show();
              $("#addFeesStructure").hide();

              $('#addstructure, #showstructure').click(function () {
                  $('#addFeesStructure').toggle(200);
                  $('#showStructure').toggle(200);
              });

  <?php } ?>

    });

    var rowNum = 0;

    function addRow() {
        rowNum++;
        var row = '<div class="clearfix">&nbsp;</div><div id="rowNum' + rowNum + '" >'.concat(
                '<div class="col-lg-3 col-md-3"> <label for="classid' + rowNum + '">Select Classes</label><br>',
                '<input type="text" name="classid[]" id="classid' + rowNum + '" required="true"></div>',
                '<div class="col-lg-3 col-md-3"> <label for="amount"> Amount </label> ',
                '<input type="text" name="amount[' + rowNum + ']" id="amount' + rowNum + '" class="form-control" required="true"> <div class="hidden" id="divamount' + rowNum + '">',
                '<code>Admission amount is required.</code></div> </div><div class="col-lg-3 col-md-3"> <label for="duedate' + rowNum + '"> Due Date(s) </label>',
                '<input type="text" class="form-control" name="duedate[' + rowNum + ']" id="duedate' + rowNum + '">',
                '<div class="hidden" id="divduedate' + rowNum + '"><code>Due date of payment is required.</code></div> </div>',
                '<div class="col-lg-3 col-md-3"> <label>Remove</label><br><button type="button" class="btn btn-danger" id="remove" onclick="removeRow(' + rowNum + ');">',
                '<span class="glyphicon glyphicon-minus"></span> </button> </div> </div>');

        jQuery('#itemRows').append(row);

        addSelectize('#classid' + rowNum);
        var fld = '#amount' + rowNum + ', '.concat('#duedate' + rowNum);
        //addInputize(fld);
    }

    function removeRow(rnum) {
        jQuery('#rowNum' + rnum).remove();
    }

    //start selectize with first two fields
    addSelectize('#classid');
    addInputize('#amount, #duedate');
    
    

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

</script>

<?php
  if (!empty($response)) {
      echo('<script type="text/javascript"> displayErrorJS(0);</script>');
  } elseif (!empty($_GET['delid'])) {
      echo('<script type="text/javascript"> displayErrorJS(1);</script>');
  }
?>
<div class="container">
    <div  id="showStructure">
        <?php
          $classArray = classMaster();
          if ($classArray > 0) {
              ?>
              <table class="table table-bordered table-hover"> 
                  <thead>
                      <tr>
                          <th>S.No</th>
                          <th align="center">Class</th>
                          <th align="center">Amount</th>
                      </tr>
                  </thead>
                  <?php
                  $i = 1;
                  foreach ($classArray as $key => $value) {
                      $returnArray = feeMasterShowSelect($value);
                      $toggleButton = "<a href=\"#\" id=\"Showdiv\" 
                                    onClick=\"JavaScript: showHideDiv('displaystructure$i')\"> <span class=\"caret\"></span> </a>";
                      ?>
                      <tr>
                          <td><a href="#" onClick="JavaScript: displayHideDiv('displaystructure<?php echo $i; ?>',null)"><?php echo $i ?></a> </td>
                          <td><a href="#" onClick="JavaScript: displayHideDiv('displaystructure<?php echo $i; ?>',null)"><?php echo $key ?></a></td>
                          <td><a href="#" onClick="JavaScript: displayHideDiv('displaystructure<?php echo $i; ?>',null)"><?php echo(formatCurrency($totalAmount)) ?></a> <?php echo $toggleButton ?></td>
                      </tr>
                      <tr style="display:none;" id="displaystructure<?php echo $i ?>">
                          <td colspan="3">
                              <?php
                              if ($returnArray > 0) {
                                  ?>
                                  <table class="table table-bordered table-hover ">
                                      <thead>
                                          <tr>
                                              <th> Fee Component</th>
                                              <th> Installments</th>
                                              <th> Amount</th>
                                              <th> Update</th>
                                              <th> Delete</th>
                                          </tr>
                                      </thead>
                                      <?php
                                      foreach ($returnArray as $k => $val) {
                                          ?>             
                                          <tr>
                                              <td> <a href="feeMaster.php?mode=edit&classid=<?php echo $value ?>&feecomponentid=<?php echo $val['feecomponentid'] ?>"><?php echo ucwords($val['feecomponent']) ?> </a></td>
                                              <td align="center"><a href="feeMaster.php?mode=edit&classid=<?php echo $value ?>&feecomponentid=<?php echo $val['feecomponentid'] ?>"><?php echo $val['installments'] ?> </a></td>
                                              <td> <a href="feeMaster.php?mode=edit&classid=<?php echo $value ?>&feecomponentid=<?php echo $val['feecomponentid'] ?>">  <?php echo formatCurrency($val['totalAmount']) ?></a></td>
                                              <td> <a href="feeMaster.php?mode=edit&classid=<?php echo $value ?>&feecomponentid=<?php echo $val['feecomponentid'] ?>"><span class="glyphicon glyphicon-pencil"></span></a></td>
                                              <td> <a href="feeMaster.php?mode=delete&classid=<?php echo $value ?>&delid=<?php echo $val['feecomponentid'] ?>&feestructureid=<?php echo $val['feestructureid'] ?>"><span class="glyphicon glyphicon-trash"></span></a></td>
                                          </tr>
                                          <?php
                                      }
                                      ?>
                                  </table><?php
                              } else {
                                  ?>
                                  <div class="alert alert-warning"><p>No Fee Structure for this Class Created yet.</p></div>
                                  <?php
                              }
                              ?>
                          </td>
                      </tr>
                      <?php
                      $i++;
                  }
                  ?>
              </table>
              <?php
          } else {
              ?>
              <div class="alert alert-warning">
                  <p>No record(s) found for Fee Structure. Please try to create a new Fee Structure by clicking the "ADD FEE STRUCTURE" below :</p>
              </div>
              <?php
          }
        ?>
        <div class="col-lg-6" style="text-align: left; padding: 0px;">
            <button type="button" class="btn btn-success" align="center" id="addstructure" >Add Fee Structure</button>
        </div>
        <div class="col-lg-6" style="text-align: right; padding: 0px;">
            <?php getPagination($countRows, ROW_PER_PAGE); ?>
        </div>
    </div> 


    <div id="addFeesStructure">
        <form action="<?php echo PROCESS_FORM ?>" method="post" name="imform">
            <?php renderMsg(); ?>
            <div class="btn-group btn-group-justified">
                <a href="feeMaster.php" class="btn btn-primary btn-lg">Create Fee Structure</a>
                <a href="feeRule.php" class="btn btn-success btn-lg">Create Fee Rules</a>
                <a href="otherFees.php" class="btn btn-info btn-lg">Create Other Fees</a>
            </div> 
            <h3>Create Fee Structure </h3><span class="clearfix">&nbsp;</span>
            <div class="clearfix"></div>
            <div class="col-lg-3 col-md-3">
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

            <div class="col-lg-4 col-md-4">
                <label for="frequency"> Frequency</label><div id="forFeeFrequencyTour"></div>
                <select name="frequency" id="frequency" class="form-control" required="true">
                    <option value="">- Select One-</option>
                    <option value="1">Once Only(per session)</option>
                    <option value="2">Periodic </option> 
                    <option value="3">Once only(At time of admission) </option> 
                </select>
                <small class="alert-danger">Payment Frequency is required.</small>
            </div>

            <div class="col-lg-1 col-md-1">  
                <label for="isrefundable">Refundable</label><div id="forFeeRefundableTour"></div>
                <input type="checkbox" name="isrefundable" id="isrefundable"  value="true">
            </div>

            <span class="clearfix">&nbsp;<br></span>
            <?php
              if (isset($_GET['classid']) && isset($_GET['feecomponentid'])) {
                  ShowStructure();
              } else {
                  ?>  
                  <br><div id="itemRows" >

                      <div class="col-lg-3 col-md-3">
                          <label for="classid">Select Classes</label><div id="forClassesTour"></div>
                          <input type="text" name="classid[0]" id="classid" required value ="<?php echo submitFailFieldValue("classname[0]"); ?>">
                          <small>Select all the classes with same structure.</small>
                      </div>

                      <div class=" col-lg-3 col-md-3">
                          <label for="Amount">Amount:</label>
                          <input type="text" id="amount" name="amount[0]"  required="true" class="form-control"
                                 value="<?php echo submitFailFieldValue("amount[0]"); ?>">
                          <small>Amount must have matching date in next column. </small>
                       <div class="hidden" id="divamount"><code>Amount per date in Rupees is required.</code></div>
                      </div>

                      <div class=" col-lg-3 col-md-3">
                          <label for="Due Date">Due Date(s)</label>
                          <input type="text" id="duedate"  name="duedate[0]" required="true" class="form-control"
                                 value="<?php echo submitFailFieldValue("duedate[0]"); ?>">
                          <small>Enter the date in  when the assigned amount is due.  </small>
                          <div class="hidden" id="divduedate"><code>Due date of payment is required.</code></div>
                      </div>

                      <div class="col-lg-2 col-md-2">
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
                <div class=\"col-lg-3 col-md-3\">
                    <label for=\"classname\">Class</label>
                    <input type=\"text\" class=\"form-control\" name=\"classname[]\" id=\"classname[$i]\" required value =\" $row[classname]\">
                     <input type=\"hidden\"  name=\"classid[]\" id=\"classid[$i]\"  value =\" $row[classid]\">
                 </div>
                 
                 
                 
                <div class=\"col-lg-3 col-md-3\">
                    <label for=\"amount\"> Amount </label>
                    <input type=\"text\" class=\"form-control\" name=\"amount[]\" id=\"amount[$i]\" required value =\"$row[amount]\">
                </div>

                <div class=\"col-lg-3 col-md-3\">
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
  