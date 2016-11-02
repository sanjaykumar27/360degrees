<?php
/*
 * 360 - School Empowerment System.
 * Developer: Prateek Mathur (pmathur@ezizneeds.com.com) | www.ebizneeds.com.au
 * Page details here: HTML page for all reports relted pages
 * Updates here:
 */
//call the main config file, functions file and header
require_once "../config/config.php";
require_once DIR_FUNCTIONS;
require_once VIEW_HEADER;
?>
<script type="text/javascript">
    function Popup(data)
    {
        var mywindow = window.open('', 'Fee Due Report', 'height=400,width=600');
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
<?php
getSessionStartEndDate();
?>

<div class="container">
    <?php renderMsg(); ?>
    <div class="span10">
        <form action="" method="GET" id="imform" name="myForm" onsubmit="return validateForm()"> 
            <div class="row">
                <div class="col-lg-4">
                    <div class="input-group">
                        <span class="input-group-addon">Scholar No</span> 
                        <input type="text" class="form-control" name="scholarnumber" id="scholarnumber" tabindex="1"
                               value ="<?php echo submitFailFieldValue("scholarnumber"); ?>" >
                        <span class="input-group-btn">
                            <button class="btn btn-default"  name="search" id="search">
                                <span class="glyphicon glyphicon-search" name="search" value='Search' > </span></a> 
                        </span>   
                    </div><!-- /input-group -->
                </div><!-- /.col-lg-4 -->

                <div class="col-lg-4">
                    <div class="input-group">
                        <span class="input-group-addon">Student First Name</span> 
                        <input type="text" class="form-control" name="studentname" id="studentname" tabindex="2" 
                               value ="<?php echo submitFailFieldValue("studentname"); ?>">
                    </div><!-- /input-group -->

                </div><!-- /.col-lg-4 -->

                <div class="col-lg-4">
                    <div class="input-group">
                        <span class="input-group-addon">Parent First Name</span> 
                        <input type="text" class="form-control" name="parentname" id="parentname" tabindex="3"
                               value ="<?php echo submitFailFieldValue("parentname"); ?>">

                    </div><!-- /input-group -->
                </div><!-- /.col-lg-4 -->
            </div>     

            <span class='clearfix'>&nbsp;<br></span>

            <div class="row">
                <div class="col-lg-4">
                    <div class="input-group">
                        <span class="input-group-addon">Class</span>
                        <select name="classid" id="classid"  class="form-control" tabindex="4"  >
                            <?php echo populateSelect("classname", submitFailFieldValue("class")); ?>
                        </select>
                    </div>
                </div> 

                <div class="col-lg-4">
                    <div class="input-group">
                        <span class="input-group-addon">Section</span>
                        <select name="sectionid" id="sectionid"  class="form-control" tabindex="5">
                            <?php echo populateSelect("sectionname", submitFailFieldValue("section")); ?>
                        </select>
                    </div>
                </div>


                <div class="col-lg-4">
                    <div class="input-group">
                        <span class="input-group-addon">Payment Mode</span>
                        <select name="paymentmode" id="paymentmode"  class="form-control" tabindex="6">
                            <?php echo populateSelect("feecollectionmode", submitFailFieldValue("paymentmode")); ?>
                        </select>
                    </div>
                </div>
            </div>
            <span class="clearfix">&nbsp;</span>

            <div class="row">  
                <div class="col-lg-6">
                    <div class="input-group">
                        <span class="input-group-addon">Date From</span>
                        <input type="date" name="monthstart" id="monthstart" class="form-control" tabindex="7" 
                               max="<?php echo $_SESSION['sessionenddate'] ?>" min="<?php echo $_SESSION['sessionstartdate'] ?>" >
                    </div>
                </div>  
                <div class="col-lg-6">
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
                    <div class='col-lg-6'>
                        <button type='reset' value="Reset" class="btn " tabindex="6">Cancel</button>
                        <button name='search' value="search" class="btn btn-success" tabindex="7">Search</button>
                    </div>
                </div>
            </div>
            <span class="clearfix"><br></span>
        </form>
    </div>
</div>

<?php
if (isset($_GET['search'])) {
    showSelectStudent();
}

require VIEW_FOOTER;

function studentdetails()
{
    $details = cleanVar($_GET);
    $studentdetails = array();
    $startPage = (int) (!isset($_REQUEST['page']) ? 0 : ($_REQUEST['page'] - 1) * ROW_PER_PAGE);
    $instsessassocid = $_SESSION['instsessassocid'];
    $sqlVar = "AND";

    $sql = "SELECT t1.studentid ,t1.scholarnumber, t1.firstname , t1.middlename ,t1.lastname,t3.classid, t4.sectionid,
        t3.classdisplayname, t4.sectionname, t1.datecreated,
        t7.parentfirstname, t7.parentmiddlename, t7.parentlastname,
        t10.instituteabbrevation , t11.sessionname,
       t15.tblid, t15.totaloriginalfees, t15.totaladjustedfees, t15.remarks
          
        FROM
        
        `tblstudent` AS t1,
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
        
        `tblfeeadjusted` AS t15
          
        WHERE
        
        t1.instsessassocid = $instsessassocid
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
        AND t15.feecollectionid = t12.feecollectionid
        
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

    $finalSql = $sql . "GROUP BY t15.tblid, t1.firstname ASC  LIMIT " . $startPage . ',' . ROW_PER_PAGE;

    $result = dbSelect($finalSql);
    //echoThis($finalSql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $studentdetails[] = $row;
        }
        $studentdetails['totalrows'] = mysqli_num_rows(dbSelect($sql . "GROUP BY t15.tblid"));

        return $studentdetails;
    } else {
        return 0;
    }
}

