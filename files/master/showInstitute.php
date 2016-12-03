<?php
  /*
   * 360 - School Empowerment System.
   * Developer: Ankur Mishra (amishra@ebizneeds.com.au) | www.ebizneeds.com.au
   * Page details here: Page to add new institute/branches
   * Updates here:
   * Modified By: Sanjay Kumar Chaurasia
   */

//call the main config file, functions file and header

  require_once "../../config/config.php";
  require_once DIR_FUNCTIONS;
  require_once VIEW_HEADER;
?>


<div class="container" id="selectinst">
    <?php
      renderMsg(); //to print message on screen
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
          <?php
      }
      if (empty($instDetailArray)) {
          ?>
          <div class="clearfix"></div>
          <div class="alert alert-danger"> No record(s) found. </div>
          <button type="button" id="show" class="btn btn-success" >Add Institute</button>
          <?php
      }
    ?>
</div>
<?php
  /* this function get the institute detail of current session */

  function getInstitute() {
      $sql = "SELECT t1.instituteid, LOWER(t1.institutename) as institutename, 
                    LOWER(t1.instituteaddress1) as instituteaddress1, t1.status 
                    
                    FROM tblinstitute as t1 
                    
                    LEFT JOIN tblinstsessassoc as t2 ON t1.instituteid=t2.instituteid WHERE 
                    t2.instsessassocid= $_SESSION[instsessassocid] 
                    AND t1.deleted!=1 ";

      $result = dbSelect($sql);
      if (mysqli_num_rows($result) > 0) {
          while ($rows = mysqli_fetch_assoc($result)) {
              $instArray[] = $rows;
          }
      }return $instArray;
  }

  // include footer of the page 
  include_once VIEW_FOOTER;
?>