<?php
  /*
   * 360 - School Empowerment System.
   * Developer: Ankur Mishra (amishra@ebizneeds.com.au) | www.ebizneeds.com.au
   * Page details here: Page to add new institute/branches
   * Updates here:
   */

//call the main config file, functions file and header
  
  require_once "../../config/config.php";
  require_once DIR_FUNCTIONS;
  
  if (isset($_GET['status'])) {
      statusUpdate('tblinstitute', $_GET['status'], 'instituteid=' . $_GET['sid']);
  }

  if ((isset($_GET['delid']))) {
      $qryString = "UPDATE tblinstitute SET deleted=1 WHERE instituteid=" . $_GET['delid'];
      $result = dbUpdate($qryString);
      @header('Location:addinstitutephp?s=6');
  }

  if (isset($_GET['page']) && !empty($_GET['page']) && $_GET['page'] > 1) {
      $page = $_GET['page'];
  } else {
      $page = 1;
  }
  require_once VIEW_HEADER;
?>
<script type="text/javascript">
    if (<?php
  if (isset($_GET['edid'])) {
      echo $_GET['edid'];
  } else {
      echo 0;
  }
?>)
    {
        $(function () {
            $("#selectinst").hide();
            $('#show, #showinst').click(function () {
                $('#addinst').toggle(500);
                $('#selectinst').toggle(500);
            });
        });
    } else
    {
        $(function () {
            $("#addinst").hide();
            $('#show, #showinst').click(function () {
                $('#addinst').toggle(200);
                $('#selectinst').toggle(200);
            });
        });
    }
    function showHideDiv(divName) {
        $('#' + divName).toggle();

    }
</script>

<div class="container" id="selectinst">
    <?php
      renderMsg();
      $instDetailArray = getInstitute();
      
      if ($instDetailArray) {
          ?>
          <table class="table table-bordered table-hover ">
              <thead>
                  <tr>
                      <th>SNo.</th>
                      <th>Institute Name</th>
                      <th>Institute Address</th>
                      <th>Update</th>
                  </tr>
              </thead>
              <tbody>
                  <?php
                  $sno = 1;
                  foreach ($instDetailArray as $instKey) {
                      if ($instKey['status'] == 1) {
                          $statusStyle = 'class="glyphicon glyphicon-ok-circle" style="color:green"';
                      } else {
                          $statusStyle = 'class="glyphicon glyphicon-ban-circle" style="color:red"';
                      }
                      ?>
                      <tr>
                          <td><a href="addInstitute.php?edid=<?php echo $instKey['instituteid']; ?>"><?php echo $sno ?></a></td>
                          <td><a href="addInstitute.php?edid=<?php echo $instKey['instituteid']; ?>"><?php echo ucwords($instKey['institutename']); ?></a></td>
                          <td><a href="addInstitute.php?edid=<?php echo $instKey['instituteid']; ?>"><?php echo ucwords($instKey['instituteaddress1']); ?></a></td>
                          <td> <a href="addInstitute.php?edid=<?php echo $instKey['instituteid']; ?>"><span class="glyphicon glyphicon-pencil" ></span></a></td>
                      </tr>
                      <?php
                      $sno++;
                  }
                  ?>
          </table>
      <?php }
                if (empty($instDetailArray)){
          ?>
          <div class="clearfix"></div>
          <div class="alert alert-danger"> No record(s) found. </div>
          <button type="button" id="show" class="btn btn-success" >Add Institute</button>
          <?php
      }
    ?>
</div>

