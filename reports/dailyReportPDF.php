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
      $content = dailyReport();
      $html2pdf = new HTML2PDF('P', 'A4', 'en');
      $html2pdf->WriteHTML($content); // in $content you put your content to have in pdf
      $html2pdf->Output('Daily_Report.pdf');
      $html2pdf->Output('pdf/fdaily_report.pdf', 'F');
  }

  if (isset($searchTerm['action']) && $searchTerm['action'] == 'xls') {
      require_once'../PHPExcel-1.8/Classes/PHPExcel.php';
      $instsessassocid = $_SESSION['instsessassocid'];
      if (isset($_GET['monthstart'])) {
          $datecreated = $_GET['monthstart'];
      } else {
          $datecreated = date('Y-m-d');
      }

      $report = feeCollectionReport($datecreated);
      $collect = getAddition($datecreated);
      $student = getStudent($datecreated);
      $tc = getTc($datecreated);
      $cheque = getCheque($datecreated);
      $other = otherFee($datecreated);
      $refunddetails = getstudentDetails($datecreated);
      $cheqBounce = getchequeBounce($datecreated);

      foreach ($cheque as $value) {
          $chequeCount = $value;
      }
      $branchDetails = getInstdetails();
      $grandTotal = 0;
      if (isset($collect)) {
          foreach ($collect as $value) {
              $grandTotal += $value['total'];
          }
      }
      if (isset($tc)) {
          foreach ($tc as $value) {
              $grandTotal +=$value['total'];
          }
      }
      if (isset($cheqBounce)) {
          foreach ($cheqBounce as $value) {
              $grandTotal +=$value;
          }
      }

      $branchDetails = getInstdetails();
      $filename = $branchDetails['instituteabbrevation'] . "-dailyReport" . date('d/m/Y H:i:s') . ".xls";
      $objPHPExcel = new PHPExcel();
      $objPHPExcel->getProperties()->setCreator("Central Academy")
              ->setTitle("Fee Collection Report");

      $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A1', 'TOTAL COLLECTION')
              ->setCellValue('C1', formatcurrencypdf($grandTotal));

      $cellNo = 2;
      $sno = 1;
      // amount aid cash or cheque -------------------
      if (isset($collect)) {
          foreach ($collect as $value) {
              if ($value['collectionname'] === 'CHEQUE') {
                  $collect = $value['collectionname'] . ' [' . $chequeCount . ']';
              } else {
                  $collect = $value['collectionname'];
              }

              $objPHPExcel->setActiveSheetIndex(0)
                      ->setCellValue('A' . $cellNo, $collect)
                      ->setCellValue('B' . $cellNo, formatcurrencypdf($value['total']));
                      

              $cellNo++;
              $sno++;
          }
      }

      // new admission --------------------
      if (isset($student)) {
          foreach ($student as $value) {
              $objPHPExcel->setActiveSheetIndex(0)
                      ->setCellValue('A' . $cellNo, 'New Admission [' . $value . ']');
              $cellNo++;
              $sno++;
          }
      }

      // TC issued ***************
      if (isset($tc)) {
          foreach ($tc as $value) {
              $objPHPExcel->setActiveSheetIndex(0)
                      ->setCellValue('A' . $cellNo, 'TC Issued [' . $value['TC'] . ']')
                      ->setCellValue('B' . $cellNo, formatcurrencypdf($value['total']));
              $cellNo++;
              $sno++;
          }
      }

      // other fees --------------
      if (isset($other)) {
          foreach ($other as $value) {
              $objPHPExcel->setActiveSheetIndex(0)
                      ->setCellValue('A' . $cellNo, $value['otherfeehead'])
                      ->setCellValue('B' . $cellNo, formatcurrencypdf($value['total']));
              $cellNo++;
              $sno++;
          }
      }

      // cheque bounce ============

      if (isset($cheqBounce)) {
          foreach ($cheqBounce as $value) {
              $objPHPExcel->setActiveSheetIndex(0)
                      ->setCellValue('A' . $cellNo, 'Cheque Bounce')
                      ->setCellValue('B' . $cellNo, formatcurrencypdf($value));
              $cellNo++;
              $sno++;
          }
      }

      // refund -----------------------
      if (isset($refunddetails)) {
          $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A' . $cellNo, 'Refund Amount')
                  ->setCellValue('C' . $cellNo, '-' . formatcurrencypdf($refunddetails));
          $cellNo++;
          $sno++;
      }


      //total amount-----------------
      $cellNo++;
      $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A' . $cellNo, 'Total Amount')
              ->setCellValue('C' . $cellNo, formatcurrencypdf($grandTotal - $refunddetails));
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

  function dailyReport() {
      if (isset($_GET['monthstart'])) {
          $datecreated = $_GET['monthstart'];
      } else {
          $datecreated = date('Y-m-d');
      }
      $report = feeCollectionReport($datecreated, '');
      $collect = getAddition($datecreated);
      $student = getStudent($datecreated);
      $tc = getTc($datecreated);
      $cheque = getCheque($datecreated);
      $other = otherFee($datecreated);
      $refunddetails = getstudentDetails($datecreated);
      $cheqBounce = getchequeBounce($datecreated);

      foreach ($cheque as $value) {
          $chequeCount = $value;
      }
      $branchDetails = getInstdetails();
      $grandTotal = 0;
      if (isset($collect)) {
          foreach ($collect as $value) {
              $grandTotal += $value['total'];
          }
      }
      if (isset($tc)) {
          foreach ($tc as $value) {
              $grandTotal +=$value['total'];
          }
      }
      if (isset($cheqBounce)) {
          foreach ($cheqBounce as $value) {
              $grandTotal +=$value;
          }
      }

      if ($branchDetails) {
          $htmlContent = "<page>
                            <html>
                                <head>
                                    <style>
                                        table
                                            {   
                                                width:1200px;
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
                                                font-family: Shree;
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
          $htmlContent.= " <h1><br>$branchDetails[institutename]</h1>
                        <h5> Address : $branchDetails[instituteaddress1],$branchDetails[instituteaddress2]</h5>
                        <h3><br>DAILY TRANSACTION REPORT<br>
                        [$datecreated]<br></h3>
                            <table align=\"center\" width=\"\">
                                <tr>
                                    <td colspan=\"2\" width=\"340\"><strong>TOTAL COLLECTION</strong></td>
                                    <td width=\"120\"><strong> " . formatcurrencypdf($grandTotal) . "</strong></td>
                                </tr>";

          if (isset($collect)) {
              foreach ($collect as $value) {
                  if ($value['collectionname'] === 'CHEQUE') {
                      $collect = $value['collectionname'] . ' [' . $chequeCount . ']';
                  } else {
                      $collect = $value['collectionname'];
                  }

                  $htmlContent.="<tr><td> --> $collect</td>
                        <td> " . formatcurrencypdf($value['total']) . "</td>
                     <td></td></tr>
                  ";
              }
          }
          if (isset($student)) {
              foreach ($student as $value) {
                  $htmlContent.="<tr>          
                      <td>--> New Admission [ " . $student['totalstudent'] . " ]</td>
                          <td></td><td></td>
                          </tr>
                            ";
              }
          }

          if (isset($tc)) {
              foreach ($tc as $value) {
                  $htmlContent.="<tr>         
                        <td> --> TC Issued [ " . $value['TC'] . " ] </td>
                        <td>" . formatcurrencypdf($value['total']) . " </td>
                        <td></td>
                        </tr>";
              }
          }
          if (isset($other)) {
              foreach ($other as $value) {
                  $htmlContent.="<tr>         
                        <td> --> " . $value['otherfeehead'] . "  </td>
                        <td>" . formatcurrencypdf($value['total']) . " </td>
                        <td></td>
                        </tr>";
              }
          }

          if (isset($cheqBounce)) {
              foreach ($cheqBounce as $value) {
                  $htmlContent.="<tr>         
                        <td> --> Cheque Bounce  </td>
                        <td>" . formatcurrencypdf($value) . " </td>
                        <td></td>
                        </tr>";
              }
          }

          if (isset($refunddetails)) {
              $htmlContent.="<tr>
                <td colspan=\"2\" align=\"right\"><strong>Refund Amount : </strong></td>
                <td><strong> " . formatcurrencypdf($refunddetails) . "
                </strong></td>
                     </tr>";
          }

          $htmlContent.=" <tr>
                        <td colspan=\"2\" align=\"right\"><strong>NEW GRAND TOTAL</strong></td>
                        <td><strong>" . formatcurrencypdf($grandTotal - $refunddetails) . "</strong></td>
                    </tr>
                </table>";
          $htmlContent.="
                <style>
.tab
{
 border: none; 
}
</style><br><br><br><p align='center'>Principle Sign: _____________ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Manager Sign:  _____________</p>
                </body></html></page>
            ";
          return $htmlContent;
      }
  }

  function getInstdetails() {
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

  function getchequeBounce($seachterm) {
      $instsessassocid = $_SESSION['instsessassocid'];
      $sql = "SELECT SUM(amount) as amount from tblotherfeepenalties WHERE
    datecreated BETWEEN '$seachterm 00:00:00' AND '$seachterm 23:59:59'
    AND instsessassocid = $instsessassocid";

      $result = dbSelect($sql);
      if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
              $chqbounce = $row;
          }
          return $chqbounce;
      }
  }

  function otherFee($seachterm) {
      $sql = "SELECT SUM(t1.feeinstallmentamount) as total, t1.collectiontype, t1.datecreated, 
	   t2.feeotherchargesid, t2.otherfeehead
       
       from tblfeecollectiondetail as t1,
       tblfeeothercharges as t2
       
       where t1.datecreated BETWEEN '$seachterm 00:00:00' AND '$seachterm 23:55:55' AND
       t1.collectiontype = t2.feeotherchargesid 
       GROUP BY t2.otherfeehead";

      $result = dbSelect($sql);
      if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
              $others[] = $row;
          }
          return $others;
      }
  }

  /* funtion to get total no of cheque on that day */

  function getCheque($searchterm) {
      $sql = "SELECT COUNT(feechecqueid) as cheque from tblfeecheque
            where datecreated BETWEEN 
            '$searchterm 00:00:00' AND '$searchterm 23:59:59'";
      $result = dbSelect($sql);
      if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
              $cheque = $row;
          }
          return $cheque;
      }
  }

  /* funtion to get the tc details of the date */

  function getTc($searchterm) {
      $sql = "SELECT COUNT(studentid) as TC , SUM(amount) as total from tblstudtc where dateofissue = '$searchterm'";
      $result = dbSelect($sql);

      if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
              $tc[] = $row;
          }
          return $tc;
      }
  }

  /* funtion to the addtion of the collected fee by cheque and cash */

  function getAddition($searchterm) {
      $amount = null;
      $sql = "SELECT SUM(t1.feeinstallmentamount) as total, t1.feemodeid, t1.datecreated, 
                t2.collectionname
                from tblfeecollectiondetail as t1, tblmastercollection as t2
                WHERE t1.datecreated BETWEEN '$searchterm 00:00:00' AND '$searchterm 23:59:59'
                AND t2.mastercollectionid = t1.feemodeid
                AND t1.feestatus = 1
                GROUP BY t1.feemodeid";

      $result = dbSelect($sql);

      if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
              $amount[] = $row;
          }
      }
      return $amount;
  }

  /* funtion to get the total no of student took admission */

  function getStudent($searchterm) {
      $sql = "SELECT COUNT(studentid) as totalstudent from tblstudent
	where datecreated BETWEEN '$searchterm 00:00:00' AND '$searchterm 23:59:59'";
      $result = dbSelect($sql);
      if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
              $student = $row;
          }
          return $student;
      }
  }

  function getstudentDetails($searchterm) {
      $totalrefamount = 0;
      $instsessassocid = $_SESSION['instsessassocid'];
      $sql = " SELECT t3.classid, t4.sectionid, t8.studentid , t9.datecreated
          
        FROM `tblstudent` AS t1,
        `tblclassmaster` AS t3,
        `tblsection` AS t4,
        `tblclsecassoc` AS  t5,
        `tblstudentacademichistory` AS t6,
        `tblparent` AS t7,
        `tblfeecollection` AS t8,
        `tblfeecollectiondetail` AS t9
        
          
        WHERE t1.instsessassocid = $instsessassocid
        AND t9.datecreated BETWEEN '$searchterm 00:00:00' AND '$searchterm 23:59:59'
        AND t1.studentid = t6.studentid
        AND t6.clsecassocid = t5.clsecassocid
        AND t5.classid = t3.classid
        AND t5.sectionid = t4.sectionid
        AND t1.studentid = t8.studentid
        AND t8.feecollectionid = t9.feecollectionid
         
        GROUP BY t1.studentid";

      $result = dbSelect($sql);
      if (mysqli_num_rows($result) > 0) {
          while ($rows = mysqli_fetch_assoc($result)) {
              $studentdetails[] = $rows;
          }
          foreach ($studentdetails as $value) {
              $refundamt[] = getRefDetails($value['studentid'], $searchterm);
          }
          foreach ($refundamt as $value) {
              $totalrefamount += $value['total'];
          }
          return $totalrefamount;
      }
  }

  function getRefDetails($studentid, $searchterm) {
      $sql = "SELECT t1.feecollectionid, t1.studentid, t1.receiptid,
            t2.feecollectiondetailid, SUM(t2.feeinstallmentamount) as total,
            t3.feerefundrecieptno, t3.datecreated

            FROM `tblfeecollection` AS t1,
            `tblfeecollectiondetail` AS t2,
            `tblfeerefund` AS t3

            WHERE t1.studentid = '$studentid'
            AND t1.feecollectionid = t2.feecollectionid
            AND t2.feecollectiondetailid = t3.feecollectiondetailid
            AND t3.datecreated BETWEEN '$searchterm 00:00:00' AND '$searchterm 23:59:59'";
      $result = dbSelect($sql);
      if (mysqli_num_rows($result) > 0) {
          while ($rows = mysqli_fetch_assoc($result)) {
              $studentRefund = $rows;
          }
          return $studentRefund;
      }
  }

?>