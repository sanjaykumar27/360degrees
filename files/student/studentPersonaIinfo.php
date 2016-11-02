<?php
/*
 * 360 - School Empowerment System.
 * Developer: Ankur Mishra (amishra@ebizneeds.com.au) | www.ebizneeds.com.au
 * Page details here: Master for fees head and related processing
 * Updates here:
 */
//call the main config file, functions file and header
require_once "../../config/config.php";
require_once DIR_FUNCTIONS;
require_once "fpdf/fpdf.php" ;
?>

<form action="<?php echo PROCESS_FORM; ?>" method="post" name="imform">
<div class ="container">
<div class="span12">
  
<?php renderMsg();
    $studentdetails = studentDetailsSql(); //echoThis($studentdetails); die;

    $dob = $studentdetails[0]['dob'];
    $DOB = date("d/m/Y", strtotime($dob));
    
    foreach ($studentdetails as $k=> $val) {
        $key = '' ;
        if ((is_numeric($k)) && (empty($val['collectionname']))) {
            $feerulename[] = $studentdetails[$k]['feerulename'];
            $documentname[] = $studentdetails[$k]['documentname'];
        }
        if ((is_numeric($k)) && (!empty($val['collectionname']))) {
            $documentName[] =  $val['collectionname'];
        }
    }

   $feeRules = array_unique($feerulename);
   $DocumentNames = array_unique($documentname);
   
    if (!empty($studentdetails[1])) {
        $flag = 1;
    } else {
        $flag = 0 ;
    }
  
class PDF extends FPDF
{

// Page header
public function Header()
{
    if ($this->PageNo() == 1) {
        $this->Cell(40);
        $this->SetFillColor(0, 0, 102);
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Helvetica', 'I', 24);
        $this->Cell(110, 10, 'STUDENT INFORMATION', 1, 0, 'C', 1);
        $this->Ln(10);
        $this->SetTextColor(0, 0, 0);
        $this->SetFont('Helvetica', '', 12);
        $this->Cell(0, 10, 'Please check the details, if necessary make Changes', 0, 0, 'C');
    } //---- if statement end here---//
}//---- header function ends here---//
}//----Class Fdf end here--//


$pdf = new PDF();
$pdf->AddPage();
ob_end_clean();

 $pdf->SetXY(155, 35);
 $pdf->Cell(40, 40, '', 0, 0, 'R', 0);
 $picture = 'http://localhost/360/asset/images/studentpicture/'.$studentdetails[0]["profilepicture"];
 $pdf->Image($picture, 160, 35, 30, 30);
 
$pdf->Ln(20);
$pdf->SetXY(-363, 50);
$pdf->SetFont('Times', '', 10);
$pdf->Cell(0, 0, 'Scholar No- '.$studentdetails[0]['scholarnumber'], 0, 0, 'C');
$pdf->SetX(-214);
$pdf->Cell(0, 0, 'Student Name -  '.$studentdetails[0]['firstname']." ".$studentdetails[0]['middlename']." ".$studentdetails[0]['lastname'], 0, 0, 'C');
 
$pdf->Ln(15);
$pdf->Cell(0, 0, "Date Of Birth:  ".$DOB, 0, 0);
$pdf->SetX(75);
$pdf->Cell(0, 0, "Gender:  ".ucfirst($studentdetails['genderData']), 0, 0);
$pdf->SetX(125);
$pdf->Cell(0, 0, "Class:  ".$studentdetails[0]['classdisplayname']."-".ucfirst($studentdetails[0]['sectionname']), 0, 0);

$pdf->Ln(15);
$pdf->Cell(0, 0, "Academic Session:  ".$studentdetails[0]['sessionname'], 0, 0);
$pdf->SetX(75);
$pdf->Cell(0, 0, "Institute Name:  ".ucfirst($studentdetails[0]['institutename']), 0, 0);



$pdf->Ln(15);
$pdf->Cell(0, 0, "Category:  ".ucfirst($studentdetails['categoryData']), 0, 0);
$pdf->SetX(75);
$pdf->Cell(0, 0, "Student Type:  ".ucfirst($studentdetails['studenttypeData']), 0, 0);
$pdf->SetX(145);
$pdf->Cell(0, 0, "Religion:  ".ucfirst($studentdetails['religionData']), 0, 0);

