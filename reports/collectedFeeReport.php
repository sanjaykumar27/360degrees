<?php

/*
 * 360 - School Empowerment System.
 * Developer: Ankur Mishra (amishra@ebizneeds.com.au) | www.ebizneeds.com.au
 * Page details here:
 * Updates here:
 */

require_once "../config/config.php";
require_once DIR_FUNCTIONS;


if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'pdf') {
    require_once('../html2pdf/html2pdf.class.php');
    $content = ob_get_clean();
    $content = collectionReportPDF();
    $html2pdf = new HTML2PDF('P', 'A4', 'en');
    $html2pdf->WriteHTML($content); // in $content you put your content to have in pdf
    $html2pdf->Output('fee_collection_report.pdf');
    $html2pdf->Output('pdf/fee_collection_report.pdf', 'F');
}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'xls') {
    require_once'../PHPExcel-1.8/Classes/PHPExcel.php';
    $content = collectionReportPDF();
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->getProperties()->setCreator("Central Academy")
            ->setTitle("Fee Collection Report");

    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'SNo')
            ->setCellValue('B1', 'Scholar No.')
            ->setCellValue('C1', 'Student Name')
            ->setCellValue('D1', 'Class Name')
            ->setCellValue('E1', 'Fee Amount')
            ->setCellValue('F1', 'Other Fee');

    $cellNo = 2;
    $sno = 1;
    $totalFeeAmount = 0;
    $otherFeeAmount = 0;

    foreach ($content as $key => $value) {
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $cellNo, $sno)
                ->setCellValue('B' . $cellNo, $value['scholarnumber'])
                ->setCellValue('C' . $cellNo, $value['studentname'])
                ->setCellValue('D' . $cellNo, $value['class'])
                ->setCellValue('E' . $cellNo, $value['feeamount'])
                ->setCellValue('F' . $cellNo, $value['otherfee']);
        $totalFeeAmount+=$value['feeamount'];
        $otherFeeAmount+=$value['otherfee'];
        $cellNo++;
        $sno++;
    }
    $cellNo++;
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('D' . $cellNo, 'Total Amount')
            ->setCellValue('E' . $cellNo, $totalFeeAmount)
            ->setCellValue('F' . $cellNo, $otherFeeAmount);

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save(str_replace('.php', '.xlsx', __FILE__));

    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="01simple.xls"');
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

