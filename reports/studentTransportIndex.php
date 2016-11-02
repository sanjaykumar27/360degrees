<?php
/*
 * 360 - School Empowerment System.
 * Developer: Ankur Mishra (amishra@ebizneeds.com.au) | www.ebizneeds.com.au
 * Page details here: Master for fees head and related processing
 * Updates here:
 */


//call the main config file, functions file and header
require_once "../config/config.php";
require_once DIR_FUNCTIONS;
require_once VIEW_HEADER;
?>
<script type='text/javascript' src='../asset/js/gs_sortable.js'></script>
<script type="text/javascript">
 $('#displayresult').modal('show');

 // This  code is used  for sorting the data inside the table using TSORT API...//
 
var TSort_Data = new Array ('displaytable', 'h', 'h', 'h', 'h', 'h', 'h');
tsRegister();


function PrintElem(elem){ 
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
     include_once "searchstudentreportHTML.php" ?>
</div>
<span class="clearfix">&nbsp;<br></span>
<span class="clearfix">&nbsp;<br></span>


<?php
if (isset($_GET['search'])) {
         showSelectStudent();
     }
require VIEW_FOOTER;
 
function StudentDetails()
{
    $details = cleanVar($_GET);
    $startPage = (int) (!isset($_REQUEST['page']) ? 0 :($_REQUEST['page']-1)*ROW_PER_PAGE);
    $sqlVar = "AND";
 
    $sql = "  SELECT t1.studentid, t1.scholarnumber, t1.firstname, t1.middlename, t1.lastname,
                t3.classdisplayname, t4.sectionname, t5.pickuppointname, t5.amount
            FROM `tblstudent` AS t1,
            `tblstudentdetails` AS t2,
            `tblclassmaster` AS t3,
            `tblsection` AS t4,
            `tblpickuppoint` As t5,
            `tblclsecassoc`AS t8,
            `tblstudentacademichistory` AS t9
		  
            WHERE t1.instsessassocid = '$_SESSION[instsessassocid]'
            AND t1.studentid = t2.studentid
            AND t1.studentid = t9.studentid
            AND t9.clsecassocid = t8.clsecassocid
            AND t8.classid = t3.classid
            AND t8.sectionid = t4.sectionid
            AND t2.conveyancerequired = 1
            AND t2.pickuppointid = t5.pickuppointid
            AND t5.status = 1
            AND t5.deleted != 1
           	  
          ";
    if (!empty($details['scholarnumber'])) {
        $sql .= "$sqlVar t1.scholarnumber  LIKE '$details[scholarnumber]%'";
    }
    if (!empty($details['studentname'])) {
        $sql .= "$sqlVar t1.firstname  LIKE '$details[studentname]%'";
    }
    if (!empty($details['classid'])) {
        $sql .= " $sqlVar t3.classid = '$details[classid]'";
    }
    if (!empty($details['sectionid'])) {
        $sql .= " $sqlVar t4.sectionid = '$details[sectionid]' ";
    }
            
    $limit = "  LIMIT $startPage,".ROW_PER_PAGE;
    $finalSql = $sql."  ORDER BY t3.classid, t4.sectionid ASC  ".$limit;
    if ($result = dbSelect($finalSql)) {
        while ($row = mysqli_fetch_assoc($result)) {
            $studentdetails[] = $row;
            $studentdetails['totalrows']= mysqli_num_rows(dbSelect($sql));
        }
        if (!empty($studentdetails)) {
            return $studentdetails;
        } else {
            return 0;
        }
    }
}
   
   
function showSelectStudent()
{
    $details = StudentDetails();
    if ($details == 0) {
        echo "<div class=\"container\"><div class=\"alert alert-danger\" role=\"alert\">
                    No record(s) found as per your criteria. Please change your criteria and try again.
                    </div></div>";
    } else {
        $StudentDataDisplay = "
       
    <div class=\"container\" >
    <div id=\"displaytable\">
    <table class=\"table table-hover  table-bordered \"  >
        <thead>
            <tr>
                <th>S.No</th>
		<th>Scholar No</th>
	        <th>Name</th>
	        <th>Class</th>
                <th>Pick Up Point</th>
                <th>Amount</th>
            </tr>  
        </thead>
    ";
        $j = 1 ;
        $totalStudents = $details['totalrows'];
        unset($details['totalrows']);
        foreach ($details as $key => $value) {
            $studentid = $value['studentid'];
            $studentname = ucwords(strtolower($value['firstname'].' '.  $value['middlename'].' '. $value['lastname']));
            $StudentDataDisplay .= "
        <tr>  
            <td class=\"col-md-1\">$j</td>
            <td class=\"col-md-2\"><a href=\"../files/student/studentFeeDetails.php?sid=$value[studentid]&mode=edit\">$value[scholarnumber]</a></td>
            <td class=\"col-md-2\"><a href=\"../files/student/studentFeeDetails.php?sid=$value[studentid]&mode=edit\">$studentname</a></td>
            <td class=\"col-md-2\"><a href=\"../files/student/studentFeeDetails.php?sid=$value[studentid]&mode=edit\">$value[classdisplayname] - $value[sectionname]</a></td>
            <td class=\"col-md-2\"><a href=\"../files/student/studentFeeDetails.php?sid=$value[studentid]&mode=edit\">$value[pickuppointname]</a></td>
            <td class=\"col-md-2\"><a href=\"../files/student/studentFeeDetails.php?sid=$value[studentid]&mode=edit\">" . formatCurrency($value['amount']). "</a></td>
         </tr>
            ";
            $j++;
        }
        $StudentDataDisplay .= " </table></div>";
        echo($StudentDataDisplay);
        echo "
        <div class=\"col-lg-6\" style=\"text-align: left;padding: 0px; \">    
            <a href=\"studentTransportPDF.php?action=pdf&report=yes&".http_build_query($_GET)."\">
                <button class=\"btn btn-primary\" name=\"pdfviewer\" id=\"pdfviewer\" > View PDF </button>
            </a>
            <a href=\"studentTransportPDF.php?action=xls&report=yes&".http_build_query($_GET)."\">
                <button class=\"btn btn-info\" name=\"excelview\" id=\"excelview\"> View Excel </button>
            </a>
        </div> 
        
        <div class=\"col-lg-6\" style=\"text-align: right;padding: 0px; \">". getPagination($totalStudents, ROW_PER_PAGE)."</div> 
           
</div>";
    }
}