$pdf->Ln(15);
$pdf->Cell(0, 0, "Current Address:  ".ucfirst($studentdetails[0]['currentaddress1']).",".ucfirst($studentdetails[0]['currentaddress2']), 0, 0);

$pdf->Ln(15);
$pdf->Cell(0, 0, "Current Suburb:  ".ucfirst($studentdetails['currentsuburbidData']), 0, 0);
$pdf->SetX(95);
$pdf->Cell(0, 0, "Current Pincode:  ".$studentdetails[0]['currentzipcode'], 0, 0);
$pdf->SetX(155);
$pdf->Cell(0, 0, "Current City:  ".ucfirst($studentdetails[0]['cityname']), 0, 0);

$pdf->Ln(15);
$pdf->Cell(0, 0, "Current State:  ".ucfirst($studentdetails[0]['statename']), 0, 0);
$pdf->SetX(95);
$pdf->Cell(0, 0, "Current Country:  ".ucfirst($studentdetails[0]['countryname']), 0, 0);


$pdf->Ln(20);
$pdf->Cell(0, 0, "Permanent Address:  ".ucfirst($studentdetails[0]['permaaddress1']).",".ucfirst($studentdetails[0]['permaaddress2']), 0, 0);

$pdf->Ln(15);
$pdf->Cell(0, 0, "Permanent Suburb:  ".ucfirst($studentdetails['currentsuburbidData']), 0, 0);
$pdf->SetX(95);
$pdf->Cell(0, 0, "Permanent Pincode:  ".$studentdetails[0]['permazipcode'], 0, 0);
$pdf->SetX(155);
$pdf->Cell(0, 0, "Permanent City:  ".ucfirst($studentdetails[0]['cityname']), 0, 0);

$pdf->Ln(15);
$pdf->Cell(0, 0, "Permanent State:  ".ucfirst($studentdetails[0]['statename']), 0, 0);
$pdf->SetX(95);
$pdf->Cell(0, 0, "Permanent Country:  ".ucfirst($studentdetails[0]['countryname']), 0, 0);

$pdf->Ln(20);
$pdf->Cell(0, 0, "Contact No(H):  ".$studentdetails[0]['phone1'].",".$studentdetails[0]['phone2'], 0, 0);
$pdf->SetX(125);
$pdf->Cell(0, 0, "Contact No(M):  ".$studentdetails[0]['mobile'], 0, 0);

$pdf->Ln(15);
$pdf->Cell(0, 0, "FAX No:  ".$studentdetails[0]['fax1'].",".$studentdetails[0]['fax2'], 0, 0);
$pdf->SetX(125);
$pdf->Cell(0, 0, "Email Address :  ".$studentdetails[0]['email'], 0, 0);

$pdf->Ln(20);
$pdf->Cell(0, 0, "Previous School(if any):  ".strtoupper($studentdetails[0]['previousschool']), 0, 0);

$pdf->Ln(15);

$pdf->Cell(0, 0, "Previous Class:  ".strtoupper($studentdetails['classname']), 0, 0);
$pdf->SetX(125);
$pdf->Cell(0, 0, "Previous Result:  ".ucfirst($studentdetails['previousresultData']), 0, 0);
$pdf->SetX(55);
$pdf->Cell(0, 0, "Percentage :  ".$studentdetails[0]['percentgrade'], 0, 0);

$pdf->Ln(65);
$pdf->SetFont('Helvetica', 'B', 12);
$pdf->Cell(0, 0, "Parent's Details : -", 0, 0);

$pdf->Ln(20);
$pdf->SetFont('Times', '', 10);
$pdf->Cell(0, 0, "Parent Name :  ".$studentdetails[0]['parentfirstname']." ".$studentdetails[0]['parentmiddlename']." ".$studentdetails[0]['parentlastname'], 0, 0);
$pdf->SetX(105);
$pdf->Cell(0, 0, "Relation :  ".ucfirst($studentdetails['relationidData']), 0, 0);

$pdf->Ln(15);
$pdf->Cell(0, 0, "Current Address :  ".ucfirst($studentdetails[0]['currentaddress1']).",".ucfirst($studentdetails[0]['currentaddress2']), 0, 0);

