<?php
  /*
   * 360 - School Empowerment System.
   * Developer: Ankur Mishra (amishra@ebizneeds.com.au) | www.ebizneeds.com.au
   * Page details here: Page to add new students
   * Updates here:
   */
  //call the main config file, functions file and header
  require_once "../../config/config.php";
  require_once DIR_FUNCTIONS;
  require_once VIEW_HEADER;
  // check is the form is in edit mode, if so pass on JS for confirm message
  $jsConfirm = "";
  if (!empty(isEditable())) {
      $jsConfirm = "onsubmit=\"return confirm('You are about to update the record, please be sure before you proceed!');\"";
  }

  if (!isset($_REQUEST['sid']) && $_REQUEST['sid'] <= 0) {
      $class = "class='disabled disabledTab' data-toggle='tab' ";
  } else {
      $class = "";
  }
?>
<script type="text/javascript">

    $(document).ready(function ($) {
        // for displaying modal for confirming before updating  record...//
        $('#save,#next').click(function () {
            $('#myModal').modal('show');
            return false;
        });
        $('#submitForm').click(function () {
            $('#imform').submit();
            $('#myModal').modal('hide');
        });
    });
</script>
<div class="container"> 	

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
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
    <ul class="nav nav-tabs" role="tablist" data>
        <li  <?= $class; ?>><a href="studentPersonal.php<?php echo isEditable(); ?>">Personal</a></li>
        <li <?= $class; ?>><a href="studentParent.php<?php echo isEditable(); ?>">Parents</a></li>
        <li class="active" data-toggle="tab"><a href="studentMedical.php<?php echo isEditable(); ?>">Medical</a></li>
        <li <?= $class; ?>><a href="studentFees.php<?php echo isEditable(); ?>">Fees Rule</a></li>
        <li <?= $class; ?>><a href="studentDocument.php<?php echo isEditable(); ?>">Documents</a></li>
        <li <?= $class; ?>><a href="studentFeeDetails.php<?php echo isEditable(); ?>">Fee Details</a></li>
    </ul>
