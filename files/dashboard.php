<?php
  /**
   * 360 - School Empowerment System.
   * Developer: Sanjay Kumar | www.ebizneeds.com.au
   * Page details here: Dashboard for the system, admin panel for the user.
   * Updated on: 21/10/2016.
   * */
// Call the includes file: config, functions, header.

  require_once '../config/config.php';
  require_once '../lib/functions.php';
  require_once VIEW_HEADER;
  require_once '../lib/reportfunctions.php';
?>
<!-- ----------------------- Style section for this page --------------------- -->
<style>
    /* this code remove the extra padding for the table.  Important dont change */
    .table-condensed>tbody>tr>td,.table-condensed>tfoot>tr>td{padding:1px}

    /* code gives shadow to the panel box for fees due / collected fees */
    div.card {
        box-shadow: 0 0 rgba(0, 0, 0, 0.2), 0 4px 4px 0 rgba(0, 0, 0, 0.19);
    }
</style>


<script type="text/javascript">
    $(function () {
        $('#showloaderfeedue').hide();
        $('#showloaderfeecollection').hide();

        $("#refreshdueamt").click(function () {
            makeAjaxcall('due');
        });

        $("#refreshcollectionamt").click(function () {
            makeAjaxcall('collection');
        });

    });
    function makeAjaxcall(type) {
        if (type == 'due') {
            $('#showloaderfeedue').show();
        } else {
            $('#showloaderfeecollection').show();
        }

        jQuery.ajax({
            url: "dashboardajax.php",
            data: 'type=' + type,
            success: function (data) {
                var returnData = JSON.parse(data);
                var feedata = returnData;
                if (type == 'due') {
                    $("#showfeedue").html(feedata);
                    $('#showloaderfeedue').hide();
                } else {
                    $("#showfeecollection").html(feedata);
                    $('#showloaderfeecollection').hide();
                }

            }

        });
    }
</script>


<?php
  /* check if the session has the dashbaord value or not 
   * if session is emply function is called and value is updated
   */

  if (isset($_SESSION['total_student'])) {
      /* get total student of session/intitute */
      $total = array_sum($_SESSION['total_student'][0]);
      /* get section according to classes */
      $clssec = $_SESSION['total_student'][1];
      /* get gender classification male/female according to class */
      $gender = $_SESSION['total_student'][2];
  } else {
      /* get all updated values into the session */
      $_SESSION['total_student'] = getTotalStudentDashboard();
      header('Location: dashboard.php');
      exit;
  }
?>
<script>
    $(function () {
        $('#divtoggle').click(function () {
            $('#chevron').toggleClass('fa fa-chevron-down fa-2x', 1000);
            $('#chevron').toggleClass('fa fa-chevron-up fa-2x', 1000);
        });
    });
</script>
<!-- ---------------------- Design section of the page ------------------------ -->

<div class="container" id="mainContainer">
    <h3 align="center">Welcome to Dashboard</h3>
    <span class="clearfix"><br></span>
    <div class="col-lg-6 card">
        <div class="panel">
            <div class="panel-heading">
                <div data-toggle="collapse" href="#collapse1" id="divtoggle" style="padding-bottom: 0px;">
                    <table class="table table-condensed table-striped">

                        <tr>
                            <th>Total Students</th>
                            <th>Males</th>
                            <th>Females</th>
                            <th></th>
                        </tr>

                        <tr>
                            <td><h3>&nbsp;<?php echo $total ?></h3></td>
                            <td><h4><?php echo $gender['Male']; ?></h4></td>
                            <td><h4><?php echo $gender['Female']; ?></h4></td>
                            <td><i id="chevron" class="fa fa-chevron-down fa-2x" aria-hidden="true"></i></td>
                        </tr>
                    </table> <!--  table end -->
                </div>  <!-- div for toggle end -->

                <div id="collapse1" class="panel-collapse collapse">
                    <div class="panel-body">
                        <table class="table table-condensed table-hover" style="font-size: 12px">

                            <tr style="font-size: 12px;">
                                <th>Class [section range]</th>
                                <th>Total Student</th>
                                <th>Boys</th>
                                <th>Girls</th>
                            </tr>

                            <?php
