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
          $inputFileName = '/home/sanjay/Videos/import/Paota/StudentInformation.xls';
          echo 'Loading file ', pathinfo($inputFileName, PATHINFO_BASENAME), ' using IOFactory with a defined reader type of ', $inputFileType, '<br />';
          $objReader = PHPExcel_IOFactory::createReader($inputFileType);
          echo 'Turning Formatting off for Load<br />';
          $objReader->setReadDataOnly(true);
          $objPHPExcel = $objReader->load($inputFileName);
          echo '<hr />';
          $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
         
          for ($i = 0; $i < 16; $i++) {
              unset($sheetData[$i]);
          }
        
          foreach ($sheetData as $key => &$row) {
              $row = array_filter($row, function($cell) {
                  return !is_null($cell);
              }
              );
              if (count($row) == 0) {
                  unset($sheetData[$key]);
                //  echoThis($sheetData[$key]); 
              }
          }
        unset($row);
          
         //Delete the last element of the array
          // current excel has signtuare fields at the bottom
          //they must be deleted, before import. 
          
        $sheetData = array_slice($sheetData,0,count($sheetData)-1);
         
         
        $cnt = 0;
       
       // echoThis(($sheetData));
        // die;


          foreach ($sheetData as $key => $data) {
              $mobilenumber = "";
              $landlinenumber = "";
              if (isset($data['AB']) && strlen($data['AB']) > 10 && (strpos($data['AB'], '-') > 0)) {
                  $landlinenumber = cleanVar($data['AB']);
                  if (isset($data['AC'])) {
                      $mobilenumber = cleanVar($data['AC']);
                  }
              } elseif (isset($data['AC'])) {
                  $mobilenumber = cleanVar($data['AC']);
              }

              $scholarnumber = cleanVar($data['D']);
              $studentname = cleanVar($data['F']); 
              $fathername =  cleanVar($data['I']);
              $mothername = cleanVar($data['K']);
              $class = cleanVar($data['M']);
              $section = cleanVar($data['N']);
             
              $dob = date('Y-m-d', strtotime($data['AD']));
              $doj = date('Y-m-d', strtotime($data['AE']));
              $category = "";
              $bloodgroup = "";
              $height = "";
              $weight = "";
              $house = "";
              $hygiene = "";
              $visionleft = "";
              $visionright = "";
              $studentype = "";
              $gender = "";
              
              if (isset($data['AF'])) {
                  $category = getstudentmasterData($data['AF']);
              }
              if (isset($data['AK'])) {
                  $bloodgroup = getstudentmasterData($data['AK']);
              }

              if (isset($data['AG'])) {
                  $height = cleanVar($data['AG']);
              }

              if (isset($data['AH'])) {
                  $weight = cleanVar($data['AH']);
              }

              if (isset($data['AL'])) {
                  $hygiene = cleanVar($data['AL']);
             }

              if (isset($data['AI'])) {
                  $house = cleanVar($data['AI']);
              }

              if (isset($data['AM'])) {
                  $visionleft = cleanVar($data['AM']);
              }

              if (isset($data['AN'])) {
                  $visionright = cleanVar($data['AN']);
             }

             if (isset($data['AO'])) {
                  $studentype = getstudentmasterData($data['AO']);
             }

              if (isset($data['AP'])) {
                  $gender = getstudentmasterData($data['AP']);
              }
              
              $address = cleanVar($data['AA']);

              $sql[] = "INSERT INTO `student_data`(`scholarnumber`, `studentname`, `fathername`, `mothername`, `class`,
                 `section`, `address`, `landlineno`, `mobile`, `dob`, `doj`, `category`, `height`, `weight`,
                 `house`,  `bloodgroup`, `dentalhygiene`, `visionleft`, `visionright`, `studenttype`, 
                 `gender`) 
                 VALUES ('$scholarnumber', '$studentname', '$fathername', '$mothername', '$class', '$section',
                '$address', '$landlinenumber', '$mobilenumber', '$dob', '$doj', '$category','$height',
                '$weight', '$house','$bloodgroup','$hygiene','$visionleft','$visionright','$studentype','$gender')";

              $cnt++;
          }
        //   echoThis($sql); die;
          $result = dbInsert($sql);

          echoThis("Total Rows Imported : " . $cnt);

          function getstudentmasterData($masterCollectionName) {
              $sql = "SELECT `mastercollectionid` FROM `tblmastercollection` WHERE `collectionname` = '$masterCollectionName' ";
              $result = dbSelect($sql);
              $row = mysqli_fetch_assoc($result);
              $returnArray = $row['mastercollectionid'];
              return $returnArray;
          }
        ?>
    <body>
</html>