$pdf->Ln(15);
$pdf->Cell(0, 0, "Current Suburb:  ".ucfirst($studentdetails['currentsuburbidData']), 0, 0);
$pdf->SetX(95);
$pdf->Cell(0, 0, "Current Pincode:  ".$studentdetails[0]['currentzipcode'], 0, 0);
$pdf->SetX(155);
$pdf->Cell(0, 0, "Current City:  ".ucfirst($studentdetails[0]['cityname']), 0, 0);

$pdf->Ln(15);
$pdf->Cell(0, 0, "Current State:  ".ucfirst($studentdetails[0]['statename']), 0, 0);
$pdf->SetX(95);
$pdf->Cell(0, 0, "Current Country:  ".ucfirst($studentdetails[0]['countryname']), 0, 0);

$pdf->Ln(20);
$pdf->Cell(0, 0, "Permanent Address :  ".ucfirst($studentdetails[0]['permaaddress1']).",".ucfirst($studentdetails[0]['permaaddress2']), 0, 0);

$pdf->Ln(15);
$pdf->Cell(0, 0, "Permanent Suburb:  ".ucfirst($studentdetails['currentsuburbidData']), 0, 0);
$pdf->SetX(95);
$pdf->Cell(0, 0, "Permanent Pincode:  ".$studentdetails[0]['currentzipcode'], 0, 0);
$pdf->SetX(155);
$pdf->Cell(0, 0, "Permanent City:  ".ucfirst($studentdetails[0]['cityname']), 0, 0);

$pdf->Ln(15);
$pdf->Cell(0, 0, "Permanent State:  ".ucfirst($studentdetails[0]['statename']), 0, 0);
$pdf->SetX(95);
$pdf->Cell(0, 0, "Permanent Country:  ".ucfirst($studentdetails[0]['countryname']), 0, 0);

$pdf->Ln(15);
$pdf->Cell(0, 0, "Contact No:  ".$studentdetails[0]['mobile1']." ,".$studentdetails[0]['mobile2'], 0, 0);
$pdf->SetX(95);
$pdf->Cell(0, 0, "Email(s):  ".$studentdetails[0]['email1']." , ".$studentdetails[0]['email2'], 0, 0);

$pdf->Ln(20);
$pdf->Cell(0, 0, "Qualification:  ".ucfirst($studentdetails['qualificationidData']), 0, 0);
$pdf->SetX(75);
$pdf->Cell(0, 0, "Occupation:  ".ucfirst($studentdetails['occupationData']), 0, 0);
$pdf->SetX(155);
$pdf->Cell(0, 0, "Income:  ".ucfirst($studentdetails['incomeData']), 0, 0);

$pdf->Ln(20);
$pdf->SetFont('Helvetica', 'B', 12);
$pdf->Cell(0, 0, "Student's Medical Information : -", 0, 0);

$pdf->Ln(15);
$pdf->SetFont('Times', '', 10);
$pdf->Cell(0, 0, "Medical History:  ".ucfirst($studentdetails[0]['medicalhistory']), 0, 0);
$pdf->Ln(10);
$pdf->Cell(0, 0, "Allergy Information:  ".ucfirst($studentdetails[0]['allergyinfo']), 0, 0);
$pdf->Ln(10);
$pdf->Cell(0, 0, "Frequent Illness :  ".ucfirst($studentdetails[0]['frequentillness']), 0, 0);

$pdf->Ln(10);
$pdf->Cell(0, 0, "Doctor's Name :  ".ucfirst($studentdetails[0]['regulardocname']), 0, 0);
$pdf->Ln(10);
$pdf->Cell(0, 0, "Doctor's Address :  ".ucfirst($studentdetails[0]['regulardocaddress']), 0, 0);
$pdf->Ln(10);
$pdf->Cell(0, 0, "Doctor's Contact No(M) :  ".ucfirst($studentdetails[0]['regulardocmobile']), 0, 0);
$pdf->SetX(85);
$pdf->Cell(0, 0, "Doctor's Contact No(L) :  ".ucfirst($studentdetails[0]['regulardocphone']), 0, 0);
$pdf->Ln(10);
$pdf->Cell(0, 0, "Doctor's Email Address :  ".ucfirst($studentdetails[0]['regulardocemail']), 0, 0);

