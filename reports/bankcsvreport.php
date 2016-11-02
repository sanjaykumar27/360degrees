<?php
    /*
     * 360 - School Empowerment System.
     * Developer: Ankur Mishra (amishra@ebizneeds.com.au) | www.ebizneeds.com.au
     * Page details here: Repoting page for fees details obtained from bank
     * Updates here:
     */
     
    //call the main config file, functions file and header
    require_once "../config/config.php";
    require_once DIR_FUNCTIONS;
    require_once VIEW_HEADER;
    
    if (isset($_GET) && !empty($_GET)) {
        $qryString= '&'.http_build_query(cleanvar($_GET));
    } else {
        $qryString='';
    }
?>
<script type='text/javascript' src='../asset/js/gs_sortable.js'></script>
<script type="text/javascript">
 
    $('#displayresult').modal('show');
    // This  code is used  for sorting the data inside the table using TSORT API...//
    var TSort_Data = new Array ('displaytable', 'h', 'h', 'h', 'h', 'h', 'h','h','h','h');
    tsRegister();
 
 
    function PrintElem(elem)
    { 
        PrintPopup($("#"+elem).html());
    }
    function PrintPopup(data) 
    {   
        var mywindow = window.open('', 'Fee Refund Report', 'height=400,width=600');
        mywindow.document.write('<html><head><title>Fee Due Report</title>');

        mywindow.document.write('</head><body >');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');

        mywindow.document.close(); // necessary for IE >= 10
        mywindow.focus(); // necessary for IE >= 10

        mywindow.print();
        mywindow.close();

        return true;
    }
</script>
 
<div class="container">
   
      <?php renderMsg();
        include_once "searchstudentreportHTML.php";
      ?>
    <span class="clearfix"> &nbsp; <br></span>
        
         <?php
        if (isset($_GET) && !empty($_GET)) {
            $studentFeeDetails =  getStudentFeeDetails("feeinstallments");
           //$studentOtherFeeDetails =  getStudentFeeDetails("OtherFees");

           $totalrows = $studentFeeDetails['totalrows'];
            unset($studentFeeDetails['totalrows']);
            if ($studentFeeDetails == 0) {
                echo "<div class=\"container\"><div class=\"alert alert-danger\" role=\"alert\">
                    No record(s) found as per your criteria. Please change your criteria and try again.
                    </div></div>";
            } else {
                ?>
        <span class="clearfix">&nbsp; <br> </span>
        <div id='displaytable'>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Student-Name</th>
                    <th>Father-Name</th>
                    <th>Class</th>
                    <th>Due Date</th>
                    <th>Due(I.N.R)</th>
                    <th> Paid(I.N.R)</th>
                    <th>Payment Date</th>
                    
                </tr>
            
            </thead>
            <?php 
                $j = 1;
                //echoThis($studentFeeDetails);die;
                foreach ($studentFeeDetails as $key => $value) {
                    $totalAmount = $value['amount'] ;
                    $tran_amt =  0 ;
                    if(!empty($value['tran_amount'])){
                        $tran_amt = $value['tran_amount'];
                    }
                    ?>
                    <tr>
                        <td><?php echo $j ?></td>
                        <td><a href="../files/student/studentFeeDetails.php?sid=<?php echo $value['studentid'] ?>"><?php echo ucfirst($value['firstname']. " ". $value['middlename']. " " .$value['lastname']) ?></a></td>
                        <td><a href="../files/student/studentFeeDetails.php?sid=<?php echo $value['studentid'] ?>"><?php echo ucfirst($value['father_name']) ?></a></td>
                        <td><a href="../files/student/studentFeeDetails.php?sid=<?php echo $value['studentid'] ?>"><?php echo($value['classname']. "- ".$value['sectionname']) ?></a></td>
                        <td><a href="../files/student/studentFeeDetails.php?sid=<?php echo $value['studentid'] ?>"><?php echo date("d/m/Y", strtotime($value['payment_due_date'])) ?></a></td>
                        <td><a href="../files/student/studentFeeDetails.php?sid=<?php echo $value['studentid'] ?>"><?php echo(formatCurrency($value['amount'])) ?></a></td>
                        <td><a href="../files/student/studentFeeDetails.php?sid=<?php echo $value['studentid'] ?>"><?php echo(formatCurrency($tran_amt)) ?></a></td>
                        <td><a href="../files/student/studentFeeDetails.php?sid=<?php echo $value['studentid'] ?>"><?php echo date("d/m/Y", strtotime($value['datecreated'])) ?></a></td>
                         
                        
                    </tr>
            
            <?php $j++;
                } ?>
            
        </table> 
        </div>
        <div class="col-lg-12" style="text-align:right;padding: 0px; "><?php getPagination($totalrows, ROW_PER_PAGE)?> </div> 
        <div class="col-lg-6" style="text-align: left; padding-left: 0px;">
            <a href="bankcsvPDF.php?action=pdf<?php echo $qryString; ?>"> 
            <input type="button" id="pdfreport"  name="pdfreport" class="btn btn-success"  value=" View PDF"></a>
            <a href="bankcsvPDF.php?action=xls<?php echo $qryString; ?>"> 
            <input type="button" id="excelreport"  name="excelreport" class="btn btn-info"  value=" View EXCEL"></a>
        </div>
    </div>
       <?php 
            }
        }
  ?>
     
