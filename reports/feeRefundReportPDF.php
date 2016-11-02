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
    $details = studentdetails();
    $branchDetails = getInstdetails();
    $filename = $branchDetails['instituteabbrevation'] . "-refund-fee-report" . date('d/m/Y H:i:s') . ".xls";
    $studentCount = count($details);
    $i = 0;
    $totalFeescollected = $totalfeeefunded = 0;
    foreach ($details as $key => $val) {
        $feeRefunded = 0;
        $collectedfee = Collectedfee($val['studentid']);
        foreach ($collectedfee as $arrKey => $arrval) {
            $feeRefunded += $arrval['amount'];
        }
        if (($key < $studentCount) && $feeRefunded != 0) {
            $contentArray[$i]['scholarnumber'] = $val['scholarnumber'];
            $contentArray[$i]['studentname'] = $val['firstname'] . " " . $val['middlename'] . " " . $val['lastname'];
            $contentArray[$i]['class'] = $val['classdisplayname'] . " " . $val['sectionname'];
            $contentArray[$i]['refundamount'] = $feeRefunded;
            $i++;
            $totalfeeefunded += $feeRefunded;
        }
    }

    $content = $contentArray;
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->getProperties()->setCreator("Central Academy")
            ->setTitle("Fee Refund Report");
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'SNo')
            ->setCellValue('B1', 'Scholar No.')
            ->setCellValue('C1', 'Student Name')
            ->setCellValue('D1', 'Class Name')
            ->setCellValue('E1', 'Refund Amount');


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
                ->setCellValue('E' . $cellNo, $content[$key]['refundamount']);

        $cellNo++;
        $sno++;
    }
    $cellNo++;
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('D' . $cellNo, 'Total Amount')
            ->setCellValue('E' . $cellNo, $totalfeeefunded);

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

function studentdetails()
{
    $details = cleanVar($_GET);
    $studentdetails = array();
    $startPage = (int) (!isset($_REQUEST['page']) ? 0 : ($_REQUEST['page'] - 1) * ROW_PER_PAGE);
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

    $finalSql = $sql . " GROUP BY t1.studentid ORDER BY t3.classid, t4.sectionid, t1.firstname ASC ";
    $result = dbSelect($finalSql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $studentdetails[] = $row;
        }
        return $studentdetails;
    } else {
        return 0;
    }
}

function showSelectStudent()
{
    $studentdetails = studentdetails();
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
                                                font-family : shree;
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
                        <h3>FEE REFUND REPORT </h3>
                 <table align=\"center\">
                <tr>
                    <td style=\"width:20px\"><strong>SNO</strong></td>
                    <td style=\"width:200px; text-align:left\"><strong>STUDENT NAME</strong></td>
                    <td style=\"width:80px; text-align:left\"><strong>CLASS</strong></td>
                    <td style=\"width:200px\"><strong>FEE REFUND AMOUNT</strong></td>
                    
                 </tr>";

    foreach ($studentdetails as $key => $detailsvalue) {
        $feeRefunded = 0;
        $sectionName = strtoupper($detailsvalue['sectionname']);
        $collectedfee = Collectedfee($detailsvalue['studentid']); // echoThis($collectedfee); die;
        for ($i = 0; $i < count($collectedfee); $i++) {
            $feeRefunded += $collectedfee[$i]['amount'];
        }
        if ($feeRefunded == 0) {
            continue;
        }
        $htmlContent.= "
                 <tr>
                    <td>$detailsvalue[scholarnumber]</td>
                    <td>$detailsvalue[firstname]  $detailsvalue[middlename]  $detailsvalue[lastname]</td>
                    <td>$detailsvalue[classdisplayname]  $detailsvalue[sectionname]</td>
                    <td>".formatCurrencypdf($feeRefunded)."</td>
                 </tr>
                 ";
        $totalFeeRefund += $feeRefunded;
    }
    $htmlContent.=" <tr>
                        <td>&nbsp;</td>
                        <td colspan=\"3\" style=text-align:center><strong>TOTAL AMOUNT - </strong>".formatCurrencypdf($totalFeeRefund)."</td></tr>
                </table></body></html></page>";

    return $htmlContent;
}

function Collectedfee($studentid)
{
    $feedetails = array();
    $sql = " SELECT t1.studentid, t2.feecollectionid,
            t2.feecollectiondetailid, t1.receiptid, t3.datecreated, t3.feerefundrecieptno,t3.remarks,t4.feecomponent,
            t9.classid, t6.feestructuredetailsid, t10.feeinstallment, t6.amount
               
            FROM `tblfeecollection` AS t1,
            `tblfeecollectiondetail` AS t2,
            `tblfeerefund` AS t3,
            `tblfeecomponent` AS t4,
            `tblfeestructure` AS t5,
            `tblfeestructuredetails` AS t6,
            `tblstudentacademichistory` AS t7,
            `tblclsecassoc` AS t8,
            `tblclassmaster` AS t9,
            `tblfeeinstallmentdates` AS t10
  
            WHERE  t1.studentid = $studentid
            AND t1.studentid = t7.studentid
            AND t7.clsecassocid = t8.clsecassocid
            AND t8.classid = t9.classid
            AND t1.feecollectionid = t2.feecollectionid
            AND t2.feecollectiondetailid = t3.feecollectiondetailid
            AND t2.refundstatus = 1 
            AND t3.feecomponentid = t4.feecomponentid
            AND t4.feecomponentid = t5.feecomponentid
            AND t5.classid =  t9.classid
            AND t5.feestructureid = t6.feestructureid
            AND t2.feecollectiondetailid = t10.feecollectiondetailid
            AND t10.feeinstallment = t6.duedate
            ";
    $result = dbSelect($sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $feedetails[] = $row;
        }
        return $feedetails;
    } else {
        return 0;
    }
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
