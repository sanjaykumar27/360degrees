<?php

  /*
   * 360 - School Empowerment System.
   * Developer: Ankur Mishra (amishra@ebizneeds.com.au) | www.ebizneeds.com.au
   * Page details here: process function of the application.
   * Updates here:
   */

  /* call the process function */
//*****************************

  if (isset($bcPage) && $bcPage != "ImportCSV") {
      processForm();
  }
  $page = bcPage();
  if (!empty($page)) {
      processForm();
  }
  /* function to process form, which process function to call once form is submitted */

  function processForm() {
      $pageName = ucfirst(rtrim(basename($_SERVER['PHP_SELF']), '.php'));
      if (!empty($pageName)) {
          $pageName = "process" . $pageName;
          $pageName();
      } else {
          trigger_error("Function doesn't exist!");
          exit();
          /** better error handling, log, email etc * */
      }
  }

  /* function to return all the form fields, their character and the table to be used */

  function getFormFields($name) {
      /* assign all table, form field to array */
      $formFields = array();
      $formFields ['processLogin'] = array("email" => "r|email", "password" => "r|string", "rememberMe" => "o|bool");
      $formFields ['processCollectionType'] = array("mastercollectiontype" => "r|string",
          "status" => "r|int",
          "collectionname" => "r|array",
          "description" => "o|string");

      $formFields ['processclassMaster'] = array("classid" => "r|arrint",
          "sectionid" => "r|arrint",
          "subjectid" => "r|arrint",
          "isoptional" => "o|int",
          "exams" => "r|arrint",
          "examstartdate" => "r|arrdate",
          "examenddate" => "r|arrdate"
      );

      $formFields ['processStudentDashboard'] = array("scholarnumber" => "o|string",
          "firstname" => "o|string",
          "fathername" => "o|string",
          "classid" => "o|int",
          "sectionid" => "o|int",
          "studenttytpe" => "o|int",
          "status" => "o|int"
      );

      $formFields ['processStudentPersonal'] = array(
          "email" => "o|email",
          "scholarnumber" => "r|string",
          "gender" => "r|int",
          "studenttype" => "r|int",
          "firstname" => "r|string",
          "middlename" => "o|string",
          "lastname" => "r|string",
          "classid" => "r|int",
          "sectionid" => "r|int",
          "dob" => "r|date",
          "category" => "r|int",
          "religion" => "r|int",
          "passportnum" => "o|string",
          "dateofjoining" => "r|date",
          "conveyancerequired" => "o|int",
          "previousclass" => "o|int",
          "profilepicture" => "o|string",
          "previousschool" => "o|string",
          "previousresult" => "o|string",
          "pickuppointid" => "o|int",
          "percentgrade" => "o|int",
          "housename" => "o|string",
          "admissionreferencedby" => "o|string",
          "otheradditionalinformation" => "o|string",
          "currentzipcode" => "r|int",
          "permazipcode" => "o|int",
          "currentcountryid" => "r|int",
          "permacountryid" => "o|int",
          "currentstateid" => "r|int",
          "permastateid" => "o|int",
          "currentcityid" => "r|int",
          "permacityid" => "o|int",
          "currentsuburbid" => "r|int",
          "currentaddress1" => "r|string",
          "currentaddress2" => "o|string",
          "permasuburbid" => "o|int",
          "permaaddress1" => "o|string",
          "permaaddress2" => "o|string",
          "phone1" => "o|int",
          "phone2" => "o|int",
          "mobile" => "o|mobile",
          "fax1" => "o|int",
          "fax2" => "o|int",
          "emeregencycontactname" => "r|string",
          "emeregencyphoneno" => "r|mobile",
          "emeregencycontactaddress" => "r|string"
      );

      $formFields['processQuickStudent'] = array(
          "scholarnumber" => "r|string",
          "gender" => "r|int",
          "firstname" => "r|string",
          "lastname" => "r|string",
          "parentfirstname" => "r|string",
          "parentlastname" => "r|string",
          "classid" => "r|int",
          "sectionid" => "r|int",
          "dob" => "o|date",
          "mobile" => "r|mobile",
      );

      $formFields['processStudentParent'] = array(
          "gender" => "r|int",
          "parentfirstname" => "r|string",
          "parentmiddlename" => "o|string",
          "parentlastname" => "r|string",
          "category" => "r|int",
          "religion" => "r|int",
          "currentcityid" => "r|int",
          "permacityid" => "o|int",
          "currentsuburbid" => "r|int",
          "permasuburbid" => "o|int",
          "currentaddress1" => "r|string",
          "currentaddress2" => "o|string",
          "currentzipcode" => "r|int",
          "permazipcode" => "o|int",
          "currentstateid" => "r|int",
          "permastateid" => "o|int",
          "currentcountryid" => "o|int",
          "permacountryid" => "o|int",
          "phone1" => "o|int",
          "officephone" => "o|int",
          "mobile1" => "r|mobile",
          "mobile2" => "o|mobile",
          "email1" => "o|email",
          "email2" => "o|email",
          "permaaddress1" => "o|string",
          "permaaddress2" => "o|string",
          "qualificationid" => "o|int",
          "occupation" => "o|int",
          "income" => "o|int",
          "relationid" => "r|int",
          "fax1" => "o|int",
          "fax2" => "o|int"
      );

      $formFields['processStudentMedical'] = array("medicalhistory" => "o|string",
          "allergyinfo" => "o|string",
          "frequentillness" => "o|string",
          "regulardocname" => "r|string",
          "regulardocaddress" => "r|string",
          "regulardocmobile" => "r|mobile",
          "regulardocphone" => "r|int",
          "regulardocemail" => "r|email",
          "regularhospname" => "r|string",
          "regularhospphone" => "r|int",
          "regularhospemail" => "r|email",
          "regularhospaddress" => "r|string",
          "height" => "r|float",
          "weight" => "r|float",
          "bloodgroup" => "r|int",
          "lefteyesight" => "o|float",
          "righteyesight" => "o|float",
          "identificationmark1" => "o|string",
          "identificationmark2" => "o|string",
          "doctorremark" => "o|string"
      );

      $formFields['processStudentFees'] = array("feerule" => "o|arrint");
      $formFields['processStudentDocument'] = array("documenttype" => "r|arrint"
      );

      $formFields['processFeeMaster'] = array("feecomponents" => "r|int",
          "isrefundable" => "o|bool",
          "classid" => "r|arrint",
          "frequency" => "r|int",
          "amount" => "r|arrint",
          "duedate" => "r|arrdate");

      $formFields['processFeeRule'] = array("feerulename" => "r|string",
          "feecomponentid" => "r|int",
          "feerulestatus" => "o|bool",
          "feeruleremarks" => "r|string",
          "feeruleamount" => "r|int",
          "feerulemodeid" => "r|int",
          "feeruletype" => "r|int"
      );

      $formFields['processAddUser'] = array("username" => "r|email",
          "password" => "r|string",
          "roleid" => "r|int",
          "status" => "r|int");

      $formFields['processaddSubject'] = array("subjectname" => "r|string",
          "subjectcode" => "r|string",
          "comments" => "o|string",
          "instituteid" => "o|int",
          "academicsessionid" => "o|int",
          "status" => "o|int"
      );

      $formFields['processAddAcademicYear'] = array("sessionname" => "r|string",
          "sessionstartdate" => "r|date",
          "sessionenddate" => "r|date",
          "status" => "r|int"
      );

      $formFields['processAcademicCollectionType'] = array("mastercollectiontype" => "r|string",
          "status" => "r|int",
          "collectionname" => "r|array",
          "description" => "o|string"
      );

      $formFields['processAddInstitute'] = array(
          "institutename" => "r|string",
          "insituteweburl" => "r|url",
          "instituteaddress1" => "r|string",
          "instituteaddress2" => "o|string",
          "institutecityid" => "r|int",
          "institutestateid" => "r|int",
          "institutephone1" => "r|int",
          "institutephone2" => "o|int",
          "instituteemail1" => "r|email",
          "instituteemail2" => "o|email",
          "institutefax1" => "o|int",
          "instituteaccreditionid" => "o|string",
          "institutecountryid" => "r|int",
          "institutelogo" => "o|string",
          "status" => "o|int",
          "institutedescription" => "o|string"
      );

      // values for OTHER FEES PAGE //
      $formFields['processOtherFees'] = array("otherfeehead" => "r|string",
          "amount" => "r|int",
          "description" => "r|string",
          "isperiodic" => "o|int",
          "frequency" => "o|int",
          "status" => "r|int",
          "chargemode" => "r|string",
          "otherfeetype" => "r|string"
      );
      // OTHER FEES ENDS HERE //

      $formFields['processfeeCollection'] = array("scholarnumber" => "o|string",
          "firstname" => "o|string",
          "lastname" => "o|string",
          "classid" => "o|int",
          "sectionid" => "o|int"
      );

      $formFields['processfeedue'] = array("studentname" => "o|string",
          "classid" => "o|int",
          "sectionid" => "o|int",
          "monthstart" => "o|date",
          "monthend" => "o|date",
          "datecheck" => "o|int");

      $formFields['processaddvehicle'] = array("vechile_name" => "r|string",
          "type" => "r|int",
          "fueltype" => "r|int",
          "modelno" => "o|string",
          "makeyear" => "r|int",
          "vehicleno" => "r|string",
          "chasisno" => "r|int",
          "engineno" => "r|int",
          "registrationno" => "r|int",
          "regvalidfrom" => "o|date",
          "regvalidto" => "o|date",
          "insurancepolicyno" => "r|int",
          "insurancefromdate" => "o|date",
          "insurancetodate" => "o|date",
          "roadtaxdate" => "o|date",
          "pcrvaliddate" => "o|date",
          "seatingcapacity" => "o|int",
          "status" => "r|int"
      );

      $formFields['processadddriver'] = array("firstname" => "r|string",
          "middlename" => "o|string",
          "lastname" => "r|string",
          "fathername" => "r|string",
          "birthdate" => "o|date",
          "qualification" => "r|int",
          "address" => "r|string",
          "city" => "r|int",
          "mobile" => "r|mobile",
          "licenseno" => "r|int",
          "licensevalidfrom" => "o|date",
          "licensevalidto" => "o|date"
      );

      $formFields['processaddroute'] = array("routename" => "r|string",
          "busid" => "r|int",
          "drivername" => "r|int",
          "pickuppointname" => "r|arrint",
          "startpoint" => "r|int",
          "endpoint" => "r|int",
      );

      $formFields['processstudenttransport'] = array("scholarnumber" => "o|int",
          "classid" => "o|int",
          "sectionid" => "o|int",
          "studentname" => "o|string"
      );

      $formFields['processnotifications'] = array("notificationtype" => "r|string",
          "sendernumber" => "o|int",
          "recievernumber" => "o|int",
          "senderemail" => "o|email",
          "recieveremail" => "o|email",
          "subjectinfo" => "o|string",
          "message" => "r|string");

      $formFields['processIssueTc'] = array("studentname" => "o|string",
          "classid" => "o|int",
          "sectionid" => "o|int"
      );

      $formFields['processSubjectTopicAnalysis'] = array("classid" => "r|int",
          "sectionid" => "r|int",
          "subjectid" => "r|int",
          "employeeid" => "r|int",
          "topicname" => "r|string",
          "expectedstartdate" => "r|date",
          "expectedenddate" => "r|date",
      );
      $formFields['processAddpickuppoint'] = array("pickuppointname" => "r|string",
          "suburbid" => "r|int",
          "amount" => "r|int",
          "pickuptime" => "r|time",
          "droptime" => "r|time"
      );

      $formFields['processFeeCollectionProcessing'] = array("studentid" => "r|int",
          "clsecassocid" => "r|int",
          "actuallatefees" => "o|arrint",
          "latefees" => "o|arrint",
          "totalinstallmentfees" => "r|arrint",
          "feeinstallment" => "r|arrdate",
          "otherFeecharged" => "o|arrint",
          "feemodeid" => "r|int",
          "remarks" => "o|string",
          "otherfeehead" => "o|arrint",
          "otherFeecharged" => "o|arrint",
          "bankname" => "o|string",
          "chequenumber" => "o|int");
      $formFields['processMileageEntry'] = array(
          "busid" => "r|int",
          "travel_date" => "r|arrdate",
          "start_meter" => "o|arrint",
          "end_meter" => "o|arrint",
          "remark" => "o|string"
      );
      $formFields['processFuelEntry'] = array(
          "busid" => "r|int",
          "filled_date" => "r|arrdate",
          "liters" => "r|arrint",
          "fuel_amount" => "r|arrint",
          "remarks" => "o|string"
      );


      if ((!empty($name)) && (array_key_exists($name, $formFields))) {
          return $formFields[$name];
      }
      trigger_error("Form Fields array doesn't exist. Please contact the administrator!");
      exit();
  }

  /* for processing login function */

  function processIndex() {
      
      /* Get the field names from getFormFields function */
      //$sessionId = session_id();
      $Fields = getFormFields("processLogin");
      if (processFormData($Fields)) {
          foreach ($Fields as $key => $value) {
              if (!isset($_POST[$key])) {
                  $_POST[$key] = null;
              }
              ${$key} = cleanVar($_POST[$key]);
          }
          $sql = "SELECT userid, username, password, roleid, instsessassocid 
                  FROM `tbluser` WHERE `username` = '$email' AND status = 1";
         
          $result = dbSelect($sql);
        
          if (mysqli_num_rows($result) > 0 ) {
              $row = mysqli_fetch_assoc($result); 
              if (password_verify($password, $row['password'])) {
                  $_SESSION['userid'] = $row['userid'];
                  $_SESSION['login'] = $row['username'];
                  $_SESSION['userGroup'] = $row['roleid'];
                  $_SESSION['instsessassocid'] = $row['instsessassocid'];
                  $_SESSION['feestructure'] = getClassFeesStructure(null);
                  
                  if ($_POST['rememberMe'] == 'yes') {
                      setcookie(COOKIE_NAME, $row['username'], time() + COOKIE_TIME, "/");
                  }

                  if (logUser('Insert')) {
                      header("Location: " . DIR_FILES . "/dashboard.php?t=" . serialize(time()));
                      exit();
                  } else {
                      addError(0, null, DIR_BASE);
                  }
              } else {
                  addError(5, null, '');
              }
          } else {
              addError(11, 'user');
          }
      }
    
  }

  function processCollectionType() {
      $Fields = getFormFields("processCollectionType");
      if (processFormData($Fields)) {
          foreach ($Fields as $key => $value) {
              if (!isset($_POST[$key])) {
                  $_POST[$key] = null;
              }
              ${$key} = cleanVar($_POST[$key]);
          }
          if (isset($_REQUEST['edid']) && !empty($_GET['edid'])) {
              $i = 1;
              $sql = array("UPDATE `tblmastercollectiontype` SET `mastercollectiontype`='$mastercollectiontype',`status`= '$status' WHERE `mastercollectiontypeid` = $_REQUEST[edid]");
              foreach ($_POST['collectionname'] as $key => $value) {
                  $sql[] = "UPDATE `tblmastercollection` SET `mastercollectiontypeid`= $_REQUEST[edid],
                         `collectionname`= '$value', `description` = '$description'  WHERE `mastercollectiontypeid` = '$_REQUEST[edid]' AND `mastercollectionid` = $key ";
                  $i++;
              }

              if ($result = dbSelect($sql)) {
                  header("Location: collectionType.php?s=6");
                  exit();
              }
          } else {
              $sql = array(" INSERT INTO tblmastercollectiontype (mastercollectiontype, status, datecreated)
                                VALUES ('$mastercollectiontype', '$status', CURRENT_TIMESTAMP)");
              $result = dbInsert($sql);
              $maxCollectionTypeId = getMaxId('tblmastercollectiontype', 'mastercollectiontypeid');

              $strSql = "INSERT INTO tblmastercollection  (mastercollectiontypeid,collectionname,description, status, datecreated)
                            VALUES";
              $status = $_POST['status'];

              foreach ($collectionname as $value) {
                  $strSql.= "('$maxCollectionTypeId', '$value', '$description', '$status', CURRENT_TIMESTAMP),";
              }
              $finalSql = rtrim($strSql, ',');
              $result = dbInsert($finalSql);
              if (isset($_POST["save"]) && $result) {
                  header("Location: collectionType.php?s=5&id={$result[0]}");
              }
          }
      }
  }

  function processStudentDashboard() {
      $Fields = getFormFields("processStudentDashboard");
      if (processFormData($Fields)) {
          foreach ($Fields as $key => $value) {
              if (!isset($_POST[$key])) {
                  $_POST[$key] = null;
              }
              ${$key} = cleanVar($_POST[$key]);
          }
      }
  }

  function uploadstudentimage() {
      $extCheck = validFileExtension($_FILES['profilepicture']['name'], 'image');
      $sizeCheck = validFileSize($_FILES['profilepicture']['tmp_name'], 'image');

      if ($extCheck) {
          if ($sizeCheck) {
              $instSqlString = "SELECT instituteabbrevation FROM tblinstitute as t1, tblinstsessassoc as t2 
                              WHERE t1.instituteid=t2.instituteid AND t2.instsessassocid=" . $_SESSION['instsessassocid'];
              $instResult = dbSelect($instSqlString);
              $instAbbreviation = mysqli_fetch_assoc($instResult);
              $picProfileName = $studentId . '-' . $instAbbreviation['instituteabbrevation'] . '-' . $dataArray['scholarnumber'];
              $imgUpload = uploadImage($_FILES, STUDENT_IMG_PATH, $picProfileName, 'image');
              if ($imgUpload) {
                  return $imgUpload;
              }
          } else {
              addError(14, 'profilepicture');
          }
      } else {
          addError("imagsize", 'profilepicture');
      }
  }

  function processStudentPersonal() {
      $instsessassocid = $_SESSION['instsessassocid'];

      //   if edit mode is set to confirm then call the edit function to update
      if (!empty($_POST['mode']) && $_POST['mode'] == "edit") {
          updateStudentPersonal($_REQUEST);
      }

      /* Get the field names from getFormFields function */

      $Fields = getFormFields("processStudentPersonal");
      if (processFormData($Fields)) {
          foreach ($Fields as $key => $value) {
              if (!isset($_POST[$key])) {
                  $_POST[$key] = null;
              }
              ${$key} = cleanVar($_POST[$key]);
          }

          $p = createPassword();
          $name = "";

          if (isset($_FILES['profilepicture']) && !empty($_FILES['profilepicture']['name'])) {
              $name = uploadstudentimage($_FILES);
          }

          $classSectionAssocId = getClassSectionAssocId($_POST['classid'], $_POST['sectionid']);
          $accessCode = mysqli_fetch_assoc(dbSelect("SELECT MIN(tblid) as tblid,code FROM tblstudentaccesscode WHERE status !=1 "));

          if (isset($_REQUEST['siblingid']) && !empty($_REQUEST['siblingid'])) {
              $returnArray = attachSibling($_REQUEST['siblingid']);
              $userID = $returnArray['userid'];
          } else {
              if (empty($email)) {
                  $email = $firstname . $scholarnumber . "@temp.com";
              }
              $roleid = 3; // roleid = 3 for student's
              $sql = array("INSERT INTO `tbluser`(`username`, `password`, `roleid`, `datecreated`)
                        VALUES('$email','$p','$roleid',CURRENT_TIMESTAMP);",
                  "SET @last_insert_id1 = LAST_INSERT_ID();");
              $userID = '@last_insert_id1';
          }

          $sql[] = "INSERT INTO `tblstudent` ( `instsessassocid`,`scholarnumber`, `firstname`, `middlename`,
                    `lastname`,`status`,  `datecreated`) 
                    VALUES ('$instsessassocid' ,'$scholarnumber', '$firstname',
                '$middlename', '$lastname', '1' , CURRENT_TIMESTAMP);";

          $sql[] = "SET @last_insert_id2 = LAST_INSERT_ID();";

          $sqlDetail = "INSERT INTO tblstudentdetails  SET  `studentid` = @last_insert_id2,
              `dob`= '$dob',`gender`='$gender',`religion`='$religion',`category`='$category',
              `percentgrade`='$percentgrade',`previousclass`='$previousclass',
              `previousresult`='$previousresult',`previousschool`='$previousschool',
              `passportnum`='$passportnum',`dateofjoining`='$dateofjoining',
              `housename`='$housename',`profilepicture`='$name',
              `conveyancerequired`='$conveyancerequired',`pickuppointid`='$pickuppointid',
              `admissionreferencedby`='$admissionreferencedby',
              `otheradditionalinformation`='$otheradditionalinformation', `status` = '1',
              `datecreated` = 'CURRENT_TIMESTAMP' ;";

          $sqlContact = "INSERT INTO tblstudentcontact SET `studentid` = @last_insert_id2, 
                        `currentaddress1`='$currentaddress1',
                       `currentaddress2`='$currentaddress2',`currentsuburbid` ='$currentsuburbid', 
                       `currentcityid`='$currentcityid',
                       `currentstateid` ='$currentstateid', `currentzipcode` ='$currentzipcode',
                       `currentcountryid` ='$currentcountryid',`mobile` ='$mobile',
                       `phone1` ='$phone1', `phone2` ='$phone2',
                       `fax1` ='$fax1', `fax2` ='$fax2',
                       `email`='$email', `emeregencycontactname` ='$emeregencycontactname',
                       `emeregencyphoneno` ='$emeregencyphoneno', `emeregencycontactaddress` ='$emeregencycontactaddress',
                       `status` = '1',`datecreated` = 'CURRENT_TIMESTAMP' ,
            ";

          if (isset($detailsmatch)) {
              $sqlContact.=" `permaaddress1` ='$currentaddress1',`permaaddress2` ='$currentaddress2',
                         `permasuburbid` ='$currentsuburbid',`permacityid` ='$currentcityid',
                         `permastateid` ='$currentstateid',  `permacountryid` ='$currentcountryid',
                         `permazipcode` ='$currentzipcode'; ";
          } else {
              $sqlContact.=" `permaaddress1` ='$permaaddress1', `permaaddress2`='$permaaddress2',
                         `permasuburbid` ='$permasuburbid',  `permacityid` ='$permacityid',
                         `permastateid` ='$permastateid',   `permacountryid` ='$permacountryid',
                         `permazipcode` ='$permazipcode' ;";
          }

          $sql[] = $sqlDetail;
          $sql[] = $sqlContact;
          $sql[] = "INSERT INTO tblstudentacademichistory SET `studentid` = @last_insert_id2 , 
                   `clsecassocid` ='$classSectionAssocId', `academicstatus` ='1',
                   `studenttype` ='$studenttype',  `status` = '1' ;";

          $sql[] = "INSERT INTO `tbluserdetailsassoc`(`userid`, `isstudent`, `isemployee`,`studentid`, `status`, `datecreated`)
         VALUES($userID,'1','0',@last_insert_id2, '1', CURRENT_TIMESTAMP);";

          if (!empty($returnArray)) {
              $subSql = "INSERT INTO `tbluserparentassociation`(`userid`, `parentid`, `studentid`,`status`, `datecreated`)
                     VALUES";
              foreach ($returnArray['parentid'] as $key => $value) {
                  $subSql .="('$returnArray[userid]','$value',@last_insert_id2, '1',CURRENT_TIMESTAMP),";
              }
              $subsql = rtrim($subSql, ",");
              $sql[] = $subsql;
          }

          $result = dbInsert($sql);
          $studentID = getMaxId('tblstudent', 'studentid');

          if (isset($_POST["save"]) && $result) {
              header("Location: studentPersonal.php?s=22&mode=edit&sid={$studentID}");
          } elseif (isset($_POST["next"]) && $result) {
              header("Location: studentParent.php?s=22&mode=edit&sid={$studentID}");
          } else {
              header("Location: studentParent.php?s=22&mode=edit&sid={$studentID}");
          }
      }
  }

  function processQuickStudent() {
      $Fields = getFormFields("processQuickStudent");
      if (processFormData($Fields)) {
          foreach ($Fields as $key => $value) {
              if (!isset($_REQUEST[$key])) {
                  $_REQUEST[$key] = null;
              }
              ${$key} = cleanVar($_REQUEST[$key]);
          }
          processQuickstudentdetails();
      }
  }

  function processQuickstudentdetails() {
      $dataArray = cleanVar($_REQUEST);
      $p = createPassword();
      $classSectionAssocId = getClassSectionAssocId($dataArray['classid'], $dataArray['sectionid']);
      $instsessassocid = $_SESSION['instsessassocid'];

      if (empty($_REQUEST['siblingid'])) {
          $email = $dataArray['firstname'] . $dataArray['scholarnumber'] . "@temp.com";
          $sql = array("INSERT INTO `tbluser`(`username`, `password`, `roleid`, `datecreated`)
                        VALUES('$email','$p','3',CURRENT_TIMESTAMP);",
              "SET @last_insert_id1 = LAST_INSERT_ID();");
          $userID = '@last_insert_id1';
      } else {
          $returnArray = attachSibling($_REQUEST['siblingid']);
          $userID = $returnArray['userid'];
      }

      $sql[] = "INSERT INTO `tblstudent` ( `instsessassocid`,`scholarnumber`, `firstname`, `middlename`,
            `lastname`, `status` , `datecreated`) VALUES ('$instsessassocid' ,'$dataArray[scholarnumber]',
                   '$dataArray[firstname]','', '$dataArray[lastname]', '1', CURRENT_TIMESTAMP);";

      $sql[] = "SET @last_insert_id2 = LAST_INSERT_ID();";

      $sql[] = "INSERT INTO tblstudentacademichistory SET `studentid` = @last_insert_id2 , 
                             `clsecassocid` ='$classSectionAssocId', `academicstatus` ='1',`studenttype` ='233', `status` = 1 ;";

      $sql[] = "INSERT INTO tblstudentdetails  SET  `studentid` = @last_insert_id2,`gender` = '$dataArray[gender]' ,
                   `dob` = '$dataArray[dob]', `category` = '$dataArray[category]', `status` = '1' ; ";

      $sql[] = "INSERT INTO tblstudentcontact SET `studentid` = @last_insert_id2, `mobile` = '$dataArray[mobile]', 
                  `status` = '1' ;  ";

      if (empty($_REQUEST['siblingid']) && $_REQUEST['siblingid'] == '') {
          $sql[] = "INSERT INTO `tblparent`( `parentfirstname`, `parentmiddlename`, `parentlastname`)
                VALUES('$dataArray[parentfirstname]','', '$dataArray[parentlastname]') ;";

          $sql[] = "SET @last_insert_id3 = LAST_INSERT_ID();";

          $sql[] = "INSERT INTO `tblparentcontact`( `parentid`, `relationid`,  `mobile1`)
              VALUES(@last_insert_id3 , '$dataArray[relation]' , '$dataArray[mobile]');";

          $sql[] = "INSERT INTO `tbluserparentassociation`(`userid`, `parentid`, `studentid`,`status`, `datecreated`)
                     VALUES($userID,@last_insert_id3,@last_insert_id2, '1',CURRENT_TIMESTAMP) ; ";
      } elseif (!empty($returnArray['parentid'])) {
          $strSql = "INSERT INTO `tbluserparentassociation`(`userid`, `parentid`, `studentid`,`status`,
                  `datecreated`) VALUES";
          foreach ($returnArray['parentid'] as $key => $val) {
              $strSql .= "('$userID', '$val',@last_insert_id2, '1', CURRENT_TIMESTAMP ),";
          }
          $strSql = rtrim($strSql, ",");
          $sql[] = $strSql;
      }

      $result = dbInsert($sql);
      return true;
  }

  function updateStudentPersonal($dataArray) {
      $Fields = getFormFields("processStudentPersonal");
      $userSql = "SELECT `userid` FROM `tbluserparentassociation` 
                    WHERE `studentid` = '$dataArray[sid]' GROUP BY `studentid` ";
      $userID = mysqli_fetch_assoc(dbSelect($userSql));
      $sqlClsSecAssoc = "SELECT clsecassocid FROM tblclsecassoc WHERE classid='" . $dataArray['classid'] . "' AND sectionid='" . $dataArray['sectionid'] . "'";
      $sqlClassSectionAssocId = dbSelect($sqlClsSecAssoc);
      $result = mysqli_fetch_assoc($sqlClassSectionAssocId);
      $classSecAssocId = $result['clsecassocid'];
      $profilepicture = "";
      if (!empty($dataArray) && $dataArray['mode'] == 'edit') {
          if (isset($_FILES['profilepicture'])) {
              $instSqlString = "SELECT t1.instituteabbrevation  FROM tblinstitute as t1, `tblinstsessassoc` as t2
                            WHERE t2.instsessassocid = $_SESSION[instsessassocid]
                            AND t1.instituteid = t2.instituteid ";
              $instResult = dbSelect($instSqlString);
              $instAbbreviation = mysqli_fetch_assoc($instResult);

              if (empty($profilepicture)) {
                  $profilepicture = $dataArray['sid'] . '-' . $instAbbreviation['instituteabbrevation'] . '-' . $dataArray['scholarnumber'];
              }

              $extChk = validFileExtension($_FILES['profilepicture']['name'], 'image');
              $sizeChk = validFileSize($_FILES['profilepicture']['tmp_name'], 'image');

              if ($extChk) {
                  if ($sizeChk) {
                      $profilepicture = uploadImage($_FILES, STUDENT_IMG_PATH, $profilepicture, 'image');
                      if (!$profilepicture) {
                          addError(14, 'profilepicture');
                      }
                  } else {
                      addError(13, 'profilepicture');
                  }
              } else {
                  addError(12, 'profilepicture');
              }
          } else {
              $sql = "SELECT `profilepicture` FROM `tblstudentdetails` WHERE `studentid` =  '$dataArray[sid]' ";
              $result = mysqli_fetch_assoc(dbSelect($sql));
              if (!empty($result)) {
                  $profilepicture = $result['profilepicture'];
              }
          }

          $sqlStringArray = array(
              " UPDATE tblstudent SET 
                                                     scholarnumber='" . $dataArray['scholarnumber'] . "',   
                                                     firstname='" . $dataArray['firstname'] . "',
                                                     middlename='" . $dataArray['middlename'] . "',
                                                     lastname='" . $dataArray['lastname'] . "',
                                                     status = '1' ,
                                                     dateupdated=CURRENT_TIMESTAMP WHERE studentid='" . $dataArray['sid'] . "'",
              " UPDATE tblstudentcontact SET  
                                                    currentaddress1='" . $dataArray['currentaddress1'] . "',
                                                    currentaddress2='" . $dataArray['currentaddress2'] . "',
                                                    currentsuburbid='" . $dataArray['currentsuburbid'] . "',
                                                    currentcityid='" . $dataArray['currentcityid'] . "',
                                                    currentzipcode='" . $dataArray['currentzipcode'] . "',
                                                    currentstateid='" . $dataArray['currentstateid'] . "',
                                                    currentcountryid='" . $dataArray['currentcountryid'] . "',
                                                    mobile='" . $dataArray['mobile'] . "',
                                                    phone1='" . $dataArray['phone1'] . "',
                                                    phone2='" . $dataArray['phone2'] . "',
                                                    fax1='" . $dataArray['fax1'] . "',
                                                    fax2='" . $dataArray['fax2'] . "',
                                                    email='" . $dataArray['email'] . "',
                                                    emeregencycontactname='" . $dataArray['emeregencycontactname'] . "',
                                                    emeregencyphoneno='" . $dataArray['emeregencyphoneno'] . "',
                                                    emeregencycontactaddress='" . $dataArray['emeregencycontactaddress'] . "',
                                                    dateupdated=CURRENT_TIMESTAMP WHERE studentid='" . $dataArray['sid'] . "'",
              " UPDATE tblstudentdetails SET
                                                    dob='" . $dataArray['dob'] . "',
                                                    gender='" . $dataArray['gender'] . "',
                                                    religion='" . $dataArray['religion'] . "',
                                                    category='" . $dataArray['category'] . "',
                                                    percentgrade='" . $dataArray['percentgrade'] . "',
                                                    previousclass='" . $dataArray['previousclass'] . "',
                                                    previousresult='" . $dataArray['previousresult'] . "',
                                                    previousschool='" . $dataArray['previousschool'] . "',
                                                    passportnum='" . $dataArray['passportnum'] . "',
                                                    dateofjoining='" . $dataArray['dateofjoining'] . "',
                                                    housename='" . $dataArray['housename'] . "',
                                                    profilepicture='" . $profilepicture . "',
                                                    conveyancerequired='" . $dataArray['conveyancerequired'] . "',
                                                    pickuppointid='" . $dataArray['pickuppointid'] . "',
                                                    admissionreferencedby='" . $dataArray['admissionreferencedby'] . "',
                                                    otheradditionalinformation='" . $dataArray['otheradditionalinformation'] . "',
                                                    dateupdated=CURRENT_TIMESTAMP WHERE studentid='" . $dataArray['sid'] . "' ",
              " UPDATE tblstudentacademichistory SET 
                                                 clsecassocid='" . $classSecAssocId . "' ,
                                                 studenttype='" . $dataArray['studenttype'] . "', 
                                                dateupdated=CURRENT_TIMESTAMP  WHERE studentid='" . $dataArray['sid'] . "' ");
          if (!isset($dataArray['permaaddress1'])) {
              $sqlStringArray[] = " UPDATE tblstudentcontact SET  
                                                    permaaddress1='" . $dataArray['currentaddress1'] . "',
                                                    permaaddress2   ='" . $dataArray['currentaddress2'] . "',
                                                    permasuburbid='" . $dataArray['currentsuburbid'] . "',
                                                    permacityid='" . $dataArray['currentcityid'] . "',
                                                    permastateid='" . $dataArray['currentstateid'] . "',
                                                    permacountryid='" . $dataArray['currentcountryid'] . "',
                                                    permazipcode='" . $dataArray['currentzipcode'] . "' ";
          } else {
              $sqlStringArray[] = " UPDATE tblstudentcontact SET  
                                                    permaaddress1='" . $dataArray['permaaddress1'] . "',
                                                    permaaddress2   ='" . $dataArray['permaaddress2'] . "',
                                                    permasuburbid='" . $dataArray['permasuburbid'] . "',
                                                    permacityid='" . $dataArray['permacityid'] . "',
                                                    permastateid='" . $dataArray['permastateid'] . "',
                                                    permacountryid='" . $dataArray['permacountryid'] . "',
                                                    permazipcode='" . $dataArray['permazipcode'] . "' ";
          }

          // Check if username exist in db
          $usernameSql = "SELECT * FROM `tbluser` WHERE `username` = '$dataArray[email]' ";
          $usernameResult = mysqli_num_rows(dbSelect($usernameSql));

          if ($usernameResult == 0) {
              $password = '$2y$10$UHO6qkVVIPuqjV65f3XQzO5lc/P70mwrw0ViJwJjdgY6KK/EzikxO';
              $sqlStringArray[] = "INSERT INTO `tbluser` ( `username`, `password`, `status` ) VALUES('$dataArray[email]' , '$password' , '1')  ";

              $sqlStringArray[] = "SET @last_insert_id = LAST_INSERT_ID();";

              $sqlStringArray[] = "UPDATE `tbluserparentassociation` SET `userid` =  @last_insert_id  WHERE `studentid`  = $dataArray[sid] ";

              $result = dbUpdate($sqlStringArray);
          } else {
              $password = '$2y$10$UHO6qkVVIPuqjV65f3XQzO5lc/P70mwrw0ViJwJjdgY6KK/EzikxO';
              $sqlStringArray[] = "UPDATE `tbluser` SET `username`  = '$dataArray[email]' WHERE `userid`='$userID[userid]'";
          }
          $sqlUpdateResult = dbUpdate($sqlStringArray);
          if (isset($_POST["submit"]) && $result && $_POST["submit"] == "SAVE") {
              header("Location: studentPersonal.php?s=23&mode=edit&sid={$dataArray['sid']}");
              exit;
          } elseif (isset($_POST["next"]) && $result && $_POST["submit"] == "SAVE & NEXT") {
              header("Location: studentParent.php?s=23&mode=edit&sid={$dataArray['sid']}");
              exit;
          } else {
              header("Location: studentParent.php?s=23&mode=edit&sid={$dataArray['sid']}");
              exit;
          }
      }
  }

  function createStudentParent($studentId = null) {
      $Fields = getFormFields("processStudentParent");

      if (processFormData($Fields)) {
          foreach ($Fields as $key => $value) {
              if (!isset($_POST[$key])) {
                  $_POST[$key] = null;
              }
              ${$key} = cleanVar($_POST[$key]);
          }

          if (!empty($studentId) && is_numeric($studentId)) {
              $studentid = $studentId;
          } else {
              $studentid = cleanVar($_REQUEST['sid']);
          }

          $sqlUserId = " SELECT DISTINCT(`userid`) FROM `tbluserparentassociation`  WHERE `studentid` ='$studentid' ";
          $resultUserId = dbSelect($sqlUserId);
          $userId = mysqli_fetch_assoc($resultUserId);
          $sql = array("INSERT INTO tblparent SET 
                                                            parentfirstname='$parentfirstname',
                                                            parentmiddlename='$parentmiddlename',
                                                            parentlastname='$parentlastname',
                                                            gender='$gender',
                                                            religion='$religion',
                                                            category='$category',
                                                            qualificationid='$qualificationid',
                                                            occupation='$occupation',
                                                            income='$income',
                                                            status=1,
                                                            datecreated=CURRENT_TIMESTAMP ",
              "SET @last_insert_id1 = LAST_INSERT_ID();",
              "INSERT INTO tblparentcontact SET 
                                                            parentid = @last_insert_id1,
                                                            relationid='$relationid',
                                                            email1='$email1',
                                                            email2='$email2',
                                                            currentcountryid='$currentcountryid',
                                                            permacountryid='$permacountryid',
                                                            currentstateid='$currentstateid',
                                                            permastateid='$permastateid',
                                                            currentcityid='$currentcityid',
                                                            permacityid='$permacityid',
                                                            currentzipcode='$currentzipcode',
                                                            permazipcode='$permazipcode',
                                                            currentsuburbid='$currentsuburbid',
                                                            permasuburbid='$permasuburbid',
                                                            currentaddress1='$currentaddress1',
                                                            currentaddress2='$currentaddress2',
                                                            permaaddress1='$permaaddress1',
                                                            permaaddress2='$permaaddress2',
                                                            phone1='$phone1',
                                                            officephone='$officephone',
                                                            mobile1='$mobile1',
                                                            mobile2='$mobile2',
                                                            fax1='$fax1',
                                                            fax2='$fax2',
                                                            status= '1',
                                                            datecreated=CURRENT_TIMESTAMP",
              "INSERT INTO `tbluserparentassociation`(`userid`, `studentid`, `parentid`, `status`) 
                VALUES ('$userId[userid]', '$studentid', @last_insert_id1, '1')"
          );

          $result = dbInsert($sql);
          return true;
      }
  }

  function attachSibling($siblingid) {
      $sql = "SELECT t1.scholarnumber,t1.studentid, t3.userid , t1.firstname, t1.middlename, t1.lastname, t5.classid,
     t6.sectionid, t3.parentid, t4.parentfirstname, t4.parentmiddlename, t4.parentlastname, t5.classname,
     t6.sectionname, t9.relationid
        
        FROM `tblstudent` AS t1 ,
        `tblstudentdetails` AS t2,
        `tbluserparentassociation` AS t3,
        `tblparent` AS t4,
        `tblclassmaster` AS t5,
        `tblsection` AS t6,
        `tblclsecassoc` as t7,
        `tblstudentacademichistory` as t8,
        `tblparentcontact`  as t9
        
        WHERE t1.studentid = '$siblingid'
        AND t1.studentid =  t2.studentid
        AND t1.studentid =  t8.studentid
        AND t8.clsecassocid = t7.clsecassocid
        AND t1.studentid =  t3.studentid
        AND t3.parentid =  t4.parentid
        AND t7.classid =  t5.classid
        AND t7.sectionid =  t6.sectionid
        AND t4.parentid = t9.parentid

          ";

      $result = dbSelect($sql);
      if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
              $returnArray['userid'] = $row['userid'];
              $returnArray['parentid'][] = $row['parentid'];
          }

          return $returnArray;
      } else {
          return 0;
      }
  }

  /* for  inserting student parent information */

  function processStudentParent() {
      if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == 'edit') {
          $result = updateStudentParent($_REQUEST);
          $s = 26;
      } elseif (isset($_REQUEST['mode']) && $_REQUEST['mode'] == 'add') {
          $result = createStudentParent();
          $s = 25;
      } else {
          $result = false;
      }

      if ($result) {
          header("Location: studentMedical.php?s=" . $s . "&sid=" . $_REQUEST['sid'] . "&mode=edit");
          exit;
      }
  }

