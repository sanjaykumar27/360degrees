<?php
/*
 * 360 - School Empowerment System.
 * Developer: Ankur Mishra (amishra@ebizneeds.com.au) | www.ebizneeds.com.au
 * Page details here:
 * Updates here:
 */
require_once "../config/config.php";
require_once DIR_FUNCTIONS;
require_once VIEW_HEADER;
SelectAcademicSession();
?>

<script type="text/javascript">
    function popUp(url, w, h) {
        var left = (screen.width / 2) - (w / 2);
        var top = (screen.height / 2) - (h / 2);
        var sw = (screen.width * .60);
        var sh = (screen.height * .60);
        window.open(url, 'pop-up', 'width=' + sw + ', height=' + sh + ', top=' + top + ', left=' + left);
    }
    function displayModalJS(studentid, sessionname, instituteabbrevation) {
        var strModal = '<div id="jsAmountAlert" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">'.concat(
                '<div class="modal-dialog"><div class="modal-content"><div class="modal-header"><strong>T.C FEES..!</strong></div>',
                '<div class="modal-body"><form class="form-inline" method="post" action="<?php echo PROCESS_FORM; ?>">' +
                '<input type="hidden" name="studentid" id="studentid" value="' + studentid + '">' +
                '<input type="hidden" name="sessionname" id="sessionname" value="' + sessionname + '">' +
                '<input type="hidden" name="instituteabbrevation" id="instituteabbrevation" value="' + instituteabbrevation + '">' +
                '<div class="form-group">' +
                '<label class="sr-only" for="exampleInputAmount">Amount (in dollars)</label>' +
                ' <div class="input-group">' +
                '<div class="input-group-addon">Enter TC Fees</div>' +
                '<input type="text" class="form-control" name="amount" id="amount" placeholder="Amount">' +
                '</div></div>' +
                '<button type="submit" class="btn btn-success">Pay Now</button>' +
                '</form></div><div class="modal-footer"><button type="button" id="removejsmodal" class="btn btn-danger" data-dismiss="modal">Close</button></div></div></div></div>');
        $(strModal).appendTo('body');
        $('#jsAmountAlert').modal('toggle');
    }
</script>
<div class="container">
    <div class="span10">
        <?php renderMsg(); ?>
        <form action="" method="GET" id="imform" name="myForm"> 
            <div class="row">
                <div class="col-lg-4 col-md-4">
                    <div class="input-group">
                        <span class="input-group-addon">Scholar No</span> 
                        <input type="text" class="form-control" name="scholarnumber" id="scholarnumber" tabindex="1"
                               value ="<?php echo submitFailFieldValue("scholarnumber"); ?>">
                        <span class="input-group-btn">
                            <button class="btn btn-default"  name="search" id="search">
                                <span class="glyphicon glyphicon-search" name="search" value='Search' > </span></a> 
                        </span>   
                    </div><!-- /input-group -->
                </div><!-- /.col-lg-4 -->

                <div class="col-lg-4 col-md-4">
                    <div class="input-group">
                        <span class="input-group-addon">Student First Name</span> 
                        <input type="text" class="form-control" name="studentname" id="studentname" tabindex="2"
                               value ="<?php echo submitFailFieldValue("studentname"); ?>">

                    </div><!-- /input-group -->
                </div><!-- /.col-lg-4 -->

                <div class="col-lg-4 col-md-4">
                    <div class="input-group">
                        <span class="input-group-addon">Parent First Name</span> 
                        <input type="text" class="form-control" name="parentname" id="parentname" tabindex="3"
                               value ="<?php echo submitFailFieldValue("parentname"); ?>">

                    </div><!-- /input-group -->
                </div><!-- /.col-lg-4 -->
            </div>     

            <span class='clearfix'>&nbsp;<br></span>

            <div class="row">
                <div class="col-lg-3 col-md-4">
                    <div class="input-group">
                        <span class="input-group-addon">Class</span>
                        <select name="classid" id="classid"  class="form-control" tabindex="4" >
                            <?php echo populateSelect("classname", submitFailFieldValue("class")); ?>
                        </select>
                    </div>
                </div> 

                <div class="col-lg-3 col-md-4">
                    <div class="input-group">
                        <span class="input-group-addon">Section</span>
                        <select name="sectionid" id="sectionid"  class="form-control" tabindex="5">
                            <?php echo populateSelect("sectionname", submitFailFieldValue("section")); ?>
                        </select>
                    </div>
                </div>


                <div class="col-lg-3 col-md-4">
                    <div class="input-group">
                        <span class="input-group-addon">Date From</span>
                        <input type="date" name="monthstart" id="monthstart" class="form-control" tabindex="7" 
                               max="<?php echo $_SESSION['sessionenddate'] ?>" min="<?php echo $_SESSION['sessionstartdate'] ?>" >
                    </div>
                </div>  
                
                <span class="clearfix visible-md"><br><br><br></span>
                <div class="col-lg-3 col-md-4">
                    <div class="input-group">
                        <span class="input-group-addon">Date To</span>
                        <input type="date" name="monthend" id="monthend" class="form-control" tabindex="8" 
                               max="<?php echo $_SESSION['sessionenddate'] ?>" min="<?php echo $_SESSION['sessionstartdate'] ?>">
                    </div>
                </div> 
            </div>

            <span class='clearfix'>&nbsp;<br></span>
            <div class="row"> 
                <div class="controls" align="right">
                    <div class='col-lg-6 col-md-8'>
                        <button name='reset' value="Reset" class="btn " tabindex="6">Cancel</button>
                        <button name='search' value="search" class="btn btn-success" tabindex="7">Search</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<span class="clearfix"> &nbsp;<br></span>