function collectionReportPDF()
{
    $totalFeeAmt = 0;
    $totalOtherFee = 0;
    $sno = 1;
    $headerStr = "";

    $where = " WHERE t1.instsessassocid=" . $_SESSION['instsessassocid'] . " AND t1.status=1 AND t1.deleted=0";
    $groupBy = " GROUP BY t1.studentid ";
    $orderby = " ORDER BY t6.feecollectionid";

    $sqlString = " SELECT  t1.scholarnumber, UPPER(CONCAT (t1.firstname,' ',t1.middlename,' ', t1.lastname)) as studentname , 
                                    UPPER(CONCAT (classname,'-',sectionname)) as class, SUM(t7.feeinstallmentamount) as feeamount, 
                                    SUM(t8.amountcharged) as otherfee
                        FROM tblstudent as t1
                                    INNER JOIN tblstudentacademichistory as t2 ON t1.studentid=t2.studentid
                                    INNER JOIN tblclsecassoc as t3 ON t2.clsecassocid=t3.clsecassocid
                                    INNER JOIN tblclassmaster as t4 ON t3.classid=t4.classid
                                    INNER JOIN tblsection as t5 ON t3.sectionid=t5.sectionid
                                    INNER JOIN tblfeecollection as t6 ON t1.studentid=t6.studentid AND t3.classid=t4.classid
                                    INNER JOIN tblfeecollectiondetail as t7 ON t6.feecollectionid=t7.feecollectionid
                                    INNER JOIN tblothertransdetails as t8 ON t6.feecollectionid=t8.feecollectionid
                     ";
    $sqlSession = "SELECT sessionname FROM tblacademicsession as t1 
                    INNER JOIN tblinstsessassoc as t2 ON t1.academicsessionid=t2.academicsessionid
                    WHERE t2.instsessassocid=" . $_SESSION['instsessassocid'];
    echoThis($sqlSession);

    $result = dbSelect($sqlSession);
    $rowSession = mysqli_fetch_assoc($result);

    if (isset($_REQUEST['classid']) && isset($_REQUEST['sectionid']) && !empty($_REQUEST['classid']) && !empty($_REQUEST['sectionid'])) {
        $sqlClassName = dbSelect("SELECT UPPER(CONCAT (classname,'-',sectionname)) as class FROM tblclassmaster,tblsection WHERE classid=$_REQUEST[classid] AND sectionid=$_REQUEST[sectionid]");
        $className = mysqli_fetch_assoc($sqlClassName);
        $headerStr = " FOR : $className[class]";
    }
    if (isset($_REQUEST['classid']) && !empty($_REQUEST['classid'])) {
        $sqlClassName = dbSelect("SELECT UPPER(classname) as class FROM tblclassmaster WHERE classid=$_REQUEST[classid]");
        $className = mysqli_fetch_assoc($sqlClassName);
        $headerStr = " FOR : $className[class]";
    }
    if (isset($_REQUEST['startdate']) && isset($_REQUEST['enddate'])) {
        $headerStr = " FROM :$_REQUEST[startdate] TO $_REQUEST[enddate]";
    }

    $htmlContent = "<page>
                            <html>
                                <head>
                                    <style >
                                        table
                                            {   
                                                width:900px;
                                                border: solid 1px #000000; 
                                                font-size:12px;
                                                cellpadding:0px;
                                                cellspacing:0px;
                                                border-collapse: collapse;
                                                font-family:arial;
                                                align:center;
                                            }
                                         td { 
                                                border:1px;
                                                padding:05px; 
                                                text-align:center;
                                            }
                                            h3
                                            {
                                                text-align:center;
                                                text-decoration:underline;
                                                font-size:18px;
                                                margin:5px;
                                                
                                            }
                                            h4
                                            {
                                                text-align:center;
                                                font-size:14px;
                                                margin:5px;
                                            }
                                    </style>
                                </head>
                                <body>";
    $htmlContent.= " <h3>FEE COLLECTION REPORT - $rowSession[sessionname]</h3>
                         <h4>$headerStr</h4>
                                    <table align=\"center\">
                                        <tr>
                                            <td style=\"width:20px\"><strong>SNO</strong></td>
                                            <td style=\"width:350px; text-align:left\"><strong>STUDENT NAME</strong></td>
                                            <td style=\"width:80px; text-align:center\"><strong>CLASS</strong></td>
                                            <td style=\"width:80px\"><strong>FEE AMOUNT</strong></td>
                                            <td style=\"width:80px\"><strong>OTHER FEE</strong></td>
                                        </tr>";

    if (isset($_REQUEST['studentname']) && !empty($_REQUEST['studentname'])) {
        $where.=" AND firstname LIKE ('" . cleanVar($_REQUEST['studentname']) . "%')";
    }
    if (isset($_REQUEST['classid']) && is_numeric($_REQUEST['classid'])) {
        $where.=" AND t3.classid=" . cleanVar($_REQUEST['classid']);
    }
    if (isset($_REQUEST['sectionid']) && is_numeric($_REQUEST['sectionid'])) {
        $where.=" AND t3.sectionid=" . cleanVar($_REQUEST['sectionid']);
    }
    if (isset($_REQUEST['paymentmode']) && is_numeric($_REQUEST['paymentmode'])) {
        $where.=" AND t7.feemodeid=" . cleanVar($_REQUEST['paymentmode']);
    }
    if (!empty($_REQUEST['monthstart']) && !empty($_REQUEST['monthend'])) {
        $where.=" AND t7.feeinstallment >= '" . $_REQUEST['monthstart'] . "' AND t7.feeinstallment <= '" . $_REQUEST['monthend'] . "'";
    } elseif (!empty($_REQUEST['monthstart'])) {
        $where.= " AND t7.feeinstallment>='" . $_REQUEST['monthstart'] . "'";
    } elseif (!empty($_REQUEST['monthend'])) {
        $where.= " AND t7.feeinstallment>='" . $_REQUEST['monthend'] . "'";
    }

    $finalSql = $sqlString . $where . $groupBy . $orderby;
    
    $result = dbSelect($finalSql);
    $rowCount = mysqli_num_rows($result);
    if (mysqli_num_rows($result) > 0) {
        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'pdf') {
            while ($row = mysqli_fetch_assoc($result)) {
                $htmlContent.="<tr>
                                      <td align=\"center\">$sno</td>
                                      <td align=\"left\">" . $row['studentname'] . "</td>
                                      <td>" . $row['class'] . "</td>
                                      <td>" . $row['feeamount'] . "</td>
                                      <td>" . $row['otherfee'] . "</td>
                                 </tr>";
                $totalFeeAmt+=$row['feeamount'];
                $totalOtherFee+= $row['otherfee'];
                $sno++;
            }
            $htmlContent.=" <tr  >
                        <td >&nbsp;</td>
                        <td ><strong>TOTAL AMOUNT</strong></td>
                        <td></td>
                        <td><strong>$totalFeeAmt</strong></td>
                        <td><strong>$totalOtherFee</strong></td>
                    </tr>
                </table></body></html></page>";
            return $htmlContent;
        }
        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'xls') {
            while ($row = mysqli_fetch_assoc($result)) {
                $detail[] = $row;
                $sno++;
            }
            return $detail;
        }
    } else {
        return 0;
    }
}