// function to edit the student parent record .

  function updateStudentParent($postValues) {
      $Fields = getFormFields("processStudentParent");
      if (processFormData($Fields)) {
          foreach ($Fields as $key => $value) {
              if (!isset($_POST[$key])) {
                  $_POST[$key] = null;
              }
              ${$key} = cleanVar($_POST[$key]);
          }

          if ($postValues['mode'] == 'edit' && $postValues['sid']) {
              $sqlUpdateStr = array("UPDATE tblparent  SET    parentfirstname     ='$parentfirstname',
                                                            parentmiddlename    ='$parentmiddlename',
                                                            parentlastname      ='$parentlastname',
                                                            gender              ='$gender',
                                                            religion            ='$religion',
                                                            category            ='$category',
                                                            qualificationid='$qualificationid',
                                                            occupation='$occupation',
                                                            income='$income',
                                                            dateupdated = 'CURRENT_TIMESTAMP' 
                                                            WHERE parentid=" . $parentid . " ;",
                  " UPDATE tblparentcontact SET
                                                            relationid          = '$relationid',
                                                            currentaddress1     ='$currentaddress1',
                                                            currentaddress2     ='$currentaddress2',
                                                            currentsuburbid     ='$currentsuburbid',
                                                            currentcityid       ='$currentcityid',
                                                            currentzipcode      ='$currentzipcode',
                                                            currentstateid      ='$currentstateid',
                                                            currentcountryid    ='$currentcountryid',
                                                            permaaddress1       ='$permaaddress1',
                                                            permaaddress2       ='$permaaddress2',
                                                            permasuburbid       ='$permasuburbid',
                                                            permacityid         ='$permacityid',
                                                            permastateid        ='$permastateid',
                                                            permacountryid      ='$permacountryid',
                                                            permazipcode        ='$permazipcode',
                                                            mobile1             ='$mobile1',
                                                            mobile2             ='$mobile2',
                                                            phone1              ='$phone1',
                                                            officephone          ='$officephone',
                                                            email1              ='$email1',
                                                            email2              ='$email2',
                                                            dateupdated         = 'CURRENT_TIMESTAMP'
                                                            WHERE parentid =" . $parentid
              );
          }

          $resultUpdate = dbUpdate($sqlUpdateStr);
          return true;
      }
  }

  function processStudentMedical() {
      $Fields = getFormFields("processStudentMedical");

      if (processFormData($Fields)) {
          foreach ($Fields as $key => $value) {
              if (!isset($_POST[$key])) {
                  $_POST[$key] = null;
              }
              ${$key} = cleanVar($_POST[$key]);
          }

          $studentid = cleanVar($_POST['sid']);
          $mode = cleanVar($_POST['mode']);

          $fieldsValue = " studentid='$studentid',medicalhistory='$medicalhistory',allergyinfo='$allergyinfo',
                        frequentillness='$frequentillness',regulardocname='$regulardocname',
                        regulardocaddress='$regulardocaddress', regulardocmobile='$regulardocmobile',
                        regulardocphone='$regulardocphone',regulardocemail='$regulardocemail',
                        regularhospname='$regularhospname',regularhospphone='$regularhospphone',regularhospemail='$regularhospemail',
                        regularhospaddress='$regularhospaddress',height='$height',weight='$weight',
                        bloodgroup='$bloodgroup',lefteyesight='$lefteyesight',righteyesight='$righteyesight',
                        identificationmark1='$identificationmark1',identificationmark2='$identificationmark2',
                        doctorremark='$doctorremark'";

          if ($mode == 'add') {
              $sqlString = "INSERT INTO tblmedicalinfo SET " . $fieldsValue . ", datecreated=CURRENT_TIMESTAMP";
              $result = dbInsert($sqlString);
          } else {
              $sqlString = "UPDATE tblmedicalinfo SET " . $fieldsValue . ", dateupdated=CURRENT_TIMESTAMP";
              $result = dbUpdate($sqlString);
          }

          if (isset($_POST["save"]) && $result) {
              header("Location: studentMedical.php?s=28&sid=$_GET[sid]&mode=add");
          } else {
              header("Location: studentFees.php?s=29&sid=$_GET[sid]&mode=edit");
          }
      }
  }