</div>
<span class="clearfix">&nbsp;<br></span>
<form action="<?php echo PROCESS_FORM; ?>" method="post" id="imform">
    <div class="container">
        <?php
          renderMsg();
          // check whether the page is in edit mode, if so call the function
          if (!empty(isEditable())) {
              $medicaldetailsArray = getStudentMedicalInfo();
          }
          if ($medicaldetailsArray <= 0) {
              $mode = 'add';
          } else {
              $mode = 'edit';
          }
        ?>
        <input type="hidden" name="sid" id="sid" value="<?php echo $_REQUEST['sid']; ?>">
        <input type="hidden" name="mode" id="mode" value="<?= $mode ?>"> 
        <div class="col-lg-6 col-md-6">
            <label for="medicalhistory">Medical Information*</label>
            <input type="text" name="medicalhistory"
                   value ="<?php
        if (!empty($medicaldetailsArray)) {
            echo $medicaldetailsArray['medicalhistory'];
        } else {
            echo submitFailFieldValue("medicalhistory");
        }
        ?>" class="form-control" required="true" />

        </div>

        <div class="col-lg-6 col-md-6">
            <label for="allergyinfo">Allergy Information*</label>
            <input type="text" name="allergyinfo"
                   value ="<?php
                     if (!empty($medicaldetailsArray)) {
                         echo $medicaldetailsArray['allergyinfo'];
                     } else {
                         echo submitFailFieldValue("allergyinfo");
                     }
                   ?>"  class="form-control" required="true"  />
        </div>

        <span class="clearfix">&nbsp;</span>


        <div class="col-lg-6 col-md-6">
            <label for="medicalhistory">Frequent IIlness</label>
            <input type="text" id="frequentillness"  class="form-control" name="frequentillness"
                   value ="<?php
                     if (!empty($medicaldetailsArray)) {
                         echo $medicaldetailsArray['frequentillness'];
                     } else {
                         echo submitFailFieldValue("frequentillness");
                     }
                   ?>" >
        </div>

        <div class="col-lg-6 col-md-6">
            <label for="regularhospname">Hospital Name*</label>
            <input type="text" id="regularhospname"  class="form-control" name="regularhospname" required="true"
                   value ="<?php
                     if (!empty($medicaldetailsArray)) {
                         echo $medicaldetailsArray['regularhospname'];
                     } else {
                         echo submitFailFieldValue("regularhospname");
                     }
                   ?>">
        </div>

        <span class="clearfix">&nbsp;</span>

        <div class="col-lg-6 col-md-6">
            <label for="regularhospphone">Hospital Phone*</label>
            <input type="text" id="regularhospphone"  class="form-control" name="regularhospphone" required="true" maxlength="7"
                   value ="<?php
                     if (!empty($medicaldetailsArray)) {
                         echo $medicaldetailsArray['regularhospphone'];
                     } else {
                         echo submitFailFieldValue("regularhospphone");
                     }
                   ?>">
        </div>

        <div class="col-lg-6 col-md-6">
            <label for="regularhospemail">Hospital Email*</label>
            <input type="text" id="regularhospemail"  class="form-control" name="regularhospemail" required="true"
                   value ="<?php
                     if (!empty($medicaldetailsArray)) {
                         echo $medicaldetailsArray['regularhospemail'];
                     } else {
                         echo submitFailFieldValue("regularhospemail");
                     }
                   ?>">
        </div>
        <span class="clearfix">&nbsp;</span>
        <div class="col-lg-6 col-md-6">
            <label for="regularhospaddress">Hospital Address*</label>
            <input type="text" id="regularhospaddress"  class="form-control" name="regularhospaddress" required="true"
                   value ="<?php
                     if (!empty($medicaldetailsArray)) {
                         echo $medicaldetailsArray['regularhospaddress'];
                     } else {
                         echo submitFailFieldValue("regularhospaddress");
                     }
                   ?>" >
        </div>
        <div class="col-lg-6 col-md-6">
            <label for="regulardocname">Doctor Name*</label>
            <input type="text" id="regulardocname"  class="form-control" name="regulardocname" required="true"
                   value ="<?php
                   if (!empty($medicaldetailsArray)) {
                       echo $medicaldetailsArray['regulardocname'];
                   } else {
                       echo submitFailFieldValue("regulardocname");
                   }
                   ?>">
        </div>
        <span class="clearfix">&nbsp;</span>
        <div class="col-lg-6 col-md-6">
            <label for="regulardocemail">Doctor Email*</label>
            <input type="text" id="regulardocemail"  class="form-control" name="regulardocemail" required="true"
                   value ="<?php
                   if (!empty($medicaldetailsArray)) {
                       echo $medicaldetailsArray['regulardocemail'];
                   } else {
                       echo submitFailFieldValue("regulardocemail");
                   }
                   ?>">
        </div>
        <div class="col-lg-6 col-md-6">
            <label for="regulardocphone">Doctor Phone*</label>
            <input type="text" id="regulardocphone"  class="form-control" name="regulardocphone" required="true" maxlength="7"
                   value ="<?php
                   if (!empty($medicaldetailsArray)) {
                       echo $medicaldetailsArray['regulardocphone'];
                   } else {
                       echo submitFailFieldValue("regulardocphone");
                   }
                   ?>">
        </div>
        <span class="clearfix">&nbsp;</span>
        <div class="col-lg-6 col-md-6">
            <label for="regulardocmobile">Doctor Mobile*</label>
            <input type="text" id="regulardocmobile"  class="form-control" name="regulardocmobile" required="true"  maxlength="10"
                   value ="<?php
                   if (!empty($medicaldetailsArray)) {
                       echo $medicaldetailsArray['regulardocmobile'];
                   } else {
                       echo submitFailFieldValue("regulardocmobile");
                   }
                   ?>">
        </div>
        <div class="col-lg-6 col-md-6">
            <label for="regulardocaddress">Doctor Address*</label>
            <input type="text" id="regulardocaddress"  class="form-control" name="regulardocaddress" required="true"
                   value ="<?php
                   if (!empty($medicaldetailsArray)) {
                       echo $medicaldetailsArray['regulardocaddress'];
                   } else {
                       echo submitFailFieldValue("regulardocaddress");
                   }
                   ?>">
        </div>

        <span class="clearfix">&nbsp;</span>

        <div class="col-lg-6 col-md-6">
            <label for="height">Height*</label>
            <input type="text" id="height"  class="form-control" name="height"  required="true"
                   value ="<?php
                     if (!empty($medicaldetailsArray)) {
                         echo $medicaldetailsArray['height'];
                     } else {
                         echo submitFailFieldValue("height");
                     }
                   ?>">
        </div>

        <div class="col-lg-6 col-md-6">
            <label for="weight">Weight*</label>
            <input type="text" id="weight"  class="form-control" name="weight" required="true"
                   value ="<?php
                     if (!empty($medicaldetailsArray)) {
                         echo $medicaldetailsArray['weight'];
                     } else {
                         echo submitFailFieldValue("weight");
                     }
                   ?>">
        </div>

        <span class="clearfix">&nbsp;</span>

        <div class="col-lg-6 col-md-6">
            <label for="righteyesight">Right Eyesight</label>
            <input type="text" id="righteyesight" class="form-control" name="righteyesight" placeholder="right-sight"
                   value ="<?php
                     if (!empty($medicaldetailsArray)) {
                         echo $medicaldetailsArray['righteyesight'];
                     } else {
                         echo submitFailFieldValue("righteyesight");
                     }
                   ?>">
        </div>

        <div class="col-lg-6 col-md-6">
            <label for="lefteyesight">Left Eyesight</label>
            <input type="text" id="lefteyesight"  class="form-control" name="lefteyesight" placeholder="left-sight"
                   value ="<?php
                  if (!empty($medicaldetailsArray)) {
                      echo $medicaldetailsArray['lefteyesight'];
                  } else {
                      echo submitFailFieldValue("lefteyesight");
                  }
                ?>">
        </div>

        <span class="clearfix">&nbsp;</span>
        <div class="col-lg-6 col-md-6">
            <label for="bloodgroup">Blood Group*</label>
            <select class="form-control"  name="bloodgroup" id="bloodgroup" required="true">
                   <?php
                     if (!empty($medicaldetailsArray)) {
                         echo PopulateSelect("bloodgroup", $medicaldetailsArray['bloodgroup']);
                     } else {
                         echo PopulateSelect("bloodgroup", submitFailFieldValue("bloodgroup"));
                     }
                   ?>
            </select>
        </div>

        <div class="col-lg-6 col-md-6">
            <label for="identificationmark1">Identification Mark A</label>
            <input type="text"  name="identificationmark1"
                   value ="<?php
                     if (!empty($medicaldetailsArray)) {
                         echo $medicaldetailsArray['identificationmark1'];
                     } else {
                         echo submitFailFieldValue("identificationmark1");
                     }
                   ?>"class="form-control"/>
        </div>

        <span class="clearfix">&nbsp;</span>
        <div class="col-lg-6 col-md-6">
            <label for="identificationmark2">Identification Mark B</label>
            <input type="text"  name="identificationmark2"
                   value ="<?php
                     if (!empty($medicaldetailsArray)) {
                         echo $medicaldetailsArray['identificationmark2'];
                     } else {
                         echo submitFailFieldValue("identificationmark2");
                     }
                   ?>" class="form-control"/>
        </div>

        <div class="col-lg-6 col-md-6">
            <label for="doctorremark">Doctor Remark</label>
            <input type="text"  name="doctorremark"
                   value ="<?php
  if (!empty($medicaldetailsArray)) {
      echo $medicaldetailsArray['doctorremark'];
  } else {
      echo submitFailFieldValue("doctorremark");
  }
