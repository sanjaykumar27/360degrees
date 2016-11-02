<?php

  /*
   * 360 - School Empowerment System.
   * Developer: Prateek Mathur (pmathur@ebizneeds.com) | www.ebizneeds.com.au
   * Page details here: Report for student information in PDF and excel file format
   * Updates here:
   */

  require_once "../config/config.php";
  require_once DIR_FUNCTIONS;
  $searchTerm = cleanVar($_REQUEST);

  if (isset($searchTerm['action']) && $searchTerm['action'] == 'pdf') {
      require_once('../html2pdf/html2pdf.class.php');
      $content = ob_get_clean();
      if ($searchTerm['type'] == 'quick') {
          $content = quickstudentReport($searchTerm);
      }
      if ($searchTerm['type'] == 'detail') {
          $content = detailstudentReport($searchTerm);
      }

      $html2pdf = new HTML2PDF('P', 'A4', 'en');
      $html2pdf->WriteHTML($content); // in $content you put your content to have in pdf
      $html2pdf->Output('fee_collection_report.pdf');
      $html2pdf->Output('pdf/fee_collection_report.pdf', 'F');
  }

  if (isset($searchTerm['action']) && $searchTerm['action'] == 'xls') {
      require_once'../PHPExcel-1.8/Classes/PHPExcel.php';
      //$content=  getInstituteClassSection();
      $branchDetails = getInstdetails();
      $totaloldstudents = $totalnewstudents = $totalleftstudents = $totaltransferredstudents = $totalmalestudents = 0;
      $totalfemalestudents = $totalgenstudents = $totalobcstudents = $totalscstudents = $totalststudents = 0;

      $objPHPExcel = new PHPExcel();
      $objPHPExcel->getProperties()->setCreator("Central Academy")
              ->setTitle("Fee Collection Report");

      $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A1', 'CLASS')
              ->setCellValue('B1', 'OLD')
              ->setCellValue('C1', 'NEW')
              ->setCellValue('D1', 'LEFT')
              ->setCellValue('E1', 'TRANSFERRED')
              ->setCellValue('F1', 'TOTAL')
              ->setCellValue('G1', 'MALE')
              ->setCellValue('H1', 'FEMALE')
              ->setCellValue('I1', 'GEN')
              ->setCellValue('J1', 'OBC')
              ->setCellValue('K1', 'SC')
              ->setCellValue('L1', 'ST');

      $cellNo = 2;
      $totalstudents = 0;

      foreach ($content as $key => $value) {
          $TOTALNEW = array_sum(getStudentsType("NEW", $value['clsecassocid']));
          $TOTALOLD = array_sum(getStudentsType("OLD", $value['clsecassocid']));
          $TOTALTRANSFERED = array_sum(getStudentsType("TRANSFER", $value['clsecassocid']));
          $TOTALLEFT = array_sum(getStudentsType("LEFT", $value['clsecassocid']));
          $TOTALGEN = array_sum(getStudentsCategory("GENERAL", $value['clsecassocid']));
          $TOTALSC = array_sum(getStudentsCategory("SC", $value['clsecassocid']));
          $TOTALST = array_sum(getStudentsCategory("ST", $value['clsecassocid']));
          $TOTALOBC = array_sum(getStudentsCategory("OBC", $value['clsecassocid']));
          $TOTALMALE = array_sum(getStudentsGender("MALE", $value['clsecassocid']));
          $TOTALFEMALE = array_sum(getStudentsGender("FEMALE", $value['clsecassocid']));
          $TOTALSTUDENT = $TOTALNEW + $TOTALOLD + $TOTALTRANSFERED + $TOTALLEFT;

          $totaloldstudents += $TOTALOLD;
          $totalnewstudents += $TOTALNEW;
          $totalleftstudents += $TOTALLEFT;
          $totaltransferredstudents += $TOTALTRANSFERED;
          $totalmalestudents += $TOTALMALE;
          $totalfemalestudents += $TOTALFEMALE;
          $totalgenstudents += $TOTALGEN;
          $totalobcstudents += $TOTALOBC;
          $totalscstudents += $TOTALSC;
          $totalststudents += $TOTALST;

          $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A' . $cellNo, $key)
                  ->setCellValue('B' . $cellNo, $TOTALOLD)
                  ->setCellValue('C' . $cellNo, $TOTALNEW)
                  ->setCellValue('D' . $cellNo, $TOTALLEFT)
                  ->setCellValue('E' . $cellNo, $TOTALTRANSFERED)
                  ->setCellValue('F' . $cellNo, $TOTALSTUDENT)
                  ->setCellValue('G' . $cellNo, $TOTALMALE)
                  ->setCellValue('H' . $cellNo, $TOTALFEMALE)
                  ->setCellValue('I' . $cellNo, $TOTALGEN)
                  ->setCellValue('J' . $cellNo, $TOTALOBC)
                  ->setCellValue('K' . $cellNo, $TOTALSC)
                  ->setCellValue('L' . $cellNo, $TOTALST)
          ;

          $cellNo++;
      }
      $cellNo++;
      $overallstrength = $totaloldstudents + $totalnewstudents + $totalleftstudents + $totaltransferredstudents;

      $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A' . $cellNo, 'Total')
              ->setCellValue('B' . $cellNo, $totaloldstudents)
              ->setCellValue('C' . $cellNo, $totalnewstudents)
              ->setCellValue('D' . $cellNo, $totalleftstudents)
              ->setCellValue('E' . $cellNo, $totaltransferredstudents)
              ->setCellValue('F' . $cellNo, $overallstrength)
              ->setCellValue('G' . $cellNo, $totalmalestudents)
              ->setCellValue('H' . $cellNo, $totalfemalestudents)
              ->setCellValue('I' . $cellNo, $totalgenstudents)
              ->setCellValue('J' . $cellNo, $totalobcstudents)
              ->setCellValue('K' . $cellNo, $totalscstudents)
              ->setCellValue('L' . $cellNo, $totalststudents);

      $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
      $objWriter->save(str_replace('.php', '.xlsx', __FILE__));

      $filename = $branchDetails['instituteabbrevation'] . "-student-status-report" . date('d/m/Y H:i:s') . ".xls";
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

  function quickstudentReport($searchTerm) {
      $branchDetails = getInstdetails();
      $studentArray = getStudent();

      if (isset($studentArray)) {

          foreach ($studentArray as $key => $value) {
              /* get all class name */
              $student_count_per_class[] = $value['classdisplayname'];
              $classArray[] = $value['classdisplayname'];
              /* get all males and females */
              $count[] = $value['gender'];
              /* get all categories */
              $category_count[] = $value['category'];
          }

          /* count total males and females using group by */
          $gender_count = array_count_values($count);

          /* count total categories and group by 
           * making list of unique categories for table heading 
           */
          $category_count = array_count_values($category_count);

          /* get all categories unique */
          $category_list = array_keys($category_count);

          /* sorting categories alphabetically and removing unwanted values */
          $category_list[] = asort($category_list);
          foreach ($category_list as $k => $value) {
              if ($value == 1) {
                  unset($category_list[$k]);
              }
          }

          /* sorting alphabetically and total and removing non required element */
          $category_count[] = ksort($category_count);
          foreach ($category_count as $k => $value) {
              if ($k == '') {
                  unset($category_count[$k]);
              }
          }


          /* it counts same values in a array */
          $student_count_per_class = array_count_values($student_count_per_class);
          $total = array_sum($student_count_per_class);

          /* array_unique give unique values from array discards duplicates and
           * array_values resets the key to serial Number
           * classArray has all the classes */
          $classArray = array_values(array_unique($classArray));

          /* making seperate array for gender, section and category
           * finding unique section from class
           */
          foreach ($classArray as $key => $value) {
              foreach ($studentArray as $k => $val) {
                  /* group gender and section name according to class */
                  if ($val['classdisplayname'] == $value) {
                      $gender[$value][] = $val['gender'];
                      $section[$value][] = $val['sectionname'];
                      $category[$value][] = $val['category'];
                  }
              }
          }


          /* creating unique array of section group by class
           * counting males and females group by class
           * doing category classification and counting group by class
           */
          foreach ($gender as $key => $value) {
              $section[$key] = array_values(array_unique($section[$key]));
              $gender_count[$key] = array_count_values($value);
              foreach ($category_list as $val) {
                  $category_total[$key] = array_count_values($category[$key]);
              }
          }

          /* making key of the array if they not found in class */
          foreach ($category_total as $key => $value) {
              foreach ($category_list as $k => $val) {
                  if (array_key_exists($val, $value)) {
                      continue;
                  } else {
                      $category_total[$key][$val] = '-';
                  }
              }
          }


          /*           * ***** this sorts the category_total array by key ********** */
          foreach ($category_total as $k => $val) {
              $val = ksort($category_total[$k]);
          }

          if ($student_count_per_class) {
              $htmlContent = "<page>
                            <html>
                                <head>
                                    <style>
                                        table
                                            {   
                                                width:100%;
                                                border: solid 1px #000000; 
                                                padding-top:20px;
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
                                <body>
                                ";

              $htmlContent.= " <h1>$branchDetails[institutename]</h1>
                        <h5> Address : $branchDetails[instituteaddress1],$branchDetails[instituteaddress2]</h5>
                        <h3>STUDENT STATUS REPORT </h3><br>
                         <h5>Quick Summary</h5>
                            <table align=\"center\">
                                <tr>
                                    <td><strong>Class [section range]</strong></td>
                                    <td><strong>Total Student</strong></td>
                                    <td><strong>Boys</strong></td>
                                    <td><strong>Girls</strong></td>";
              foreach ($category_list as $key => $val) {
                  $htmlContent.= "<td><strong>$val</strong></td>";
              }
              $htmlContent.="</tr>";

              foreach ($student_count_per_class as $key => $value) {
                  $total = array_sum($student_count_per_class);

                  $htmlContent.="<tr>
                            <td width=200>" . $key . ' [ ' . reset($section[$key]) . ' - ' . end($section[$key]) . ' ]' . "</td>
                            <td>" . $value . "</td>";
                  foreach ($gender_count[$key] as $k => $val) {

                      $htmlContent.="<td>" . $val . "</td>";
                  }

                  foreach ($category_total[$key] as $k => $val) {

                      $htmlContent.="<td>" . $val . "</td>";
                  }


                  $htmlContent.="
                            
                     </tr>";
              }

              $htmlContent.="<tr>
                    <td><strong>TOTAL</strong></td>
                    <td><strong>" . $total . "</strong></td>
                    <td><strong>" . $gender_count['Male'] . "</strong></td>
                    <td><strong>" . $gender_count['Female'] . "</strong></td>";
              foreach ($category_count as $k => $val) {

                  $htmlContent.="<td><strong>" . $val . "</strong></td>";
              }
              $htmlContent.="</tr>
                    </table></body></html></page>";

              return $htmlContent;
          } else {
              return 0;
          }
      }
  }

  /* function for detail student */

  function detailstudentReport($searchTerm) {
      $branchDetails = getInstdetails();
      $studentArray = getStudent();
      if (isset($studentArray)) {

          foreach ($studentArray as $key => $value) {
              $classArray[] = $value['classdisplayname'] . ' - ' . $value['sectionname'];
              $studentArray[$key]['classname'] = $value['classdisplayname'] . ' - ' . $value['sectionname'];
          }
          /* create class array with unique names removes duplicates */
          $classArray = array_unique($classArray);
          if ($classArray) {
              $htmlContent = "<page>
                            <html>
                                <head>
                                    <style>
                                        table
                                            {   
                                                width:100%;
                                                border: solid 1px #000000; 
                                                padding-top:20px;
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
                                <body>
                                ";

              $htmlContent.= " <h1>$branchDetails[institutename]</h1>
                        <h5> Address : $branchDetails[instituteaddress1],$branchDetails[instituteaddress2]</h5>
                        <h3>STUDENT STATUS REPORT </h3><br>
                         <h5>Detail Summary</h5>";

              foreach ($classArray as $k => $val) {
                  $htmlContent.="<h3>" . $val . "</h3> 
                           <table align=center>
                              <tr>
                                  <td>Scholar No</td>
                                  <td>Student Name</td>
                                  <td>Father Name</td>
                                  <td>Gender</td>
                                  <td>Category</td>
                              </tr>";

                  foreach ($studentArray as $key => $value) {

                      if ($value['classname'] == $val) {
                          $htmlContent.="<tr>
                                                 <td>" . $value['scholarnumber'] . "</td>
                                                 <td>" . $value['firstname'] . ' ' . $value['middlename'] . ' ' . $value['lastname'] . "</td>
                                                 <td>" . $value['parentfirstname'] . ' ' . $value['parentmiddlename'] . ' ' . $value['parentlastname'] . "</td>
                                                 <td>" . $value['gender'] . "</td>
                                                 <td>". $value['category']."</td>
                                            </tr> ";
                      }
                  }
                  $htmlContent.="</table>";
              }
              $htmlContent.="</body></html></page>";

              return $htmlContent;
          } else {
              return 0;
          }
      }
  }

  function getInstdetails() {
      $instsessassocid = $_SESSION['instsessassocid'];

      $sqlBranchDetail = " SELECT UPPER(institutename) as institutename,institutelogo, 
                            TRIM(instituteaddress1) as instituteaddress1 ,TRIM(instituteaddress2) as instituteaddress2,
                            institutephone1, instituteemail1 , instituteabbrevation
                            
                            FROM tblinstitute as t1, 
                            tblinstsessassoc as t2 
                            
                            WHERE t1.instituteid=t2.instituteid 
                            AND t2.instsessassocid=$instsessassocid ";

      $resBranch = dbSelect($sqlBranchDetail);
      $branchDetails = mysqli_fetch_assoc($resBranch);
      return $branchDetails;
  }

  /* this function return the all the student according to 
   * class , section , session or all.
   * Made By: Sanjay Kumar
   * Date: 14/10/2016
   */

  function getStudent() {
      $studentArray = array();
      $sql = "  select t1.studentid, t1.scholarnumber, t1.firstname, t1.middlename, t1.lastname,
                t2.classid, t3.clsecassocid, t5.collectionname as gender,
                t6.classdisplayname, t7.sectionname,
                t8.parentfirstname, t8.parentmiddlename, t8.parentlastname
       
                FROM tblstudent as t1,
                tblclsecassoc as t2,
                tblstudentacademichistory as t3,
                tblstudentdetails as t4,
                tblmastercollection as t5,
                tblclassmaster as t6,
                tblsection as t7,
                tblparent as t8,
                tbluserparentassociation as t9
                
                where 
                
                t3.studentid = t1.studentid AND
                t3.clsecassocid = t2.clsecassocid AND
                t2.classid = t6.classid AND
                t2.sectionid = t7.sectionid AND
                t4.studentid = t3.studentid AND
                t5.mastercollectionid = t4.gender AND
                t9.studentid = t1.studentid AND
                t8.parentid = t9.parentid
                AND t1.instsessassocid = '$_SESSION[instsessassocid]' AND t1.status = 1 
                AND t1.deleted = 0 AND t1.tcissued = 0
                GROUP BY t1.studentid ORDER BY t2.classid, t7.sectionid
               ";

      $result = dbSelect($sql);
      if (mysqli_num_rows($result) > 0) {
          while ($rows = mysqli_fetch_assoc($result)) {
              $studentArray[] = $rows;
              $studentid[] = $rows['studentid'];
          }
      }

      $category = getCategory(); /* get categories of all the student of the session */

      /* merging student category with studentArray by comparing studentid */
      foreach ($studentArray as $key => $value) {
          $studentArray[$key]['category'] = '';
          foreach ($category as $k => $val) {
              if ($val['studentid'] == $value['studentid']) {
                  $studentArray[$key]['category'] = $val['category'];
              }
          }
      }

      /* checking if the categories are emply then initializing it with NA */
      foreach ($studentArray as $key => $value) {
          if (empty($value['category'])) {
              $studentArray[$key]['category'] = 'NA';
          }
      }
      return $studentArray;
  }

  /* this function gives the categories of the student of the session 
   * Parent function: getStudent()
   * Made by: Sanjay Kumar
   * Date; 25/10/2016
   */

  function getCategory() {

      $sql = " select t1.studentid, t1.scholarnumber,
               t2.collectionname as category
               
                FROM tblstudent as t1,
                tblmastercollection as t2,
                tblstudentdetails as t3
                
                where 
                
                t3.studentid = t1.studentid AND 
                t2.mastercollectionid = t3.category
                AND t1.instsessassocid = $_SESSION[instsessassocid]
                AND t1.status = 1
                AND t1.deleted = 0 AND t1.tcissued = 0
                ORDER BY t2.collectionname ASC
                ";

      $result = dbSelect($sql);
      if (mysqli_num_rows($result) > 0) {
          while ($rows = mysqli_fetch_assoc($result)) {
              $category[] = $rows;
          }
      }

      return $category;
  }
  