// function to edit the student medical record .

  function updateStudentMedical($arr) {
      $studentid = $arr['sid'];

      $sql = " SELECT * FROM `tblstudent` as t1, `tblmedicalinfo` as t2 WHERE t1.studentid = $studentid
            AND t1.studentid = t2.studentid";

      $result = dbSelect($sql);
      $row = mysqli_fetch_assoc($result);
      unset($sql);
      unset($result);

      $sql = " UPDATE `tblmedicalinfo`   
             SET `medicalhistory`='$arr[medicalhistory]',`allergyinfo`='$arr[allergyinfo]',`frequentillness`='$arr[frequentillness]',
            `regulardocname`='$arr[regulardocname]',`regulardocname`='$arr[regulardocname]',`regulardocmobile`='$arr[regulardocmobile]',
            `regulardocphone`='$arr[regulardocphone]',`regulardocemail`='$arr[regulardocemail]',`regularhospname`='$arr[regularhospname]',
            `regularhospphone`='$arr[regularhospphone]',`regularhospemail`='$arr[regularhospemail]',
            `regularhospaddress`='$arr[regularhospaddress]',`height`='$arr[height]',`weight`='$arr[weight]',
            `bloodgroup`='$arr[bloodgroup]',`lefteyesight`='$arr[lefteyesight]',`righteyesight`='$arr[righteyesight]',
            `identificationmark1`='$arr[identificationmark1]',`identificationmark2`='$arr[identificationmark2]',
            `doctorremark`='$arr[doctorremark]',`dateupdated` = CURRENT_TIMESTAMP
             
            WHERE `tblmedicalinfo`.studentid = $studentid";

      if ($result = dbUpdate($sql)) {
          header("Location: studentFees.php?s=11&sid={$studentid}");
          exit;
      }
  }

  function processStudentFees() {
      $studentId = cleanVar($_REQUEST['sid']);
      $Fields = getFormFields("processStudentFees");
      if (processFormData($Fields)) {
          foreach ($Fields as $key => $value) {
              if (!isset($_POST[$key])) {
                  $_POST[$key] = null;
              }
              ${$key} = cleanVar($_POST[$key]);
          }
          if (isset($_REQUEST['sid']) && is_numeric($_REQUEST['sid'])) {
              $newFeeRule = isset($_POST['feerule']) ? implode(',', $_POST['feerule']) : 0;
              $countRules = "SELECT count(studfeeruleassocid) as rulecount FROM tblstudfeeruleassoc WHERE studentid=$studentId";
              $resCount = dbSelect($countRules);
              $ruleCount = mysqli_fetch_assoc($resCount);

              /*               * ** IF THERE IS NO PREVIOUS RULE COUNT THEN INSERT ALL THE NEW VAUES */

              if (!$ruleCount['rulecount']) {
                  if (isset($_POST['feerule']) && !empty($_POST['feerule'])) {
                      foreach ($_POST['feerule'] as $feeRuleKey => $feeRuleValue) {
                          $sqlInsertFeeRule[] = " INSERT INTO tblstudfeeruleassoc SET studentid=$studentId,feeruleid=$feeRuleValue,associationstatus=1,datecreated=CURRENT_TIMESTAMP";
                          $sqlInsertFeeRule[] = " SET @feeruleassoc=LAST_INSERT_ID()";
                          foreach ($_POST['feeInst'][$feeRuleValue] as $instKey => $instValue) {
                              $sqlInsertFeeRule[] = " INSERT INTO tblstudfeeruleinstasssoc SET studfeeruleassocid=@feeruleassoc,installment='$instValue',status=1,datecreated=CURRENT_TIMESTAMP";
                          }
                      }
                      $result = dbInsert($sqlInsertFeeRule);
                  }
              }
              /* IF THERE IS ANY RULE DEFINES THEN WE ARE FOLLOWING THESE STEPS
               * STEP 1 - DISABLE ALL THE RULE WHICH IS NOT PRESENT NOW AND INSTALLMENTS
               * STEP 2 - CHECK THE NEW RULES AND INSTALLENTS AND THEN ENTER
               */ else {
                  /* STEP 1 - DISABLE ALL THE PREVIOUS RULE WHICH IS NOT IN THE CURRENT LIST */
                  $sqlUpdateFeeRule [] = "UPDATE tblstudfeeruleassoc SET associationstatus=0 , dateupdated=CURRENT_TIMESTAMP 
                            WHERE feeruleid NOT IN ($newFeeRule) AND studentid=$studentId ";

                  $sqlUpdateFeeRule [] = "UPDATE tblstudfeeruleinstasssoc SET status=0 , dateupdated=CURRENT_TIMESTAMP
                            WHERE studfeeruleassocid 
                            IN ( SELECT studfeeruleassocid FROM tblstudfeeruleassoc WHERE studentid=$studentId AND associationstatus=0)";
                  $resultUpdate = dbUpdate($sqlUpdateFeeRule);

                  $sqlPrevRule = dbSelect("SELECT studfeeruleassocid,feeruleid FROM tblstudfeeruleassoc WHERE studentId=$studentId AND associationstatus=1");
                  $sqlNewInstInsert = "INSERT INTO tblstudfeeruleinstasssoc (studfeeruleassocid,installment,status,datecreated) VALUES ";


                  if (mysqli_num_rows($sqlPrevRule) > 0) {
                      while ($rowPrevRule = mysqli_fetch_assoc($sqlPrevRule)) {
                          $prevRules[] = $rowPrevRule['feeruleid'];
                      }
                  }

                  foreach ($_POST['feerule'] as $feeRuleKey => $feeRuleValue) {
                      $newInstArray = isset($_POST['feeInst'][$feeRuleValue]) ? implode("','", $_POST['feeInst'][$feeRuleValue]) : 0;
                      $newInstArray = "'" . $newInstArray . "'";

                      if (in_array($feeRuleValue, $prevRules)) {
                          $instFlagInsert = false;
                          $instFlagUpdate = false;
                          $updateInst = dbUpdate("UPDATE tblstudfeeruleinstasssoc SET status=0, dateupdated = CURRENT_TIMESTAMP WHERE installment NOT IN ($newInstArray) AND studfeeruleassocid=(SELECT studfeeruleassocid FROM tblstudfeeruleassoc WHERE studentid=$studentId AND feeruleid=$feeRuleValue) ");
                          $prevInstArray = dbSelect("SELECT installment FROM tblstudfeeruleinstasssoc WHERE studfeeruleassocid=(SELECT studfeeruleassocid FROM tblstudfeeruleassoc WHERE studentid=$studentId AND feeruleid=$feeRuleValue)");
                          if (mysqli_num_rows($prevInstArray) > 0) {
                              while ($rowPrevInst = mysqli_fetch_assoc($prevInstArray)) {
                                  $prevInst[] = $rowPrevInst['installment'];
                              }
                              foreach ($_POST['feeInst'][$feeRuleValue] as $instKey => $instValue) {
                                  if (!in_array($instValue, $prevInst)) {
                                      $sqlNewInstInsert.="( (SELECT studfeeruleassocid FROM tblstudfeeruleassoc WHERE studentid=$studentId AND feeruleid=$feeRuleValue),'$instValue',1,CURRENT_TIMESTAMP ),";
                                      $instFlagInsert = true;
                                  } else {
                                      $sqlInstUpdate [] = "UPDATE tblstudfeeruleinstasssoc SET status=1 , dateupdated = CURRENT_TIMESTAMP WHERE studfeeruleassocid=(SELECT studfeeruleassocid FROM tblstudfeeruleassoc WHERE studentid=$studentId AND feeruleid=$feeRuleValue) AND installment='$instValue'";
                                      $instFlagUpdate = true;
                                  }
                              }

                              if ($instFlagInsert) {
                                  $result = dbInsert(rtrim($sqlNewInstInsert, ','));
                              }
                              if ($instFlagUpdate) {
                                  $result = dbUpdate($sqlInstUpdate);
                              }
                          }
                          if (isset($_REQUEST['pop-up']) && $_REQUEST['pop-up'] == 'y') {
                              header("Location: " . DIR_FILES . "/fees/feeCollectionProcessing.php?pop-up=y&studentid={$_GET['sid']}");
                              exit;
                          } else {
                              header("Location: studentDocument.php?s=31&sid={$_GET['sid']}");
                              exit;
                          }
                      } else {
                          $sqlNewRuleInsert[] = " INSERT INTO tblstudfeeruleassoc (studentid,feeruleid,associationstatus,datecreated) VALUES($studentId,$feeRuleValue,1,CURRENT_TIMESTAMP)";
                          $sqlNewRuleInsert[] = " SET @newrulassoc=LAST_INSERT_ID()";

                          foreach ($_POST['feeInst'][$feeRuleValue] as $instNewKey => $instNewValue) {
                              $sqlNewRuleInsert[] = " INSERT INTO tblstudfeeruleinstasssoc SET studfeeruleassocid=@newrulassoc,installment='$instNewValue',status=1,datecreated=CURRENT_TIMESTAMP";
                          }

                          $result = dbInsert($sqlNewRuleInsert);
                      }
                      header("Location: studentDocument.php?s=31&sid={$_GET['sid']}");
                      exit;
                  }
              }
          }
      }
  }

