<?php
    /*
     * 360 - School Empowerment System.
     * Developer: Ankur Mishra (amishra@ebizneeds.com.au) | www.ebizneeds.com.au 
     * Page details here: Master for fees head and related processing 
     * Updates here: 
     */
  
    /* Assign the breadcrumb page name for current page */
   

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
</div>
 
<span class="clearfix"> &nbsp; <br></span>
<span class="clearfix"> &nbsp; <br></span>
 
<?php
if(isset($_GET['search']) && $_GET['search'] =='search' )
{
    showSelectStudent();
}
require VIEW_FOOTER;
  
function StudentDetails() 
{ 
  
     
    $details = cleanVar($_GET);
    $startPage = (int) (!isset($_REQUEST['page']) ? 0 :($_REQUEST['page']-1)*ROW_PER_PAGE);
 
    $sqlVar = "AND";
  
    $sql = "    SELECT t1.studentid, t1.scholarnumber, t1.firstname, t1.middlename, t1.lastname, t1.datecreated,
                t3.classdisplayname, t4.sectionname,t7.dateofissue,  t7.recieptno, t7.amount
                FROM `tblstudent` AS t1,
                    `tblstudentdetails` AS t2,
                    `tblclassmaster` AS t3, 
                    `tblsection` AS t4,
                    `tblclsecassoc`AS t5, 
                    `tblstudentacademichistory` AS t6,
                    `tblstudtc` AS t7
             
                    WHERE t1.studentid = t2.studentid 
                    AND t1.studentid = t6.studentid 
                    AND t6.clsecassocid = t5.clsecassocid 
                    AND t5.classid = t3.classid
                    AND t5.sectionid = t4.sectionid 
                    AND t1.studentid = t7.studentid
                    AND t1.tcissued = 1 
                    AND t1.status = 0
                   
          ";
    if (!empty($details['scholarnumber'])){$sql .= "$sqlVar t1.scholarnumber  LIKE '$details[scholarnumber]%'";}
    if (!empty($details['studentname'])){$sql .= "$sqlVar t1.firstname  LIKE '$details[studentname]%'"; }
    if (!empty($details['classid'])){$sql .= " $sqlVar t3.classid = '$details[classid]'"; }
    if (!empty($details['sectionid'])){$sql .= " $sqlVar t4.sectionid = '$details[sectionid]' ";}
    if (!empty($details['monthstart'])){$sql .= " $sqlVar t1.datecreated <= '$details[monthstart]' ";}
    if (!empty($details['monthend'])){$sql .= " $sqlVar t1.datecreated >= '$details[monthstart]' ";}
             
    $limit = "  LIMIT $startPage,".ROW_PER_PAGE;
    
    $finalSql = $sql."  ORDER BY t3.classid, t4.sectionid ASC  ".$limit;
    
    
    if($result = dbSelect($finalSql))
    {
        while($row = mysqli_fetch_assoc($result))
        {
            $studentdetails[] = $row; 
            $studentdetails['totalrows']= mysqli_num_rows(dbSelect($sql));
        }
        if(!empty($studentdetails))
        {
            return $studentdetails;
        }
        else{ return 0;} 
    }
}
    
    
function showSelectStudent()
{
    
    $details = StudentDetails(); 
    if($details == 0) 
    { 
        echo "<div class=\"container\"><div class=\"alert alert-danger\" role=\"alert\">
                    No record(s) found as per your criteria. Please change your criteria and try again.
                    </div></div>";
    }
    else
    {
        $StudentDataDisplay = " <div class=\"container\" >
                                    <div id=\"displaytable\">
                                        <table class=\"table table-bordered table-hover\"  >
                                            <thead>
                                                <tr >
                                                    <th>S.No</th>
                                                    <th>Scholar No</th>
                                                    <th>Name</th>
                                                    <th>Class</th>
                                                    <th>Date of Admission</th>
                                                    <th>Date of Issue</th>
                                                    <th>Amount</th>
                                                </tr>  
                                            </thead>
                              ";  
        $j = 1 ;$totalFee = 0;
        $totalStudents = $details['totalrows']; unset($details['totalrows']);
        foreach($details as $key => $value)
        { 
            $studentid = $value['studentid'];
            $admissionDate = date('d/m/Y',  strtotime($value['datecreated']));
            $dateofissue = date('d/m/Y',strtotime($value['dateofissue']));
            $tcFees = formatCurrency($value['amount']);
            $StudentDataDisplay .= "
                                    <tr>  
                                        <td class=\"col-md-1\">$j</td>
                                        <td class=\"col-md-2\">$value[scholarnumber]</td>
                                        <td class=\"col-md-2\">$value[firstname]  $value[middlename] $value[lastname]</td>
                                        <td class=\"col-md-2\">$value[classdisplayname] - $value[sectionname]</td>
                                        <td class=\"col-md-2\">$admissionDate</td>
                                        <td class=\"col-md-2\">$dateofissue</td>
                                        <td class=\"col-md-2\">$tcFees</td>
                                    </tr>
                                    ";
            $totalFee += $value['amount'];
            $j++; 
        }
        $totalFee = formatCurrency($totalFee);
        $StudentDataDisplay .= "
                    <tr class=\"info\">
                        <td colspan=\"6\" align=\"right\"><strong>Gross Total</strong></td>
                        <td><strong> $totalFee </strong></td>
                    
                </tr>
                 </table></div>";
        echo($StudentDataDisplay);
        echo "  
                <div class=\"col-lg-6\" style=\"text-align: left;padding: 0px; \">    
                    <a href=\"studentTcPDF.php?action=pdf&report=yes&".http_build_query($_GET)."\">
                        <button class=\"btn btn-primary\" name=\"pdfviewer\" id=\"pdfviewer\" > View PDF </button>
                    </a>
                    <a href=\"studentTcPDF.php?action=xls&report=yes&".http_build_query($_GET)."\">
                    <button class=\"btn btn-info\" name=\"excelview\" id=\"excelview\"> View Excel </button>
                    </a>
                </div> 
         
                <div class=\"col-lg-6\" style=\"text-align:right;padding: 0px; \">". getPagination($totalStudents, ROW_PER_PAGE)."</div> 
                </div>";
        
    }  
     
}