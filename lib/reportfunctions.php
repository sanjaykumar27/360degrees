
<?php

  /*
   * To change this license header, choose License Headers in Project Properties.
   * To change this template file, choose Tools | Templates
   * and open the template in the editor.
   */

  function studentFeeRule($dueFeeDetails) {
      $feeRuleArray = array();
      foreach ($dueFeeDetails as $key => $value) {
          $feeRuleArray = getFeeRule($key);
          foreach ($value as $k => $val) {
              if ($feeRuleArray != 0) {
                  foreach ($feeRuleArray as $ruleKey => $ruleValue) {
                      $feeRuleInstAssoc = getRuleAssocInst($ruleValue['feeruleid'], $key);
                      foreach ($feeRuleInstAssoc as $feeRuleInstKey => $feeRuleInstValue) {
                          if ($feeRuleInstValue == $k) {
                              if (array_key_exists($ruleValue['feecomponent'], $val)) {
                                  if ($ruleValue['feeruletype'] == 261) {
                                      if ($ruleValue['feerulemodeid'] == 263) {
                                          $dueFeeDetails[$key][$k][$ruleValue['feecomponent']] = $dueFeeDetails[$key][$k][key($val)] - ($dueFeeDetails[$key][$k][key($val)] * $ruleValue['feeruleamount'] / 100);
                                      } else {
                                          $dueFeeDetails[$key][$k][$ruleValue['feecomponent']] = $dueFeeDetails[$key][$k][key($val)] - $ruleValue['feeruleamount'];
                                      }
                                  } else {
                                      if ($ruleValue['feerulemodeid'] == 263) {
                                          $dueFeeDetails[$key][$k][$ruleValue['feecomponent']] = $dueFeeDetails[$key][$k][key($val)] + ($dueFeeDetails[$key][$k][key($val)] * $ruleValue['feeruleamount'] / 100);
                                      } else {
                                          $dueFeeDetails[$key][$k][$ruleValue['feecomponent']] = $dueFeeDetails[$key][$k][key($val)] + $ruleValue['feeruleamount'];
                                      }
                                  }
                              }
                          }
                      }
                  }
              }
          }
      }
      

      return $dueFeeDetails;
  }

  function studentFeeDetails($type) {
      
      $startPage = (!isset($_GET['page'])) ? 0 : ($_GET['page'] - 1) * ROW_PER_PAGE;
      $instsessassocid = $_SESSION['instsessassocid'];
      $details = cleanVar($_REQUEST);
      $orderBy = ' GROUP BY t1.studentid  ORDER BY t3.classid, t4.sectionid, t1.firstname ASC ';
      $limit = "  LIMIT $startPage," . ROW_PER_PAGE;

      $sql = "SELECT  
            t1.studentid ,t1.scholarnumber,t1.firstname ,t1.middlename, t1.lastname,
            t2.studentid, 
            t3.classid, t3.classdisplayname,
            t4.sectionid, t4.sectionname,
            t5.clsecassocid,
            t6.clsecassocid,
            t7.studentid
     
            FROM tblstudent AS t1,
           `tblstudentdetails` AS t2, 
           `tblclassmaster` AS t3, 
           `tblsection` AS t4, 
           `tblclsecassoc` AS t5, 
           `tblstudentacademichistory` AS t6,
           `tbluserparentassociation` AS t7

            WHERE t1.instsessassocid = $instsessassocid 
            AND t1.studentid = t2.studentid  
            AND t1.studentid = t6.studentid  
            AND t5.classid = t3.classid  
            AND t6.clsecassocid = t5.clsecassocid  
            AND t5.sectionid = t4.sectionid  
            AND t1.studentid = t7.studentid
            AND t1.status = 1 
            AND t1.deleted != 1
            AND t1.tcissued != 1

        ";

      if (!empty($details['studentname'])) {
          $sql .= "AND t1.firstname  LIKE '$details[studentname]%'";
      }
      if (!empty($details['classid'])) {
          $sql .= " AND t3.classid = '$details[classid]'";
      }

      if (!empty($details['sectionid'])) {
          $sql .= " AND t4.sectionid = '$details[sectionid]' ";
      }
      if (!empty($details['parentname'])) {
          $sql .= " AND t8.parentfirstname LIKE '$details[parentname]%' ";
      }

      if ($type == 'report' ) {
          $finalSql = $sql . $orderBy. $limit;
      } elseif($type == 'dashboard') {
          $finalSql = $sql . $orderBy ;
      }
      
      
      $result = dbSelect($finalSql);
      $totalDueFee = 0;
      if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
              $feedetails = getDueFeeAmount($row['studentid'], $row['classid']);
              $feedetails = explode('|', $feedetails);
              $row['feedetails'] = $feedetails[0];
              $totalDueFee += $row['feedetails'];
              $row['dueinstallments'] = $feedetails[1];
              $studentdetails['records'][] = $row;
          }
          $studentdetails['totaldue'] = $totalDueFee;
          $studentdetails['totalrows'] = mysqli_num_rows(dbSelect($sql . ' GROUP BY t1.studentid'));
         // echoThis($studentdetails);die;
          return $studentdetails;
      } else {
          return 0;
      }
  }

  /* Function to get the Total Fee Due Amount for a student */

  function getDueFeeAmount($studentId, $classId, $month = null) {
      $instsessassocid = $_SESSION['instsessassocid'];
      $feeStructureArray =  getFeeStructure($instsessassocid, $classId, $studentId, $month);
      $feeRuleArray = getFeeRule($studentId);
     // global $totalDueFee;
      $totalDueFee = 0;
      $duedates = '';

     
          foreach ($feeStructureArray as $key => $value) {
              $feeInstStatus = chkInstStatus($studentId, $key, $classId);

              if ($feeInstStatus != 1) {
                  foreach ($value as $compKey => $compValue) {
                      if ($feeRuleArray != 0) {
                          foreach ($feeRuleArray as $ruleKey => $ruleValue) {
                              $feeRuleInstAssoc = getRuleAssocInst($ruleValue['feeruleid'], $studentId);

                              foreach ($feeRuleInstAssoc as $feeRuleInstKey => $feeRuleInstValue) {
                                  if ($feeRuleInstValue == $key) {
                                      if ($compValue['feecomponentid'] == $ruleValue['feecomponentid']) {
                                          if ($ruleValue['feeruletype'] == 261) {
                                              if ($ruleValue['feerulemodeid'] == 263) {
                                                  $feeStructureArray[$key][$compKey]['amount'] = $feeStructureArray[$key][$compKey]['amount'] - ($feeStructureArray[$key][$compKey]['amount'] * $ruleValue['feeruleamount'] / 100);
                                              } else {
                                                  $feeStructureArray[$key][$compKey]['amount'] = $feeStructureArray[$key][$compKey]['amount'] - $ruleValue['feeruleamount'];
                                              }
                                          } else {
                                              if ($ruleValue['feerulemodeid'] == 263) {
                                                  $feeStructureArray[$key][$compKey]['amount'] = $feeStructureArray[$key][$compKey]['amount'] + ($feeStructureArray[$key][$compKey]['amount'] * $ruleValue['feeruleamount'] / 100);
                                              } else {
                                                  $feeStructureArray[$key][$compKey]['amount'] = $feeStructureArray[$key][$compKey]['amount'] + $ruleValue['feeruleamount'];
                                              }
                                          }
                                      }
                                  }
                              }
                          }
                      }
                      $totalDueFee = $totalDueFee + $feeStructureArray[$key][$compKey]['amount'];
                  }
                  $duedates .= getInstallmentNumber($classId, $key);
                  $otherFees = OtherFees($key, $totalDueFee);
                  $totalDueFee += getTransportFees($studentId);
                  $totalDueFee += $otherFees;
              }
          }
     

      $duedates = rtrim($duedates, ',');

      return $totalDueFee . '|' . $duedates;
  }

  /* Function to get the complete fee strcuture of a class for the current session */

  function getFeeStructure($instsessassocid, $classId, $studentId, $month) {
      $searchTerm = cleanVar($_REQUEST);
      $currentDate = date('Y-m-d');
      $totalFeeInstallments = "
                            SELECT  DISTINCT(t2.duedate)
                            FROM    tblfeestructure as t1, 
                            tblfeestructuredetails as t2 , 
                            tblfeecomponent as t3 
                             
                            WHERE  t1.feestructureid=t2.feestructureid 
                            AND t1.feecomponentid=t3.feecomponentid 
                            AND t1.classid = $classId 
                            AND t1.instsessassocid = $instsessassocid 
                            AND t1.status = 1 
                            AND t1.deleted != 1 
                             ";

      if (isset($searchTerm) && !empty($searchTerm['monthstart'])) {
          $date1 = $searchTerm['monthstart'];
          $date2 = $searchTerm['monthend'];
          $totalFeeInstallments .= " AND  t2.duedate >= '$date1' AND t2.duedate <= '$date2' ";
      }
      else{
          $totalFeeInstallments .= " AND t2.duedate <= '$currentDate' ";
      }

      $resInst = dbSelect($totalFeeInstallments);
      if (mysqli_num_rows($resInst) > 0) {
          while ($row = mysqli_fetch_assoc($resInst)) {
              /* getting all fee components associated with particular due date */

              $sqlFeeInstDetails = "  SELECT  t3.feecomponentid, t2.amount, t3.feecomponent
                                    FROM tblfeestructure as t1, 
                                    tblfeestructuredetails as t2 ,
                                    tblfeecomponent as t3 
                                   
                                    WHERE t1.feestructureid=t2.feestructureid 
                                    AND t1.feecomponentid = t3.feecomponentid 
                                    AND t1.classid = $classId 
                                    AND t1.instsessassocid = $instsessassocid 
                                    AND  t2.duedate = '$row[duedate]' 
                                    AND t1.status = 1 ";

              $resInstDetails = dbSelect($sqlFeeInstDetails);
              if (mysqli_num_rows($resInstDetails) > 0) {
                  while ($rowInstDetails = mysqli_fetch_assoc($resInstDetails)) {
                      /*
                       * creating an multi-dimensional array containg all fee heads per duedate
                       * eg [2015-04-10] = array(
                       *                    [admission fees] = 3000
                       *                    ....
                       *                   )
                       */
                      $feeInstallments[$row['duedate']][] = $rowInstDetails;
                  }
              }
          }

          return $feeInstallments;
      }
  }

  /* Function to get the fee rules details applied over a stuent. */

  function getFeeRule($studentid) {
      $sqlFeeRule = " SELECT   t2.feeruleid, t2.feerulename, t3.feecomponentid,
                            t3.feerulemodeid, t3.feeruletype, t3.feeruleamount , t4.feecomponent
                    FROM    tblstudfeeruleassoc as t1,  tblfeerule as t2,  tblfeeruledetail as t3 ,
                    `tblfeecomponent` AS t4
                    WHERE   t1.feeruleid=t2.feeruleid   
                            AND t2.feeruleid=t3.feeruleid  
                            AND t1.studentid=$studentid  
                            AND t1.associationstatus=1 
                            AND t3.feecomponentid = t4.feecomponentid
                            AND t2.feerulestatus=1 ";

      $resFeeRule = dbSelect($sqlFeeRule);
      if (mysqli_num_rows($resFeeRule) > 0) {
          while ($row = mysqli_fetch_assoc($resFeeRule)) {
              $feeRuleDetails[] = $row;
          }

          return $feeRuleDetails;
      } else {
          return 0;
      }
  }

  /* Function to check the installment status whether it is paid or not */

  function chkInstStatus($studentId, $instDate, $classId) {
      $instsessassocid = $_SESSION['instsessassocid'];
      $sqlChkStatus = " SELECT feestatus 
          FROM `tblfeecollection` as t1, 
          `tblfeecollectiondetail` as t2 , 
          `tblclsecassoc` as t3,
          `tblfeeinstallmentdates` as t4 
          
          WHERE t1.feecollectionid = t2.feecollectionid 
          AND t1.clsecassocid = t3.clsecassocid 
          AND t1.studentid= '$studentId' 
          AND t3.classid = '$classId' 
          AND t1.instsessassocid = '$instsessassocid' 
          AND t2.feecollectiondetailid = t4.feecollectiondetailid 
          AND t4.feeinstallment = '$instDate' 
          AND t2.feestatus = 1   
                            ";

      $resultStatus = dbSelect($sqlChkStatus);
      if (mysqli_num_rows($resultStatus) > 0) {
          $statusRow = mysqli_fetch_assoc($resultStatus);
          if ($statusRow['feestatus'] == '1') {
              return 1;
          } else {
              return 0;
          }
      } else {
          return 0;
      }
  }

  function getRuleAssocInst($feeRuleId, $studentId) {
      $sqlRuleInstAssoc = "  SELECT installment FROM tblstudfeeruleinstasssoc as t1 , tblstudfeeruleassoc as t2 
                                WHERE t2.feeruleid=$feeRuleId AND t2.studentid=$studentId AND 
                                      t2.associationstatus=1 AND t1.studfeeruleassocid=t2.studfeeruleassocid 
                                      AND t1.status=1 ORDER BY t1.installment";
      $resRuleInstAssoc = dbSelect($sqlRuleInstAssoc);
      if (mysqli_num_rows($resRuleInstAssoc) > 0) {
          while ($row = mysqli_fetch_assoc($resRuleInstAssoc)) {
              $instArray[] = $row['installment'];
          }

          return $instArray;
      } else {
          return 0;
      }
  }

  function selectSessionStart() {
      $instsessassocid = $_SESSION['instsessassocid'];
      $sql = "SELECT sessionstartdate,sessionenddate FROM tblacademicsession as t1, tblinstsessassoc as t2 
        WHERE t1.academicsessionid=t2.academicsessionid AND t2.instsessassocid=$instsessassocid";

      $result = dbSelect($sql);
      if (mysqli_num_rows($result) > 0) {
          $row = mysqli_fetch_assoc($result);
          $sessionDate['startdate'] = $row['sessionstartdate'];
          $sessionDate['enddate'] = $row['sessionenddate'];

          return $sessionDate;
      }
  }

  function getFeeCollected($studentId, $classid, $instsessassocid) {
      $sql[] = " SELECT SUM(t2.feeinstallmentamount) as feeamount FROM tblfeecollection as t1, tblfeecollectiondetail as t2
               WHERE t1.feecollectionid=t2.feecollectionid  AND t1.studentid=$studentId  GROUP BY t2.feecollectionid ORDER BY t1.feecollectionid";
      $sql[] = " SELECT SUM(t2.amountcharged) as otherfee FROM tblfeecollection as t1 , tblothertransdetails as t2 WHERE 
                t1.feecollectionid=t2.feecollectionid AND t1.studentid=$studentId GROUP BY t2.feecollectionid ORDER BY t2.feecollectionid";

      $result = dbSelect($sql);
      foreach ($result as $key => $value) {
          if (mysqli_num_rows($result[$key]) > 0) {
              $row[] = mysqli_fetch_assoc($value);
          } else {
              $row[] = 0;
          }
      }
  }

  function feeCollectionReport($searchTerms, $type = null) {
      global $totalrows;
      $totalrows = 0;
      $instsessassocid = $_SESSION['instsessassocid'];
      $startPage = (int) (!isset($searchTerms['page']) ? 0 : ($searchTerms['page'] - 1) * ROW_PER_PAGE);
      $sessionDates = selectSessionStart();
      $where = " AND t1.instsessassocid = $instsessassocid";
      $groupBy = ' GROUP BY t7.feecollectionid  ';
      $orderBy = ' ORDER  BY t1.firstname,t1.studentid  ';

      $limit = " LIMIT $startPage," . ROW_PER_PAGE;

      $sqlCollection = "   SELECT t1.studentid,t1.scholarnumber, t6.receiptid, t6.feecollectionid , 
                            DATE(t7.datecreated) as dated,
                            t1.firstname,t1.middlename,t1.lastname,
                            t4.classname,t5.sectionname,  
                            SUM(t7.feeinstallmentamount) as feeamount, 
                            t8.feeinstallment, t6.feecollectionid ,t9.collectionname, t7.feemodeid,
                            t6.receiptid, t6.remarks
                        
                        FROM tblstudent as t1, 
                        tblstudentacademichistory as t2, 
                        tblclsecassoc as t3, 
                        tblclassmaster as t4, 
                        tblsection as t5  , 
                        tblfeecollection as t6,
                        tblfeecollectiondetail as t7,
                        tblfeeinstallmentdates as t8, 
                         tblmastercollection as t9
                        
                        WHERE   t1.studentid = t2.studentid 
                        AND     t2.clsecassocid = t3.clsecassocid  
                        AND     t3.classid = t4.classid  
                        AND     t3.sectionid = t5.sectionid  
                        AND     t1.studentid = t6.studentid 
                        AND     t6.feecollectionid = t7.feecollectionid  
                        AND 	t1.instsessassocid = '$instsessassocid' 
                        AND 	t7.feecollectiondetailid = t8.feecollectiondetailid
                        AND 	t7.feemodeid = t9.mastercollectionid
                        AND     t7.feestatus = 1
                        
                    ";
      if (isset($searchTerms['scholarnumber']) && !empty($searchTerms['scholarnumber'])) {
          $where .= ' AND t1.scholarnumber=' . $searchTerms['scholarnumber'];
      }
      if (isset($searchTerms['classid']) && !empty($searchTerms['classid']) && is_numeric($searchTerms['classid'])) {
          $where .= ' AND t4.classid=' . $searchTerms['classid'];
      }
      if (isset($searchTerms['sectionid']) && !empty($searchTerms['sectionid']) && is_numeric($searchTerms['sectionid'])) {
          $where .= ' AND t5.sectionid=' . $searchTerms['sectionid'];
      }
      if (isset($searchTerms['paymentmode']) && !empty($searchTerms['paymentmode']) && is_numeric($searchTerms['paymentmode'])) {
          $where .= " AND t7.feemodeid= ' " . $searchTerms['paymentmode'] . "' ";
      }
      if (isset($searchTerms['monthstart']) && !empty($searchTerms['monthstart'])) {
          $where .= " AND DATE(t7.datecreated)>= '" . $searchTerms['monthstart'] . "' ";
      }
      if (isset($searchTerms['monthend']) && !empty($searchTerms['monthend'])) {
          $where .= " AND DATE(t7.datecreated)<= '" . $searchTerms['monthend'] . "' ";
      }

      /*
       * This piece of code is written for searching student by 3 options
       * 1) If user search student using firstname
       * 2) If user search student using first+middle name
       * 3) If user search Student using first+middle+lastname
       */

      if (isset($searchTerms['studentname']) && !empty($searchTerms['studentname'])) {
          $studentName = explode(' ', $_REQUEST['studentname']);

          if (count(array_keys($studentName)) == 1) {
              $where .= " AND UPPER(t1.firstname) LIKE ('" . strtoupper(trim($studentName[0])) . "%')";
          }
          if (count(array_keys($studentName)) == 2) {
              $where .= " AND ( UPPER(t1.firstname) LIKE ('" . strtoupper(trim($studentName[0])) . "%')
                        OR UPPER(t1.lastname)  LIKE ('" . strtoupper(trim($studentName[1])) . "%'))";
          }

          if (count(array_keys($studentName)) == 3) {
              $where .= " AND ( UPPER(t1.firstname) LIKE ('" . strtoupper(trim($studentName[0])) . "%')
                          OR UPPER(t1.middlename) LIKE ('" . strtoupper(trim($studentName[1])) . "%') 
                          OR UPPER(t1.lastname) LIKE ('" . strtoupper(trim($studentName[2])) . "%') )";
          }
      }

      /*
       * This piece of code is written for searching student using dates criteria
       * 1) Show student's who all deposited fees 'today' ie current date
       * 2) Show student's who all deposited fees  till 'today' ie current date
       * 3) Show student's who all deposited fees  till 'last month' ie exactly one month back from previous date
       */

      if (isset($searchTerms['date']) && !empty($searchTerms['date'])) {
          $todayDate = date('Y-m-d');
          $prevMonth = (date('m') > 1) ? date('m') - 1 : 12;

          if ($searchTerms['date'] == 'today') {
              $where .= " AND DATE(t7.datecreated)= '$todayDate'";
          }
          if ($searchTerms['date'] == 'start') {
              $where .= " AND DATE(t7.datecreated) >= '$sessionDates[startdate]' AND DATE(t7.datecreated)<= '$sessionDates[enddate]'  ";
          }
          if ($searchTerms['date'] == 'prevmonth') {
              $where .= " AND MONTH(t7.datecreated)= '$prevMonth' ";
          }
      }
      
      if($type == 'pdf' || $type == 'dashboard'){
          $resCollection = dbSelect($sqlCollection . $where . $groupBy . $orderBy );
      }
      else{
        $resCollection = dbSelect($sqlCollection . $where . $groupBy . $orderBy.$limit);
      }
      
      $totalrows = mysqli_num_rows(dbSelect($sqlCollection . $where . $groupBy));

      if (mysqli_num_rows($resCollection) > 0) {
          $arrayKey = 0;
          while ($row = mysqli_fetch_assoc($resCollection)) {
              $otherFeeAmount = 0;
              $collectionDetails[] = $row;
          }
          $collectionDetails = otherFeeCollected($collectionDetails);

          return $collectionDetails;
      } else {
          return 0;
      }
  }

  /*
   * Checking Out Cases for other fees
   * like Late Fees, Conveyance Fees etc
   * which ever gets charged from student along with installment fees
   *
   */

  function otherFeeCollected($collectionDetails) {
      foreach ($collectionDetails as $key => $value) {
          $feecollectionid[] = $value['feecollectionid'];
          $selectOtherAmount = " SELECT SUM(t2.feeinstallmentamount) AS OtherFee

        FROM `tblfeecollection` AS t1,
        `tblfeecollectiondetail` AS t2,
        `tblfeeothercharges` AS t3,
        `tblfeeotherchargesdetails` AS t4,
        `tblfeepenaltydetails` as t5,
        `tblmastercollectiontype` AS t6,
        `tblmastercollection` AS t7

        WHERE t1.instsessassocid = $_SESSION[instsessassocid]
        AND  t1.instsessassocid = t3.instsessassocid
        AND t1.feecollectionid = '$value[feecollectionid]'
        AND t1.feecollectionid = t2.feecollectionid 
        AND t2.collectiontype = t3.feeotherchargesid 
        AND t3.feeotherchargesid = t4.feeotherchargesid 
        AND t2.feecollectiondetailid = t5.feecollectiondetailid
        AND t6.mastercollectiontype = 'collection type'
        AND t6.mastercollectiontypeid = t7.mastercollectiontypeid
        
        AND t7.collectionname != 'Fees' ";
          
          $resOtherFee = dbSelect($selectOtherAmount);
          if (mysqli_num_rows($resOtherFee) > 0) {
              $otherFee = mysqli_fetch_assoc($resOtherFee);
              $otherFeeAmount = $otherFee['OtherFee'];
              $collectionDetails[$key]['otherfeeamount'] = $otherFeeAmount;
          }
          $chequeBounceAmt = getchequeBounceAmt($value['feecollectionid']);
          $collectionDetails[$key]['otherfeeamount'] += $chequeBounceAmt;
      }

      $refundDetails = getRefundDetails();

      if ($refundDetails != 0) {
          foreach ($collectionDetails as $ck => $cvalue) {
              if (array_key_exists($cvalue['studentid'], $refundDetails) && ($cvalue['feecollectionid'] == key($refundDetails[$cvalue['studentid']]))) {
                  $collectionDetails[$ck]['refunded'] = implode('', $refundDetails[$cvalue['studentid']]);
              }
          }
      }

      return $collectionDetails;
  }

  function getRefundDetails() {
      $refundAmt = 0;
      $sql = 'SELECT t1.studentid , t5.classid,  t1.feecollectionid , t2.feecollectiondetailid, t5.feecomponentid,
           SUM(t6.amount)  AS refundedamount
            FROM `tblfeecollection` AS t1,
            `tblfeecollectiondetail` AS t2,
             `tblfeerefund` AS t3,
             `tblfeestructure` AS t5,
             `tblfeestructuredetails` AS t6, 
             `tblstudentacademichistory` AS t7,
             `tblclsecassoc` AS t8,
             `tblclassmaster` AS t9,
            `tblfeeinstallmentdates` AS t10
            
            WHERE t1.feecollectionid = t2.feecollectionid 
            AND t2.feecollectiondetailid = t3.feecollectiondetailid
            AND t1.studentid = t7.studentid
            AND t7.clsecassocid = t8.clsecassocid
            AND t8.classid = t9.classid
            AND t5.classid = t9.classid
            AND t3.feecomponentid = t5.feecomponentid
            AND t5.feestructureid = t6.feestructureid
            AND t2.feecollectiondetailid =  t10.feecollectiondetailid
            AND t10.feeinstallment = t6.duedate
           
            GROUP BY t1.studentid
            
              ';
      $result = dbSelect($sql);
      if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
              $refundAmt = $row['refundedamount'];
              $refundDetails[$row['studentid']][$row['feecollectionid']] = $refundAmt;
          }

          return $refundDetails;
      } else {
          return 0;
      }
  }

  function OtherFees($duedate, $installmentamount) {
      $feeamount = 0;
      $totaldays = 0;
      $calcAmount = 0;
      $otherFeeDetails = otherFeeSql(null);

      if ($duedate < date('Y-m-d')) {
          $datediff = date_diff(date_create($duedate), date_create(date('Y-m-d')));
          $totaldays += $datediff->format('%R%a days');
      }

      foreach ($otherFeeDetails as $key => $value) {
          $otherAmount = OtherFeeCalculate($value['chargemode'], $value['otherfeetype'], $value['frequency'], $value['amount'], $installmentamount, $totaldays);
          if ($otherAmount != 0) {
              $calcAmount += implode('', $otherAmount);
          }
      }

      return $calcAmount;
  }

  function OtherFeeCalculate($chargemode, $otherfeetype, $frequency, $amount, $feeamount, $totaldays) {
      $updatedAmt = 0;
      if (!empty($amount)) {
          if (strtolower($chargemode) == 263) {

              //daily basis.
              if ($frequency == 303) {
                  $dueAmount = (($amount / 100) * $duedate) * $feeamount;
                  $diffAmount = $dueAmount;
              }
              //weekly basis.
              elseif ($frequency == 302) {
                  $daysfromWeek = ceil($duedate / 7);
                  $dueAmount = (($amount / 100) * $daysfromWeek) * $feeamount;
                  $diffAmount = $dueAmount;
              }

              return $diffAmount;
          } else {
              $calculatedAmt = OtherFeesCalculateAmount($totaldays, $amount, $frequency);

              return $calculatedAmt;
          }
      }
  }

  function OtherFeesCalculateAmount($totaldays, $amount, $frequency) {
      switch (strtolower($frequency)) {
          // Calculate for Daily (303 = Daily)
          case '303':
              $dueAmount = ($totaldays * $amount);
              $diffAmount[] = $dueAmount;

              return $diffAmount;
              break;

          // Calculate for Weekly (302 = Weekly)
          case '302':

              $daysfromWeek = ceil($totaldays / 7); //echoThis($daysfromWeek ); die;
              $dueAmount = (($daysfromWeek) * $amount);
              $diffAmount[] = $dueAmount;

              return $diffAmount;
              break;

          default:
              return $amount;
      } //end of switch statement
  }

  function getTransportFees($studentid) {
      $sql = "SELECT t1.conveyancerequired, t1.pickuppointid, t2.amount 
                FROM `tblstudentdetails` AS t1,
                `tblpickuppoint` AS t2
                WHERE t1.studentid = $studentid
                AND t1.pickuppointid = t2.pickuppointid";

      $result = dbSelect($sql);
      if (mysqli_num_rows($result) > 0) {
          $row = mysqli_fetch_assoc($result);
          $amount = $row['amount'];

          return $amount;
      } else {
          return 0;
      }
  }

  function getchequeBounceAmt($feecollectionid) {
      $sql = "SELECT `amount` FROM `tblotherfeepenalties`
           WHERE  `feecollectionid` = '$feecollectionid'
           AND `status` = 1
";
      $result = dbSelect($sql);
      if (mysqli_num_rows($result) > 0) {
          $row = mysqli_fetch_assoc($result);
          $chequeAmount = $row['amount'];
          return $chequeAmount;
      } else {
          return 0;
      }
  }

  function otherFeeSql($frequency) {
      $sqlStr = "AND t4.collectionname = 'Per Transaction'";
      if (empty($frequency) || is_null($frequency)) {
          $sqlStr = "AND t4.collectionname != 'Per Transaction'";
      }

      $instsessassocid = $_SESSION['instsessassocid'];
      $sql = "SELECT *
            FROM `tblfeeothercharges` AS t1, 
            `tblfeeotherchargesdetails` AS t2,
            `tblmastercollectiontype` AS t3,
            `tblmastercollection` AS t4
            
            WHERE t1.instsessassocid = '$instsessassocid'
            AND t1.feeotherchargesid = t2.feeotherchargesid
            AND t1.status = 1
            AND t1.deleted != 1
            AND t3.mastercollectiontype = 'Fee Frequency'
            $sqlStr
            AND t3.mastercollectiontypeid = t4.mastercollectiontypeid
            AND t4.mastercollectionid = t2.frequency
        ";

      $result = dbSelect($sql);
      if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
              $otherfeeDetails[] = $row;
          }

          return $otherfeeDetails;
      } else {
          return 0;
      }
  }

  /* This function gives total students of the session 
   * wise and classwise. Males and Females
   * Made By: Sanjay Kumar [used for dashboard]
   */

  function getTotalStudentDashboard() {

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
                GROUP BY t1.studentid ORDER BY t2.classid, t7.sectionid";

      //echoThis($sql);die;
      $result = dbSelect($sql);
      if (mysqli_num_rows($result) > 0) {
          while ($rows = mysqli_fetch_assoc($result)) {
              $studentArray[] = $rows;
          }
          foreach ($studentArray as $key => $value) {
              $student_count_per_class[] = $value['classdisplayname'];
              $classArray[] = $value['classdisplayname'];
              $count[] = $value['gender'];
          }
          /* count total males and females using group by */
          $gender_count = array_count_values($count);
         
          /* it counts same values in a array */
          $student_count_per_class = array_count_values($student_count_per_class);
          $total = array_sum($student_count_per_class);

          /* array_unique give unique values from array discards duplicates and
           * array_values resets the key to serial Number
           * classArray has all the classes */
          $classArray = array_values(array_unique($classArray));

          foreach ($classArray as $key => $value) {
              foreach ($studentArray as $k => $val) {
                  /* group gender and section name according to class */
                  if ($val['classdisplayname'] == $value) {
                      $gender[$value][] = $val['gender'];
                      $section[$value][] = $val['sectionname'];
                  }
              }
          }

          /* creating unique array of section group by class
           * counting males and females group by class
           */
          foreach ($gender as $key => $value) {
              $section[$key] = array_values(array_unique($section[$key]));
              $gender_count[$key] = array_count_values($value);
          }
      }return array($student_count_per_class, $section, $gender_count);
  }

  /* this function calculate total student from each institute of
   * current session.
   * Made by; Sanjay Kumar [used for dashboard]
   */

  /* need to be re-visted 
  function getStudentCountDashboard() {
      $sql = "select t1.sessionname, t2.instsessassocid,
              COUNT(t3.studentid) as student_count, t4.institutename
              
              FROM
              
              tblacademicsession as t1,
              tblinstsessassoc as t2,
              tblstudent as t3,
              tblinstitute as t4
              
              where
              
             t2.academicsessionid = t1.academicsessionid AND
             t3.instsessassocid = t2.instsessassocid AND
             t4.instituteid = t2.instituteid AND
             t1.sessionname = '2016-17' AND t3.status = 1 AND
             t3.deleted = 0 AND t3.tcissued = 0
             GROUP BY t3.instsessassocid";
      $result = dbSelect($sql);
      if (mysqli_num_rows($result) > 0) {
          while ($rows = mysqli_fetch_assoc($result)) {
              $count[] = $rows;
          }
          foreach ($count as $key => $value) {
              $sessionname = $value['sessionname'];
              $graphArray['xData'][] = $value['student_count'];
              $graphArray['yData'][] = "'".$value['institutename']."'";
          }
          $graphArray['xAxis'] = "Institute Name";
          $graphArray['yAxis'] = "Student Count";
          $graphArray['title'] = "";
          $graphArray['entity'] = $sessionname;
          $graphArray['width'] = '500';
          $graphArray['height'] = '170';
      }
     // echoThis($graphArray);
      return $graphArray;
  }
  */
  
  function studentdetails_dashboard() {
      $studentdetails = array();

      $sql = "SELECT t1.studentid ,t1.scholarnumber,t3.classid, t4.sectionid,
        t3.classdisplayname, t4.sectionname
        
        FROM `tblstudent` AS t1,
        `tblclassmaster` AS t3,
        `tblsection` AS t4,
        `tblclsecassoc` AS  t5,
        `tblstudentacademichistory` AS t6
        
        
        WHERE t1.instsessassocid = $_SESSION[instsessassocid]
        AND t1.studentid = t6.studentid
        AND t6.clsecassocid = t5.clsecassocid
        AND t5.classid = t3.classid
        AND t5.sectionid = t4.sectionid
        AND t1.status != 0 
        AND t1.tcissued != 1  
        GROUP BY t1.studentid  ORDER BY t3.classid, t4.sectionid ASC
        ";


      $result = dbSelect($sql);
      if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
              $studentdetails[$row['classdisplayname']][$row['studentid']] = $row['studentid'];
          }
          return $studentdetails;
      }
  }

 
  function getDueFees_dashboard($returnType = null) {
      $studentDetails = studentdetails_dashboard();
      $collectedFee = getCollectedFees_dashboard();
      $feeStructure = $_SESSION['feestructure'];
     // echoThis($collectedFee);die;
      foreach ($studentDetails as $key => $value) {
          $dateArray[$key] = array_keys($feeStructure[$key]);
          foreach ($value as $k => $val) {
              if(array_key_exists($k, $collectedFee[$key])){
                  $collectedFeeArray = array_flip(explode(',', $collectedFee[$key][$k]));
                  $studentDetails[$key][$k] = array_diff_key($feeStructure[$key],  $collectedFeeArray);
                  if(empty($studentDetails[$key][$k])){
                     continue;
                 }
              }
              else {
                  /* student who havnt paid a single installment */
                  $studentDetails[$key][$k] = $feeStructure[$key];
              }
              $studentDetails = array_map('array_filter', $studentDetails);
          }
      }
     

      //echoThis($studentDetails);die;

      foreach ($studentDetails as $key => $value) {
          foreach ($value as $k => $val) {
              $per_installment_sum = 0;
              foreach ($val as $arrk => $arrval) {
                  $studentFeeRuleDetails = feeRuleSql_dashboard($k);
                  if ($studentFeeRuleDetails != 0) {
                      foreach ($studentFeeRuleDetails as $ruleK => $ruleval) {
                          $ruleval['installments'] = explode(",", $ruleval['installments']);
                          if (array_key_exists($ruleval['feecomponent'], $studentDetails[$key][$k][$arrk]) && (in_array($arrk, $ruleval['installments']))) {
                              $originalamt = $studentDetails[$key][$k][$arrk][$ruleval['feecomponent']];
                              $updatedamt = updateFees_dashboard($ruleval['feeruletype'], $ruleval['feerulemodeid'], $originalamt, $ruleval['feeruleamount']);
                              $studentDetails[$key][$k][$arrk][$ruleval['feecomponent']] = $updatedamt;
                          }
                      }
                  }
              }
          }
      }
      $per_inst_sum = 0;
      foreach ($studentDetails as $key => $value) {
          $per_class_sum = 0;
          foreach ($value as $k => $val) {
              $per_installment_sum = 0;
              foreach ($val as $arrk => $arrval) {
                  $per_installment_sum += array_sum($arrval);
              }
              $studentDetails[$key][$k]['student_due'] = $per_installment_sum;
              $per_class_sum += $per_installment_sum;
           }
          $studentDetails[$key]['class_due'] = $per_class_sum;
          $per_inst_sum += $per_class_sum;
      }
    //  echoThis($studentDetails);die;
      $studentDetails['totalDue'] = $per_inst_sum;

      if($returnType == 'dashboard'){
          return $studentDetails['totalDue'];
      }
  }

  function getCollectedFees_dashboard() {
      $sql = "SELECT t1.studentid , GROUP_CONCAT(t3.feeinstallment) as collectedinstallment,
            t7.classdisplayname
             FROM `tblfeecollection` AS t1,
                    `tblfeecollectiondetail` AS t2,
                    `tblfeeinstallmentdates` as t3,
                    `tblstudent` as t4,
                    `tblclsecassoc` AS  t5,
                    `tblstudentacademichistory` AS t6,
                    `tblclassmaster` as t7
             
            WHERE t1.instsessassocid = $_SESSION[instsessassocid]
            AND t1.feecollectionid = t2.feecollectionid
            AND t2.feecollectiondetailid = t3.feecollectiondetailid
            AND t1.studentid = t4.studentid
            AND t4.studentid = t6.studentid
            AND t6.clsecassocid = t5.clsecassocid
            AND t5.classid = t7.classid
            AND t2.feestatus = 1
            GROUP BY t1.studentid
                
              ";
      $result = dbSelect($sql);
      if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
              $collectedFee[$row['classdisplayname']][$row['studentid']] = $row['collectedinstallment'];
          }
      }
      return $collectedFee;
  }

  function feeRuleSql_dashboard($studentid) {
      $sql = "SELECT t1.feeruleid,t1.feerulename, t2.feerulemodeid, t2.feeruletype,t2.feeruleamount,
                t4.feecomponentid, t4.feecomponent, GROUP_CONCAT(t5.installment) As installments
        FROM `tblfeerule` AS t1,
        `tblfeeruledetail` AS t2, 
        `tblstudfeeruleassoc` AS t3,
        `tblfeecomponent` AS t4,
        `tblstudfeeruleinstasssoc` AS t5

        WHERE t3.studentid = $studentid
        AND t1.feeruleid = t2.feeruleid 
        AND t1.feeruleid = t3.feeruleid
        AND t2.feecomponentid = t4.feecomponentid
        AND t3.studfeeruleassocid = t5.studfeeruleassocid
        AND t1.feerulestatus = 1
        AND t1.deleted != 1
         GROUP BY t4.feecomponentid, t3.studentid
        ";

      if (($result = dbSelect($sql)) && (($num_row = mysqli_num_rows($result)) != 0)) {
          while ($row = mysqli_fetch_assoc($result)) {
              $feeruledetails[] = $row;
          }
          return($feeruledetails);
      } else {
          return 0;
      }
  }

  function updateFees_dashboard($type, $mode, $amount, $value) {
      if ($type == 261) {
          if ($mode == 263) {
              $amount = ($amount - ($amount * $value / 100));
              return $amount;
          } else {
              $amount = ($amount - $value);
              return $amount;
          }
      } else {
          if ($mode == 263) {
              $amount = ($amount + ($amount * $value / 100));
              return $amount;
          } else {
              $amount = ($amount + $value);
              return $amount;
          }
      }
  }