$pdf->Ln(10);
$pdf->Cell(0, 0, "Hospital's Name :  ".ucfirst($studentdetails[0]['regularhospname']), 0, 0);
$pdf->Ln(10);
$pdf->Cell(0, 0, "Hospital's Address :  ".ucfirst($studentdetails[0]['regularhospaddress']), 0, 0);
$pdf->Ln(10);
$pdf->Cell(0, 0, "Hospital's Contact No(L) :  ".ucfirst($studentdetails[0]['regularhospphone']), 0, 0);
$pdf->SetX(85);
$pdf->Cell(0, 0, "Hospital's Email Address :  ".ucfirst($studentdetails[0]['regularhospemail']), 0, 0);

$pdf->Ln(10);
$pdf->Cell(0, 0, "Height :  ".ucfirst($studentdetails[0]['height'])." feet", 0, 0);
$pdf->SetX(85);
$pdf->Cell(0, 0, "Weight :  ".ucfirst($studentdetails[0]['weight'])." Kgs", 0, 0);
$pdf->SetX(135);
$pdf->Cell(0, 0, "Blood Group :  ".ucfirst($studentdetails['bloodgroupData']), 0, 0);

$pdf->Ln(10);
$pdf->Cell(0, 0, "Right Eye Sight :  ".$studentdetails[0]['righteyesight']." dpt", 0, 0);
$pdf->SetX(85);
$pdf->Cell(0, 0, "Left Eye Sight :  ".ucfirst($studentdetails[0]['lefteyesight'])." dpt", 0, 0);
$pdf->SetX(135);
$pdf->Cell(0, 0, "Doctor's Remarks :  ".ucfirst($studentdetails[0]['doctorremark']), 0, 0);

$pdf->Ln(20);
$pdf->SetFont('Helvetica', 'B', 12);
$pdf->Cell(0, 0, "Student's Fee Rule(if any) : -", 0, 0);

$pdf->Ln(15);
$pdf->SetFont('Times', '', 10);
if ($flag == 1) {
    foreach ($feeRules as $k=> $val) {
        $pdf->Cell(0, 0, "Rule Name :  ".ucfirst($val), 0, 0);
        $pdf->Ln(5);
    }
} else {
    $pdf->Cell(0, 0, "Rule Name :  ".ucfirst($studentdetails[0]['feerulename']), 0, 0);
}
$pdf->Ln(20);
$pdf->SetFont('Helvetica', 'B', 12);
$pdf->Cell(0, 0, "Documents Submitted : -", 0, 0);

$pdf->Ln(15);
$pdf->SetFont('Times', '', 10);
if ($flag == 1) {
    foreach ($documentName as $key => $value) {
        $pdf->Cell(0, 0, "Document Name(s) :  ".ucfirst($value), 0, 0);
        $pdf->Ln(5);
    }
} else {
    $pdf->Cell(0, 0, "Document Name :  ".ucfirst($studentdetails['documenttypeData']), 0, 0);
}

$pdf->Ln(15);
$pdf->SetFont('Helvetica', '', 12);
$pdf->SetFillColor(0, 0, 102);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetX(105);

$pdf->Cell(45, 10, "Edit Information", 1, 1, 'C', '1', 'http://localhost/360/files/student/StudentPersonal.php?edid='.$studentdetails[0]['studentid'].'&userid='.$studentdetails[0]['userid']);

$pdf->SetFont('Helvetica', '', 12);
$pdf->SetFillColor(0, 102, 0);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetX(65);
$pdf->SetY(-172);
$pdf->Cell(45, 10, "Save Information", 1, 1, 'C', '1', 'http://localhost/360/files/student/StudentDashboard.php?page=0');
$pdf->Output();


?>
    
    
</div> <!---------div span12 closed---->
</div><!---------div Container closed---->
</form>
     
<?php

