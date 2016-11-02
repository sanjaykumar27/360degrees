<?php

/*
 * 360 - School Empowerment System.
 * Developer: Ankur Mishra (amishra@ebizneeds.com.au) | www.ebizneeds.com.au
 * Page details here: Display collected fee report in PDF format
 * Updates here:
 */

/* Assign the breadcrumb page name for current page */
require_once "../config/config.php";
require_once '../lib/reportfunctions.php';
require_once DIR_FUNCTIONS;

$searchTerm = cleanVar($_REQUEST);
if (isset($searchTerm['action']) && $searchTerm['action'] == 'pdf') {
    require_once('../html2pdf/html2pdf.class.php');
    $content = ob_get_clean();
    $content = collectionReportPDF($searchTerm);
    $html2pdf = new HTML2PDF('P', 'A4', 'en');
    $html2pdf->WriteHTML($content); // in $content you put your content to have in pdf
    $html2pdf->Output('fee_collection_report.pdf');
    $html2pdf->Output('pdf/fee_collection_report.pdf', 'F');
}

if (isset($searchTerm['action']) && $searchTerm['action'] == 'xls') {
    require_once'../PHPExcel-1.8/Classes/PHPExcel.php';
    $content = feeCollectionReport($searchTerm);
    $branchDetails = getInstdetails();
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->getProperties()->setCreator("Central Academy")
            ->setTitle("Fee Collection Report");

    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'SNO')
            ->setCellValue('B1', 'SCHOLAR NO.')
            ->setCellValue('C1', 'STUDENT NAME')
            ->setCellValue('D1', 'CLASS')
            ->setCellValue('F1', 'DATE')
            ->setCellValue('G1', 'FEE AMOUNT')
            ->setCellValue('H1', 'OTHER FEE');

    $cellNo = 2;
    $sno = 1;
    $totalFeeAmount = 0;
    $otherFeeAmount = 0;

    foreach ($content as $key => $value) {
        $studentname = $value['firstname'] . '' . $value['middlename'] . ' ' . $value['lastname'];
        $classname = $value['classname'] . '-' . $value['sectionname'];
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $cellNo, $sno)
                ->setCellValue('B' . $cellNo, $value['scholarnumber'])
                ->setCellValue('C' . $cellNo, $studentname)
                ->setCellValue('D' . $cellNo, $classname)
                ->setCellValue('E' . $cellNo, $value['dated'])
                ->setCellValue('F' . $cellNo, formatcurrencypdf($value['feeamount']))
                ->setCellValue('G' . $cellNo, formatcurrencypdf($value['otherfeeamount']));
        $totalFeeAmount+=$value['feeamount'];
        $otherFeeAmount+=$value['otherfeeamount'];

        $cellNo++;
        $sno++;
    }
    $cellNo++;
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('E' . $cellNo, 'Total Amount')
            ->setCellValue('F' . $cellNo, formatcurrencypdf($totalFeeAmount))
            ->setCellValue('G' . $cellNo, formatcurrencypdf($otherFeeAmount));


    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save(str_replace('.php', '.xlsx', __FILE__));

    $filename = $branchDetails['instituteabbrevation'] . "-fee-collection-report" . date('d/m/Y H:i:s') . ".xls";
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

function collectionReportPDF($searchTerm)
{
    $branchDetails = getInstdetails();
    $detailArray = feeCollectionReport($searchTerm, 'pdf');
    //echoThis($detailArray); die;
    if ($detailArray) {
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
                                                padding:04px; 
                                                text-align:left;
                                                background-color : #F6F6F6;
                                                height : 3px;
                                            }
                                            

                                            h1
                                            {
                                                text-align:center;
                                                font-size:24px;
                                                margin:5px;
                                                padding:0px;
                                                font-family : shree;
                                            }
                                            
                                            h3
                                            {
                                                text-align:center;
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
        $htmlContent.= " <h1>$branchDetails[institutename]</h1>
                        <h5> Address : $branchDetails[instituteaddress1],$branchDetails[instituteaddress2]</h5>
                        <h3>FEE COLLECTION REPORT </h3>
                            <table align=\"center\">
                                <tr>
                                    <td ><strong>SNO</strong></td>
                                    <td ><strong>SCHOLAR NO</strong></td>
                                    <td ><strong>STUDENT NAME</strong></td>
                                    <td ><strong>CLASS</strong></td>
                                    <td ><strong>DATE</strong></td>
                                    <td ><strong>FEE AMOUNT</strong></td>
                                    <td ><strong>OTHER FEE</strong></td>
                                    <td ><strong>FEE RECIEPT</strong></td>
                                </tr>";
        $sno = 1;
        $c = 0;
        $totalOtherFee = 0;
        $refundFee = 0;
        $totalFeeAmt = 0;
        foreach ($detailArray as $key => $value) {
            $studentname = $value['firstname'] . '' . $value['middlename'] . ' ' . $value['lastname'];
            $classname = $value['classname'] . '-' . $value['sectionname'];
            if (!empty($value['refunded'])) {
                $refundFee = $value['refunded'];
            }
            $studentname = ucfirst($studentname);
            $htmlContent.="<tr>
                               <td> $sno</td>
                               <td> $value[scholarnumber]</td>
                               <td> $studentname </td>
                               <td> $classname</td>
                               <td> $value[dated]</td>
                               <td> " . formatcurrencypdf($value['feeamount']) . "</td>
                               <td> " . formatcurrencypdf($value['otherfeeamount']) . "</td>
                               <td> $value[receiptid]</td>   
                               ";
           /* if (!empty($value['refunded'])) {
                $htmlContent.= "<td> " . formatcurrencypdf($refundFee) . "</td>";
            } else {
                $htmlContent.= "<td>  - </td>";
            }
            * 
            */
            $htmlContent.= "</tr>";
            $sno++;
            $totalFeeAmt += $value['feeamount'];
            $totalOtherFee += (int) $value['otherfeeamount'];
        }
        $htmlContent.=" <tr>
                        <td>&nbsp;</td>
                        <td colspan=4 style=text-align:center><strong>TOTAL AMOUNT</strong></td>
                        <td><strong>" . formatcurrencypdf($totalFeeAmt) . "</strong></td>
                        <td><strong>" . formatcurrencypdf($totalOtherFee) . "</strong></td>";
        if (!empty($refundFee)) {
            $htmlContent.= "<td><strong>" . formatcurrencypdf($refundFee) . "</strong></td>";
        } else {
            $htmlContent.= "<td><strong> - </strong></td>";
        }
        $htmlContent.= " </tr>
                <tr><td>&nbsp;</td>
                <td colspan=4 style=text-align:center><strong>NET FEES</strong></td>
                <td colspan=2 style=text-align:center><strong> " . formatcurrencypdf($totalFeeAmt + $totalOtherFee - $refundFee) . " </strong></td>
                 <td></td></tr>
                </table></body></html></page>";

        return $htmlContent;
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
