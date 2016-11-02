<?php

/*
 * 360 - School Empowerment System.
 * Developer: Ankur Mishra (amishra@ebizneeds.com.au) | www.ebizneeds.com.au
 * Page details here:
 * Updates here:
 */
require_once "../config/config.php";
require_once DIR_FUNCTIONS;

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'xls') {
    $content = ob_get_clean();
    require_once'../PHPExcel-1.8/Classes/PHPExcel.php';

    $details = StudentDetails();
    $branchDetails = getInstdetails();
    $filename = $branchDetails['instituteabbrevation'] . "-student-transport-report" . date('d/m/Y H:i:s') . ".xls";
    $studentCount = count($details);
    $i = 0;
    $totalfee = 0;
    unset($details['totalrows']);
    foreach ($details as $key => $val) {
        $contentArray[$i]['scholarnumber'] = $val['scholarnumber'];
        $contentArray[$i]['studentname'] = $val['firstname'] . " " . $val['middlename'] . " " . $val['lastname'];
        $contentArray[$i]['class'] = $val['classdisplayname'] . " " . $val['sectionname'];
        $contentArray[$i]['pickuppoint'] = $val['pickuppointname'];
        $contentArray[$i]['amount'] = $val['amount'];
        $i++;
        $totalfee += $val['amount'];
    }

    $content = $contentArray;
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->getProperties()->setCreator("Central Academy")
            ->setTitle("Student Transport Report");

    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'SNo')
            ->setCellValue('B1', 'Scholar No.')
            ->setCellValue('C1', 'Student Name')
            ->setCellValue('D1', 'Class Name')
            ->setCellValue('E1', 'Pick Up Point')
            ->setCellValue('F1', 'Amount');

    $cellNo = 2;
    $sno = 1;
    $totalFeeAmount = 0;
    $otherFeeAmount = 0;

    foreach ($content as $key => $value) {
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $cellNo, $sno)
                ->setCellValue('B' . $cellNo, $content[$key]['scholarnumber'])
                ->setCellValue('C' . $cellNo, $content[$key]['studentname'])
                ->setCellValue('D' . $cellNo, $content[$key]['class'])
                ->setCellValue('E' . $cellNo, $content[$key]['pickuppoint'])
                ->setCellValue('F' . $cellNo, $content[$key]['amount']);

        $cellNo++;
        $sno++;
    }
    $cellNo++;
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('E' . $cellNo, 'Total Amount')
            ->setCellValue('F' . $cellNo, $totalfee);


    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save(str_replace('.php', '.xlsx', __FILE__));

    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    // If you're serving to IE 9, then the following may be needed
    header('Cache-Control: max-age=1');

    // If you're serving to IE over SSL, then the following may be needed
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header('Pragma: public'); // HTTP/1.0

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
    exit;
}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'pdf') {
    require_once('../html2pdf/html2pdf.class.php');

    $content = ob_get_clean();
    $content = showSelectStudent();
    $html2pdf = new HTML2PDF('P', 'A4', 'en');
    $html2pdf->WriteHTML($content); // in $content you put your content to have in pdf
    $html2pdf->Output('fee_collection_report.pdf');
}

function StudentDetails()
{
    $details = cleanVar($_GET);
    $startPage = (int) (!isset($_REQUEST['page']) ? 0 : ($_REQUEST['page'] - 1) * ROW_PER_PAGE);

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
		  
            WHERE  t1.instsessassocid = '$_SESSION[instsessassocid]'
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

    $limit = "  LIMIT $startPage," . ROW_PER_PAGE;
    $finalSql = $sql . "  ORDER BY t3.classid, t4.sectionid ASC  " . $limit;
    //echoThis($finalSql); die;
    if ($result = dbSelect($finalSql)) {
        while ($row = mysqli_fetch_assoc($result)) {
            $studentdetails[] = $row;
            $studentdetails['totalrows'] = mysqli_num_rows(dbSelect($sql));
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
    $htmlContent = "<page>
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
                                                font-family:shree;
                                                font-size:24px;
                                                margin:5px;
                                                padding:0px;
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
                                            h5
                                            {
                                                text-align:center;
                                                margin:5px;
                                                padding:0px;
                                            }
                                             
                                    </style>
                                </head>
                                <body>";
    $htmlContent.= "<h1>$branchDetails[institutename]</h1>
                        <h5> Address : $branchDetails[instituteaddress1],$branchDetails[instituteaddress2]</h5>
                        <h3>STUDENT TRANSPORT REPORT </h3>
                         
                 <table align=\"center\">
                <tr>
                    <td style=\"width:20px\"><strong>S.No</strong></td>
                    <td style=\"width:70px\"><strong>Scholar No</strong></td>
                    <td style=\"width:200px; text-align:left\"><strong>STUDENT NAME</strong></td>
                    <td style=\"width:50px; text-align:left\"><strong>CLASS</strong></td>
                    <td style=\"width:80px; text-align:left\"><strong>Pick Up Point</strong></td>
                    <td style=\"width:80px; text-align:left\"><strong>Amount</strong></td>
                 </tr>";

    $j = 1;
    $totalStudents = $studentdetails['totalrows'];
    unset($studentdetails['totalrows']);
    $totalFee = 0;
    foreach ($studentdetails as $key => $value) {
        $studentid = $value['studentid'];
        $htmlContent.= "
                <tr>  
                    <td class=\"col-md-1\">$j</td>
                    <td class=\"col-md-2\">$value[scholarnumber]</td>
                    <td class=\"col-md-2\">$value[firstname]  $value[middlename] $value[lastname]</td>
                    <td class=\"col-md-2\">$value[classdisplayname] - $value[sectionname]</td>
                    <td class=\"col-md-2\">$value[pickuppointname]</td>
                    <td class=\"col-md-2\">" . formatcurrencypdf($value['amount']) . "</td>
                </tr>
                 ";
        $j++;
        $totalFee += $value['amount'];
    }
    $totalFee = formatcurrencypdf($totalFee);
    $htmlContent.=" <tr>
                        <td colspan=\"6\" style=text-align:center><strong>TOTAL AMOUNT - </strong>$totalFee</td></tr>
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

    $resBranch = dbSelect($sqlBranchDetail);
    $branchDetails = mysqli_fetch_assoc($resBranch);
    return $branchDetails;
}
