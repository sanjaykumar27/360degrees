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
  require_once VIEW_HEADER;
  
?>
<div class="container" id="selectinst"> 
    <div class="row">
    <?php renderMsg(); 
       
      $instDetailArray = getInstitute();
      
      if ($instDetailArray) {
          ?>
          <table class="table table-bordered table-hover ">
              <thead>
                  <tr>
                      <th>S No.</th>
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
                          <td><a href="addInstitute.php?edid=<?php echo $instKey['instituteid']; ?>"><span class="glyphicon glyphicon-pencil" ></span></a></td>
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
