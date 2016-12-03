<?php

    /*
    * 360 - School Empowerment System.
    * Developer: Prateek Mathur (pmathur@ebizneeds.com) | www.ebizneeds.com.au
    * Page details here:
    * Updates here:
    */
    require_once "../config/config.php";
    require_once '../lib/reportfunctions.php';
    require_once DIR_FUNCTIONS;
    require_once VIEW_HEADER;
 
    $Date = date('Y-m-d');
    $Date1 = Date('Y-m-d', strtotime($Date . " last month"));
    
    $sno=(int)(isset($_GET['page']) ?  (($_GET['page']-1)*ROW_PER_PAGE)+1 : 1);
    
    if (isset($_GET) && !empty($_GET)) {
        $qryString= '&'.http_build_query(cleanvar($_GET));
    } else {
        $qryString='';
    }
  
   $session= selectSessionStart();
   $startDate =  date('Y-m-01');
   $endDate = date('Y-m-t');
   
?>
<div id="printdiv" class="container"> 
    
    <div class="btn-group btn-group-justified">
        <a href="feeDueIndex.php?monthstart=<?php echo date('Y-m-01') ?>&monthend=<?php echo date('Y-m-t') ?> " class="btn btn-success btn-lg">Current Month Due</a>
        <a href="feeDueIndex.php?monthstart=<?php echo $session['startdate'] ?>&monthend=<?php echo date('Y-m-d') ?>"class="btn btn-primary btn-lg">Due From Start</a>
        <a href="feeDueIndex.php?monthstart=<?php echo date('Y-m-d', strtotime('-1 day', strtotime(date('Y-m-01')))) ?>&monthend=<?php echo date('Y-m-d') ?>"class="btn btn-info btn-lg">Last Month Due</a>           
    </div> 
    
    <span class="clearfix">&nbsp;</span>
   
    <?php 
      /*
       * Including the file containing HTML for searching student based on diffreret search parameters
       */
      include_once "searchstudentreportHTML.php";
      
    if (isset($_REQUEST['search']) || !empty($_REQUEST['monthstart'])) {
        $reportArray = studentFeeDetails('report');  ?>
        
    <span class="clearfix">&nbsp;</span>
    <div id="showreport" name="showreport">
         
        <?php renderMsg();
        
        if ($reportArray !=0 && $reportArray['totaldue'] != 0) {
            
        $totalRows = $reportArray['totalrows'];  ?>
        <table class="table table-hover table-bordered" id="displaytable">
            <thead> 
                <tr >
                    <th>S.No</th>
                    <th>Scholar No </th>
                    <th>Name</th>
                    <th>Class</th>
                    <th>Due Installments</th>
                    <th>Fee Due</th>
                </tr>
            </thead> 
            <tbody>
                <?php 
                  $totalDue = 0 ;
                  
            foreach ($reportArray['records'] as $key=>$value) {
                $studentname=$value['firstname'].''.$value['middlename'].' '.$value['lastname'];
                $classname= $value['classdisplayname'].'-'.$value['sectionname'];
                     
                $studentname = strtoupper($studentname);
                if ($value['feedetails'] == 0) {
                    $feedetails = "<span class='text-success'> No Dues </span>";
                    $duedates = " - ";
                } else {
                    $feedetails = formatCurrency($value['feedetails']);
                    
                    $totalDue += $value['feedetails'];
                    $duedates = $value['dueinstallments'];
                } ?>                  
                <tr>
                    <td> <a href="../files/student/studentFeeDetails.php?sid=<?php echo $value['studentid'] ?>&mode=edit "> <?php echo $sno; ?> </a></td>
                    <td> <a href="../files/student/studentFeeDetails.php?sid=<?php echo $value['studentid'] ?>&mode=edit "> <?php echo ucwords($value['scholarnumber']); ?> </a> </td>
                    <td> <a href="../files/student/studentFeeDetails.php?sid=<?php echo $value['studentid'] ?>&mode=edit "> <?php echo ucwords($studentname); ?></a></td>
                    <td> <a href="../files/student/studentFeeDetails.php?sid=<?php echo $value['studentid'] ?>&mode=edit "> <?php echo $classname?></a></td>
                    <td> <a href="../files/student/studentFeeDetails.php?sid=<?php echo $value['studentid'] ?>&mode=edit "> <?php echo $duedates ?></a></td>
                    <td> <a href="../files/student/studentFeeDetails.php?sid=<?php echo $value['studentid'] ?>&mode=edit "> <?php echo($feedetails)?></a></td>
                    
                </tr>
                <?php $sno++;
            } ?>
                <tr class="info">
                    <td colspan="5" align="right"><strong>Gross Total</strong></td>
                    <td><strong><?php echo formatCurrency($totalDue) ?></strong></td>
                    
                </tr>
            </tbody>
        </table>
        <!-- loader ------------>
                <script type="text/javascript">
                    function showDiv() {
                        document.getElementById('showloader').style.display = "block";
                    }
                </script>
                <div class="modal fade" id="myModal" role="dialog">
                    <center><div class="modal-dialog">
                            <div class="loader" id="showloader" style="display:none;"></div>
                            <p style="color: #ffffff"><strong class="h3">Processing...</strong></p>    
                        </div></center>
                </div>
                <!-- loader ends here ----- -->
        <?php 
        if (isset($_GET['search']) || !empty($_GET) && $reportArray != 0) {
            ?>
        <div class="col-lg-6" style="text-align: left; padding-left: 0px;">
            <a href="feeDueReportPdf.php?action=pdf&report=yes<?php echo $qryString; ?>"> 
                <input type="button" id="pdfreport"  name="pdfreport" class="btn btn-success" data-toggle="modal" data-target="#myModal" onclick="showDiv()" value=" View PDF"></a>
            <a href="feeDueReportPdf.php?action=xls&report=yes<?php echo $qryString; ?>"> 
            <input type="button" id="excelreport"  name="excelreport" class="btn btn-info"  value=" View EXCEL"></a>
        </div>
        <div class="col-lg-6" style="text-align: right; padding-right: 0px;">
            <?php echo getPagination($totalRows, ROW_PER_PAGE); ?>
        </div>
    </div>
        <?php 
        }
        } else {
            ?>
        <div class="alert alert-danger">
            <p> No record(s) found for the specified criteria. Please change the criteria and try again. ! </p>
        </div>
        <?php 
             $totalRows=0;
        }
    }?>
    
</div>

    <span class="clearfix">&nbsp;</span>
<?php 
require_once VIEW_FOOTER;

?>