<?php
if (isset($_GET['search'])) {
    showSelectStudent();
}
require VIEW_FOOTER;
function studentDetails(){
    $details = cleanVar($_GET);
    $instsessassocid = $_SESSION['instsessassocid'];
    $sqlVar = "AND";
    $startPage = (int) (!isset($_REQUEST['page']) ? 0 : ($_REQUEST['page'] - 1) * ROW_PER_PAGE);
    $sql = "SELECT t1.studentid ,t1.scholarnumber, t1.firstname , t1.middlename ,t1.lastname, t1.datecreated,
        t3.classid, t3.classdisplayname,
        t4.sectionid, t4.sectionname, 
        t7.parentfirstname, t7.parentmiddlename, t7.parentlastname,
        t10.instituteabbrevation, 
        t11.sessionname
        
        FROM `tblstudent` AS t1,
        `tblclassmaster` AS t3,
        `tblsection` AS t4,
        `tblclsecassoc` AS  t5,
        `tblstudentacademichistory` AS t6,
        `tblparent` AS t7,
        `tbluserparentassociation` AS t8,
        `tblinstsessassoc` AS t9,
        `tblinstitute` AS t10,
        `tblacademicsession` AS t11
		
        WHERE t1.instsessassocid = $instsessassocid
        AND t9.instsessassocid = t1.instsessassocid
        AND t1.studentid = t6.studentid
        AND t5.classid = t3.classid
        AND t6.clsecassocid = t5.clsecassocid
        AND t5.sectionid = t4.sectionid
        AND t1.studentid = t8.studentid
        AND t7.parentid = t8.parentid
        AND t9.instituteid = t10.instituteid
        AND t9.academicsessionid = t11.academicsessionid 
	AND t1.deleted !=1
        ";
    if (!empty($details['studentid'])) {
        $sql .= "$sqlVar t1.studentid  = '$details[studentid]'";
        $sqlVar = "AND";
    }
    if (!empty($details['scholarnumber'])) {
        $sql .= "$sqlVar t1.scholarnumber  LIKE '$details[scholarnumber]%'";
        $sqlVar = "AND";
    }
    if (!empty($details['studentname'])) {
        $sql .= "$sqlVar t1.firstname  LIKE '$details[studentname]%'";
        $sqlVar = "AND";
    }
    if (!empty($details['classid'])) {
        $sql .= " $sqlVar t3.classid = '$details[classid]'";
        $sqlVar = "AND";
    }
    if (!empty($details['sectionid'])) {
        $sql .= " $sqlVar t4.sectionid = '$details[sectionid]' ";
    }
    if (!isset($_GET['tcfees'])) {
        $sql .= "AND t1.tcissued != 1";
    }
    $sql .= " GROUP BY t1.studentid ORDER BY t3.classid, t4.sectionid, t1.firstname ASC ";
    $finalSql = $sql . "   LIMIT " . $startPage . ',' . ROW_PER_PAGE;
    $result = dbSelect($finalSql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $studentdetails[] = $row;
        }
        $studentdetails['totalrows'] = mysqli_num_rows(dbSelect($sql));
        
        return $studentdetails;
    } else {
        return 0;
    }
}
function showSelectStudent(){
    $studentdetails = studentDetails();
    
    if ($studentdetails == 0) {
        echo "<div class=\"alert alert-danger\" role=\"alert\">
                    No record(s) found as per your criteria. Please change your criteria and try again.
                    </div>";
    } else {
       
        $totalStudents = $studentdetails['totalrows'];
        unset($studentdetails['totalrows']);
        $j = 1;
        
        $feedue = array();
        $strTable = " <div class=\"container\">
        <table class=\"table table-bordered table-hover \" id=\"displaytable\">
               <thead>
                    <tr >
                        <th>S.No</th>
                        <th>Scholar No </th>
                        <th>Student Name</th>
                        <th>Father Name</th>
                        <th>Class</th>
                        <th>Installments</th>
                        <th style=\"text-align: center\">More Options</th>
                        
                    </tr>
                </thead> 
        <tbody>";
 
        foreach ($studentdetails as $key => $detailsvalue) {
            
            $feeComponentdetails = feeComponentsSql($detailsvalue['studentid'], $detailsvalue['classid']);
            $installmentArray = createInstallmentArray($feeComponentdetails);
            $collectedfee = collectedfeeSql($detailsvalue['studentid']);
            $newinstallmentArray = array_diff_key($installmentArray, $collectedfee);
            
            if (empty($newinstallmentArray)) {
                $flag = "paid";
                $status = "<button class=\"btn btn-success btn-sm\" onClick=\"popUp('../files/fees/feeCollectionProcessing.php?studentid=" . $detailsvalue['studentid'] . "&pop-up=y&tc=y',1100,500);\">
            No Fees Dues</button>";
            }
            $duedates = "";
            foreach ($newinstallmentArray as $key => $value) {
                $duedates .= getInstallmentNumber($detailsvalue['classid'], $key) . ",";
            }
            
            if (!empty($duedates)) {
                $act = "0";
                $duedates = rtrim($duedates, ",");
                $duedates = "<span class=\"text-danger\" onClick=\"popUp('../files/fees/feeCollectionProcessing.php?studentid=" . $detailsvalue['studentid'] . "&pop-up=y&tc=y',1100,500);\">$duedates</span>";
                $link = "<button class=\"btn btn-sm\"  disabled=\"\" onClick=\"window.open('issueTCPDF.php?studentid=" . $detailsvalue['studentid'] . "')\">
                <span class=\"fa fa-file-text fa-lg\" aria-hidden=\"true\" data-toggle=\"tooltip\" title=\"Generate TC\"></span></button>";
                $action = "<button class=\"btn btn-sm\" id=\"cautionfeebtn\" disabled=\"\" >
                        <span class=\"fa fa-list-alt fa-lg\" aria-hidden=\"true\" data-toggle=\"tooltip\" title=\"Caution Money Voucher\"></span>
                        </button>";
                $tcFees = "<button class=\"btn btn-sm\" disabled=\"\" onClick=\"displayModalJS('$detailsvalue[studentid]','$detailsvalue[sessionname]', '$detailsvalue[instituteabbrevation]')\">
                 <span class=\"fa fa-inr fa-lg\" aria-hidden=\"true\" data-toggle=\"tooltip\" title=\"Pay TC Fees\"></span></button>";
            } else {
                $act = "disabled";
                $duedates = "<span class=\"text-success\">No Fees Dues</span>";
                $link = "<button class=\"btn btn-sm\" onClick=\"window.open('issueTCPDF.php?studentid=" . $detailsvalue['studentid'] . "')\">
                    <span class=\"fa fa-file-text fa-lg\" aria-hidden=\"true\" data-toggle=\"tooltip\" title=\"Generate TC\"></span></button>";
                $tcFees = "<button class=\"btn btn-sm\" onClick=\"popUp('../files/fees/feeCollectionProcessing.php?studentid=" . $detailsvalue['studentid'] . "&pop-up=y&tc=y',1100,500)\">
                 <span class=\"fa fa-inr fa-lg\" aria-hidden=\"true\" data-toggle=\"tooltip\" title=\"Pay TC Fees\"></span></button>";
                $action = "<a href=\"cautionmoneyvoucher.php?studentid=$detailsvalue[studentid]\">
                        <button class=\"btn btn-sm\" id=\"cautionfeebtn\">
                        <span class=\"fa fa-list-alt fa-lg\" aria-hidden=\"true\" data-toggle=\"tooltip\" title=\"Caution Money Voucher\"></span>
                        </button></a>";
                if (collectedTCFeeSql($detailsvalue['studentid'])) {
                    $action = "<a href=\"cautionmoneyvoucher.php?studentid=$detailsvalue[studentid]\"><button class=\"btn btn-sm\" id=\"cautionfeebtn\"  ></button></a>";
                    $tcFees = "<button class=\"btn btn-sm\" disabled=\"false\" onClick=\"displayModalJS('$detailsvalue[studentid]','$detailsvalue[sessionname]', '$detailsvalue[instituteabbrevation]')\">
                    <span class=\"fa fa-file-text fa-lg\" aria-hidden=\"true\" data-toggle=\"tooltip\" title=\"Generate TC\"></span></button>";
                }
            }
            $sectionName = strtoupper($detailsvalue['sectionname']);
            $admissiondate = date("d/m/Y", strtotime($detailsvalue['datecreated']));
            $studentname = ucwords(strtolower($detailsvalue['firstname'] . ' ' . $detailsvalue['middlename'] . ' ' . $detailsvalue['lastname']));
            $parentname = ucwords(strtolower($detailsvalue['parentfirstname'] . ' ' . $detailsvalue['parentmiddlename'] . ' ' . $detailsvalue['parentlastname']));
            $strTable .="
            <tr>  
            <td class=\"col-md-1\"> $j </a> </td>
            <td class=\"col-md-2\"> <a href=\"../files/student/studentFeeDetails.php?sid=$detailsvalue[studentid]&mode=edit\">$detailsvalue[scholarnumber]</a></td>
            <td class=\"col-md-2\"><a href=\"../files/student/studentFeeDetails.php?sid=$detailsvalue[studentid]&mode=edit\"> $studentname</a></td>
            <td class=\"col-md-3\"><a href=\"../files/student/studentParent.php?sid=$detailsvalue[studentid]&mode=edit\">$parentname</a></td>
            <td class=\"col-md-1\"><a href=\"../files/student/studentPersonal.php?sid=$detailsvalue[studentid]&mode=edit\">$detailsvalue[classdisplayname] - $sectionName</a></td>
            <td class=\"col-md-2\"> $duedates </td>
            <td class=\"col-md-2\" width=\"100\">
          <div class=\"hovereffect\">
                <a href=\"\" class=\"button\">More options</a>
                    <div class=\"overlay\">
                        <p class=\"icon-links\">
                             $link
                             $action
                             $tcFees
                        </p>
                   </div>
          </div>
          </td>
    </tr>";
            $j++;
        }
       
        $strTable .= " </tbody>
     </table>
    ";
        echo $strTable;
        echo "<div class=\"col-sm-8\" style=\"text-align: right; padding: 0px\">" . getPagination($totalStudents, ROW_PER_PAGE) . "</div>";
    }
}
?>