function getSessionStartEndDate()
{
    $sql = "SELECT  sessionstartdate, sessionenddate 
        
            FROM tblacademicsession as t1, 
            tblinstsessassoc as t2 
            
            WHERE t1.academicsessionid = t2.academicsessionid 
            AND t2.instsessassocid = $_SESSION[instsessassocid]";

    $result = dbSelect($sql);
    $row = mysqli_fetch_assoc($result);

    if (!isset($_SESSION['sessionstartdate']) && empty($_SESSION['sessionstartdate'])) {
        $_SESSION['sessionstartdate'] = $row['sessionstartdate'];
        $_SESSION['sessionenddate'] = $row['sessionenddate'];
    }
}

function showSelectStudent()
{
    $studentdetails = studentdetails();
    
    $qryString = '';
    if (isset($_GET) && !empty($_GET)) {
        $qryString = '&' . http_build_query(cleanvar($_GET));
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
                        <th>Class</th>
                        <th>Original Fees</th>
                        <th>Adjusted Amount</th>
                        <th>Reason</th>
                        
                    </tr>
                </thead> 
        <tbody>";
        $j = 1;

        $totalStudents = $studentdetails['totalrows'];
        unset($studentdetails['totalrows']);

        $totalOriginalfee = $totalCollectedfee = $diffamount = 0;
      
        
        foreach ($studentdetails as $key => $detailsvalue) {
            
            $sectionName = strtoupper($detailsvalue['sectionname']);
            $totalOriginalfee += $detailsvalue['totaloriginalfees'];
            $totalCollectedfee += $detailsvalue['totaladjustedfees'];

            $toggleButton = "<button class=\"btn btn-primary\" id=\"Showdiv\" onClick=\"JavaScript: showHideDiv('displaystructure$j')\">Show</button>";

            $modalShow = "";

            $strTable .="
            <tr>  
            
                <td > $j</td>
                <td>
                <a href=\"../files/student/studentFeeDetails.php?sid=" . $detailsvalue['studentid'] . "&mode=edit\"> $detailsvalue[scholarnumber]</a></td>
                <td  \">
                <a href=\"../files/student/studentFeeDetails.php?sid=" . $detailsvalue['studentid'] . "&mode=edit\"> $detailsvalue[firstname]  $detailsvalue[middlename] $detailsvalue[lastname]</a> </td>
                <td >$detailsvalue[classdisplayname] - $sectionName </td>
                <td >".formatCurrency($detailsvalue['totaloriginalfees'])."</td>
                   
                <td >".formatCurrency($detailsvalue['totaladjustedfees'])."</td>
                <td class=\"col-md-2\">$detailsvalue[remarks]</td>
             ";



            $j++;
        }
        $diffamount = $totalOriginalfee - $totalCollectedfee;

        $strTable .= "<tr style=\"background-color: #ccc;\"><td></td><td></td><td></td>"
                . "<td ></td>"
                . "<td>Total :<b>".formatCurrency($totalOriginalfee)."</b></td>"
                . "<td >Total : <b>".formatCurrency($totalCollectedfee)."</b></td>"
                . "<td>Total Differance:  <b>".formatCurrency($diffamount)."</b></td>"
                . "</tbody></table>";
        echo $strTable;

        echo "
        <div class=\"col-lg-3\" style=\"text-align: left;padding: 0px; \">    
             <a href=\"adjustedfeeReportpdf.php?action=pdf$qryString\"> 
                <input type=\"button\" id=\"pdfreport\"  name=\"pdfreport\" class=\"btn btn-success\"  value=\" View PDF\"></a>
                <a href=\"adjustedfeeReportpdf.php?action=xls$qryString\"> 
                <input type=\"button\" id=\"excelreport\"  name=\"excelreport\" class=\"btn btn-info\"  value=\" View EXCEL\"></a>
        </div> 
         <div class=\"col-lg-4\"></div> 
        <div class=\"col-lg-6\" style=\"text-align: right;padding: 0px; \">" . getPagination($totalStudents, ROW_PER_PAGE) . "</div> 
             
</div>";
    }
}
?>