<span class="clearfix"><p>&nbsp;</p></span>
<form action="<?php echo PROCESS_FORM; ?>" enctype="multipart/form-data" method="post" name="imForm">
    <div class="container" id="addinst">
        <div class="span11">

            <?php
              renderMsg();

              if (isset($_GET['edid'])) {
                  $institutedetailsArray = '';
                  $institutedetailsArray = instituteDetails(); 
                  $mode = 'edit';
                  ?>
                  <input type="hidden" name="edid" id="edid" value="<?php echo $_GET['edid'] ?>">

                  <?php
              } else {
                  $mode = 'add';
              }
            ?>
            <input type="hidden" name="mode" value="<?= $mode ?>" id="mode"> 

            <div class="col-lg-6 col-md-6">
                <label for="institutename">Name</label>
                <input type="text" id="institutename" class="form-control" name="institutename" 
                       placeholder="Institute Name" maxlength="50" required="true"
                       value ="<?php
                         if (!empty($institutedetailsArray)) {
                             echo $institutedetailsArray['institutename'];
                         } else {
                             echo submitFailFieldValue("institutename");
                         }
                       ?>">


            </div>
            <div class="col-lg-6 col-md-6">
                <label for="insituteweburl">Website</label>

                <input type="text" class="form-control" id="insituteweburl"
                       name="insituteweburl" placeholder="Institute Website" maxlength="50" palceholder="http://abc@xx.com"
                       value ="<?php
                         if (!empty($institutedetailsArray)) {
                             echo $institutedetailsArray['insituteweburl'];
                         } else {
                             echo submitFailFieldValue("insituteweburl");
                         }
                       ?>">

            </div>

            <span class="clearfix"><p>&nbsp;</p></span>

            <div class="col-lg-6 col-md-6">
                <label for="instituteaddress1">Address 1 </label>
                <input type="text" class="form-control" id="instituteaddress1" 
                       name="instituteaddress1" placeholder="Institute Address" maxlength="50" required="true" 
                       value ="<?php
                         if (!empty($institutedetailsArray)) {
                             echo $institutedetailsArray['instituteaddress1'];
                         } else {
                             echo submitFailFieldValue("instituteaddress1");
                         }
                       ?>">
            </div>
            <div class="col-lg-6 col-md-6">
                <label for="instituteaddress2">Address 2 </label>
                <input type="text" class="form-control" id="instituteaddress2"
                       name="instituteaddress2" placeholder="Institute Address" maxlength="50"
                       value ="<?php
                         if (!empty($institutedetailsArray)) {
                             echo $institutedetailsArray['instituteaddress2'];
                         } else {
                             echo submitFailFieldValue("instituteaddress2");
                         }
                       ?>">
            </div>

            <span class="clearfix"><p>&nbsp;</p></span>

            <div class="col-lg-6 col-md-6">
                <label for="institutecityid">City</label>
                <select name="institutecityid" class="form-control">
                    <?php
                      if (!empty($institutedetailsArray)) {
                          echo PopulateSelect("cityname", $institutedetailsArray['institutecityid']);
                      } else {
                          echo PopulateSelect("cityname", submitFailFieldValue("institutecityid"));
                      }
                    ?> 

                </select>
            </div>
            <div class="col-lg-6 col-md-6">
                <label for="institutestateid">State</label>
                <select name="institutestateid" class="form-control">
                    <?php
                      if (!empty($institutedetailsArray)) {
                          echo PopulateSelect("statename", $institutedetailsArray['institutestateid']);
                      } else {
                          echo PopulateSelect("statename", submitFailFieldValue("institutestateid"));
                      }
                    ?>

                </select>
            </div>

            <span class="clearfix"><p>&nbsp;</p></span>

            <div class="col-lg-6 col-md-6">
                <label for="institutephone1">Primary Phone</label>
                <input type="text" class="form-control" id="institutephone1" 
                       name="institutephone1" placeholder="Institute Phone" maxlength="10" required="true"
                       value ="<?php
                         if (!empty($institutedetailsArray)) {
                             echo $institutedetailsArray['institutephone1'];
                         } else {
                             echo submitFailFieldValue("institutephone1");
                         }
                       ?>">
            </div>
            <div class="col-lg-6 col-md-6">
                <label for="institutephone2">Secondary Phone</label>
                <input type="text" class="form-control" id="institutephone2" 
                       name="institutephone2" placeholder="Institute Phone" maxlength="10"
                       value ="<?php
                         if (!empty($institutedetailsArray)) {
                             echo $institutedetailsArray['institutephone2'];
                         } else {
                             echo submitFailFieldValue("institutephone2");
                         }
                       ?>">
            </div>

            <span class="clearfix"><p>&nbsp;</p></span>

            <div class="col-lg-6 col-md-6">
                <label for="instituteemail1">Primary Email</label>
                <input type="text" class="form-control" id="instituteemail1" name="instituteemail1" 
                       placeholder="Institute Email" maxlength="50" required="true"
                       value ="<?php
                         if (!empty($institutedetailsArray)) {
                             echo $institutedetailsArray['instituteemail1'];
                         } else {
                             echo submitFailFieldValue("instituteemail1");
                         }
                       ?>">
            </div>

            <div class="col-lg-6 col-md-6">
                <label for="instituteemail2">Secondary Email</label>
                <input type="text" class="form-control" id="instituteemail2" name="instituteemail2" 
                       placeholder="Institute Email" maxlength="50"
                       value ="<?php
                         if (!empty($institutedetailsArray)) {
                             echo $institutedetailsArray['instituteemail2'];
                         } else {
                             echo submitFailFieldValue("instituteemail2");
                         }
                       ?>">
            </div>

            <span class="clearfix"><p>&nbsp;</p></span>

            <div class="col-lg-6 col-md-6">
                <label for="institutefax1">Fax</label>
                <input type="text" class="form-control" id="institutefax1" name="institutefax1"
                       placeholder="Fax" maxlength="50" 
                       value ="<?php
                         if (!empty($institutedetailsArray)) {
                             echo $institutedetailsArray['institutefax1'];
                         } else {
                             echo submitFailFieldValue("institutefax1");
                         }
                       ?>">
            </div>

            <div class="col-lg-6 col-md-6">
                <label for="instituteaccreditionid">Institute Accreditation</label>
                <input type="text" class="form-control" id="instituteaccreditionid" name="instituteaccreditionid"
                       value ="<?php
                         if (!empty($institutedetailsArray)) {
                             echo $institutedetailsArray['instituteaccreditionid'];
                         } else {
                             echo submitFailFieldValue("instituteaccreditionid");
                         }
                       ?>">
            </div>

            <span class="clearfix"><p>&nbsp;</p></span>

            <div class="col-lg-6 col-md-6">
                <label for="institutecountryid">Country</label>
                <select name="institutecountryid" class="form-control">
                    <?php
                      if (!empty($institutedetailsArray)) {
                          echo PopulateSelect("countryname", $institutedetailsArray['institutecountryid']);
                      } else {
                          echo PopulateSelect("countryname", submitFailFieldValue("institutecountryid"));
                      }
                    ?>
                </select>
            </div>

            <div class="col-lg-6 col-md-6">
                <label for="institutelogo">Institute Logo</label> 
                <?php if (!empty($institutedetailsArray['institutelogo'])) {
                      ?> ( click to enlarge)

                      <img src="../../asset/images/institute-logo/<?php echo $institutedetailsArray['institutelogo'] ?>" 
                           width="20" onclick=" showHideDiv('enlarge_logo')" /> 

                  <?php } if (!empty($institutedetailsArray['institutelogo'])){
                      $logo = $institutedetailsArray['institutelogo']; }
                      else
                      { $logo =""; }?>
                

                <input type="file" name="institutelogo" class="form-control" >
                <div id="enlarge_logo" style="display: none">
                    <img src="../../asset/images/institute-logo/<?php echo $logo ?>" 
                         width="200" /></div>
            </div>

            <span class="clearfix"><p>&nbsp;</p></span>

            <div class="col-lg-6 col-md-6">
                <label for="status">Status</label>
                <select name="status" class="form-control">
                    <?php
                     
                      if (!empty($institutedetailsArray['status']) && $institutedetailsArray['status'] == 0) {
                          ?>
                          <option value="0"> Inactive </option>
                          <option value="1"> Active </option>
                          <?php
                      } elseif (!empty($institutedetailsArray['status']) && $institutedetailsArray['status'] == 1) {
                          ?>
                          <option value="1"> Active </option>
                          <?php
                      } else {
                          ?>
                          <option value=""> -Select one - </option>
                          <option value="1"> Active </option>
                          <option value="0"> Inactive </option>
                      <?php }
                    ?>

                </select>
            </div>

            <div class="col-lg-6 col-md-6">
                <label for="institutedescription">Brief Description</label>
                <textarea name="institutedescription"  class="form-control">
                    <?php
                      if (!empty($institutedetailsArray['institutedescription'])) {
                          echo(cleanVar($institutedetailsArray['institutedescription']));
                      } else {
                          echo submitFailFieldValue("institutedescription");
                      }
                    ?>
                </textarea>
            </div>

            <span class="clearfix"><p>&nbsp;</p></span>
            <div class="controls" align="center">
                <button type="button" class="btn btn-success" align="center" id="showinst" >Show Institutes</button>
                <input id="clearDiv" type="button"  value="Cancel" class="btn">
                <input type="submit" id="save" name="save" value="SAVE" class="btn btn-success">
            </div>
        </div>
    </div>