</div>
 
 
 
<?php

    require VIEW_FOOTER;
    
  function getStudentFeeDetails($type)
  {
      $intsessassocid = $_SESSION['instsessassocid'];
      $startPage = (int) (!isset($_REQUEST['page']) ? 0 :($_REQUEST['page']-1)*ROW_PER_PAGE);
      $details = cleanVar($_REQUEST);
      
      switch ($type) {
         case "feeinstallments":
             $sql = "SELECT t1.studentid , t1.feecollectionid,  SUM(t2.feeinstallmentamount) AS amount, 
              t3.father_name, t3.datecreated, t4.firstname, t4.middlename, t4.lastname, t4.scholarnumber,
              t7.classname, t8.sectionname, t3.payment_due_date, t3.tran_amount

                FROM `tblfeecollection` AS t1,
                `tblfeecollectiondetail` AS t2,
                `tblbanktransdetails` AS t3,
                `tblstudent` AS t4,
                `tblstudentacademichistory` AS t5,
                `tblclsecassoc` AS t6,
                `tblclassmaster` AS t7,
                `tblsection` AS t8


                WHERE t1.feecollectionid = t2.feecollectionid
                AND t1.feecollectionid = t3.feecollectionid
                AND t1.studentid = t4.studentid
                AND t4.studentid = t5.studentid
                AND t5.clsecassocid = t6.clsecassocid
                AND t6.classid = t7.classid
                AND t6.sectionid = t8.sectionid
                 
            ";
             break;
         
            case "OtherFees":
            $sql = "SELECT t1.studentid , t1.feecollectionid,  SUM(t2.feeinstallmentamount) AS feeOtherAmountCollected, 
              t3.father_name, t3.datecreated, t4.firstname, t4.middlename, t4.lastname, t4.scholarnumber,
              t7.classname, t8.sectionname, t9.feeinstallmentid

                FROM `tblfeecollection` AS t1,
                `tblfeecollectiondetail` AS t2,
                `tblbanktransdetails` AS t3,
                `tblstudent` AS t4,
                `tblstudentacademichistory` AS t5,
                `tblclsecassoc` AS t6,
                `tblclassmaster` AS t7,
                `tblsection` AS t8,
                `tblfeepenaltydetails` AS t9


                WHERE t1.feecollectionid = t2.feecollectionid
                AND t1.feecollectionid = t3.feecollectionid
                AND t1.studentid = t4.studentid
                AND t4.studentid = t5.studentid
                AND t5.clsecassocid = t6.clsecassocid
                AND t6.classid = t7.classid
                AND t6.sectionid = t8.sectionid
                AND t2.feecollectiondetailid = t9.feecollectiondetailid

                 ";
                break;
     }
      
    
    
      if (!empty($details['scholarnumber'])) {
          $sql .= " AND t4.scholar_no = '$details[scholarnumber]'";
      }
      if (!empty($details['studentname'])) {
          $sql .= " AND   t4.firstname LIKE '$details[studentname]%' ";
      }
      if (!empty($details['classid'])) {
          $sql .= " AND   t7.classid = '$details[classid]' ";
      }
            
      if (!empty($details['sectionid'])) {
          $sql .= " AND   t8.sectionid = '$details[sectionid]' ";
      }
            
      if (!empty($details['paymentmode'])) {
          $sql .= " AND   t1.payer_opted_mode = '$details[paymentmode]' ";
      }
            
            
      $limit = "  GROUP BY t1.studentid   LIMIT $startPage,".ROW_PER_PAGE;
      $finalSql = $sql. $limit;
           
      $result = dbSelect($finalSql);
      if ($result && mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
              $studentDetails[] = $row;
          }
          $studentDetails['totalrows']= mysqli_num_rows(dbSelect($sql));
          return $studentDetails;
      } else {
          return 0;
      }
  }
