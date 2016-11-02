<?php
  /*
   * 360 - School Empowerment System.
   * Developer:  Sanjay Kumar | schourasia@ebizneeds.com | www.ebizneeds.com.au
   * Page details here: This page display Student Summary including total Strength according
   * to class, section  and Session
   * Date: 13/10/2016
   */

  //call the main config file, functions file and header
  require_once "../config/config.php";
  require_once DIR_FUNCTIONS;
  require_once VIEW_HEADER;
?>

<div class="container">
    <!-- TO display any success of failur message on the screen -->
    <?php renderMsg(); ?> 
    <form action="<?php echo PROCESS_FORM; ?>" enctype="multipart/form-data" method="post" name="imForm">

        <div class="row">
            <!--  select class using populateSelect() function  -->
            <div class="col-lg-3">
                <div class="input-group">
                    <span class="input-group-addon">Class</span>
                    <select name="classid" id="classid"  class="form-control" tabindex="1" >
                        <?php echo populateSelect("classname", submitFailFieldValue("class")); ?>
                    </select>
                </div>
            </div> 

            <!--  select Section using populateSelect() function  -->
            <div class="col-lg-3">
                <div class="input-group">
                    <span class="input-group-addon">Section</span>
                    <select name="sectionid" id="sectionid"  class="form-control" tabindex="2">
                        <?php echo populateSelect("sectionname", submitFailFieldValue("section")); ?>
                    </select>
                </div>
            </div> 

            <!--  select Session using populateSelect() function  -->
            <div class="col-lg-3">
                <div class="input-group">
                    <span class="input-group-addon">Session</span>
                    <select name="session" id="session" value="session" class="form-control" tabindex="3">
                        <option>2016-17</option>
                    </select>
                </div>
            </div>

            <!--  select type of the search quick or detail -->
            <div class="col-lg-3">
                <div class="input-group">
                    <span class="input-group-addon">Type</span>
                    <select name="type" id="type" value="type" class="form-control" tabindex="4">
                        <option value="quick">Quick Summary</option>
                        <option value="detail">Detail Summary</option>
                    </select>
                </div>
            </div> 
        </div>     

        <!-- submit and cancel buttons -->
        <span class='clearfix'>&nbsp;<br></span>
        <div class="row"> 
            <div class="controls" align="right">
                <div class='col-lg-6'>
                    <button name='reset' value="Reset" class="btn " tabindex="6">Cancel</button>
                    <button name='search' value="search" class="btn btn-success" tabindex="7">Search</button>
                </div>
            </div>
        </div>
        <span class="clearfix"><br></span>
    </form>

</div><!-- div container end -->