// function to edit the student fees record .

  function updateStudentFees($arr) { //echoThis($arr); die;
      $studentid = $arr['studentid'];

      $sql = "SELECT  * FROM `tblstudent`AS t1, `tblstudfeeruleassoc` AS t2 WHERE t1.studentid = $studentid  AND t1.studentid = t2.studentid";
      $result = dbSelect($sql);

      while ($row = mysqli_fetch_assoc($result)) {
          $studentfeeruleid[] = $row['studfeeruleassocid']; // echoThis($studentfeeruleid); die;
          $userid = $row['userid'];
      }

      unset($sql);
      unset($result);

      $sql = array();
      $feeruleid = $arr['feerule'];

      foreach ($feeruleid as $key => $value) {
          $sql[] .= "UPDATE `tblstudfeeruleassoc` SET `feeruleid`='$value', dateupdated=CURRENT_TIMESTAMP WHERE `tblstudfeeruleassoc`.studfeeruleassocid = $studentfeeruleid[0]";
      }

      $result = dbUpdate($sql[$key]);

      if ($result) {
          header("Location: studentDocument.php?s=32&userid={$userid}&studentid={$studentid}");
          exit;
      }
  }

  function processStudentDocument() {
      $Fields = getFormFields("processStudentDocument");
      if (processFormData($Fields)) {
          foreach ($Fields as $key => $value) {
              if (!isset($_POST[$key])) {
                  $_POST[$key] = null;
              }
              ${$key} = cleanVar($_POST[$key]);
          }
          $sql = "SELECT  `documenttype`, `documentname`
                  FROM `tbluserdocument` 
                  WHERE `studentid` = $_REQUEST[sid]";

          $result = dbSelect($sql);
          if (mysqli_num_rows($result) > 0) {
              if ($_POST['mode'] == "edit") {
                  updateStudentDocuments($_POST, $_FILES);
                  exit();
              }
          }

          $sqlUserDetail = "SELECT userid FROM tbluserparentassociation WHERE studentid='" . $_POST['sid'] . "'";
          $resultUserDetail = dbSelect($sqlUserDetail);
          $userDetailAssocId = mysqli_fetch_assoc($resultUserDetail);

          foreach ($_POST['documenttype'] as $key => $value) {
              if (isset($_FILES['document'])) {
                  $document[0] = array(
                      "name" => $_FILES['document']['name'][$key],
                      "type" => $_FILES['document']['type'][$key],
                      "tmp_name" => $_FILES['document']['tmp_name'][$key],
                      "error" => $_FILES['document']['error'][$key],
                      "size" => $_FILES['document']['size'][$key]
                  );

                  $docMaxId = getMaxId('tbluserdocument', 'documentid');
                  $docType = validFileExtension($_FILES['document']['name'][$key], 'document');

                  $docName = $docMaxId . '-' . $_POST['sid'] . '-' . $_SESSION['instsessassocid'];
                  $imgUpload = uploadImage($document, STUDENT_DOC_PATH, $docName, 'document');

                  if ($imgUpload) {
                      $sqlDocument = "INSERT INTO tbluserdocument 
                                    SET instsessassocid='" . $_SESSION['instsessassocid'] . "', 
                                        studentid='" . $_GET['sid'] . "',
                                        userid = '" . $userDetailAssocId['userid'] . "',
                                        documenttype='" . $value . "', 
                                        documentname='$imgUpload',
                                        status=1,
                                        datecreated=CURRENT_TIMESTAMP ";

                      $resultDocument = dbInsert($sqlDocument);
                  } else {
                      addError(14, 'documentname', 'studentDocument.php');
                  }
              }
          }
      }
      if (isset($resultDocument) && $resultDocument) {
          header('Location:studentDashboard.php?s=44&sid=' . $_POST['sid'] . '&mode=edit');
      } else {
          addError('document', null);
          header('Location:studentDocument.php?sid=' . $_POST['sid'] . '&mode=edit');
      }
  }

