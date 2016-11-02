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
    $content = adjustedAmountReport();
    $html2pdf = new HTML2PDF('P', 'A4', 'en');
    $html2pdf->WriteHTML($content); // in $content you put your content to have in pdf
    $html2pdf->Output('fee_collection_report.pdf');
    $html2pdf->Output('pdf/fee_collection_report.pdf', 'F');
}

if (isset($searchTerm['action']) && $searchTerm['action'] == 'xls') {
    require_once'../PHPExcel-1.8/Classes/PHPExcel.php';
    $instsessassocid = $_SESSION['instsessassocid'];
    $content = studentdetails($instsessassocid);
    $branchDetails = getInstdetails();
    $filename = $branchDetails['instituteabbrevation'] . "-due-fee-report" . date('d/m/Y H:i:s') . ".xls";
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->getProperties()->setCreator("Central Academy")
            ->setTitle("Adjusted Fee Report");

    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'SNO')
            ->setCellValue('B1', 'SCHOLAR NO.')
            ->setCellValue('C1', 'STUDENT NAME')
            ->setCellValue('D1', 'CLASS')
            ->setCellValue('E1', 'ORIGINAL AMOUNT')
            ->setCellValue('F1', 'COLLECTED AMOUNT')
            ->setCellValue('G1', 'REMARKS');

    $cellNo = 2;
    $sno = 1;
    $totalOriginalAmount = 0;
    $totalcollectedamount = 0;
    $totaladjustedAmount = 0;
    unset($content['totalrows']);
    foreach ($content as $key => $value) {
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $cellNo, $sno)
                ->setCellValue('B' . $cellNo, $value['scholarnumber'])
                ->setCellValue('C' . $cellNo, $value['firstname'])
                ->setCellValue('D' . $cellNo, $value['classdisplayname'])
                ->setCellValue('E' . $cellNo, $value['totaloriginalfees'])
                ->setCellValue('F' . $cellNo, $value['totaladjustedfees'])
                ->setCellValue('G' . $cellNo, $value['remarks']);

        $cellNo++;
        $sno++;
        $totalOriginalAmount +=$value['totaloriginalfees'];
        $totaladjustedAmount +=$value['totaladjustedfees'];
        $grandTotal = $totalOriginalAmount - $totaladjustedAmount;
    }

    $cellNo++;
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('D' . $cellNo, 'Total Amount')
            ->setCellValue('E' . $cellNo, formatcurrencypdf($totalOriginalAmount))
            ->setCellValue('F' . $cellNo, formatcurrencypdf($totaladjustedAmount))
            ->setCellValue('G' . $cellNo, 'Differance Amount')
            ->setCellValue('H' . $cellNo, formatcurrencypdf($grandTotal));

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

function studentdetails($instsessassocid)
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

    $finalSql = $sql . "GROUP BY t15.tblid, t1.firstname ASC ";

    $result = dbSelect($finalSql);
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

function adjustedAmountReport()
{
    $branchDetails = getInstdetails();
    $reportArray = studentdetails($_SESSION['instsessassocid']);
    unset($reportArray['totalrows']);
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
                        <h3>FEE ADJUSTED REPORT</h3>
                            <table align=\"center\" width=\"100%\">
                                <tr>
                                    <td ><strong>SNO</strong></td>
                                    <td ><strong>SCHOLAR NO</strong></td>
                                    <td ><strong>STUDENT NAME</strong></td>
                                    <td ><strong>CLASS</strong></td>
                                    <td ><strong>ORIGINAL AMOUNT </strong></td>
                                    <td ><strong>ADJUSTED AMOUNT</strong></td>
                                    <td ><strong>REMARKS</strong></td>
                                </tr>";
        $sno = 1;

        $totalOriginalfee = 0;
        $totalCollectedfee = 0;
        foreach ($reportArray as $key => $value) {
            $totalOriginalfee += $value['totaloriginalfees'];
            $totalCollectedfee += $value['totaladjustedfees'];
            $htmlContent.=" <tr>
                               <td> $sno</td>
                               <td> $value[scholarnumber]</td>
                               <td> $value[firstname]  $value[middlename] $value[lastname]</td>
                               <td> $value[classdisplayname] -  $value[sectionname]</td>
                               <td> " . formatcurrencypdf($value['totaloriginalfees']) . " </td>
                               <td> " . formatcurrencypdf($value['totaladjustedfees']) . " </td>
                              <td width=\"100\"> $value[remarks]</td>
                            </tr>";
            $sno++;
        }

        $diffamount = formatcurrencypdf($totalOriginalfee - $totalCollectedfee);
        $htmlContent.=" 
                    <tr>
                        <td colspan=\"4\"></td>
                        <td >Total Amount:<strong>" . formatcurrencypdf($totalOriginalfee) . "</strong></td>
                        <td >Total Collected:<strong>" . formatcurrencypdf($totalCollectedfee) . "</strong></td>
                        <td >Difference:<strong>$diffamount</strong></td>
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