?>" class="form-control"/>
        </div>
    </div> <!--//Container Closed-->

    <span class="clearfix"><p>&nbsp;</p></span> 
    <div class="controls" align="center">
        <input id="clearDiv" type="button"  value="Cancel" class="btn">
        <!-- Button trigger modal -->
        <input type="submit" id="submit1"  name="submit" value="SAVE" class="btn btn-success">
        <input type="submit" id="submit"  name="submit" value="SAVE & NEXT>>" class="btn btn-success">
    </div> 
</form>
<?php
  require_once VIEW_FOOTER;

//select *from tblmedicalinfo where studentid IN (select studentid from 
//tblstudent where instsessassocid = $_SESSION[instsessassocid] AND studentid = '".cleanVar($_REQUEST['sid'])."')
  function getStudentMedicalInfo() {
      if (isset($_REQUEST['sid'])) {
          $sqlMedical = "select *from tblmedicalinfo where studentid IN 
            (select studentid from 
             tblstudent where instsessassocid = $_SESSION[instsessassocid] AND
             studentid = '" . cleanVar($_REQUEST['sid']) . "')";
         
          $sqlResult = dbSelect($sqlMedical);
          if (mysqli_num_rows($sqlResult) > 0) {
              $medicalInfoArray = mysqli_fetch_assoc($sqlResult);
              return $medicalInfoArray;
          } else {
              return 0;
          }
      } else {
          return 0;
      }
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

