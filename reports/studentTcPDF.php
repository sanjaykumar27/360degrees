<?php 
 
/*
* 360 - School Empowerment System.
* Developer: Ankur Mishra (amishra@ebizneeds.com.au) | www.ebizneeds.com.au
* Page details here:
* Updates here:
*/

require_once "../config/config.php";
require_once DIR_FUNCTIONS;
 
 if (isset($_REQUEST['action']) && $_REQUEST['action']=='xls') {
     $content = ob_get_clean();
     require_once'../PHPExcel-1.8/Classes/PHPExcel.php';
         
     $details = StudentDetails();
     $branchDetails = getInstdetails();
     $filename = $branchDetails['instituteabbrevation']."-student-tc-report". date('d/m/Y H:i:s').".xls";
     $studentCount = count($details);
     $i = 0 ;
     $totalfee = 0;
     unset($details['totalrows']);
       
     foreach ($details as $key => $val) {
         $admissionDate = date('d/m/Y',  strtotime($val['datecreated']));
         $dateofissue = date('d/m/Y', strtotime($val['dateofissue']));
         $contentArray[$i]['scholarnumber'] = $val['scholarnumber'];
         $contentArray[$i]['studentname'] = $val['firstname'] ." ".  $val['middlename']. " " .  $val['lastname'] ;
         $contentArray[$i]['class'] = $val['classdisplayname'] ." ". $val['sectionname'] ;
         $contentArray[$i]['admissiondate'] = $admissionDate ;
         $contentArray[$i]['dateofissue'] =  $dateofissue;
         $contentArray[$i]['amount'] = $val['amount'] ;
         $i++;
         $totalfee += $val['amount'] ;
     }
         
     $content=  $contentArray;
     $objPHPExcel = new PHPExcel();
     $objPHPExcel->getProperties()->setCreator("Central Academy")
                    ->setTitle("Student TC Report");
         
     $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'SNo')
        ->setCellValue('B1', 'Scholar No.')
        ->setCellValue('C1', 'Student Name')
        ->setCellValue('D1', 'Class Name')
        ->setCellValue('E1', 'Admission Date')
        ->setCellValue('F1', 'Issue Date')
        ->setCellValue('G1', 'Amount');
         
     $cellNo=2;
     $sno=1;
     $totalFeeAmount=0;
     $otherFeeAmount=0;
         
     foreach ($content as $key=>$value) {
         $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$cellNo, $sno)
            ->setCellValue('B'.$cellNo, $content[$key]['scholarnumber'])
            ->setCellValue('C'.$cellNo, $content[$key]['studentname'])
            ->setCellValue('D'.$cellNo, $content[$key]['class'])
            ->setCellValue('E'.$cellNo, $content[$key]['admissiondate'])
            ->setCellValue('F'.$cellNo, $content[$key]['dateofissue'])
            ->setCellValue('G'.$cellNo, $content[$key]['amount']);
         $cellNo++;
         $sno++;
     }
     $cellNo++;
     $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('F'.$cellNo, 'Total Amount')
        ->setCellValue('G'.$cellNo, $totalfee);
         
     $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
     $objWriter->save(str_replace('.php', '.xlsx', __FILE__));
         
     header('Content-Type: application/vnd.ms-excel');
     header('Content-Disposition: attachment;filename="'.$filename.'"');
     header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
 
        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
     $objWriter->save('php://output');
     exit;
 }
 