</form>

<?php
  require VIEW_FOOTER;

  function getInstitute() {
    
      $sql = "SELECT t1.instituteid,LOWER(t1.institutename) as institutename, 
                    LOWER(t1.instituteaddress1) as instituteaddress1,t1.status 
                    FROM tblinstitute as t1 
                    LEFT JOIN tblinstsessassoc as t2 ON t1.instituteid=t2.instituteid WHERE 
                    t2.instsessassocid= $_SESSION[instsessassocid] 
                    AND t1.deleted!=1 AND t1.deleted!=1 ";

      //echoThis($sql);die;
      $result = dbSelect($sql);
      if (mysqli_num_rows($result) > 0) {
          while ($rows = mysqli_fetch_assoc($result)) {
              $instArray[] = $rows;
          }
      }return $instArray;
       
  }

  function instituteDetails() {

      $instituteid = cleanVar($_GET['edid']);
      $chequeSql = "SELECT `instituteid`  FROM `tblinstsessassoc` WHERE `instsessassocid` = $_SESSION[instsessassocid]";
      $result = dbSelect($chequeSql);
      if (mysqli_num_rows($result) > 0) {
          while ($rows = mysqli_fetch_assoc($result)) {
              $instid = $rows['instituteid'];
          }
      }
      if ($instid == $instituteid) {
          $sql = " SELECT * FROM `tblinstitute` AS t1 WHERE t1.instituteid = $instituteid  AND t1.deleted = 0 ";

          $result = dbSelect($sql);
          $row = mysqli_fetch_assoc($result);

          return $row;
      } else {
         // addError('custom');
          addError("custom", "", "addInstitute.php");
          
      }
  }
?>