// function to edit the student fees record .

  function updateStudentDocuments($arr, $files) {
      $instsessassocid = $_SESSION['instsessassocid'];
      $studentid = $arr['sid'];
      $rand = rand(0, 999999);

      $editName = array();
      $name = array();
      $editName = $files['document']['name'];
      $sql = " SELECT * FROM `tblstudent` as t1, 
              `tbluserdocument` as t2 
              WHERE t1.studentid = $studentid 
              AND t1.studentid = t2.studentid ";

      $result = dbSelect($sql);

      while ($row = mysqli_fetch_assoc($result)) {
          $editdocument[] = DIR_FILES . "/student/documents/" . $row['documentname'];
          $editdocumenttype[] = $row['documenttype'];
          $editdocumentid[] = $row['documentid'];
          $Userid = $row['userid'];
      }

      foreach ($editName as $key => $value) {
          $namePrefix = $rand . "-" . $instsessassocid . "-";
          $name[] = $namePrefix . "-" . $studentid . "-" . $value;
      }

      unset($sql);
      unset($result);
      $sql = array();

      foreach ($editdocumentid as $key => $value) {
          $documentid = $arr['documenttype'][$key];
          $sql[] .= "UPDATE `tbluserdocument` SET `documenttype`='$documentid', `documentname` = '$name[$key]',
                    `dateupdated` = CURRENT_TIMESTAMP WHERE `tbluserdocument`.studentid = $_GET[sid]";

          $result = dbUpdate($sql[$key]);
          $document[0] = array(
              "name" => $files['document']['name'][$key],
              "type" => $files['document']['type'][$key],
              "tmp_name" => $files['document']['tmp_name'][$key],
              "error" => $files['document']['error'][$key],
              "size" => $files['document']['size'][$key]
          );

          uploadImage($document, STUDENT_DOC_PATH, $name[$key], "document");
      }
      if ($result) {
          header("Location: studentDashboard.php?s=11&userid={$Userid}&page=0");
          exit;
      }
  }

  /*
   * function used to refund the collected fee in two ways
   * Firstly complete installment could be refunded
   * Secondly an fee component of the collected fee could be refunded
   * eg .Caution Money etc.
   */

  function processStudentFeeDetails() {
      $studentid = cleanVar($_POST['studentid']);
      $renderArray = array();
      $instAbbre = cleanVar($_POST['instituteabbrevation']);
      $sessionName = cleanVar($_POST['sessionname']);
      $dataArray['originalfeereceiptid'] = cleanVar($_POST['originalfeereceiptid']);

      $sql = "INSERT INTO `tblfeerefund`(`feecollectiondetailid`, `feecomponentid`, `originalfeereceiptid`,
              `feerefundrecieptno`, `remarks`) VALUES ";
      $recieptcode = GenerateRefundReciept();
      $recieptid = $instAbbre . "/REF/" . $sessionName . "/" . $recieptcode;
      $totalFeeRefunded = 0;
      $originalfeereceiptid = $_POST['originalfeereceiptid'];
      $remarks = cleanVar($_POST['Remarks']);

      if (isset($_POST['feecollectiondetailid'])) {
          foreach ($_POST['feecollectiondetailid'] as $key => $value) {
              
              foreach ($value as $arrk => $arrval) {
                  $sql .= "('$key', '$arrk', '$originalfeereceiptid','$recieptid','$remarks'),";
                  $totalFeeRefunded += $arrval;
                  $renderArray[$arrk] = $arrval;
              }
              $updateSql[] = "UPDATE `tblfeecollectiondetail` SET `refundstatus`= '1', 
                  `feestatus` = '0', `dateupdated` = CURRENT_TIMESTAMP  WHERE `feecollectiondetailid`= '$key' ";
          }
      }
      if (isset($_POST['otherfees']) && !empty($_POST['otherfees'])) {
          foreach ($_POST['otherfees'] as $key => $value) {
              $updateSql[] = "UPDATE `tblfeecollectiondetail` SET `refundstatus`= '1', 
                  `feestatus` = '0', `dateupdated` = CURRENT_TIMESTAMP  WHERE `feecollectiondetailid`= '$key' ";

              $sql .= "('$key','NULL','$originalfeereceiptid','$recieptid', '$remarks'),";
          }
      }
      $otherfees = '';
      if (isset($_POST['otherfees'])) {
          foreach ($_POST['othefeedetails'] as $key => $value) {
              $otherfees .= $key . "=" . $value . "|";
          }
          $otherfees = rtrim($otherfees, "|");
      }
      $sql = rtrim($sql, ",");
     
      if ($result = dbInsert($sql)) {
          $result = dbUpdate($updateSql);
          header("Location: " . DIR_FILES . "/fees/refundFeeReciept.php?pop-up=y&studentid=$studentid&totalFee=$totalFeeRefunded&" . http_build_query($renderArray) . "&recieptid=$recieptid&ofd=" . $otherfees);
          exit;
      }
  }

  function processFeeMaster() {
      $instsessassocid = $_SESSION['instsessassocid'];
      $Fields = getFormFields("processFeeMaster");
      $sql = array();
      if (processFormData($Fields)) {
          foreach ($Fields as $key => $value) {
              if (!isset($_POST[$key])) {
                  $_POST[$key] = null;
              }
              ${$key} = cleanVar($_POST[$key]);
          }

          if (isset($_POST['edid']) && $_GET['mode'] == 'edit') {
              editFeeMaster();
              exit();
          }

          $tmpSql = "INSERT INTO `tblfeestructure` (`instsessassocid`, `classid`, `feecomponentid`,`status`)  VALUES";
          $tmpSql2 = "INSERT INTO `tblfeestructuredetails`  (`feestructureid`,  `amount`, `duedate`,`frequency`,`isrefundable`)VALUES";

        
          foreach ($classid as $key => $value) {
              
              if (strpos($value, ',') !== true) {
                  $tmpClass = explode(",", $value);
                  $tmpAmount = explode(",", $amount[$key]);
                  $tmpDuedate = explode(",", $duedate[$key]);
              } else {
                  $tmpClass = $classname;
                  $tmpAmount = explode(",", $amount[$key]);
                  $tmpDuedate = explode(",", $duedate[$key]);
              }

              foreach ($tmpClass as $k => $cls) {
                  $sql[] = $tmpSql . "('$instsessassocid', '$cls', '$feecomponents','1')";
                  $sql[] = "SET @last_insert_id = LAST_INSERT_ID()";
                  $sql2helper = "";

                  foreach ($tmpAmount as $k2 => $amt) {
                      $sql2helper .= "(@last_insert_id, '$amt','" . $tmpDuedate[$k2] . "', '$frequency', '$isrefundable'),";
                  }
                  $sql[] = $tmpSql2 . (rtrim($sql2helper, ','));
              }
          }
              

          $result = dbInsert($sql);
          if ($result[0] != 0) {
              header("Location: feeMaster.php?s=10&id={$result[0]}");
              exit;
          } else {
              addError(5, null);
          }
      }
  }

  function editFeeMaster() {
      $classidArray = cleanVar($_POST['classid']);

      foreach ($classidArray as $key => $value) {
          $sql[] = "UPDATE `tblfeestructure` , `tblfeestructuredetails`"
                  . " SET `tblfeestructure`.feecomponentid ='" . cleanVar($_POST['feecomponents']) . "',
                        `tblfeestructure`.classid = '$value' ,
                        `tblfeestructuredetails`.amount =  '" . cleanVar($_POST['amount'][$key]) . "',
                        `tblfeestructuredetails`.duedate =  '" . cleanVar($_POST['duedate'][$key]) . "',
                        `tblfeestructuredetails`.frequency =  '" . cleanVar($_POST['frequency']) . "',
                        `tblfeestructuredetails`. `dateupdated` = CURRENT_TIMESTAMP,
                        `tblfeestructure`.`dateupdated` = CURRENT_TIMESTAMP
                    WHERE `tblfeestructure`.classid = $value
                    AND `tblfeestructure`.feestructureid = '" . cleanVar($_POST['feestructureid']) . "'
                    AND `tblfeestructuredetails`.feestructuredetailsid = '" . cleanVar($_POST['feestructuredetailsid'][$key]) . "'
                ";
      }
      $result = dbUpdate($sql);
      if (!in_array(0, $result) || empty(mysql_error())) {
          header("Location: feeMaster.php?s=11");
          exit();
      }
  }

  function processFeeRule() {
      $instsessassocid = $_SESSION['instsessassocid'];
      $Fields = getFormFields("processFeeRule");

      if (processFormData($Fields)) {
          foreach ($Fields as $key => $value) {
              if (!isset($_POST[$key])) {
                  $_POST[$key] = null;
              }
              ${$key} = cleanVar($_POST[$key]);
          }

          // if edit mode is set to confirm then call the edit function to update
          if (isset($_POST['edid'])) {
              updateFeeRule($_POST);
              exit();
          }

          $sql = array("INSERT INTO `tblfeerule`(`instsessassocid`,`feerulename`, `feeruleremarks`, `feerulestatus`)
                      VALUES ('$instsessassocid','$feerulename','$feeruleremarks','$feerulestatus');",
              "SET @last_insert_id = LAST_INSERT_ID();");

          $strSql = "INSERT INTO `tblfeeruledetail`(`feeruleid`, `feecomponentid`, `feerulemodeid`, `feeruletype`, `feeruleamount`)
                    VALUES";

          if (is_array($feecomponentid)) {
              foreach ($feecomponentid as $key => $value) {
                  $strSql .= "(@last_insert_id, '$value', '$feerulemodeid', '$feeruletype', '$feeruleamount'),";
              }
          } else {
              $strSql .= "(@last_insert_id, '$feecomponentid', '$feerulemodeid', '$feeruletype', '$feeruleamount'),";
          }

          $sql[] = rtrim($strSql, ',');

          if ($result = dbInsert($sql)) {
              header("Location: feeRule.php?s=13&id={$result[0]}");
          }
      }
  }

  function updateFeeRule($RuleDetails) {
      $instsessassocid = $_SESSION['instsessassocid'];
      $feeruleid = cleanVar($_POST['edid']);
      $feeruleremarks = trim($_POST['feeruleremarks']);
      $strSql = "SELECT `feeruledetailid` FROM `tblfeeruledetail` WHERE `feeruleid` = '$feeruleid' ";
      $strResult = dbSelect($strSql);
      while ($row = mysqli_fetch_assoc($strResult)) {
          $feeruledetailsid[] = $row['feeruledetailid'];
      }
      if (count($_POST['feecomponentid']) == count($feeruledetailsid)) {
          $sql[] = "UPDATE `tblfeerule` 
             SET `instsessassocid`= '$instsessassocid',`feerulename`='$_POST[feerulename]',
            `feeruleremarks`= '$feeruleremarks',`feerulestatus`= '$_POST[feerulestatus]'
               WHERE `feeruleid` = '$feeruleid' ;";

          foreach ($_POST['feecomponentid'] as $key => $value) {
              $sql[] = "UPDATE `tblfeeruledetail` SET
                          `feeruleid`= '$feeruleid' ,`feecomponentid`= '$value',
                          `feerulemodeid`= '$_POST[feerulemodeid]',`feeruletype`='$_POST[feeruletype]',
                          `feeruleamount`='$_POST[feeruleamount]',`dateupdated` = 'CURRENT_TIMESTAMP'
                          WHERE `feeruleid` = '$feeruleid' AND `feeruledetailid` = '$feeruledetailsid[$key]' ; ";
          }
          if ($result = dbUpdate($sql)) {
              header("Location: feeRule.php?s=14&feeruleid={$feeruleid}");
              exit;
          }
      } else {
          $sql[] = "UPDATE `tblfeerule`, `tblfeeruledetail` 
                      SET `tblfeeruledetail`.`status`  = 0 
                      WHERE `tblfeerule`.`feeruleid` = '$feeruleid'
                      AND`tblfeeruledetail`.`feeruleid` = `tblfeerule`.`feeruleid` ;";

          foreach ($_POST['feecomponentid'] as $key => $value) {
              $sql[] = "INSERT INTO `tblfeeruledetail`(`feeruleid`, `feecomponentid` ,`feerulemodeid`,
                            `feeruletype`,`feeruleamount`, `status`, `datecreated` )
                            VALUES('$feeruleid', '$value', '$_POST[feerulemodeid]', '$_POST[feeruletype]',
                             '$_POST[feeruleamount]' , '1', 'CURRENT_TIMESTAMP'); ";
          }
          $result = dbInsert($sql);
          header("Location: feeRule.php?s=14&feeruleid={$feeruleid}");
          exit;
      }
  }

  function  processAddUser() {
      
      $Fields = getFormFields("processAddUser");
      
      if (processFormData($Fields)) {
          foreach ($Fields as $key => $value) {
              if (!isset($_POST[$key])) {
                  $_POST[$key] = null;
              }
              ${$key} = cleanVar($_POST[$key]);
          }
          /*
          if (isset($_GET['mode']) && $_GET['mode'] = "edit") {
              $sql = "UPDATE tbluser SET `username`= '" . $_POST['username'] . "', password='" . encryptIt($_POST['password']) . "' ,
                    roleid = '" . $_POST['roleid'] . "',dateupdated = CURRENT_TIMESTAMP WHERE userid = '" . $_GET['edid'] . "'";
              // echoThis($sql); die;

              if ($result = dbUpdate($sql)) {
                  header("Location: User.php?s=9");
                  exit();
              }
          } else { */ 
          
              //check if the username/email already exists.
            $sql = "SELECT 1 FROM `tbluser` WHERE `username` = '$username' LIMIT 1"; 
           
            $result = dbSelect($sql);
         //   echoThis(is_bool($result)); die; 
          if ((mysqli_num_rows($result)) != 1) {
            $sql = "INSERT INTO `tbluser`(`username`,`instsessassocid`, `password`,  `roleid`,`status`) 
                            VALUES('$username','" . $_SESSION['instsessassocid'] . "','" . encryptIt($password) . "','$roleid','$status')";

                  if ($result = dbInsert($sql)) {
                      header("Location: addUser.php?s=8");
                      exit();
                  }  
           
          }
          
          addError("8");
      }
  }

  function processClassMaster() {
      //echoThis($_POST); die;
      $instsessassocid = $_SESSION['instsessassocid'];
      $Fields = getFormFields("processclassMaster");

      if (processFormData($Fields)) {
          foreach ($Fields as $key => $value) {
              if (!isset($_POST[$key])) {
                  $_POST[$key] = null;
              }
              ${$key} = cleanVar($_POST[$key]);
          }
          $sql = array();

          foreach ($classid as $key => $value) {
              $strSql = " INSERT INTO `tblclsecassoc`(`instsessassocid`,`classid`, `sectionid`,`status`) 
                        VALUES";
              //tblclsecassoc ????????????????????????????????//
              foreach ($sectionid as $k => $val) {
                  $strSql .= "('$instsessassocid','$value','$val','1'),";
              }

              //clean the string of comma (,) and append to SQL array
              $sql[] = rtrim($strSql, ',');
              $strSql = " INSERT INTO `tblclssubjassoc`(`classid`, `subjectid`,`isoptional`) 
                        VALUES";

              foreach ($subjectid as $s => $v) {
                  $strSql .= "('$value','$v','$isoptional'),";
              }

              //clean the string of comma (,) and append to SQL array
              $sql[] = rtrim($strSql, ',');

              $strSql = "INSERT INTO `tblclsexamassoc`(`instsessassocid`, `classid`,`examid`,  
                    `examstartdate`, `examenddate`,`status`) VALUES";

              // since the date will always come as an array having value in string,
              // we need to convert the individual string value, possibility keeping multiple dates into another array

              $startDate = explode(",", $examstartdate[0]);
              $endDate = explode(",", $examenddate[0]);

              foreach ($startDate as $esdkey => $esdval) {
                  $strSql .= "('$instsessassocid','$value','$exams[$esdkey]','$esdval','$endDate[$esdkey]','1'),";
              }
              $sql[] = rtrim($strSql, ',');
          }
          //echoThis($sql); die;
          if ($result = dbInsert($sql)) {
              header("Location: classMaster.php?s=12");
          }
      }
  }

  function processAddSubject() {
      $Fields = getFormFields("processaddSubject");
      if (processFormData($Fields)) {
          foreach ($Fields as $key => $value) {

              if (!isset($_POST[$key])) {
                  $_POST[$key] = null;
              }
              ${$key} = cleanVar($_POST[$key]);
          }
          if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == 'edit') {
              $strSql = " UPDATE tblsubjects SET subjectname='" . $_POST['subjectname'][0] . "', subjectcode='" . $_POST['subjectcode'][0] . "',"
                      . "comments='" . $_POST['comments'][0] . "', instsessassocid='" . $_SESSION['instsessassocid'] . "', dateupdated = CURRENT_TIMESTAMP WHERE subjectid='" . $_REQUEST['edid'] . "'";

              $result = dbUpdate($strSql);
              if ($result) {
                  header('Location:addSubject.php?s=49');
              }
          } else {
              $strSql = "INSERT INTO `tblsubjects`(`subjectname`, `subjectcode`, `instsessassocid`,`comments`, `status`, `sortorder`) 
                       VALUES";

              //run the loop to make sql statement.
              foreach ($subjectname as $key => $subject) {
                  if (!isset($status[$key])) {
                      $status[$key] = 0;
                  }
                  $strSql .= "('$subject', '$subjectcode[$key]', '" . $_SESSION['instsessassocid'] . "', '$comments[$key]', '0    ', NULL),";
              }
              $sql[] = rtrim($strSql, ',');
              $result = dbInsert($sql);

              if ($result) {
                  header("Location: addSubject.php?s=48");
              } else {
                  addError(28, null);
                  exit;
              }
          }
      }
  }

  function processAddAcademicYear() {
      $Fields = getFormFields("processAddAcademicYear");
      if (processFormData($Fields)) {
          foreach ($Fields as $key => $value) {
              if (!isset($_POST[$key])) {
                  $_POST[$key] = null;
              }
              ${$key} = cleanVar($_POST[$key]);
          }

          if (isset($_GET['mode']) && $_GET['mode'] == 'edit') {
              $sqlStr = "UPDATE tblacademicsession SET sessionname='" . $_POST['sessionname'] . "' , sessionstartdate='" . $_POST['sessionstartdate'] . "',"
                      . " sessionenddate='" . $_POST['sessionenddate'] . "', status='" . $_POST['status'] . "', dateupdated=CURRENT_TIMESTAMP "
                      . " WHERE academicsessionid='" . $_POST['edid'] . "'";


              $result = dbUpdate($sqlStr);
              header('Location:addAcademicYear.php?s=6');
          } else {
              $sql = array("INSERT INTO `tblacademicsession`(`sessionname`, `sessionstartdate`, `sessionenddate`, `status`, `datecreated`) 
                        VALUES ( '$sessionname', '$sessionstartdate', '$sessionenddate', '$status', CURRENT_TIMESTAMP);");

              $result = dbInsert($sql);
              if (isset($_POST["save"]) && $result) {
                  header("Location: addAcademicYear.php?s=3&id={$result[0]}");
              }
          }
      }
  }

  function processAcademicCollectionType() {
      $Fields = getFormFields("processAcademicCollectionType");

      if (processFormData($Fields)) {
          foreach ($Fields as $key => $value) {
              if (!isset($_POST[$key])) {
                  $_POST[$key] = null;
              }
              ${$key} = cleanVar($_POST[$key]);
          }
          $sql = array(" INSERT INTO `tblmastercollectiontype`(`mastercollectiontype`, `status`, `datecreated`)
                        VALUES ('$mastercollectiontype','$status', CURRENT_TIMESTAMP);");

          if ($result = dbInsert($sql)) {
              unset($sql);

              foreach ($collectionname as $value) {
                  $sql[] = "  INSERT INTO `tblmastercollection`(`mastercollectiontypeid`,`collectionname`,`description`, `datecreated`)
                            VALUES('$result[0]', '$value', '$description', CURRENT_TIMESTAMP);";
              }
              $result = dbInsert($sql);
          }
          if (isset($_POST["save"]) && $result) {
              header("Location: academicCollectionType.php?s=7&id={$result[0]}");
          }
      }
  }

  function processAddInstitute() {
      $Fields = getFormFields("processAddInstitute");
      if (processFormData($Fields)) {
          foreach ($Fields as $key => $value) {
              if (!isset($_POST[$key])) {
                  $_POST[$key] = null;
              }
              ${$key} = cleanVar($_POST[$key]);
          }
          $uploadImgName = '';

          if ($_REQUEST['mode'] == 'add') {
              $msg = 1;
              if (isset($_FILES['institutelogo']) && !empty($_FILES['institutelogo']['tmp_name'])) {
                  $maxInstId = getMaxId('tblinstitute', 'instituteid') + 1;
                  $imgExt = strtolower(substr($_FILES['institutelogo']['name'], strpos($_FILES['institutelogo']['name'], '.') + 1, 4));
                  $uploadImgName = $maxInstId . strtolower(str_replace(' ', '-', $institutename) . '-' . $_FILES['institutelogo']['name']);
                  $uploadResult = uploadImage($_FILES, INST_LOGO_IMG_PATH, $uploadImgName, 'image');
              }
              $sql = array("INSERT INTO `tblinstitute` ( `institutename`,`insituteweburl`,`instituteaddress1`,`instituteaddress2`,
                       `institutecityid`,`institutestateid`,`institutephone1`,`institutephone2`, `instituteemail1`, `instituteemail2`,
                       `institutefax1`,`instituteaccreditionid`,`institutecountryid`,`status`,`institutedescription`,`institutelogo`)
                       VALUES('$institutename','$insituteweburl','$instituteaddress1','$instituteaddress2','$institutecityid',
                       '$institutestateid','$institutephone1','$institutephone2','$instituteemail1','$instituteemail2','$institutefax1','$instituteaccreditionid',
                       '$institutecountryid','$status', '$institutedescription','$uploadImgName');");

              $result = dbInsert($sql);
              // header('Location:addInstitute.php?s=1');
          } else {
              if (isset($_FILES['institutelogo']) && !empty($_FILES['institutelogo']['tmp_name'])) {
                  $maxInstId = getMaxId('tblinstitute', 'instituteid') + 1;
                  $imgExt = strtolower(substr($_FILES['institutelogo']['name'], strpos($_FILES['institutelogo']['name'], '.') + 1, 4));
                  $uploadImgName = $maxInstId . strtolower(str_replace(' ', '-', $institutename) . '-' . $_FILES['institutelogo']['name']);
                  $uploadResult = uploadImage($_FILES, INST_LOGO_IMG_PATH, $uploadImgName, 'image');
              } else {
                  $instituteid = cleanVar($_POST['edid']);
                  $row = mysqli_fetch_assoc(dbSelect("SELECT `institutelogo` FROM `tblinstitute` WHERE `instituteid` =  '$instituteid' "));
                  $uploadImgName = $row['institutelogo'];
              }

              $msg = 2;
              $sql = "   UPDATE `tblinstitute` SET `institutename`='$institutename',`instituteaddress1`='$instituteaddress1',
                        `instituteaddress2`='$instituteaddress2',`institutecityid`='$institutecityid',`institutestateid`='$institutestateid',
                        `institutecountryid`='$institutecountryid',`institutephone1`='$institutephone1',`institutephone2`='$institutephone2',
                        `institutefax1`='$institutefax1',`instituteemail1`='$instituteemail1',`instituteemail2`='$instituteemail2',
                        `insituteweburl`='$insituteweburl',`institutedescription`='$institutedescription',
                        `instituteaccreditionid`='$instituteaccreditionid',`status`='$status', `institutelogo`='$uploadImgName',
                        `dateupdated` = CURRENT_TIMESTAMP
                        WHERE `tblinstitute`.instituteid = " . $_GET['edid'];

              $result = dbUpdate($sql);
              // header('Location:addInstitute.php?s=2');
          }
          header('Location:addInstitute.php?s=' . $msg);
      }
  }

  function processOtherFees() {
      //echoThis($_POST); die;
      $instsessassocid = $_SESSION['instsessassocid'];
      $Fields = getFormFields("processOtherFees");

      if (processFormData($Fields)) {
          foreach ($Fields as $key => $value) {
              if (!isset($_POST[$key])) {
                  $_POST[$key] = null;
              }
              ${$key} = cleanVar($_POST[$key]);
          }
          if (isset($_POST['edid']) && !empty($_GET['edid'])) {
              $description = cleanVar($description);
              $sql[] = "UPDATE `tblfeeothercharges` SET `otherfeehead`='$otherfeehead',`description`='$description',dateupdated = CURRENT_TIMESTAMP WHERE `feeotherchargesid` = $_POST[edid]";
              $sql[] = "UPDATE `tblfeeotherchargesdetails` SET `chargemode`='$chargemode',`otherfeetype`='$otherfeetype',
                    `frequency`='$frequency', `isperiodic`='$isperiodic',`amount`='$amount', dateupdated = CURRENT_TIMESTAMP
                     WHERE `feeotherchargesid` = $_POST[edid]";

              $result = dbUpdate($sql);
              header("Location: otherFees.php?s=17");
              exit();
          }


          $sql = array("INSERT INTO `tblfeeothercharges`( `instsessassocid`,`otherfeehead`,`description`,`status`) "
              . "VALUES('$instsessassocid','$otherfeehead','$amount','$status');",
              "SET @last_insert_id = LAST_INSERT_ID()",
              "INSERT INTO `tblfeeotherchargesdetails`( `feeotherchargesid`,`chargemode`,`otherfeetype`,`frequency`,`isperiodic`,`amount`)
                    VALUES(@last_insert_id,'$chargemode','$otherfeetype','$frequency','$isperiodic','$amount');"
          );

          $result = dbInsert($sql);

          if (isset($_POST["save"]) && $result) {
              header("Location: otherFees.php?s=16&id={$result[0]}");
          }
      }
  }

  function processfeeRefund() {
      $dataArray = cleanVar($_POST);

      $studentid = cleanVar($_POST['studentid']);
      $instAbbre = cleanVar($_POST['instituteabbrevation']);
      $sessionName = cleanVar($_POST['sessionname']);
      $dataArray['feerefund'] = cleanVar($_POST['feerefund']);
      $dataArray['originalfeereceiptid'] = cleanVar($_POST['originalfeereceiptid']);
      $dataArray['remarks'] = cleanVar($_POST['remarks']);
      $dataArray['feeamount'] = cleanVar($_POST['feeamount']);

      $sql = "INSERT INTO `tblfeerefund`(`feecollectiondetailid`, `originalfeereceiptid`,`feerefundrecieptno`, `remarks`) VALUES ";
      $recieptcode = GenerateRefundReciept();
      $recieptid = $instAbbre . "/REF/" . $sessionName . "/" . $recieptcode;
      $totalFeeRefunded = 0;
      foreach ($dataArray['feerefund'] as $key => $value) {
          $originalfeereceiptid = $dataArray['originalfeereceiptid'][$key];
          $remarks = $dataArray['remarks'][$key];
          $sql .= "('$value', '$originalfeereceiptid','$recieptid','$remarks'),";
          $updateSql[] = "UPDATE `tblfeecollectiondetail` SET `refundstatus`= '1', `feestatus` = '1', `dateupdated` = CURRENT_TIMESTAMP  WHERE `feecollectiondetailid`= $value ";
          $totalFeeRefunded += $dataArray['feeamount'][$key];
          $renderArray[$value] = $dataArray['feeamount'][$key];
      }
      $sql = rtrim($sql, ",");

      if ($result = dbInsert($sql)) {
          $result = dbUpdate($updateSql);
          header("Location: refundfeeReciept.php?pop-up=y&studentid=$studentid&totalFee=$totalFeeRefunded&recieptid=$recieptid&" . http_build_query($renderArray));
          exit;
      }
  }

  function processFeeCollection() {
      $Fields = getFormFields("processfeeCollection");

      if (processFormData($Fields)) {
          foreach ($Fields as $key => $value) {
              if (!isset($_POST[$key])) {
                  $_POST[$key] = null;
              }
              ${$key} = cleanVar($_POST[$key]);
          }
      }
  }

  function processFeeCollectionProcessing($studentData = NULL) {
    //echoThis($_REQUEST); die;
      if (!empty($studentData)) {
          $dataArray = $studentData;
      } else {
          $dataArray = $_POST;
      }
      
      if(!empty($_POST['tcfeesamount'])){
          processIssueTC($dataArray);
          exit();
      }
      
      $instsessassocid = $_SESSION['instsessassocid'];
      $otherFeeHeads = $otherFeeDetails = '';
      $originalAmount = '';
      $totalFeePaid = 0;
      $deductedAmtPerInst= 0;
      $chequedepositdate = date('Y-m-d');
      $totalLatefees = $totalChequeBounceFees = $totalConveyanceFees = 0;

      // Calling a function for generating Reciept number  
      // passing the function ; institute abrevation and session name as parameters
     
      
        $recieptid = GenerateRecieptNumber($dataArray['instituteabbrevation'],$dataArray['sessionname']);
        $PayingInstNo = count($dataArray['feeinstallment']);
        
        
        // To be included only if fee amount is adjusted
       // echoThis($dataArray);
        if(is_numeric($dataArray['feeadjustedvalue']) && !empty($dataArray['feeeditremarks'])){
           $deductedAmtPerInst = (($dataArray['feeoriginalvalue'] - $dataArray['feeadjustedvalue']) / $PayingInstNo) ;
        }
        
     
      
     $sql = array("INSERT INTO `tblfeecollection`(`studentid`,`instsessassocid`,`clsecassocid`,`receiptid`,
                `remarks`,`datecreated` ) VALUES('$dataArray[studentid]','$instsessassocid',
                '$dataArray[clsecassocid]','$recieptid', '$dataArray[remarks]', 'CURRENT_TIMESTAMP');",
          
         "SET @last_insert_id = LAST_INSERT_ID();");

      
      
    foreach ($dataArray['feeinstallment'] as $key => $value) {
        $installmentAmount = $dataArray['feeinstallmentamount'][$key];
         
        if($deductedAmtPerInst != 0 ){
            $installmentAmount = $installmentAmount - $deductedAmtPerInst;
        }
       
          $installmentDate = convertDate($dataArray['feeinstallment'][$key]);
          $renderArray[$installmentDate] = $installmentAmount;
           // Fee mode Id '305' depicts 'Cash' Mode.
          
          $totalFeePaid += $installmentAmount;
        if ($dataArray['feemodeid'] == 305) {
            $sql[] = "INSERT INTO `tblfeecollectiondetail`( `feecollectionid`,`feeinstallmentamount`,
                `feemodeid`,`feestatus`, `collectiontype`)
                VALUES(@last_insert_id,'$installmentAmount','$dataArray[feemodeid]','1','316')";
          
           
        } 
        else {
            $sql[] = "INSERT INTO `tblfeecollectiondetail`( `feecollectionid`,`feeinstallmentamount`,
                `feemodeid`,`feestatus`, `collectiontype`)
                VALUES(@last_insert_id,'$installmentAmount','$dataArray[feemodeid]','0','316')";
        }
          
         $sql[] = "SET @last_insert_id1 = LAST_INSERT_ID();";
            
         $sql[] = "INSERT INTO `tblfeeinstallmentdates`(`feecollectiondetailid`, `feeinstallment`) 
                        VALUES(@last_insert_id1, '$installmentDate')";
         
            
     }
   
    // Making entries for all penalties 
    // ie late fees, cheque bounce, conveyance etc
    // And collection type id for all such penalties is '317'
         $totalOtherFees = 0;
        foreach($dataArray['otherfeehead'] as $key => $value){
            if(isset($dataArray['feeinstallment'][$key - 1])){ 
                $installmentDate = convertDate($dataArray['feeinstallment'][$key - 1]);
               
                foreach($value as $k => $val){ 
                    $otherFeeCharged = $dataArray['otherFeecharged'][$key][$k];
                    if(isset($dataArray['otherFeecharged'][$key][$k])){
                             $otherFeeDetails[] = $dataArray['otherfees'][$key][$k];
                             $totalOtherFees += $otherFeeCharged;
                        }
                    if ($dataArray['feemodeid'] == 305 && $otherFeeCharged != 0 ) {
                        
                       
                            $sql[] = "INSERT INTO `tblfeecollectiondetail`( `feecollectionid`,`feeinstallmentamount`,
                                      `feemodeid`,`feestatus`, `collectiontype`)
                                       VALUES(@last_insert_id,'$otherFeeCharged','$dataArray[feemodeid]','1','$val');";

                            $sql[] = "SET @last_insert_id2 = LAST_INSERT_ID();";

                            $sql[] = " INSERT INTO `tblfeepenaltydetails`(`feecollectiondetailid`, `feeinstallmentid`) 
                                        VALUES (@last_insert_id2, '$installmentDate')";

                    } 
                    elseif($otherFeeCharged != 0) {
                        
                         
                        $sql[] = "INSERT INTO `tblfeecollectiondetail`( `feecollectionid`,`feeinstallmentamount`,
                                  `feemodeid`,`feestatus`, `collectiontype`)

                                  VALUES(@last_insert_id,'$otherFeeCharged','$dataArray[feemodeid]','0','$val');";

                        $sql[] = "SET @last_insert_id2 = LAST_INSERT_ID();";

                        $sql[] = "   INSERT INTO `tblfeepenaltydetails`(`feecollectiondetailid`, `feeinstallmentid`) 
                                VALUES (@last_insert_id2, '$installmentDate')
                                ";

                    }
                    
                }
                
           
             }
            
        }
      
     
        if(isset($dataArray['otherpenalty']) && !empty($dataArray['otherpenalty'])){
            foreach($dataArray['otherpenalty'] as $pkey => $pval){
               $otherFeeDetails[] = $pkey;
               $totalOtherFees += $pval;
            }
         
         
            $sql[] = "UPDATE `tblotherfeepenalties` SET `feecollectionid` = @last_insert_id , `status` = '1', 
                `paidon` = CURRENT_TIMESTAMP, `dateupdated` = 'CURRENT_TIMESTAMP' 
                  WHERE `studentid` = '$dataArray[studentid]' AND `status` = '0' ";
        }
      

      // Making enteries for fees paid by 
      // Cheque. Complete details for cheque given against payment
      
     
      if ($dataArray['feemodeid'] == 304) {
          $sql[] = "INSERT INTO `tblfeecheque`( `feecollectionid`,`chequenumber`,`bankname`,
                 `chequedepositdate`,`remarks`,`chequestatus`)VALUES(@last_insert_id,'$dataArray[chequenumber]',
                  '$dataArray[bankname]','$chequedepositdate','$dataArray[remarks]','1')";
      }
     // $totalFeePaid += $dataArray['netinstallmentfees'] + $dataArray['netotherfees'];
      
      foreach($dataArray['otherfees'] as $key => $value){
          if(array_key_exists(($key-1), $dataArray['feeinstallment']) ){
              $otherfees = 0;
           foreach($value as $k => $val){
               $totalotherFees[$val][] = $dataArray['otherFeecharged'][$key][$k];
            }
          }
      }
    
     
    // To be included only if fee amount is adjusted
    
    if($deductedAmtPerInst != 0){
        $feeoriginalvalue = $dataArray['feeoriginalvalue'];
        $feeadjustedValue = $dataArray['feeadjustedvalue'];
        $sql[] = "INSERT INTO `tblfeeadjusted`(`feecollectionid`, `totaloriginalfees`, `totaladjustedfees`, `remarks`) 
                VALUES (@last_insert_id,'$feeoriginalvalue','$feeadjustedValue','$dataArray[feeeditremarks]') ";
    }
    
    $otherFeedetails = '';
   if(!empty($otherFeeDetails)){
         foreach(array_unique($otherFeeDetails) as $key => $value){
             $otherFeedetails .= $value .'+';
         }
     }
    
    $otherFeedetails = rtrim($otherFeedetails, "+");
    $otherFeedetails .=  "=" . $totalOtherFees;
    
    $result = dbInsert($sql);
     
            
      if ($dataArray['feemodeid'] == 304) {
          header("Location: feeReciept.php?pop-up=y&studentid=$dataArray[studentid]&totalFee=$totalFeePaid&ofd=$otherFeedetails&recieptid=$recieptid&" . http_build_query($renderArray) . "&chequenumber=" . $_POST['chequenumber'] . "&bankname=" . $_POST['bankname']);
          exit;
      } else {
          header("Location: feeReciept.php?pop-up=y&studentid=$dataArray[studentid]&ofd=$otherFeedetails&totalFee=$totalFeePaid&recieptid=$recieptid&" . http_build_query($renderArray));
          exit;
      }
  }

  function processFeeDueIndex() {
      $Fields = getFormFields("processfeedue"); //echoThis($Fields); die;
      if (processFormData($Fields)) {
          foreach ($Fields as $key => $value) {
              if (!isset($_POST[$key])) {
                  $_POST[$key] = null;
              }
              ${$key} = cleanVar($_POST[$key]);
          }
      }
  }

  function processCollectedFeeIndex() {
      $Fields = getFormFields("processfeedue"); //echoThis($Fields); die;
      if (processFormData($Fields)) {
          foreach ($Fields as $key => $value) {
              if (!isset($_POST[$key])) {
                  $_POST[$key] = null;
              }
              ${$key} = cleanVar($_POST[$key]);
          }
      }
  }

  function processAddVehicle() {
      $instsessassocid = $_SESSION['instsessassocid'];
      $Fields = getFormFields("processaddvehicle");
      if (processFormData($Fields)) {
          foreach ($Fields as $key => $value) {
              if (!isset($_POST[$key])) {
                  $_POST[$key] = null;
              }
              ${$key} = cleanVar($_POST[$key]);
          }

          if (isset($_FILES) && !empty($_FILES['vehicleimage']['tmp_name'])) {
              $imgName = $_POST['type'] . '-' . $_POST['vehicleno'] . 'jpg';
              uploadImage($_FILES, VEHICLE_IMG_PATH, $imgName, 'image');
          }
          if (isset($regvalidfrom) && !empty($regvalidfrom)) {
              $regvalidfrom = $regvalidfrom;
          }

          if (isset($regvalidto) && !empty($regvalidto)) {
              $regvalidto = $regvalidto;
          }
          if (isset($roadtaxdate) && !empty($roadtaxdate)) {
              $roadtaxdate = $roadtaxdate;
          }
          if (isset($insurancefromdate) && !empty($insurancefromdate)) {
              $insurancefromdate = $insurancefromdate;
          }
          if (isset($insurancetodate) && !empty($insurancetodate)) {
              $insurancetodate = $insurancetodate;
          }
          if ($_REQUEST['mode'] == "edit") {
              $imgUpdated = "";
              if (!empty($imgName)) {
                  $imgUpdated = "`vehicleimage`='$imgName',";
              }
              $sql = "UPDATE `tblvehicle` SET `vehicletitle`='$vechile_name',`vehicletype`='$type',
                    `fueltype`='$fueltype',`modelno`='$modelno',`makeyear`='$makeyear',
                    `platenumber`='$vehicleno',
                    `chasisnumber`='$chasisno',`enginenumber`='$engineno',
                    `registrationno`= '$registrationno',
                    `rcvalidfrom`= '$regvalidfrom',`rcvalidto`= '$regvalidto',
                    `roadtaxpaidupto`='$roadtaxdate',`insurancepolicyno`='$insurancepolicyno',
                    `insurancevalidfrom`='$insurancetodate',`insurancevalidto`='$insurancetodate',
                    `pcrvalidupto`='$roadtaxdate',`seatcapacity`='$seatingcapacity',
                    $imgUpdated `dateupdated`=CURRENT_DATE 
                    WHERE `vehicleid`  = $_REQUEST[vid] ";

              $result = dbUpdate($sql);
              header("Location: addVehicle.php?s=34");
              exit();
          } else {
              $sqlVehicle = " INSERT INTO tblvehicle SET 
                            instsessassocid = $instsessassocid,
                            vehicletitle='$vechile_name',
                            vehicletype='$type',
                            fueltype='$fueltype',
                            modelno='" . $modelno . "',
                            makeyear='" . $makeyear . "',
                            platenumber='" . $vehicleno . "',
                            chasisnumber='" . $chasisno . "',
                            enginenumber='" . $engineno . "',
                            registrationno='" . $registrationno . "',
                            rcvalidfrom='" . $regvalidfrom . "',
                            rcvalidto='" . $regvalidto . "',
                            roadtaxpaidupto='" . $roadtaxdate . "',
                            insurancepolicyno='$insurancepolicyno',
                            insurancevalidfrom='" . $insurancefromdate . "',
                            insurancevalidto='" . $insurancetodate . "',
                            pcrvalidupto='" . $roadtaxdate . "',
                            seatcapacity='" . $seatingcapacity . "',";
              if (!empty($imgName)) {
                  $sqlVehicle .= " vehicleimage='" . $imgName . "',";
              } else {
                  $sqlVehicle .= "  status='$status',
                                        datecreated=CURDATE()";
              }

              $result = dbInsert($sqlVehicle) or die(mysqli_error());
              if ($result) {
                  header("Location: addVehicle.php?s=33");
                  exit();
              }
          }
      }
  }

  function processAddDriver() {
      $Fields = getFormFields("processadddriver");
      if (processFormData($Fields)) {
          foreach ($Fields as $key => $value) {
              if (!isset($_POST[$key])) {
                  $_POST[$key] = null;
              }
              ${$key} = cleanVar($_POST[$key]);
          }

          if (isset($_POST['mode']) && $_POST['mode'] == 'add') {
              $imgUpdated = '';

              $sqlInsrt = "   INSERT INTO tbldrivers SET 
                            driverfirstname='$firstname',
                            drivermiddlename = '$middlename',
                            driverlastname = '$lastname',
                            fathername ='$fathername',
                            dob = '$birthdate',
                            qualification = '$qualification',
                            address ='$address',
                            city ='$city',
                            mobile ='$mobile',
                            licenseno ='$licenseno',
                            licensevalidfrom = '$licensevalidfrom',
                            licensevalidto ='$licensevalidto',
                             status = 1,
                            datecreated = CURRENT_DATE";

              $result = dbInsert($sqlInsrt);
              if (!in_array(0, $result)) {
                  header("Location: addDriver.php?s=36");
              }

              exit();
          } else {
              $imgUpdated = "";

              $sql = "UPDATE `tbldrivers` SET `driverfirstname`='$firstname',`drivermiddlename`='$middlename',
                    `driverlastname`='$lastname',`fathername`='$fathername',
                    `dob`= '$_POST[birthdate]',`qualification`='$qualification',
                    `address`='$address',`city`='$city',
                    `mobile`='$mobile',`licenseno`='$licenseno',
                    `licensevalidfrom`='$licensevalidfrom',`licensevalidto`='$licensevalidto',
                     `dateupdated`=CURRENT_DATE WHERE `driverid` = $_POST[driverid]";

              $result = dbUpdate($sql);
              header("Location: addDriver.php?s=37");
              exit();
          }
      }
  }

  function processAddRoute() {
      $Fields = getFormFields("processaddroute");

      if (processFormData($Fields)) {
          foreach ($Fields as $key => $value) {
              if (!isset($_POST[$key])) {
                  $_POST[$key] = null;
              }
              ${$key} = cleanVar($_POST[$key]);
          }

          $instsessassocid = $_SESSION['instsessassocid'];
          if (isset($_POST['mode']) && $_POST['mode'] == 'edit') {
              $sql = "UPDATE `tblbusroute` , `tblrouteassoc` SET 
                        `tblbusroute`.routename    = '$routename',
                        `tblbusroute`.busid        = '$busid', 
                        `tblbusroute`.driverid     = '$drivername',
                        `tblbusroute`.startpointid = '$startpoint',
                        `tblbusroute`.endpointid     = '$endpoint',
                        `tblbusroute`.dateupdated  =  CURRENT_TIMESTAMP ,";

              foreach ($pickuppointname as $key => $value) {
                  $sql .= " `tblrouteassoc`.pickuppointid = '$value',";
              }
              $sql .= "`tblrouteassoc`.dateupdated = CURRENT_TIMESTAMP
                    
                WHERE `tblbusroute`.busrouteid = '$_POST[edid]'
                AND  `tblbusroute`.busrouteid = `tblrouteassoc`.routeid
                ";

              $result = dbUpdate($sql);
              if (!in_array(0, $result)) {
                  echo "<script>window.location='addRoute.php?s=40'</script>";
              }
          } else {
              $sqlInsert[] = " INSERT INTO tblbusroute SET instsessassocid='$instsessassocid', routename='$routename', busid='$busid', driverid='$drivername', endpointid='$endpoint', startpointid='$startpoint', status = 1, datecreated=CURRENT_TIMESTAMP";
              $sqlInsert[] = " SET @vrouteid=LAST_INSERT_ID();";
              $sqlPickupInsert = "INSERT INTO tblrouteassoc (routeid,pickuppointid,status, datecreated) VALUES ";
              foreach ($pickuppointname as $key) {
                  $sqlPickupInsert.= "(@vrouteid,'$key',1,CURRENT_TIMESTAMP),";
              }
              $sqlInsert[] = rtrim($sqlPickupInsert, ',');

              $result = dbInsert($sqlInsert);
              if (!in_array(0, $result)) {
                  $s = 39;
              }
          }
          header('Location :addRoute.php?s=' . $s);
          exit();
      }
  }

  function processStudentTransportIndex() {
      //echoThis($_POST); die;
      $Fields = getFormFields("processstudenttransport");
      if (processFormData($Fields)) {
          foreach ($Fields as $key => $value) {
              if (!isset($_POST[$key])) {
                  $_POST[$key] = null;
              }
              ${$key} = cleanVar($_POST[$key]);
          }
      }
  }

  function processsendNotification() {
      $instsessassocid = $_SESSION['instsessassocid'];
      $Fields = getFormFields("processnotifications"); //echoThis($Fields); die;
      if (processFormData($Fields)) {
          foreach ($Fields as $key => $value) {
              if (!isset($_POST[$key])) {
                  $_POST[$key] = null;
              }
              ${$key} = cleanVar($_POST[$key]);
          }
          $details = $_POST;
          $delieverystatus = false;
          switch ($details['notificationtype']) {

              case 'SMS':
                  $delieverystatus = sendMessage($details);
                  break;

              case 'Email':
                  $delieverystatus = sendEmail($details);
                  break;
          }

          $sql = " INSERT INTO `tblnotificationslog` (`notificationtype`, `instsessassocid`,`sendernumber`,`senderemail`,
                 `recievernumber`, `recieveremail`,`subjectinfo`, `message`, `delieveryreport`,`datecreated`) 
		   VALUES('$notificationtype','$instsessassocid','$sendernumber','$senderemail','$recievernumber','$recieveremail','$subjectinfo',
                '$message','$delieverystatus',CURRENT_TIMESTAMP) ";


          if (($result = dbInsert($sql)) && (!empty($sendernumber))) {
              header("Location: sendNotification.php?s=21");
          } else {
              header("Location: sendNotification.php?s=22");
          }
      }
  }

  function processIssueTC($insertDetails) {
      $instsessassocid = $_SESSION['instsessassocid'];
      $dateofissue = date('Y-m-d');
      $sql = "SELECT `tcissued` FROM `tblstudtc` WHERE `studentid` = $insertDetails[studentid] ";
      $result = dbSelect($sql);
      $row = mysqli_fetch_assoc($result);
      $recieptcode = GenerateTCRecieptNumber();
      $recieptid = $insertDetails['instituteabbrevation'] . "/ TC /" . $insertDetails['sessionname'] . "/" . $recieptcode;

      if (empty($row['tcissued'])) {
          $duplicate = 0;
          unset($sql);

          $sql = "INSERT INTO `tblstudtc` (`studentid`, `instsessassocid`, `dateofissue`,`amount`,`recieptno`,
                `tcissued`,`duplicate`)VALUES( '$insertDetails[studentid]', '$instsessassocid',
           '$dateofissue', '$insertDetails[tcfeesamount]','$recieptid','1','$duplicate')";
      } else {
          $sql = "INSERT INTO `tblstudtc` (`studentid`, `instsessassocid`, `dateofissue`,`amount`,`recieptno`,
                    `tcissued`,`duplicate`) VALUES('$insertDetails[studentid]', '$instsessassocid','$dateofissue',
                    '$insertDetails[tcfeesamount]','$recieptid','1','1')";
      }

      if ($result = dbInsert($sql)) {
          unset($sql);
          $sql = "UPDATE `tblstudent` SET `status`= '0', `tcissued` = '1' , dateupdated = CURRENT_TIMESTAMP  WHERE `studentid`= $insertDetails[studentid] ";
          $result = dbUpdate($sql);
          $renderArray = http_build_query($_POST);
          echo "<script type='text/javascript'>
                var url= '../fees/feeReciept.php?pop-up=y&studentid=$insertDetails[studentid]&totalFee=$insertDetails[tcfeesamount]&tcfees=$insertDetails[tcfeesamount]&recieptid=$recieptid';
                    var left = (screen.width/2)-(1100/2);
                    var top = (screen.height/2)-(50/2);
                    var top = (screen.height/2)-(50/2);
                    var sw = (screen.width*.60);
                    var sh = (screen.height*.60);
                    window.open(url,'_blank','width='+sw+', height='+sh+', top=' +top+', left='+left);
                    window.location.replace('../../studentservices/issueTC.php?tcfees=$insertDetails[tcfeesamount]&search=&$renderArray');
                    </script>";
      }
  }

  function processStudentTCDetails() {
      $instsessassocid = $_SESSION['instsessassocid'];
      $insertDetails = cleanVar($_POST); //echoThis( $insertDetails); die;
      $dateofissue = date('Y-m-d');
      $sql = "SELECT `noofcopies` FROM `tblstudtc` WHERE `studentid` = $insertDetails[studentid] ";
      $result = dbSelect($sql);
      $row = mysqli_fetch_assoc($result);
      if (!empty($row)) {
          $numberofCopies = $row['noofcopies'] + 1;
          unset($sql);

          $sql = "INSERT INTO `tblstudtc` (`studentid`, `instsessassocid`, `dateofissue`,`noofcopies`, `duplicate`) VALUES('$insertDetails[studentid]', '$instsessassocid','$dateofissue','$numberofCopies','1')";
      } else {
          $sql = "INSERT INTO `tblstudtc` (`studentid`, `instsessassocid`, `dateofissue`,`noofcopies`,
           `duplicate`) VALUES('$insertDetails[studentid]', '$instsessassocid','$dateofissue','$numberofCopies','0')";
      }

      if ($result = dbInsert($sql)) {
          unset($sql);
          $sql = "UPDATE `tblstudentdetails` SET `status`= '0' , dateupdated = CURRENT_TIMESTAMP  WHERE `studentid`= $insertDetails[studentid] ";
          $result = dbUpdate($sql);
          header("Location: issueTCPDF.php?studentid=$insertDetails[studentid]");
          exit;
      }
  }

