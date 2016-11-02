<?php
 require_once "../config/config.php";
 require_once DIR_FUNCTIONS;
 require_once '../PHPWord/PhpWord.php';

$studentDetails = studentdetails();//echoThis($studentDetails); die;
$dob = date("d/m/Y", strtotime($studentDetails[0]['dob']));
$dobwords = date("d, F , Y", strtotime($studentDetails[0]['dob']));
if (empty($studentDetails[0]['dob'])) {
    $dob = "NA";
    $dobwords = "NA";
}
$intsessassocid = $_SESSION['instsessassocid'];
//$intsessassocid =3;
$generalcategory = "No";
if ($studentDetails[0]['category'] == 255) {
    $generalcategory = "Yes";
}
$scholarnumber = $studentDetails[0]['scholarnumber'];
$datecreated = date("d/m/Y", strtotime($studentDetails[0]['datecreated']));
$currentDate =  date("d/m/Y");
// New Word Document
$PHPWord = new \PHPWord\PhpWord.php;
// New portrait section
$section = $PHPWord->createSection();
$html = " <div>
        <div style=\"width: 240px;  display: inline\">No 6.</div>
        <div style=\"width: 240px; text-align: center; font-size: 15px; display: inline\"><b>RECORD - A</b></div>
        <div style=\"width: 250px; text-align: center; font-size: 15px; display: inline\"><b>SCHOLAR NO.   &nbsp;  &nbsp;$scholarnumber</b></div>
        <br>";
$html .= "<div style=\" width: 900px;\">
            <div style=\"width: 230px; height: 15px; padding-top: 5px; border: thin 1px; text-align: center;display: inline\"><b>DATE OF ADMISSION</b></div>
            <div style=\"width: 230px; height: 15px; padding-top: 5px; border: thin 1px; text-align: center;display: inline\"><b>DATE OF REMOVAL</b></div>
            <div style=\"width: 250px; height: 15px; padding-top: 5px; border: thin 1px; text-align: center;display: inline\"><b>CAUSE OF REMOVAL</b></div>
        </div>";
$html .= "<div style=\" width: 900px;\">
            <div style=\"width: 230px; height: 15px; padding-top: 5px; border: thin 1px; text-align: center;display: inline\"><b> $datecreated </b></div>
            <div style=\"width: 230px; height: 15px; padding-top: 5px; border: thin 1px; text-align: center;display: inline\"><b>$currentDate</b></div>
            <div style=\"width: 250px; height: 15px; padding-top: 5px; border: thin 1px; text-align: center;display: inline\">Change of school</div>
        </div>
    </div>";

$html .= '<p>Unordered (bulleted) list:</p>';
$html .= '<ul><li>Item 1</li><li>Item 2</li><ul><li>Item 2.1</li><li>Item 2.1</li></ul></ul>';
$html .= '<p>Ordered (numbered) list:</p>';
$html .= '<ol><li>Item 1</li><li>Item 2</li></ol>';

\PhpWord\Shared\Html::addHtml($section, $html);
// Save file
echo write($phpWord, 'tctest.php', $writers);
?>
<?php 
  
function studentdetails()
{
    $studentid = cleanVar($_GET['studentid']);
    $sql = "SELECT *
        FROM `tblstudent` AS t1,
        `tblstudentacademichistory` AS t2,
        `tblclassmaster` AS t3,
        `tblsection` AS t4,
        `tblclsecassoc` AS t5,
        `tblparent` AS t6,
        `tbluserparentassociation` AS t7,
        `tblstudentdetails` AS t8,
        `tblstudentcontact` AS t9
		
        WHERE t1.studentid = $studentid
        AND t1.studentid = t2.studentid
        AND t1.studentid = t8.studentid
        AND t1.studentid = t9.studentid
	AND t2.clsecassocid = t5.clsecassocid
        AND t5.classid = t3.classid
        AND t5.sectionid = t4.sectionid
        AND t1.studentid = t7.studentid
        AND t7.parentid = t6.parentid
	";
        
    $result = dbSelect($sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $studentdetails[] = $row;
    }
    return $studentdetails;
}