function studentDetailsSql()
{
    if (isset($_GET['studentid'])) {
        $studentid = cleanVar($_GET['studentid']);
    }
   
    $sql = "SELECT *
           FROM `tblstudent` AS t1, 
		  `tblstudentdetails` AS t2, 
		  `tblclassmaster` AS t3, 
		  `tblsection` AS t4,
		  `tblinstitute` AS t5,
		  `tblacademicsession` AS t6,
          `tblcountry` AS t7,
          `tblstate` AS t8,
          `tblcity` AS t9,
          `tbluserparentassociation` AS t10,
          `tblparent` AS t11,
          `tblmedicalinfo` AS t12,
          `tblstudentfeeruleassociation` AS t13,
          `tblfeerule` AS t14,
          `tbluserdocument` AS t15
          
          
          WHERE t1.studentid = $studentid
          AND t1.studentid = t2.studentid
          AND t3.classid = t2.classid 
		  AND t2.sectionid = t4.sectionid
		  AND t1.instituteid = t5.instituteid
		  AND t1.academicsessionid = t6.academicsessionid 
		  AND t2.currentcountryid = t7.countryid
		  AND t2.currentstateid = t8.stateid
		  AND t2.currentcityid = t9.cityid
		  AND t1.userid = t10.userid
		  AND t10.parentid = t11.parentid
		  AND t1.userid = t12.userid
		  AND t1.studentid = t13.studentid
		  AND t13.feeruleid = t14.feeruleid
		  AND t1.studentid = t15.studentid

		  ";
         // echoThis($sql); die;
          $result = dbSelect($sql);
    $i = 0;
    while ($row[] = mysqli_fetch_assoc($result)) {
        $details = $row ;
        $documentname[] = $row[$i]['documenttype'];
        $i++;
    }
        
    
    $k = 0;
    $itemarray = array("studenttype" => $row[0]['studenttype'],
          "gender" =>$row[0]['gender'],
          "religion"=>$row[0]['religion'],
          "category" => $row[0]['category'],
          "currentsuburbid" => $row[0]['currentsuburbid'],
          "previousresult"=> $row[0]['previousresult'],
          "relationid" => $row[0]['relationid'],
          "qualificationid" => $row[0]['qualificationid'],
          "occupation" => $row[0]['occupation'],
          "income" => $row[0]['income'],
          "bloodgroup" =>$row[0]['bloodgroup']
    );
    if (!empty($documentname)) {
        $DocumentName = array_unique($documentname);
        foreach ($DocumentName as $key=> $value) {
            $documents[] = $value ;
            $k++;
        }
    } else {
        $DocumentName = 0;
    }
       
    $items =  fetchMasterCollectionNames($itemarray);
    $previousclass = fetchPreviousClass($details[0]['previousclass']);//echoThis($previousclass); die;
       $documentname = fetchDocumentname($documents);//echoThis($documentname); die;

        if (!empty($previousclass) && !empty($documentname)) {
            $previousclass = array_merge($previousclass, $documentname);
            $items = array_merge($items, $previousclass);
            $details = array_merge($details, $items); //echoThis($details); die;
               return $details;
        }
        
    return $details;
}

function fetchMasterCollectionNames($itemarray)
{
    $sql = array();
 // echoThis($itemarray); die;
  $masterData = array('studenttypeData'=>'','genderData'=>'','religionData'=>'','categoryData'=>'','currentsuburbidData'=>'',
   'previousresultData'=>'','relationidData'=>'','qualificationidData'=>'','occupationData'=>'','incomeData'=>'','bloodgroupData'=>''
   );
  
    foreach ($itemarray as $key => $value) {
        $sql[] = " SELECT `collectionname` FROM `tblmastercollection` WHERE `mastercollectionid` = $value   ";
    }
  //echoThis($sql); die;
  $result = dbSelect($sql);
    foreach ($result as $key => $value) {
        $row[] = mysqli_fetch_assoc($value);
    }
    $i =0 ;
    foreach ($masterData as $key=> $value) {
        $masterData[$key] = $row[$i]['collectionname'];
        $i++;
    }
    return $masterData;
}
function fetchDocumentname($name = array())
{
    foreach ($name as $key => $value) {
        $sql[] = " SELECT `collectionname` FROM `tblmastercollection` WHERE `mastercollectionid` = $value   ";
    }

    $result = dbSelect($sql);
    foreach ($result as $key => $value) {
        $row[] = mysqli_fetch_assoc($value);
    }

    return $row;
}

function fetchPreviousClass($previousclass)
{
    $sql = "SELECT `classname` FROM `tblclassmaster` WHERE `classid` = $previousclass ";
    $result = dbSelect($sql);
    $row = mysqli_fetch_assoc($result);
    if (!empty($row)) {
        return $row;
    } else {
        return false;
    }
}