//reset[PHP] function gives the first element of arrayed
//end[PHP] function gives last element of array. Printing 
//first and class section of a class
                              foreach ($_SESSION['total_student'][0] as $key => $value) {
                                  ?>
                                  <tr>

                                      <td><?php echo $key . '  [ ' . reset($clssec[$key]) . ' - ' . end($clssec[$key]) . ' ]' ?></td>
                                      <td><?php echo $_SESSION['total_student'][0][$key]; ?></td>

                                      <?php foreach ($gender[$key] as $k => $val) { ?>
                                          <td><?php echo $val; ?></td><?php } ?>
                                      <td></td>
                                  </tr>
                              <?php } /* foreach for $_SESSION['total_student'][0] end  */ ?>
                        </table>
                    </div> <!-- panel-body end -->
                </div> <!-- div collapse end -->
            </div> <!-- panel end -->
        </div> <!-- panel-group end -->
    </div> <!-- col-lg-4 end -->

    <!----------------------- Fee due Section ----------------------------- -->
    <div class="col-lg-3 col-xs-6 card" style="height: 153px;">
        <div class="panel">
            <div class="panel-heading">
                <table class="table table-condensed table-striped">
                    <tr>
                        <th>Total Fee Due</th>
                    </tr>
                    <tr><td>
                            <div class="loader" id="showloaderfeedue" style="height: 25px; width:25px; "></div>
                        </td>
                    </tr>
                    <tr> <!-- print due fees -->
                        <td>
                            <div class="h4" id="showfeedue"></div>
                        </td>
                    </tr>
                </table>
                <button type="button" class="btn btn-success" id="refreshdueamt">
                    Get Fee Due
                </button>
            </div> <!-- panel end -->
        </div> <!-- panel-group end -->
    </div> <!-- col-lg-4 end -->

    <!--------------------- Fee Collected Section -------------------------- -->
    <div class="col-lg-3 col-xs-6  card" style="height: 153px;">
        <div class="panel">
            <div class="panel-heading">
                <table class="table table-condensed table-striped">
                    <tr>
                        <th>Total Fee Collected</th>
                    </tr>
                    <tr><td>
                            <div class="loader" id="showloaderfeecollection" style="height: 25px; width:25px;" ></div>
                        </td></tr>
                    <tr> <!--print collected fee -->
                        <td width="200">
                            <div class="h4" id="showfeecollection"></div>
                        </td>
                    </tr>
                </table>
                <button type="button" class="btn btn-success" id="refreshcollectionamt">
                    Get Fee Collection
                </button>
            </div> <!-- panel end -->
        </div> <!-- panel-group end -->
    </div> <!-- col-lg-4 end -->
    <span class="clearfix"><br></span>
    <span class="clearfix"><br><br></span>


    <!------------------- quick links section starts here ---------------------->
    <div class="col-lg-12" align="center">
        <div class="card panel">
            <h2 align="center">Quick Links</h2>
            <a href ="<?php echo DIR_FILES ?>/student/quickStudent.php" class="btn btn-primary">Create Student</a>
            <a href ="<?php echo DIR_FILES ?>/student/studentDashboard.php" class="btn btn-primary ">Student Dashboard</a>
            <a href ="<?php echo DIR_FILES ?>/fees/feeCollection.php" class="btn btn-primary ">Collect Fees</a>
            <a href ="<?php echo DIR_FILES ?>/fees/chequemanagement.php" class="btn btn-primary ">Cheque Management</a>
            <a href ="<?php echo DIR_REPORTS ?>/collectedFeeIndex.php" class="btn btn-success ">Collected Fee Report</a>
            <a href ="<?php echo DIR_REPORTS ?>/feeDueIndex.php" class="btn btn-success ">Fee Due Report</a>
            <span class="clearfix visible-md"><br></span>
            <a href ="<?php echo DIR_REPORTS ?>/dailyReport.php" class="btn btn-success ">Daily Report</a>
            <a href ="../studentservices/issueTC.php" class="btn btn-success">TC</a>
            <span class="clearfix"><br></span>   
        </div>
    </div>
</div> <!--container end -->

<?php
  require VIEW_FOOTER;


  /* memcache connection details do not delete
   *  
 // $memcache = new Memcache;
 // $memcache->connect('localhost', 11211);

  //$inst = getInstituteDetails();
  
  //$memcache->set('inst', array($inst));
 // echoThis($memcache->get('inst'));
 //$student  = getStudent();
 
 //$memcache->set('student', array($student));
 /*
 function getStudent(){
      $sql = "SELECT *from tblstudent";
      $result = dbSelect($sql);
      if(mysqli_num_rows($result)){
          while($rows = mysqli_fetch_assoc($result)){
              $_SESSION['student_list'][] = $rows;
              $student_row[] = $rows;
          }
          return $student_row;
      }
  }*/
//echoThis($memcache->get('student'));
   
  