// This Function is used for associating  Teacher's with subjects of Particular class//
// And Further associates various topics with there respective Subjects .//
  function processSubjectTopicAnalysis() {
      $Fields = getFormFields("processSubjectTopicAnalysis");

      if (processFormData($Fields)) {
          foreach ($Fields as $key => $value) {
              if (!isset($_POST[$key])) {
                  $_POST[$key] = null;
              }
              ${$key} = cleanVar($_POST[$key]);
          }
      }
      $classid = $classid[0];
      $sql = "SELECT `clssubjassocid`  FROM `tblclssubjassoc` WHERE `classid`= $classid AND `subjectid` = $subjectid";
      $result = dbSelect($sql);
      while ($row = mysqli_fetch_assoc($result)) {
          $clsSubjAssocId = $row['clssubjassocid'];
      }
      unset($sql);
      unset($result);

      if (!empty($clsSubjAssocId)) {
          $sql = array("INSERT INTO `tblsubjteacherassoc`(`clssubjassocid`, `employeeid`) VALUES ('$clsSubjAssocId','$employeeid');",
              "SET @last_insert_id = LAST_INSERT_ID();");

          $tmpSql = "INSERT INTO `tblsubtopicassoc`(`subjteacherassocid`, `topicname`, `expectedstartdate`, `expectedenddate`)VALUES ";

          foreach ($topicname as $key => $value) {
              $tmpSql .="(@last_insert_id,'$value','$expectedstartdate[$key]','$expectedenddate[$key]'),";
          }

          $sql[] = rtrim($tmpSql, ',');
          if ($result = dbInsert($sql)) {
              header("Location: subjectTopicAnalysis.php?s=23");
          }
      } else {
          addError(0);
      }
  }

  function processAddpickuppoint() {

      $Fields = getFormFields("processAddpickuppoint");

      if (processFormData($Fields)) {
          foreach ($Fields as $key => $value) {
              if (!isset($_POST[$key])) {
                  $_POST[$key] = null;
              }
              ${$key} = cleanVar($_POST[$key]);
          }


          $instsessid = cleanVar($_SESSION['instsessassocid']);

          if (isset($_GET['mode']) && $_GET['mode'] == 'edit') {
              $sqlUpdate = "  UPDATE tblpickuppoint SET pickuppointname='$pickuppointname' , 
                            suburbs='$suburbid', amount='$amount' , pickuptime = '$pickuptime',droptime='$droptime', dateupdated=CURRENT_TIMESTAMP WHERE pickuppointid='$_POST[edid]'";

              $result = dbUpdate($sqlUpdate) or die('Update Error');

              if (!in_array(0, $result)) {
                  header('Location:addPickUpPoint.php?s=47');
              }
          } else {
              $sqlInsert = " INSERT INTO tblpickuppoint SET pickuppointname='$pickuppointname' , 
                            suburbs='$suburbid', amount='$amount' , pickuptime='$pickuptime', droptime='$droptime' ,status=1, datecreated=CURRENT_TIMESTAMP";
              $result = dbInsert($sqlInsert) or die('Update Error');
              if (!in_array(0, $result)) {
                  header('Location:addPickUpPoint.php?s=46');
              }
          }
      }
  }

  function processbanktransactions() {
      if (isset($_POST['createcsv'])) {
          createCSV();
      } elseif (isset($_FILES['uploadcsv']['name']) && !empty($_FILES['uploadcsv']['name'])) {
          uploadData($_FILES["uploadcsv"]["tmp_name"]);
      }
  }

  function processvehicleDashboard() {
      
  }

  function processMileageEntry() {
     
      $Fields = getFormFields("processMileageEntry");
      if (processFormData($Fields)) {

          foreach ($Fields as $key => $value) {
              if (!isset($_POST[$key])) {
                  $_POST[$key] = null;
              }
              ${$key} = cleanVar($_POST[$key]);
          }
          foreach ($travel_date as $key => $value) {
              if ($travel_date[$key] != '') {
                  $startmeter = $start_meter[$key];
                  $endmeter = $end_meter[$key];
                  $remarks = $remark[$key];
                  $sql[] = "INSERT INTO `tblvehiclemileage`(`travel_date`, `vehicleid`, 
                       `start_meter`, `end_meter`,`Remarks`) VALUES ('$value', '$busid', 
                       '$start_meter[$key]','$end_meter[$key]', '$remark[$key]') ";
              }
          }

          $result = dbInsert($sql);
          if (!in_array(0, $result)) {
              header('Location:mileageEntry.php?s=52');
          }else{
              addError(18, null, '');
          }
      }
  }

  function processFuelEntry() {
      
      $Fields = getFormFields("processFuelEntry");
      if (processFormData($Fields)) {
          foreach ($Fields as $key => $value) {
              if (!isset($_POST[$key])) {
                  $_POST[$key] = null;
              }
              ${$key} = cleanVar($_POST[$key]);
          }
          foreach ($filled_date as $key => $value) {
              if ($filled_date[$key] != '') {
                  
                  $sql[] = "INSERT INTO `tblvehiclefuel`(`vehicleid`, `date_filled`, 
                       `liters`, `amount`,`Remarks`) VALUES ('$busid', '$value', 
                       '$liters[$key]','$fuel_amount[$key]', '$remarks[$key]') ";
              }
          }
          $result = dbInsert($sql);
          if (!in_array(0, $result)) {
              header('Location:mileageEntry.php?s=53');
          }else{
              addError(18, null, '');
          }
      }
  }

  function processFormFields() {
      $processedFormFields = array();
      $Fields = getFormFields(debug_backtrace()[1]['function']);
      if (processFormData($Fields)) {
          foreach ($Fields as $key => $value) {
              if (!isset($_POST[$key])) {
                  $_POST[$key] = null;
              }
              // ${$key} = cleanVar($_POST[$key]);
              $processedFormFields[${$key}] = $_POST[$key];
          }
      }
      return $processedFormFields;
  }
  

  // Function to import fee collection of student from CSV file
  // made by : Sanjay Kumar

