<?php 

    /*
    * 360 - School Empowerment System.
    * Developer: Ankur Mishra (amishra@ebizneeds.com.au) | www.ebizneeds.com.au
    * Page details here: Display bankCSV Upload data in PDF format
    * Updates here:
    */

    /* Assign the breadcrumb page name for current page */
  
    require_once "../config/config.php";
    require_once '../lib/reportfunctions.php';
    require_once DIR_FUNCTIONS;
    
    $searchTerm = cleanVar($_REQUEST);
    if (isset($searchTerm['action']) && $searchTerm['action']=='pdf') {
        require_once('../html2pdf/html2pdf.class.php');
        $content = ob_get_clean();
        $content=  collectionReportPDF();
     
        $html2pdf = new HTML2PDF('P', 'A4', 'en');
        $html2pdf->WriteHTML($content); // in $content you put your content to have in pdf
        $html2pdf->Output('fee_collection_report.pdf');
        $html2pdf->Output('pdf/fee_collection_report.pdf', 'F');
    }
    
    if (isset($searchTerm['action']) && $searchTerm['action']=='xls') {
        require_once'../PHPExcel-1.8/Classes/PHPExcel.php';
        $content=  getStudentFeeDetails();
        unset($content['totalrows']);
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("Central Academy")
                    ->setTitle("Fee Collection Report");

        $branchDetails = getInstdetails();
        
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'SCHOLAR NO.')
        ->setCellValue('B1', 'STUDENT NAME')
        ->setCellValue('C1', 'CLASS')
        ->setCellValue('D1', 'DUE DATE')
        ->setCellValue('E1', 'DUE FEES')
        ->setCellValue('F1', 'PAYMENT DATE')
        ->setCellValue('G1', 'FEE PAID');
        
        $cellNo=2;
        $totalFeeAmount=0;
        
        foreach ($content as $key=>$value) {
            $tran_amt = 0 ;
            if(!empty($value['tran_amount'])){
                $tran_amt = $value['tran_amount'];
            }
            $payment_due_date =  date('d/m/Y', strtotime($value['payment_due_date']));
            $tran_date =         date('d/m/Y', strtotime($value['tran_date']));
            $studentName =       ucfirst($value['firstname']. " ".$value['middlename']." ".$value['lastname']);
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$cellNo, $value['scholarnumber'])
            ->setCellValue('B'.$cellNo, $studentName)
            ->setCellValue('C'.$cellNo, $value['classname']. " - " .$value['sectionname'])
            ->setCellValue('D'.$cellNo, $payment_due_date)
            ->setCellValue('E'.$cellNo, formatcurrencypdf($value['amount']))
            ->setCellValue('F'.$cellNo, $tran_date)
            ->setCellValue('G'.$cellNo, formatcurrencypdf($tran_amt));
            $totalFeeAmount+= $tran_amt;
            
            $cellNo++;
        }
        $cellNo++;
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('F'.$cellNo, 'Total Amount')
        ->setCellValue('G'.$cellNo, formatcurrencypdf($totalFeeAmount));
        
 
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(str_replace('.php', '.xlsx', __FILE__));
        
        $filename = $branchDetails['instituteabbrevation']."-bank-csv-report". date('d/m/Y H:i:s').".xls";
        
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
    
    function collectionReportPDF()
    {
        $branchDetails = getInstdetails();
        $detailArray = getStudentFeeDetails();
        unset($detailArray['totalrows']);
       // echoThis($detailArray); die;
        if ($detailArray) {
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
                                                text-decoration:underline;
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
            $htmlContent.= " <h1>$branchDetails[institutename]</h1>
                        <h6> Address : $branchDetails[instituteaddress1],$branchDetails[instituteaddress2]</h6>
                        <h3>BANK FEE COLLECTION REPORT </h3>
                            <table align=\"center\">
                                <tr>
                                    <td ><strong>SCHOLAR NO</strong></td>
                                    <td ><strong>STUDENT NAME</strong></td>
                                    <td ><strong>CLASS</strong></td>
                                    <td ><strong>DUE DATE</strong></td>
                                    <td ><strong>DUE FEE</strong></td>
                                    <td ><strong>PAYMENT DATE</strong></td>
                                    <td ><strong>FEE PAID</strong></td>
                                </tr>";
            $sno=1;
            $totalFeeAmt=0;
            $totalOtherFee=0;
        
            foreach ($detailArray as $key=>$value) {
                $payment_due_date =  date('d/m/Y', strtotime($value['payment_due_date']));
                $tran_date        =  date('d/m/Y', strtotime($value['tran_date']));
                $studentName      =  ucfirst($value['firstname']. " ".$value['middlename']." ".$value['lastname']);
                $tran_amt = 0 ;
                if(!empty($value['tran_amount'])){
                   $tran_amt = $value['tran_amount']; 
                }
                $htmlContent.="<tr>
                               <td> $value[scholarnumber]</td>
                               <td> $studentName </td>
                               <td> $value[classname] - $value[sectionname]</td>
                               <td> $payment_due_date </td>
                               <td> ".formatcurrencypdf($value['amount'])."</td>
                               <td> $tran_date</td>
                               <td> ".formatcurrencypdf($tran_amt)."</td>
                            </tr>";
                $sno++;
                $totalFeeAmt += $value['amount'] + $value['tran_amount'];
            }
            $htmlContent.=" <tr>
                        <td>&nbsp;</td>
                        <td colspan=5 style=text-align:center><strong>TOTAL AMOUNT</strong></td>
                        <td><strong>Rs ".formatcurrencypdf($totalFeeAmt)."</strong></td>
                        
                    </tr>
                </table></body></html></page>";
        
            return $htmlContent;
        } else {
            return 0;
        }
    }
    
    
    function getStudentFeeDetails()
    {
        $startPage = (int) (!isset($_REQUEST['page']) ? 0 :($_REQUEST['page']-1)*ROW_PER_PAGE);
        $details = cleanVar($_REQUEST);
        $sql = "SELECT t1.studentid , t1.feecollectionid,  SUM(t2.feeinstallmentamount) AS amount, 
              t3.father_name, t3.datecreated, t4.firstname, t4.middlename, t4.lastname, t4.scholarnumber,
              t7.classname, t8.sectionname, t3.payment_due_date, t3.tran_amount, t3.tran_date

                FROM `tblfeecollection` AS t1,
                `tblfeecollectiondetail` AS t2,
                `tblbanktransdetails` AS t3,
                `tblstudent` AS t4,
                `tblstudentacademichistory` AS t5,
                `tblclsecassoc` AS t6,
                `tblclassmaster` AS t7,
                `tblsection` AS t8

                WHERE t1.feecollectionid = t2.feecollectionid
                AND t1.feecollectionid = t3.feecollectionid
                AND t1.studentid = t4.studentid
                AND t4.studentid = t5.studentid
                AND t5.clsecassocid = t6.clsecassocid
                AND t6.classid = t7.classid
                AND t6.sectionid = t8.sectionid
            ";
      
        if (!empty($details['scholarnumber'])) {
            $sql .= " AND t4.scholar_no = '$details[scholarnumber]'";
        }
        if (!empty($details['studentname'])) {
            $sql .= " AND   t4.firstname LIKE '$details[studentname]%' ";
        }
        if (!empty($details['classid'])) {
            $sql .= " AND   t7.classid = '$details[classid]' ";
        }
            
        if (!empty($details['sectionid'])) {
            $sql .= " AND   t8.sectionid = '$details[sectionid]' ";
        }
            
        if (!empty($details['paymentmode'])) {
            $sql .= " AND   t1.payer_opted_mode = '$details[paymentmode]' ";
        }
            
            
        $limit = "  GROUP BY t1.studentid   LIMIT $startPage,".ROW_PER_PAGE;
        $finalSql = $sql. $limit;
        $result = dbSelect($finalSql);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $studentDetails[] = $row;
            }
            $studentDetails['totalrows']= mysqli_num_rows(dbSelect($sql));
            return $studentDetails;
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
        
      $resBranch=  dbSelect($sqlBranchDetail);
      $branchDetails = mysqli_fetch_assoc($resBranch);
      return $branchDetails;
  }