<?php
function createInstallmentArray($HtmlArray)
{
    $newOptions = array();
    $i = 0;
    $totalamount = array();
    foreach ($HtmlArray as $option) {
        $duedate = $option['duedate'];
        $feecomponents = $option['feecomponent'];
        $amount = $option['amount'];
        $newOptions[$duedate][$feecomponents] = $amount;
    }
    foreach ($newOptions as $key => $value) {
        $total = 0;
        $total = array_sum($value);
        $newOptions[$key]['totalamount'] = $total;
    }
    return $newOptions;
}
function collectedfeeSql($id)
{    
    
    $collectedfeedetails = array();
    $today = date('Y-m-d');
    $sql = " SELECT *
       FROM `tblfeecollection` AS t1,
      `tblfeecollectiondetail` AS t2,
      `tblfeeinstallmentdates` AS t3
       WHERE t1.studentid = $id
       AND t1.instsessassocid = $_SESSION[instsessassocid]
       AND t1.feecollectionid = t2.feecollectionid
       AND t2.feecollectiondetailid = t3.feecollectiondetailid
	   
      ";
   
    $result = dbSelect($sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $collectedfeedetails[$row['feeinstallment']] = $row;
    }
    
    return $collectedfeedetails;
}
function collectedTCFeeSql($studentid)
{
    $status = false;
    $sql = "SELECT `tcissued` FROM `tblstudtc` WHERE `studentid` = $studentid ";
    $result = dbSelect($sql);
    $row = mysqli_fetch_assoc($result);
    if ($row['tcissued'] != 0) {
        $status = true;
        return $status;
    }
    return $status;
}
function feeComponentsSql($studentid, $classid)
{  
    $currentDate = date('Y-m-d');
    $orderBy = "  ORDER BY t2.duedate ASC ";
    $rangeStartDate = $_SESSION['sessionstartdate'];
    $sql = " SELECT t1.feestructureid, t3.feecomponent, t2.feestructureid,
            t2.amount, t2.duedate, t2.isrefundable, t2.frequency 
                FROM `tblfeestructure` AS t1,
		`tblfeestructuredetails` AS t2,
		`tblfeecomponent` AS t3
		  
               WHERE t1.classid = $classid
               AND t1.feestructureid = t2.feestructureid
               AND t1.feecomponentid = t3.feecomponentid
               AND t2.duedate < '$currentDate'
               
           ";
    if (isset($_REQUEST['monthstart']) && !empty($_REQUEST['monthstart'])) {
        $rangeStartDate = $_REQUEST['monthstart'];
        $sql .= " HAVING t2.duedate >= ' $rangeStartDate '";
    }
    if (isset($_REQUEST['monthend']) && !empty($_REQUEST['monthend'])) {
        //set the start of the session date, taken from Session
        $sql .= " AND MONTH (t2.duedate) <= '" . date('m', strtotime($_REQUEST['monthend'])) . "'";
        $sql .= " AND YEAR (t2.duedate) <= '" . date('Y', strtotime($_REQUEST['monthend'])) . "'";
    }
    $result = dbSelect($sql);
    if (mysqli_num_rows($result)) {
        while ($row = mysqli_fetch_assoc($result)) {
            $feedetails[] = $row;
        }
        
        return($feedetails);
    }
    return 0;
}
function SelectAcademicSession()
{
    $instsessassocid = $_SESSION['instsessassocid'];
    $sql = "SELECT t1.sessionstartdate, t1.sessionenddate 
        FROM `tblacademicsession`  AS t1,
        `tblinstsessassoc` AS t2
    WHERE t2.instsessassocid = '$instsessassocid'
    AND t1.academicsessionid = t2.academicsessionid ";
    $result = dbSelect($sql);
    $row = mysqli_fetch_assoc($result);
    $_SESSION['sessionstartdate'] = $row['sessionstartdate'];
    $_SESSION['sessionenddate'] = $row['sessionenddate'];
}