if (isset($_REQUEST['action']) && $_REQUEST['action']=='pdf') {
    require_once('../html2pdf/html2pdf.class.php');
        
    $content = ob_get_clean();
    $content=  showSelectStudent();
    $html2pdf = new HTML2PDF('P', 'A4', 'en');
    $html2pdf->WriteHTML($content); // in $content you put your content to have in pdf
        $html2pdf->Output('tc_report.pdf');
}
  
 
function StudentDetails()
{
    $details = cleanVar($_GET);
    $startPage = (int) (!isset($_REQUEST['page']) ? 0 :($_REQUEST['page']-1)*ROW_PER_PAGE);
    $sqlVar = "AND";
    $sql = "  SELECT t1.studentid, t1.scholarnumber, t1.firstname, t1.middlename, t1.lastname, t1.datecreated,
                t3.classdisplayname, t4.sectionname,t8.datecreated, t8.feeinstallmentamount, t7.receiptid
                FROM `tblstudent` AS t1,
                    `tblstudentdetails` AS t2,
                    `tblclassmaster` AS t3, 
                    `tblsection` AS t4,
                    `tblclsecassoc`AS t5, 
                    `tblstudentacademichistory` AS t6,
                    `tblfeecollection` AS t7,
                    `tblfeecollectiondetail` AS t8
             
                WHERE t1.studentid = t2.studentid 
                AND t1.instsessassocid = $_SESSION[instsessassocid]
                AND t1.studentid = t6.studentid 
                AND t6.clsecassocid = t5.clsecassocid 
                AND t5.classid = t3.classid
                AND t5.sectionid = t4.sectionid 
                AND t7.studentid = t1.studentid
                AND t7.feecollectionid = t8.feecollectionid
                AND t8.collectiontype = 4
                AND t1.status = 0
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
    $studentdetails = StudentDetails();
    $branchDetails = getInstdetails();
    $totalFeeRefund = 0;
    $htmlContent="<page>
                            <html>
                                <head>
                                    <style>
                                        table
                                            {   
                                                width:100%;
                                                border: solid 1px #000000; 
                                                font-size:12px;
                                                cellpadding:0px;
                                                cellspacing:1px;
                                                border-collapse: collapse;
                                                font-family:arial;
                                                align:center;
                                                
                                            }
                                         td { 
                                                border:1px solid #000000;
                                                padding:05px; 
                                                text-align:left;
                                                background-color : #F6F6F6;
                                            }
                                            h1
                                            {
                                                text-align:center;
                                                font-size:24px;
                                                margin:5px;
                                                padding:0px;
                                                font-family: Shree;
                                            }
                                            h3
                                            {
                                                text-align:center;
                                                text-decoration:underline;
                                                font-size:16px;
                                               margin:5px;
                                                padding:0px;
                                            }
                                            h4
                                            {
                                                text-align:center;
                                                font-size:14px;
                                                margin:5px;
                                                padding:0px;
                                            }
                                            h6
                                            {
                                                text-align:center;
                                                margin:5px;
                                                padding:0px;
                                            }
                                    </style>
                                </head>
                                <body>";
    $htmlContent.= "<h1>$branchDetails[institutename]</h1>
                        <h6> Address : $branchDetails[instituteaddress1],$branchDetails[instituteaddress2]</h6>
                        <h3>STUDENT TC REPORT </h3>
                          
                 <table align=\"center\">
                <tr>
                    <td ><strong>S.No</strong></td>
                    <td ><strong>Scholar No</strong></td>
                    <td ><strong>STUDENT NAME</strong></td>
                    <td ><strong>CLASS</strong></td>
                    <td ><strong>Admission Date</strong></td>
                    <td ><strong>TC Issue Date</strong></td>
                    <td ><strong>Amount</strong></td>
                 </tr>";
         
    $j = 1 ;
    $totalStudents = $studentdetails['totalrows'];
    unset($studentdetails['totalrows']);
    $totalFee = 0;
    foreach ($studentdetails as $key => $value) {
        $studentid = $value['studentid'];
        $admissionDate = date('d/m/Y',  strtotime($value['datecreated']));
        $dateofissue = date('d/m/Y', strtotime($value['datecreated']));
        $htmlContent.= "
                <tr>  
                    <td class=\"col-md-1\">$j</td>
                    <td class=\"col-md-2\">$value[scholarnumber]</td>
                    <td class=\"col-md-2\">$value[firstname]  $value[middlename] $value[lastname]</td>
                    <td class=\"col-md-2\">$value[classdisplayname] - $value[sectionname]</td>
                    <td class=\"col-md-2\">$admissionDate</td>
                    <td class=\"col-md-2\">$dateofissue</td>
                    <td class=\"col-md-2\">".formatCurrencypdf($value['feeinstallmentamount'])."</td>
                </tr>
                 ";
        $j++;
        $totalFee += $value['feeinstallmentamount'];
    }
         
    $htmlContent.=" <tr>
                        <td>&nbsp;</td>
                        <td colspan=\"6\" style=text-align:center><strong>TOTAL AMOUNT ".formatCurrencypdf($totalFee)."</strong></td></tr>
                </table></body></html></page>";
    return $htmlContent;
}
 
function getInstdetails()
{
    $instsessassocid = $_SESSION['instsessassocid'];
        
    $sqlBranchDetail = " SELECT UPPER(institutename) as institutename,institutelogo, 
                            TRIM(instituteaddress1) as instituteaddress1 ,TRIM(instituteaddress2) as instituteaddress2,
                            institutephone1,instituteemail1 , instituteabbrevation
                            
                            FROM tblinstitute as t1, 
                            tblinstsessassoc as t2 
                            
                            WHERE t1.instituteid=t2.instituteid 
                            AND t2.instsessassocid=$instsessassocid ";
        
    $resBranch=  dbSelect($sqlBranchDetail);
    $branchDetails = mysqli_fetch_assoc($resBranch);
    return $branchDetails;
}