function processImportstudentfee() {
   
    /*
     * All the data is been imported into a temporary table 
     * From the excel file provided.
     * Getting data from the temporary table 
     * And importing it with required manipulation
     * into our original table respectively
     */
    $instsessassocid = $_SESSION['instsessassocid'];
    $sql = "SELECT `scholarnumber`, `studentname`,
            GROUP_CONCAT( `installment`) AS installment , GROUP_CONCAT( `amount`) AS amount ,
            GROUP_CONCAT( `Late_Fees`) AS latefees,
            GROUP_CONCAT(`Conveyance`) As Conveyance, 
            GROUP_CONCAT(`Penalty`) as penalty , 
            GROUP_CONCAT(`TC`) as tcfees, 
            GROUP_CONCAT(`Other_Charges`) as othercharges, 
            GROUP_CONCAT(`Bounce_Cheque`) as chequeBounce, 
            GROUP_CONCAT(`feeruleid`) as feeruleid, 
           `recieptid`, `feemodeid`, `dateofcollection`, `transferfee`

            FROM `Fee_Data` 
            WHERE 1
            GROUP By `recieptid` ORDER BY `studentname` ";
    
    $result = dbSelect($sql);
    if(mysqli_num_rows($result) > 0){
        
        while($row = mysqli_fetch_assoc($result)){
            $studentDetails = getStdid($row['scholarnumber']);
            $stdid = $studentDetails['studentid'];
            
      
              
            $clssessec = $studentDetails['clsecassocid'];
            $classdetails = getClassid($clssessec);
            $classid = $classdetails['classid'];
            $classname = $classdetails['classname'];
            $recieptId = $row['recieptid'];
            $dataCreated = date('Y-m-d', strtotime($row['dateofcollection']));
            $remarks = "FEE COLLECTED ON  " . $dataCreated;
            $feemodeid = $row['feemodeid'];
            $feestatus = 1;
            $feeruleid = 0 ;
            $transferFee = $row['transferfee'];
            if ($feemodeid == '304') {
                $feestatus = '0';
            }
           
            $installements = $row['installment'];
            $installemntAmount = $row['amount'];
            if(strpos($row['installment'], ",")){
                $installements = explode(",", $row['installment']);
                $installemntAmount = explode(",", $row['amount']);
                $lateFee = explode(",", $row['latefees']);;
                $Conveyance = explode(",", $row['Conveyance']);;
                $TC = explode(",", $row['tcfees']);
                $feeruleidArray = explode(",", $row['feeruleid']);
                
                
            }
           
            $InstSql[] = "INSERT INTO `tblfeecollection`(`instsessassocid`, `studentid`, 
                    `clsecassocid`, `receiptid`, `remarks`,`datecreated`)
                    VALUES ('$instsessassocid', '$stdid', '$clssessec', '$recieptId', '$remarks','$dataCreated' ); ";
            
            $InstSql[] = "SET @last_insert_id_1 = LAST_INSERT_ID();";
             
           
           
            if(is_array($installements)){
                 foreach($installements as $key => $value){
                    $amount =  $installemntAmount[$key];
                    $lateFeesAmount = $lateFee[$key];
                    $ConveyanceAmount = $Conveyance[$key];
                    $TCAmount = $TC[$key];
                    $feeruleid = $feeruleidArray[$key];
                    $feeAmount = $amount;
                    
                    if ($TCAmount != 0) {
                        $feeAmount = $TCAmount;
                    }
                    
                    $InstSql[] = " INSERT INTO `tblfeecollectiondetail`( `feecollectionid`, `collectiontype`,
                     `feeinstallmentamount`, `feemodeid`, `feestatus`, `refundstatus`) 
                      VALUES(@last_insert_id_1 , '316' , '$feeAmount' , '$feemodeid' , '$feestatus', '0' ); ";
                    
                    $InstSql[] = "SET @last_insert_id_2 = LAST_INSERT_ID();";
                    
                    $duedate = getDuedate($classid, $value);
                    
                    
                    if ($TCAmount == 0) {
                        $InstSql[] = "INSERT INTO `tblfeeinstallmentdates`(`feecollectiondetailid`, `feeinstallment`, 
                        `status`, `datecreated`) VALUES (@last_insert_id_2, '$duedate', '1','$dataCreated' );";
                    }
                    
                    
                    
                if ($lateFeesAmount != 0) { 
                    $InstSql[] = " INSERT INTO `tblfeecollectiondetail`( `feecollectionid`, `collectiontype`,
                        `feeinstallmentamount`, `feemodeid`, `feestatus`, `refundstatus`) 
                         VALUES(@last_insert_id_1 , '1' , '$lateFeesAmount' , '$feemodeid' , '$feestatus', '0' ) ; ";
                    
                    $InstSql[] = "SET @last_insert_id_3 = LAST_INSERT_ID();";
            
                    $InstSql[] = "INSERT INTO `tblfeepenaltydetails`(`feecollectiondetailid`, `feeinstallmentid`,
                        `status`, `datecreated`) VALUES (@last_insert_id_3, '$duedate', '1','$dataCreated') ;";
                }
                
                if ($ConveyanceAmount != 0) {
                    $InstSql[] = " INSERT INTO `tblfeecollectiondetail`( `feecollectionid`, `collectiontype`,
                            `feeinstallmentamount`, `feemodeid`, `feestatus`, `refundstatus`) 
                            VALUES(@last_insert_id_1 , '2' , '$ConveyanceAmount' , '$feemodeid' ,'$feestatus', '0' ); ";

                    $InstSql[] = "SET @last_insert_id_3 = LAST_INSERT_ID();";
            
                    $InstSql[] = "INSERT INTO `tblfeepenaltydetails`(`feecollectiondetailid`, `feeinstallmentid`,
                        `status`, `datecreated`) VALUES (@last_insert_id_3, '$duedate', '1','$dataCreated') ;";
                    
                    
                }
                
                if ($TCAmount != 0) {
                        $InstSql[] = " INSERT INTO `tblstudtc`( `instsessassocid`, `studentid`, `feecollectiondetailid`,
                            `dateofissue`,  `recieptno`, `tcissued`, `duplicate`) 
                            VALUES('$instsessassocid', '$stdid',@last_insert_id_2,'$dataCreated' , 
                            '$recieptId', '1', '0' ); ";

                        $InstSql[] = " UPDATE `tblstudent` SET `status`= 0  WHERE `studentid` = '$stdid' ; ";

                }
                
                if($feemodeid == '304'){
                    $InstSql[] = "INSERT INTO `tblfeecheque`(`feecollectionid`,`chequedepositdate`, `remarks`, `chequestatus`, 
                         `datecreated`) VALUES (@last_insert_id_1, '$dataCreated','CHEQUE ACCEPTED ON $dataCreated', 
                         '1' ,'$dataCreated'); ";
                }
                
                if($feeruleid != 0){
                    $InstSql[] =  "INSERT INTO `tblstudfeeruleassoc`(`studentid`, `feeruleid`, `associationstatus`) 
                                    VALUES ('$stdid', '$feeruleid', '1') ";
                    
                    $InstSql[] =  "SET @last_insert_id_5 = LAST_INSERT_ID();";
                    
                    $InstSql[] =  "INSERT INTO `tblstudfeeruleinstasssoc`( `studfeeruleassocid`, `installment`,`status`) 
                                    VALUES (@last_insert_id_5, '$duedate', '1')";
                }
                    
              }
                 if($transferFee != 0){
                     $InstSql[] = "INSERT INTO `tblfeecollectiondetail`( `feecollectionid`, `collectiontype`,
                                   `feeinstallmentamount`, `feemodeid`, `feestatus`, `refundstatus`) 
                                   
                                    VALUES(@last_insert_id_1 ,'5','$transferFee','$feemodeid','$feestatus','0' );";
                 }
            }
            
            else{
                $amount = $row['amount'];
                $lateFeesAmount = $row['latefees'];
                $ConveyanceAmount = $row['Conveyance'];
                $TCAmount = $row['tcfees'];
                $feeAmount = $amount;
                $feeruleid = $row['feeruleid'];
                $collectionType = 316;
                if ($TCAmount != 0) {
                    $feeAmount = $TCAmount;
                    $collectionType = 4;
                }
                
                $InstSql[] = " INSERT INTO `tblfeecollectiondetail`( `feecollectionid`, `collectiontype`,
                     `feeinstallmentamount`, `feemodeid`, `feestatus`, `refundstatus`) 
                    VALUES(@last_insert_id_1 , '$collectionType' , '$feeAmount' , '$feemodeid' , '$feestatus', '0' ) ;"; 
                
                 $InstSql[] = "SET @last_insert_id_2 = LAST_INSERT_ID();";
            
                 $duedate = getDuedate($classid, $row['installment']);
                 
                 if ($TCAmount == 0) {
                     $InstSql[] = "INSERT INTO `tblfeeinstallmentdates`(`feecollectiondetailid`, `feeinstallment`, 
                        `status`, `datecreated`) VALUES (@last_insert_id_2, '$duedate', '1','$dataCreated' );";
                 }
                 
                 if ($lateFeesAmount != 0) { 
                    $InstSql[] = " INSERT INTO `tblfeecollectiondetail`( `feecollectionid`, `collectiontype`,
                        `feeinstallmentamount`, `feemodeid`, `feestatus`, `refundstatus`) 
                         VALUES(@last_insert_id_1 , '1' , '$lateFeesAmount' , '$feemodeid' , '$feestatus', '0' ) ; ";
                    
                     $InstSql[] = "SET @last_insert_id_3 = LAST_INSERT_ID();";
            
                    $InstSql[] = "INSERT INTO `tblfeepenaltydetails`(`feecollectiondetailid`, `feeinstallmentid`,
                        `status`, `datecreated`) VALUES (@last_insert_id_3, '$duedate', '1','$dataCreated') ;";
                }
                 
                 if ($ConveyanceAmount != 0) {
                    $InstSql[] = " INSERT INTO `tblfeecollectiondetail`( `feecollectionid`, `collectiontype`,
                            `feeinstallmentamount`, `feemodeid`, `feestatus`, `refundstatus`) 
                            VALUES(@last_insert_id_1 , '2' , '$ConveyanceAmount' , '$feemodeid' ,'$feestatus', '0' ); ";
                    
                    $InstSql[] = "SET @last_insert_id_3 = LAST_INSERT_ID();";
            
                    $InstSql[] = "INSERT INTO `tblfeepenaltydetails`(`feecollectiondetailid`, `feeinstallmentid`,
                        `status`, `datecreated`) VALUES (@last_insert_id_3, '$duedate', '1','$dataCreated') ;";
                
                }
                
                if ($TCAmount != 0) {
                    
                    $InstSql[] = " INSERT INTO `tblstudtc`( `instsessassocid`, `studentid`,`feecollectiondetailid`,
                                    `dateofissue`,`recieptno`, `tcissued`, `duplicate`) 
                                    
                                    VALUES('$instsessassocid', '$stdid', @last_insert_id_2 , '$dataCreated' ,
                                    '$recieptId', '1', '0' ); ";

                    $InstSql[] = " UPDATE `tblstudent` SET `status`= 0  WHERE `studentid` = '$stdid' ; ";

                }
                
                if($feemodeid == '304'){
                    $InstSql[] = "INSERT INTO `tblfeecheque`(`feecollectionid`,`chequedepositdate`, `remarks`, `chequestatus`, 
                                    `datecreated`) VALUES (@last_insert_id_1, '$dataCreated',
                                    'CHEQUE ACCEPTED ON $dataCreated', '1' ,'$dataCreated'); ";
                }
                
                if($feeruleid != 0){
                    $InstSql[] =  "INSERT INTO `tblstudfeeruleassoc`(`studentid`, `feeruleid`, `associationstatus`) 
                                    VALUES ('$stdid', '$feeruleid', '1') ";
                    
                    $InstSql[] =  "SET @last_insert_id_5 = LAST_INSERT_ID();";
                    
                    $InstSql[] =  "INSERT INTO `tblstudfeeruleinstasssoc`( `studfeeruleassocid`, `installment`,`status`) 
                                    VALUES (@last_insert_id_5, '$duedate', '1')";
                }
                
                 if($transferFee != 0){
                     $InstSql[] = "INSERT INTO `tblfeecollectiondetail`( `feecollectionid`, `collectiontype`,
                                   `feeinstallmentamount`, `feemodeid`, `feestatus`, `refundstatus`) 
                                   
                                    VALUES(@last_insert_id_1 ,'5','$transferFee','$feemodeid','$feestatus','0' );";
                 }
                 
            }
       
        
       /* else{
             $scholarnumber = $row['scholarnumber'];
             $msg = "Scholar number ". $scholarnumber . " not found........\n\n";
             
             $fileHandler = fopen("/opt/lampp/htdocs/360/files/admin/SN/studentlist.txt", "a+");
             fwrite($fileHandler, $msg);
        }*/
         // while loop end here
      }
       //  fclose($fileHandler); 
        
    }
   // echoThis($InstSql); die;
    $result = dbInsert($InstSql);
}

function processStudentStatus(){
}

