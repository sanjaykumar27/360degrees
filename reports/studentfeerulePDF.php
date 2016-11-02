<?php 

    /*
    * 360 - School Empowerment System.
    * Developer: Prateek Mathur (pmathur@ebizneeds.com) | www.ebizneeds.com.au
    * Page details here: PDF/EXCEL format report for student feerule association
    * Updates here:
    */

    require_once "../config/config.php";
    require_once '../lib/reportfunctions.php';
    require_once DIR_FUNCTIONS;
    
    $searchTerm = cleanVar($_REQUEST);
    if (isset($searchTerm['action']) && $searchTerm['action']=='pdf') {
        require_once('../html2pdf/html2pdf.class.php');
        $content = ob_get_clean();
        $content=  StudentFeeRuleReport();
        $html2pdf = new HTML2PDF('P', 'A3', 'en');
        $html2pdf->WriteHTML($content); // in $content you put your content to have in pdf
        $html2pdf->Output('student_fee_rule_report.pdf');
        $html2pdf->Output('pdf/student_fee_rule_report.pdf', 'F');
    }
    
    if (isset($searchTerm['action']) && $searchTerm['action']=='xls') {
        require_once'../PHPExcel-1.8/Classes/PHPExcel.php';
        $instsessassocid = $_SESSION['instsessassocid'];
        $content = getStudentFeeRuleReport();
        unset($content['totalrows']);
        $branchDetails = getInstdetails();
        
        $filename = $branchDetails['instituteabbrevation']."-student-fee-rule-report". date('d/m/Y H:i:s').".xls";
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("Central Academy")
                    ->setTitle("Student Fee Rule Report");
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'SNO')
        ->setCellValue('B1', 'SCHOLAR NO.')
        ->setCellValue('C1', 'STUDENT NAME')
        ->setCellValue('D1', 'CLASS')
        ->setCellValue('E1', 'FEE RULES')
        ->setCellValue('F1', 'INSTALLMENTS');
        
        $cellNo=2;
        $sno=1;
        $totalFeeAmount=0;
        
        foreach ($content as $key=>$value) {
            $studentName = $value['firstname'] ." ".$value['middlename']." ".$value['lastname'];
            $installmentNo = getInstallmentNumber($value['classid'], $value['installment']);
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$cellNo, $sno)
            ->setCellValue('B'.$cellNo, $value['scholarnumber'])
            ->setCellValue('C'.$cellNo, $studentName)
            ->setCellValue('D'.$cellNo, $value['classname'])
            ->setCellValue('E'.$cellNo, $value['feerulename'])
            ->setCellValue('F'.$cellNo, $installmentNo);
            $cellNo++;
            $sno++;
        }
        
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
    
    ?>