<?php
  /* check which type of display information is selected by user {detail or quick} */
  if ((isset($_POST['type'])) && ($_POST['type'] == 'detail')) {

      $studentArray = getStudent(); /* Main Function for all details */
      if (isset($studentArray)) {
          foreach ($studentArray as $key => $value) {
              /* making seprate array according to class - section */
              $classArray[] = $value['classdisplayname'] . ' - ' . $value['sectionname'];
              $studentArray[$key]['classname'] = $value['classdisplayname'] . ' - ' . $value['sectionname'];
          }

          /* create class array with unique names removes duplicates */
          $classArray = array_unique($classArray);
          ?>

          <div class ="container">
              <div class="row">

                  <?php foreach ($classArray as $k => $val) { ?>
                      <!--  class name heading like NR - A -->
                      <h3><?php echo $val ?></h3> 

                      <!-- ------------ Table for detail students start here --------------->
                      <table class="table table-striped table-hover table-bordered">
                          <thead>
                              <tr>
                                  <th>Scholar No</th>
                                  <th>Student Name</th>
                                  <th>Father Name</th>
                                  <th>Gender</th>
                                  <th>Category</th>
                              </tr>
                          </thead>
                          <?php
                          foreach ($studentArray as $key => $value) {
                              if ($value['classname'] == $val) {
                                  ?>
                                  <tr >
                                      <td><a href="<?php echo DIR_FILES ?>/student/studentPersonal.php?sid=<?= $value['studentid']; ?>&mode=edit"><?php echo $value['scholarnumber'] ?></a></td>
                                      <td><a href="<?php echo DIR_FILES ?>/student/studentPersonal.php?sid=<?= $value['studentid']; ?>&mode=edit"><?php echo $value['firstname'] . ' ' . $value['middlename'] . ' ' . $value['lastname'] ?></a></td>
                                      <td><a href="<?php echo DIR_FILES ?>/student/studentParent.php?sid=<?= $value['studentid']; ?>&mode=edit"><?php echo $value['parentfirstname'] . ' ' . $value['parentmiddlename'] . ' ' . $value['parentlastname'] ?></a></td>
                                      <td><?php echo $value['gender'] ?></td>
                                      <td><?php echo $value['category'] ?></td>
                                  </tr> 
                                  <?php
                              } /* if condition end */
                          } /* foreach studentArray close and if close */
                          ?> 
                      </table>
                      <!-- ------------ Table for detail students ends here --------------->

                  <?php } /* for classArray Close */ ?>
              </div>

              <!-- generate PDF and CSV files link buttons -->
              <div class="row"> 
                  <div class="col-lg-6" style="text-align: left; padding-left: 0px;">
                      <a href="studentStatusReportPDF.php?action=pdf&type=detail"> 
                          <input type="button" id="pdfreport"  name="pdfreport" class="btn btn-success"  value=" View PDF"></a>
                      <a href="studentStatusReportPDF.php?action=xls&type=detail<?php echo $qryString; ?>"> 
                          <input type="button" id="excelreport"  name="excelreport" class="btn btn-info"  value=" View EXCEL"></a>
                  </div>
              </div>
          </div> <!-- container div end -->
          <?php
      }
  } /* isset[$_POST[Detail Summary] closed here */


  /* ===== this section is to display quick summary of the students ==== */
  if ((isset($_POST['type'])) && ($_POST['type'] == 'quick')) {

      $studentArray = getStudent(); /* Main function get all required details */
      if (isset($studentArray)) {

          foreach ($studentArray as $key => $value) {
              /* get all class name */
              $student_count_per_class[] = $value['classdisplayname'];
              $classArray[] = $value['classdisplayname'];
              /* get all males and females */
              $count[] = $value['gender'];
              /* get all categories */
              $category_count[] = $value['category'];
          }

          /* count total males and females using group by */
          $gender_count = array_count_values($count);

          /* count total categories and group by 
           * making list of unique categories for table heading 
           */
          $category_count = array_count_values($category_count);

          /* get all categories unique */
          $category_list = array_keys($category_count);

          /* sorting categories alphabetically and removing unwanted values */
          $category_list[] = asort($category_list);
          foreach ($category_list as $k => $value) {
              if ($value == 1) {
                  unset($category_list[$k]);
              }
          }

          /* sorting alphabetically and total and removing non required element */
          $category_count[] = ksort($category_count);
          foreach ($category_count as $k => $value) {
              if ($k == '') {
                  unset($category_count[$k]);
              }
          }


          /* it counts same values in a array */
          $student_count_per_class = array_count_values($student_count_per_class);
          $total = array_sum($student_count_per_class);

          /* array_unique give unique values from array discards duplicates and
           * array_values resets the key to serial Number
           * classArray has all the classes */
          $classArray = array_values(array_unique($classArray));

          /* making seperate array for gender, section and category
           * finding unique section from class
           */
          foreach ($classArray as $key => $value) {
              foreach ($studentArray as $k => $val) {
                  /* group gender and section name according to class */
                  if ($val['classdisplayname'] == $value) {
                      $gender[$value][] = $val['gender'];
                      $section[$value][] = $val['sectionname'];
                      $category[$value][] = $val['category'];
                  }
              }
          }


          /* creating unique array of section group by class
           * counting males and females group by class
           * doing category classification and counting group by class
           */
          foreach ($gender as $key => $value) {
              $section[$key] = array_values(array_unique($section[$key]));
              $gender_count[$key] = array_count_values($value);
              foreach ($category_list as $val) {
                  $category_total[$key] = array_count_values($category[$key]);
              }
          }

          /* making key of the array if they not found in class */
          foreach ($category_total as $key => $value) {
              foreach ($category_list as $k => $val) {
                  if (array_key_exists($val, $value)) {
                      continue;
                  } else {
                      $category_total[$key][$val] = '-';
                  }
              }
          }


          /*           * ***** this sorts the category_total array by key ********** */
          foreach ($category_total as $k => $val) {
              $val = ksort($category_total[$k]);
          }
          ?>

          <!-- HTML Part starts here ----------- -->     
          <div class="container">
              <h3>Quick Summary</h3><br>

              <!--  table for quick student details ---- -->
              <table class="table table-hover table-bordered">
                  <thead>
                      <tr>
                          <th width="500">Class [section range]</th>
                          <th>Total Student</th>
                          <th>Boys</th>
                          <th>Girls</th>
                          <!-- This loop prints categories in table heading -->
                          <?php foreach ($category_list as $key => $val) { ?>
                              <th><?php echo $val; ?></th>
                          <?php } ?>
                          <!-- -------------------------------------------- -->    
                      </tr>
                  </thead>

                  <!-- this main loop print count of student class - vise wise -->
                  <?php foreach ($student_count_per_class as $key => $value) { ?>
                      <tr>
                          <!-- reset[PHP] function gives the first element of arrayed
                               end[PHP] function gives last element of array. Printing 
                               first and class section of a class 
                          -->
                          <td><?php echo $key . '  [ ' . reset($section[$key]) . ' - ' . end($section[$key]) . ' ]' ?></td>
                          <td><?php echo $student_count_per_class[$key]; ?></td>

                          <!-- This loop print gender count class wise ---------->
                          <?php foreach ($gender_count[$key] as $k => $val) { ?>
                              <td><?php echo $val; ?></td><?php
                          }
                          /* -this loop print categories classification class wise -- */
                          foreach ($category_total[$key] as $k => $val) {
                              ?>
                              <td><?php echo $val; ?></td>
                          <?php } ?>
                          <!-- --------------------------------------------- -->
                      </tr>
                  <?php } /* forech $student_count_per_class end */ ?>
                  <tr>
                      <td><strong>TOTAL</strong></td>
                      <td><strong><?php echo $total; ?></strong></td><!-- total students -->
                      <td><strong><?php echo $gender_count['Male']; ?></strong></td> <!-- Totals males -->
                      <td><strong><?php echo $gender_count['Female']; ?></strong></td><!-- Totals females -->
                      <!-- total by category classification -->
                      <?php foreach ($category_count as $key => $value) { ?> 
                          <td><strong><?php echo $value ?></strong></td>
                      <?php } ?>
                      <!-- ----------------------------------------- -->
                  </tr>
              </table>

              <!-- generate PDF and CSV files link buttons -->
              <div class="row"> 
                  <div class="col-lg-6" style="text-align: left; padding-left: 0px;">
                      <a href="studentStatusReportPDF.php?action=pdf&type=quick"> 
                          <input type="button" id="pdfreport"  name="pdfreport" class="btn btn-success"  value=" View PDF"></a>
                      <a href="studentStatusReportPDF.php?action=xls&type=quick<?php echo $qryString; ?>"> 
                          <input type="button" id="excelreport"  name="excelreport" class="btn btn-info"  value=" View EXCEL"></a>
                  </div>
              </div>
          </div>
          <?php
      } /* if isset end */
  } /* is $_POST['quick summary] end */
