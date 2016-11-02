<?php 
 
/*
* 360 - School Empowerment System.
* Developer: Prateek Mathur (pmathur@ebizneeds.com) | www.ebizneeds.com.au
* Page details here: Generates caution Money Voucher In Word file format(.doc, .docx)
* Updates here:
*/
require_once "../config/config.php";
require_once DIR_FUNCTIONS;
require_once('../html2pdf/html2pdf.class.php');
?>
<style>
   body {
    background-color: #d0e4fe;
    font-family: shree-english;
}
p{
    font-family: shree-english;
}
    
</style>

<?php
$content = ob_get_clean();
$content=  showSelectStudent();
$html2pdf = new HTML2PDF('P', 'A4', 'en');
$html2pdf->WriteHTML($content); // in $content you put your content to have in pdf
$html2pdf->Output('fee_collection_report.pdf');

function studentDetails()
{
    $details = cleanVar($_GET);
    $instsessassocid = $_SESSION['instsessassocid'];
    $sqlVar = "AND";
    $sql = "SELECT t1.studentid ,t1.scholarnumber, t1.firstname , t1.middlename ,t1.lastname,t3.classid, t4.sectionid,
        t3.classdisplayname, t4.sectionname, t1.datecreated,
        t7.parentfirstname, t7.parentmiddlename, t7.parentlastname,
        t10.instituteabbrevation, t11.sessionname, t13.amount
        
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
        `tblfeestructure` AS t12,
        `tblfeestructuredetails` AS t13,
        `tblfeecomponent` AS t14
		
        WHERE t1.instsessassocid = $instsessassocid
        AND t9.instsessassocid = t1.instsessassocid
        AND t1.studentid = t6.studentid
        AND t6.clsecassocid = t5.clsecassocid
        AND t5.classid = t3.classid
        AND t5.sectionid = t4.sectionid
        AND t1.studentid = t8.studentid
        AND t7.parentid = t8.parentid
        AND t9.instituteid = t10.instituteid
        AND t9.academicsessionid = t11.academicsessionid 
        AND t3.classid =  t12.classid
        AND t12.feestructureid = t13.feestructureid
        AND t12.feecomponentid = t14.feecomponentid
        AND t14.feecomponent = 'Caution Money'
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
    $sql .= " GROUP BY t1.studentid ORDER BY t3.classid, t4.sectionid, t1.firstname ASC ";
        
    $result = dbSelect($sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $studentdetails = $row;
            $studentdetails['cautionmoney'] = $row['amount'];
            unset($studentdetails['amount']);
        }
        return $studentdetails ;
    } else {
        return 0 ;
    }
}

function showSelectStudent()
{
    $studentdetails =  studentDetails();
    //echoThis($studentdetails); die;
    $currentDate = date('d/m/Y');
    $studentname = ucfirst($studentdetails['firstname']. " ".$studentdetails['middlename']." ". $studentdetails['lastname']);
    $cautionMoney = convertNum2Words($studentdetails['cautionmoney']);
    $strHTML = " <div style=\" border: solid black;\">
                <br>
                <div class=\"control\" align=\"center\">
                    <span style=\"font-family:shree; font-size: 32px;font-style: italic\">CENTRAL ACADEMY</span>
                </div>
                <div style=\"border-radius: 5px; border: solid black; width:250px;text-align: center; margin-left:250px; font-size: 24px;\">CASH VOUCHER</div>
                 
               <span class=\"clearfix\">&nbsp;<br><br></span>   
               <span style=\"margin-left:25px; display: inline; font-size: 16px; font-weight: bold\"> Voucher No.</span>
               <span style=\"margin-left:125px; font-size: 16px; display: inline; font-weight: bold\"> Cash Book Folio No. -</span>
               <span style=\"margin-left:125px; font-size: 16px; display: inline; font-weight: bold\"> Date. $currentDate</span>
                <br>   <br>  <br> 
                <div style=\"margin-left:25px;\">_______________________________________________________________________<strong>for Rs.<u>$cautionMoney</u></strong></div>
                <br>
                <div style=\"margin-left:25px;\"><strong>DEBIT/CREDIT______________________________________________________________________________</strong></div>
                <br>
                <div style=\"margin-left:25px;\"><strong>Paid To/by <u>$studentname Scholar No. $studentdetails[scholarnumber]</u>__________________________________________________</strong></div>
                <br>
                <div style=\"margin-left:25px;\"><strong>Rupees <u>$cautionMoney  only</u>____________________________________________________________________</strong></div>
                 <br>
                <div style=\"margin-left:25px;\"><strong>on account of <u>Caution Money refund.</u>____________________________________________________________</strong></div>

               <span class=\"clearfix\">&nbsp;<br><br></span>   
               <span style=\"margin-left:45px; display: inline;\"><b>---------------------</b></span>
               <span style=\"margin-left:185px;  display: inline; \"><b>-------------------------------</b></span>
               <span style=\"margin-left:135px;display: inline;\"><b>----------------</b></span>
               <br>
               
               <span style=\"margin-left:45px; display: inline; font-size: 16px; font-weight: bold\"> Accountant.</span>
               <span style=\"margin-left:185px; font-size: 16px; display: inline; font-weight: bold\">Manager/Principal.</span>
               <span style=\"margin-left:125px; font-size: 16px; display: inline; font-weight: bold\"> Signature</span>
                
               <span class=\"clearfix\">&nbsp;<br><br><br></span>

</div>";
    return $strHTML;
}?>
