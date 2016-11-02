<?php
/*
* 360 - School Empowerment System.
* Developer: Ankur Mishra (amishra@ebizneeds.com.au) | www.ebizneeds.com.au
* Page details here: Fee Refund and associated Processing here
* Updates here:
*/

require_once "../config/config.php";
require_once DIR_FUNCTIONS;
require_once VIEW_HEADER;
  
?>
  
<script type="text/javascript">
  
    function PrintElem(elem)
    {
        PrintPopup($('#'+elem).html());
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
  
function popUp(url,w,h) {
    var left = (screen.width/2)-(w/2);
    var top = (screen.height/2)-(h/2);
    var sw = (screen.width*.60);
    var sh = (screen.height*.60);
      
    window.open(url,'pop-up','width='+sw+', height='+sh+', top=' +top+', left='+left);
  
}
function showHideDiv(divName){ 
    $('#'+ divName).toggle();
}

</script>
  
<div class="container">
    <?php renderMsg();
    include_once "searchstudentreportHTML.php"; ?>
</div>
  
  <span class="clearfix"> &nbsp;<br></span>
  <span class="clearfix"> &nbsp;<br></span>
  
<?php
    if (isset($_GET['search'])) {
        showSelectStudent();
    }
      
require VIEW_FOOTER;
  
  
function studentdetails()
{
    $details = cleanVar($_GET);
    $studentdetails = array();
    $startPage = (int) (!isset($_REQUEST['page']) ? 0 :($_REQUEST['page']-1)*ROW_PER_PAGE);
    $instsessassocid = $_SESSION['instsessassocid'];
    $sqlVar = "AND";
    
    $sql = "SELECT t1.studentid ,t1.scholarnumber, t1.firstname , t1.middlename ,t1.lastname,t3.classid, 
                t4.sectionid,t3.classdisplayname, t4.sectionname, t1.datecreated,
                t7.parentfirstname, t7.parentmiddlename, t7.parentlastname,
                t10.instituteabbrevation , t11.sessionname
          
        FROM `tblstudent` AS t1,
        `tblclassmaster` AS t3,
        `tblsection` AS t4,
        `tblclsecassoc` AS  t5,
        `tblstudentacademichistory` AS t6,
        `tblparent` AS t7,
        `tbluserparentassociation` AS t8,
        `tblinstsessassoc` AS t9,
        `tblinstitute` AS t10,
        `tblacademicsession` AS t11,
        `tblfeecollection` AS t12,
        `tblfeecollectiondetail` AS t13,
        `tblfeerefund` AS t14
          
        WHERE t1.instsessassocid = $instsessassocid
        AND t1.studentid = t6.studentid
        AND t6.clsecassocid = t5.clsecassocid
        AND t5.classid = t3.classid
        AND t5.sectionid = t4.sectionid
        AND t1.studentid = t8.studentid
        AND t7.parentid = t8.parentid
        AND t1.instsessassocid = t9.instsessassocid
        AND t9.instituteid = t10.instituteid
        AND t11.academicsessionid =  t9.academicsessionid
        AND t1.studentid = t12.studentid
        AND t12.feecollectionid = t13.feecollectionid
        AND t13.feecollectiondetailid = t14.feecollectiondetailid
        
          
               ";
    if (!empty($details['scholarnumber'])) {
        $sql .= "$sqlVar t1.scholarnumber  LIKE '$details[scholarnumber]%'";
    }
    if (!empty($details['firstname'])) {
        $sql .= " $sqlVar t1.firstname LIKE '$details[firstname]%'";
    }
    if (!empty($details['classid'])) {
        $sql .= " $sqlVar   t5.classid = '$details[classid]' ";
    }
    if (!empty($details['sectionid'])) {
        $sql .= " $sqlVar   t5.sectionid = '$details[sectionid]' ";
    }
    if (!empty($details['monthstart'])) {
        $sql .= " $sqlVar   t15.feeinstallment >= '$details[monthstart]' ";
    }
    if (!empty($details['monthend'])) {
        $sql .= " $sqlVar   t15.feeinstallment <= '$details[monthend]' ";
    }
  
  
    $finalSql = $sql ." GROUP BY t1.studentid ORDER BY t3.classid, t4.sectionid, t1.firstname ASC  LIMIT ".$startPage.','.ROW_PER_PAGE;
        
    $result = dbSelect($finalSql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $studentdetails[] = $row;
        }
        $studentdetails['totalrows'] = mysqli_num_rows(dbSelect($sql."GROUP BY t1.studentid"));
        return $studentdetails;
    } else {
        return 0;
    }
}
  
  
function showSelectStudent()
{
    $studentdetails = studentdetails();
    
    $qryString='';
    if (isset($_GET) && !empty($_GET)) {
        $qryString= '&'.http_build_query(cleanvar($_GET));
    }
             
    if ($studentdetails == 0) {
        echo "<div class=\"container\"><div class=\"alert alert-danger\" role=\"alert\">
                    No record(s) found as per your criteria. Please change your criteria and try again.
                    </div></div>";
    } else {
        $strTable = "<div class=\"container\" >
              
             <table class=\"table table-bordered table-hover \" >
                <thead> 
                    <tr >
                        <th>S.No</th>
                        <th>Scholar No</th>
                        <th>Student Name</th>
                        <th>Father Name</th>
                        <th>Class</th>
                        <th> Refund Details</th>
                    </tr>
                </thead> 
        <tbody>";
        $j = 1;
        $totalStudents = $studentdetails['totalrows'];
        unset($studentdetails['totalrows']);
        foreach ($studentdetails as $key => $detailsvalue) {
            $sectionName= strtoupper($detailsvalue['sectionname']);
            $collectedfee = Collectedfee($detailsvalue['studentid']);
            $toggleButton = "<button class=\"btn btn-primary\" id=\"Showdiv\" onClick=\"JavaScript: showHideDiv('displaystructure$j')\">Show</button>";
            
             
            if (empty($collectedfee)) {
                continue;
            }
            $modalShow = "";
            $strTable .="
            <tr>  
                <td class=\"col-md-1\"> $j  </td>
                <td class=\"col-md-2\" \"><a href=\"javascript:(void)\" onClick=\"popUp('../files/student/studentFeeDetails.php?sid=".$detailsvalue['studentid']."&mode=edit&pop-up=y',1100,500);\"> $detailsvalue[scholarnumber]</a></td>
                <td class=\"col-md-2\" \"><a href=\"javascript:(void)\" onClick=\"popUp('../files/student/studentFeeDetails.php?sid=".$detailsvalue['studentid']."&mode=edit&pop-up=y',1100,500);\" > $detailsvalue[firstname]  $detailsvalue[middlename] $detailsvalue[lastname]</a> </td>
                <td class=\"col-md-3\"> <a href=\"../files/student/studentParent.php?sid=".$detailsvalue['studentid']."&mode=edit\" > $detailsvalue[parentfirstname]  $detailsvalue[parentmiddlename] $detailsvalue[parentlastname] </a> </td>
                <td class=\"col-md-2\"  > $detailsvalue[classdisplayname] - $sectionName </td>
                <td> $toggleButton
             ";
            if (!empty($collectedfee)) {
                $strTable .=" <tr id=\"displaystructure$j\" style=\"display:none;\"><td colspan=\"6\">
                    <div id=\"displaytable\">
                <table  class=\"table table-bordered table-hover\"  >
                    <thead>
                        <tr >
                            <th> Amount
                            <th> Collected On </th>
                            <th> Fee Receipt No </th>
                            <th> Refund Reciept No </th>
                        </tr>
                    </thead>
            ";
              
            
                foreach ($collectedfee as $key => $value) {
                    $collectionDate = date("d-m-Y", strtotime($collectedfee[$key]['datecreated']));
              
                    $strTable .="
            <tr>
                <td>Rs $value[refundAmount]</td>
                <td> $collectionDate</td>
                <td> $value[receiptid]</td>
                <td> $value[feerefundrecieptno]</td>           
            </tr>
              
            ";
                }
          
                $strTable .="</table></div></td></tr>";
            }
      
            $j++;
        }
          
        $strTable .= "</td></tbody></table>";
        echo $strTable;
              
        echo "
        <div class=\"col-lg-6\" style=\"text-align: left;padding: 0px; \">    
            <a href=\"feeRefundReportPDF.php?action=pdf&report=yes&".http_build_query($_GET)."\">
                <button class=\"btn btn-primary\" name=\"pdfviewer\" id=\"pdfviewer\" > View PDF </button>
            </a>
            <a href=\"feeRefundReportPDF.php?action=xls&report=yes&".http_build_query($_GET)."\">
                <button class=\"btn btn-info\" name=\"excelview\" id=\"excelview\"> View Excel </button>
            </a>
        </div> 
          
        <div class=\"col-lg-6\" style=\"text-align: right;padding: 0px; \">". getPagination($totalStudents, ROW_PER_PAGE)."</div> 
             
</div>";
    }
}
  
function Collectedfee($studentid)
{
    $feedetails = array();
    $sql = " SELECT t1.feecollectionid, t1.studentid, t1.receiptid,
            t2.feecollectiondetailid, SUM(t2.feeinstallmentamount) as refundAmount,
            t3.feerefundrecieptno, t3.datecreated

            FROM `tblfeecollection` AS t1,
            `tblfeecollectiondetail` AS t2,
            `tblfeerefund` AS t3

            WHERE t1.studentid = '$studentid'
            AND t1.feecollectionid = t2.feecollectionid
            AND t2.feecollectiondetailid = t3.feecollectiondetailid
            ";
   
    $result = dbSelect($sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $feedetails[] =  $row;
        }
        return $feedetails;
    } else {
        return 0;
    }
}