?>

<!-- functions starts here ---------------- -->
<?php
  /* this function return the all the student according to 
   * class , section , session or all.
   * Made By: Sanjay Kumar
   * Date: 14/10/2016
   */

  /* NOTE: this sql gives correct answer only when , gender section is not empty
   * otherwise it will skip that student */

  function getStudent() {

      $studentArray = array();
      $sql = "  select t1.studentid, t1.scholarnumber, t1.firstname, t1.middlename, t1.lastname,
                t2.classid, t3.clsecassocid, t5.collectionname as gender,
                t6.classdisplayname, t7.sectionname,
                t8.parentfirstname, t8.parentmiddlename, t8.parentlastname
       
                FROM tblstudent as t1,
                tblclsecassoc as t2,
                tblstudentacademichistory as t3,
                tblstudentdetails as t4,
                tblmastercollection as t5,
                tblclassmaster as t6,
                tblsection as t7,
                tblparent as t8,
                tbluserparentassociation as t9
                
                where 
                
                t3.studentid = t1.studentid AND
                t3.clsecassocid = t2.clsecassocid AND
                t2.classid = t6.classid AND
                t2.sectionid = t7.sectionid AND
                t4.studentid = t3.studentid AND
                t5.mastercollectionid = t4.gender AND
                t9.studentid = t1.studentid AND
                t8.parentid = t9.parentid
               ";

      /* this if condition checks wheather user passed any search parameter
       * like class or section. 
       */
      if (isset($_POST['classid']) && !empty($_POST['classid'])) {
          $sql .= " AND t2.classid = $_POST[classid] ";
      }
      if (isset($_POST['sectionid']) && !empty($_POST['sectionid'])) {
          $sql .= "AND t2.sectionid = $_POST[sectionid] ";
      }

      $sql .= " AND t1.instsessassocid = '$_SESSION[instsessassocid]' AND t1.status = 1 
                AND t1.deleted = 0 AND t1.tcissued = 0
                GROUP BY t1.studentid ORDER BY t2.classid, t7.sectionid";

      //echoThis($sql);
      $result = dbSelect($sql);
      if (mysqli_num_rows($result) > 0) {
          while ($rows = mysqli_fetch_assoc($result)) {
              $studentArray[] = $rows;
              $studentid[] = $rows['studentid'];
          }
      }

      $category = getCategory(); /* get categories of all the student of the session */

      /* merging student category with studentArray by comparing studentid */
      foreach ($studentArray as $key => $value) {
          $studentArray[$key]['category'] = '';
          foreach ($category as $k => $val) {
              if ($val['studentid'] == $value['studentid']) {
                  $studentArray[$key]['category'] = $val['category'];
              }
          }
      }
      
      /* checking if the categories are emply then initializing it with NA */
      foreach ($studentArray as $key => $value) {
          if (empty($value['category'])) {
              $studentArray[$key]['category'] = 'NA';
          }
      }

      return $studentArray;
  }

  /* this function gives the categories of the student of the session 
   * Parent function: getStudent()
   * Made by: Sanjay Kumar
   * Date; 25/10/2016
   */

  function getCategory() {

      $sql = " select t1.studentid, t1.scholarnumber,
               t2.collectionname as category
               
                FROM tblstudent as t1,
                tblmastercollection as t2,
                tblstudentdetails as t3
                
                where 
                
                t3.studentid = t1.studentid AND 
                t2.mastercollectionid = t3.category
                AND t1.instsessassocid = $_SESSION[instsessassocid]
                AND t1.status = 1
                AND t1.deleted = 0 AND t1.tcissued = 0
                ORDER BY t2.collectionname ASC
                ";

      $result = dbSelect($sql);
      if (mysqli_num_rows($result) > 0) {
          while ($rows = mysqli_fetch_assoc($result)) {
              $category[] = $rows;
          }
      }

      return $category;
  }

  /* loading footer **** */
  include_once VIEW_FOOTER;
?>