<?php 
function StudentFeeRuleReport()
{
    $branchDetails = getInstdetails();
    $reportArray=getStudentFeeRuleReport();
    unset($reportArray['totalrows']);
    if ($reportArray) {
        $htmlContent="<page>
                            <html>
                                <head>
                                    <style>
                                        table
                                            {   
                                                width:900px;
                                                border: solid 1px #000000; 
                                                padding-top : 20px;
                                                font-size:13px;
                                                cellpadding:0px;
                                                cellspacing:1px;
                                                border-collapse: collapse;
                                                font-family: arial;
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
                                                font-family: shree;
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
        $htmlContent.= " <h1>$branchDetails[institutename]</h1>
                        <h5> Address : $branchDetails[instituteaddress1],$branchDetails[instituteaddress2]</h5>
                        <h3>STUDENT FEE RULE REPORT </h3>
                            <table align=\"center\" width=\"100%\">
                                <tr>
                                    <td ><strong>SNO</strong></td>
                                    <td ><strong>SCHOLAR NO</strong></td>
                                    <td ><strong>STUDENT NAME</strong></td>
                                    <td ><strong>CLASS</strong></td>
                                    <td><strong>FEE RULES </strong></td>
                                    <td ><strong>INSTALLMENTS</strong></td>
                                </tr>";
        $sno=1;
        $totalFeeAmt=0;
        foreach ($reportArray as $key=>$value) {
            $studentName = $value['firstname'] ." ".$value['middlename']." ".$value['lastname'];
            $installmentNo = getInstallmentNumber($value['classid'], $value['installment']);
            
            $htmlContent.="<tr>
                               <td> $sno</td>
                               <td> $value[scholarnumber]</td>
                               <td> $studentName</td>
                               <td> $value[classname] - $value[sectionname]</td>
                               <td  width=\"430\"> $value[feerulename]</td>
                               <td> $installmentNo</td>
                            </tr>";
            $sno++;
        }
        $htmlContent.=" </table></body></html></page>";
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
        
      $resBranch=  dbSelect($sqlBranchDetail);
      $branchDetails = mysqli_fetch_assoc($resBranch);
      return $branchDetails;
  }
  
  
function getStudentFeeRuleReport()
{
    $instsessassocid = $_SESSION['instsessassocid'];
    $studentDetails = array();
    $detailsArray = cleanVar($_REQUEST);
    $startPage = (int) (!isset($_REQUEST['page']) ? 0 :($_REQUEST['page']-1)*ROW_PER_PAGE);
    $sql = "SELECT t1.studentid, t1.scholarnumber, t1.firstname, t1.middlename, t1.lastname,
            t5.feeruleamount, t8.classname, t9.sectionname, t8.classid,
            GROUP_CONCAT(t3.installment) AS installment, GROUP_CONCAT(t4.feerulename) AS  feerulename
             
            FROM `tblstudent` AS t1,
            `tblstudfeeruleassoc` AS t2,
            `tblstudfeeruleinstasssoc` AS t3,
            `tblfeerule` AS t4,
            `tblfeeruledetail` AS t5,
            `tblstudentacademichistory` AS t6,
            `tblclsecassoc` AS t7,
            `tblclassmaster` AS t8,
            `tblsection` AS t9


            WHERE t1.instsessassocid = '$instsessassocid'
            AND t1.studentid = t2.studentid
            AND t2.studfeeruleassocid = t3.studfeeruleassocid
            AND t2.feeruleid = t4.feeruleid
            AND t4.feeruleid = t5.feeruleid
            AND t1.studentid = t6.studentid
            AND t6.clsecassocid = t7.clsecassocid
            AND t7.classid = t8.classid
            AND t7.sectionid = t9.sectionid
            AND t3.status = 1";
           
    if (isset($detailsArray['scholarnumber']) && !empty($detailsArray['scholarnumber'])) {
        $sql .=" AND t1.scholarnumber LIKE '$detailsArray[scholarnumber]%' ";
    }
        
    if (isset($detailsArray['studentname']) && !empty($detailsArray['studentname'])) {
        $studentName = explode(" ", $_REQUEST['studentname']);

        if (count(array_keys($studentName)) == 1) {
            $sql .= " AND UPPER(t1.firstname) LIKE ('" . strtoupper(trim($studentName[0])) . "%')";
        }
                
        if (count(array_keys($studentName)) == 2) {
            $sql .= " AND ( UPPER(t1.firstname) LIKE ('" . strtoupper(trim($studentName[0])) . "%')
                          OR UPPER(t1.lastname)  LIKE ('" . strtoupper(trim($studentName[1])) . "%'))";
        }
        if (count(array_keys($studentName)) == 3) {
            $sql .= " AND ( UPPER(t1.firstname) LIKE ('" . strtoupper(trim($studentName[0])) . "%')
                          OR UPPER(t1.middlename) LIKE ('" . strtoupper(trim($studentName[1])) . "%') 
                          OR UPPER(t1.lastname) LIKE ('" . strtoupper(trim($studentName[2])) . "%') )";
        }
    }
        
    if (isset($detailsArray['classid']) && !empty($detailsArray['classid'])) {
        $sql .=" AND t7.classid = '$detailsArray[classid]' ";
    }
        
    if (isset($detailsArray['sectionid']) && !empty($detailsArray['sectionid'])) {
        $sql .=" AND t8.sectionid = '$detailsArray[sectionid]' ";
    }
        
    $sql .="  GROUP BY t1.studentid ORDER BY t8.classid, t9.sectionid, t1.firstname, t3.installment ASC";
    $finalSql = $sql;
    $result = dbSelect($finalSql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $studentDetails[] = $row;
        }
        $studentDetails['totalrows'] = mysqli_num_rows(dbSelect($sql));
        return $studentDetails;
    } else {
        return 0;
    }
}
