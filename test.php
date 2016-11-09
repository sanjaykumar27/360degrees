

<?php
  require_once "config/config.php";
  require_once DIR_FUNCTIONS;
  require_once VIEW_HEADER;

//$date = "07/13/2012";
//echoThis(date('Y/m/d', strtotime($date))); die;
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

        <title>PHPExcel Reader Example #05</title>

    </head>
    <body>

        <h1>PHPExcel Reader Example #05</h1>
        <h2>Simple File Reader using the "Read Data Only" Option</h2>
        <?php
          /** Include path * */
          /** PHPExcel_IOFactory */
          include 'PHPExcel-1.8/Classes/PHPExcel/IOFactory.php';
          $inputFileType = 'Excel5';
//	$inputFileType = 'Excel2007';
//	$inputFileType = 'Excel2003XML';
//	$inputFileType = 'OOCalc';
//	$inputFileType = 'Gnumeric';
          $inputFileName = '/home/sanjay/Videos/chb updated fee/CHB/FeeCollection_XII_SCI.xls';
          echo 'Loading file ', pathinfo($inputFileName, PATHINFO_BASENAME), ' using IOFactory with a defined reader type of ', $inputFileType, '<br />';
          $objReader = PHPExcel_IOFactory::createReader($inputFileType);
          echo 'Turning Formatting off for Load<br />';
          $objReader->setReadDataOnly(true);
          $objPHPExcel = $objReader->load($inputFileName);
          echo '<hr />';
          $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);

          for ($i = 0; $i < 13; $i++) {
              unset($sheetData[$i]);
          }

          foreach ($sheetData as $key => &$row) {
              $row = array_filter($row, function($cell) {
                  return !is_null($cell);
              }
              );
              if (count($row) == 0) {
                  unset($sheetData[$key]);
              }
          }
          unset($row);
          
          $sheetData = array_slice($sheetData,0,count($sheetData)-2);
          $cnt = 0;

         // echoThis($sheetData);
          foreach ($sheetData as $key => $data) {
              $intsessassocid = $_SESSION['instsessassocid'];
              if ($data['W'] == 'Cash') {
                  $feemodeid = '305';
              } else {
                  $feemodeid = '304';
              }

              $feeruleid = '-';
              $transferFee = 0;

              if ($data['Y'] != '-') {
                  $feeruleid = getfeeruleid($data[Y]);
              }

              if ($data['Y'] == 'Transfer Fee') {
                  $transferFee = 200;
              }


              $sql[] = "INSERT INTO `Fee_Data`(`scholarnumber`, `intsessassocid`, `studentname`, `installment`,
                 `amount`, `Late_Fees`, `Conveyance`, `Penalty`, `TC`, `Other_Charges`, `Bounce_Cheque`, 
                 `Collection_Amount`, `recieptid`, `feemodeid`, `dateofcollection`,`feeruleid`, `transferfee`) 
                 
                VALUES('$data[B]', '$intsessassocid',  '$data[D]', '$data[H]', '$data[J]', '$data[K]', 
                '$data[L]', '$data[M]', '$data[N]', '$data[O]', '$data[Q]', '$data[S]', '$data[U]',
                 '$feemodeid', '$data[X]','$feeruleid', '$transferFee')";

              $transferFee = 0;

              $cnt++;
          }
         // echoThis($sql); die;
          $result = dbInsert($sql);

          echoThis("Total Rows Imported : " . $cnt);
        ?>
    <body>
</html>

<?php

  function getfeeruleid($rulename) {

      if ($ruleName == '25% Management Rebate') {
          $ruleName = 'MANAGEMENT RULE 25%';
      }
      $intsessassocid = $_SESSION['instsessassocid'];
      $sql = "SELECT `feeruleid` FROM `tblfeerule` WHERE `feerulename` = '$rulename'  AND `instsessassocid` = '$intsessassocid'";

      $result = dbSelect($sql);
      if (mysqli_num_rows($result) > 0) {
          $row = mysqli_fetch_assoc($result);
          $feeruleName = $row['feeruleid'];
          return $feeruleName;
      } else {
          return 0;
      }
  }
?>