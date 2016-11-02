<?php

/*
 * 360 - School Empowerment System.
 * Developer: Ankur Mishra (amishra@ebizneeds.com.au) | www.ebizneeds.com.au
 * Page details here:
 * Updates here:
 */

require_once "../config/config.php";
require_once '../lib/reportfunctions.php';
require_once DIR_FUNCTIONS;

$searchTerm = cleanVar($_REQUEST);

if (isset($searchTerm['action']) && $searchTerm['action'] == 'pdf') {
    require_once('../html2pdf/html2pdf.class.php');
    $content = ob_get_clean();
    $content = feeDueReport();
    $html2pdf = new HTML2PDF('P', 'A4', 'en');
    $html2pdf->WriteHTML($content); // in $content you put your content to have in pdf
    $html2pdf->Output('fee_collection_report.pdf');
    $html2pdf->Output('pdf/fee_collection_report.pdf', 'F');
}

if (isset($searchTerm['action']) && $searchTerm['action'] == 'xls') {
    require_once'../PHPExcel-1.8/Classes/PHPExcel.php';
    $instsessassocid = $_SESSION['instsessassocid'];
    $content = studentFeeDetails('report');
    $branchDetails = getInstdetails();
    $filename = $branchDetails['instituteabbrevation'] . "-due-fee-report" . date('d/m/Y H:i:s') . ".xls";
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->getProperties()->setCreator("Central Academy")
            ->setTitle("Fee Collection Report");
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'SNO')
            ->setCellValue('B1', 'SCHOLAR NO.')
            ->setCellValue('C1', 'STUDENT NAME')
            ->setCellValue('D1', 'CLASS')
            ->setCellValue('E1', 'DUE INSTALLMENTS')
            ->setCellValue('F1', 'FEE AMOUNT');

    $cellNo = 2;
    $sno = 1;
    $totalFeeAmount = 0;

    foreach ($content['records'] as $key => $value) {
        $studentname = $value['firstname'] . '' . $value['middlename'] . ' ' . $value['lastname'];
        $classname = $value['classdisplayname'] . '-' . $value['sectionname'];
        $studentname = strtoupper($studentname);
        if ($value['feedetails'] == 0) {
            continue;
        }

        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $cellNo, $sno)
                ->setCellValue('B' . $cellNo, $value['scholarnumber'])
                ->setCellValue('C' . $cellNo, $studentname)
                ->setCellValue('D' . $cellNo, $classname)
                ->setCellValue('E' . $cellNo, $value['dueinstallments'])
                ->setCellValue('F' . $cellNo, formatcurrencypdf($value['feedetails']));

        $totalFeeAmount+=$value['feedetails'];
        $cellNo++;
        $sno++;
    }
    $cellNo++;
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('E' . $cellNo, 'Total Amount')
            ->setCellValue('F' . $cellNo, formatcurrencypdf($totalFeeAmount));

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
?>
<?php

function feeDueReport()
{
    $branchDetails = getInstdetails();
    $reportArray = studentFeeDetails('dashboard');
    
    if ($reportArray) {
        $htmlContent = "<page>
                            <html>
                                <head>
                                    <style>
                                        table
                                            {   
                                                width:900px;
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
                        <h3>FEE DUE REPORT </h3>
                            <table align=\"center\" width=\"100%\">
                                <tr>
                                    <td ><strong>SNO</strong></td>
                                    <td ><strong>SCHOLAR NO</strong></td>
                                    <td ><strong>STUDENT NAME</strong></td>
                                    <td ><strong>CLASS</strong></td>
                                    <td ><strong> DUE INSTALLMENTS </strong></td>
                                    <td ><strong>FEE AMOUNT</strong></td>
                                </tr>";
        $sno = 1;
        $totalFeeAmt = 0;
        
        foreach ($reportArray['records'] as $key => $value) {
            if ($value['feedetails'] == 0) {
                continue;
            }
            $studentname = $value['firstname'] . '' . $value['middlename'] . ' ' . $value['lastname'];
            $classname = $value['classdisplayname'] . '-' . $value['sectionname'];

            $htmlContent.="<tr>
                               <td> $sno</td>
                               <td> $value[scholarnumber]</td>
                               <td> $studentname</td>
                               <td> $classname</td>
                               <td> $value[dueinstallments]</td>
                               <td> " . formatcurrencypdf($value['feedetails']) . "</td>
                            </tr>";
            $sno++;
            $totalFeeAmt+=$value['feedetails'];
        }
        $htmlContent.=" <tr>
                        <td>&nbsp;</td>
                        <td  colspan=4 style=text-align:center><strong>TOTAL AMOUNT</strong></td>
                        <td><strong>" . formatcurrencypdf($totalFeeAmt) . "</strong></td>
                    </tr>
                </table></body></html></page>";
        return $htmlContent;
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
