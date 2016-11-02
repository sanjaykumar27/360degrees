<?php
  /*
   * 360 - School Empowerment System.
   * Developer: Prateek Mathur (pmathur@ebizneeds.com) | www.ebizneeds.com.au 
   * Page details here: Page to import student from csv file
   * Updates here: 
   */


  require_once "../../config/config.php";
  require_once DIR_FUNCTIONS;
  require_once VIEW_HEADER;
?>

<div class="container">
    <form action="" method="post" id="imform" enctype="multipart/form-data">
        <div class="col-lg-4">
            <label for="csvfilename"> C.S.V File </label>
            <input class="form-control" type="file" name="csvfilename" id="csvfilename">
        </div>

        <div class="col-lg-4">
            <label for="tblName"> Import Data </label>
            <select class="form-control" name="tblName" id="tblName">
                <option value="">-Select One-</option>
                <option value="Student Details">Import Student Details</option>
                <option value="studentFees">Import Student Fees Details</option>


            </select>
        </div>

        <span class="clearfix"><p>&nbsp;</p></span> 
        <span class="clearfix"><p>&nbsp;</p></span> 

        <div class="controls" align="center"> 
            <input id="clearDiv" type="reset"  value="Cancel" class="btn">
            <input type="submit" id="save"  name="save" value="Submit" class="btn btn-success">
        </div>

    </form>
</div>


<?php
  /* Sql1 is used to get all student data whose siblings are not found
   * we can individually run both sql to enter student
   * with & without siblings
   * as in siblings case parent details needs to be entered only once in db
   * ************** */

 
    function processImportStudent() {

    $intsessassocid = $_SESSION['instsessassocid'];
    $sql1 = "SELECT * FROM `student_data`
   *               WHERE `mobile` NOT IN (SELECT `mobile` FROM `student_data` 
   *                      GROUP BY `mobile` HAVING COUNT(*) > 1 AND `mobile` != '-' ))";


    $sql2 = "SELECT GROUP_CONCAT(`tblid`) as IDs,
    GROUP_CONCAT(`scholarnumber`) as scholarnumber,
    GROUP_CONCAT(`studentname`) as studentname,
    `fathername`, `mothername`,
    GROUP_CONCAT(`class`) as class,
    GROUP_CONCAT(`section`) as section,
    GROUP_CONCAT(`dob`) as dob,
    GROUP_CONCAT(`doj`) as doj,
    GROUP_CONCAT(`studenttype`) as studenttype,
    `category`,`address`,`mobile`, `landlineno`,
    GROUP_CONCAT(`height`) as height,
    GROUP_CONCAT(`weight`) as weight,
    GROUP_CONCAT(`house`) as house,
    GROUP_CONCAT(`bloodgroup`) as bloodgroup,
    GROUP_CONCAT(`dentalhygiene`) as dentalhygiene,
    GROUP_CONCAT(`visionleft`) as visionleft,
    GROUP_CONCAT(`visionright`) as visionright,
    GROUP_CONCAT(`gender`) as gender
    FROM `student_data`
    GROUP BY `mobile`
    HAVING COUNT(*) > 1
    AND `mobile` != '-'
    ";

    $result = dbSelect($sql2);
    if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {

    $scholarnumberArray = explode(",", $row['scholarnumber']);
    $studentnameArray = explode(",", $row['studentname']);
    $fatherName = $row['fathername'];
    $motherName = $row['mothername'];
    $classArray = explode(",", $row['class']);
    $sectionArray = explode(",", $row['section']);
    $dobArray = explode(",", $row['dob']);
    $genderArray = explode(",", $row['gender']);
    $religion = '';
    $category = getstudentmasterData($row['category']);
    $studenttypeArray = explode(",", $row['studenttype']);

    $currentaddress1 = cleanVar($row['address']);
    $currentaddress2 = 'Jodhpur';
    $currentsuburbid = '';
    $cityid = 1;
    $currentzipcode = 342001;
    $stateid = 1;
    $countryid = 1;
    $permaaddress1 = cleanVar($row['address']);
    $permaaddress2 = NULL;
    $permasuburbid = '';
    $permastateid = 1;
    $permazipcode = 342001;
    $mobile = $row['mobile'];
    $phone1 = $row['landlineno'];
    $phone2 = NULL;
    $fax1 = NULL;
    $fax2 = NULL;
    $percentgrade = '';
    $previousclass = '';
    $previousresult = '';
    $previousschool = '';
    $passportnum = '';
    $dateofjoiningArray = explode(",", $row['doj']);
    $status = '1';
    $profilepicture = NULL;
    $conveyancerequired = '';
    $admissionreferencedby = '';
    $otheradditionalinformation = '';

    $fatherFirstName = cleanVar($row['fathername']);
    $fatherMiddleName = '';
    $fatherLastName = '';

    $motherFirstName = cleanVar($row['mothername']);
    $motherMiddleName = '';
    $motherLastName = '';

    if (strpos($row['fathername'], ' ') > 0) {
    $name = explode(" ", $row['fathername']);
    $fatherFirstName = cleanVar($name[0]);
    $fatherLastName = $name[1];
    }

    if (strpos($row['mothername'], ' ') > 0) {
    $name = explode(" ", $row['mothername']);
    $motherFirstName = cleanVar($name[0]);
    $motherLastName = $name[1];
    }

    $studentHeightArray = explode(",", $row['height']);
    $studentWeightArray = explode(",", $row['weight']);
    $housenameArray = explode(",", $row['house']);
    $studentBloodGroupArray = explode(",", $row['bloodgroup']);
    $visionLeftArray = explode(",", $row['visionleft']);
    $visionRightArray = explode(",", $row['visionright']);
    $medicalhistoryArray = explode(",", $row['dentalhygiene']);

    $identificationmark1 = '';
    $identificationmark2 = '';
    $doctorremark = '';
    $allergyinfo = '';
    $frequentillness = '';
    $regulardocname = '';
    $regulardocaddress = '';
    $regulardocmobile = '';
    $regulardocphone = '';
    $regulardocemail = '';
    $regularhospname = '';
    $regularhospphone = '';
    $regularhospemail = '';
    $regularhospaddress = '';
    $makeEmail = $fatherFirstName . "_" . $mobile . '@temp.com';
    $pass = '$2y$10$gFscDWwWKR8Iven6RuE3Juha/yV9bjwEt3aSbxYuAqKBsHhQAdUNe';


    $import[] = ("INSERT INTO `tbluser`(`username`, `password`, `roleid`, `status`, `instsessassocid`)
    VALUES('$makeEmail','$pass','3','1', '$intsessassocid');");

    $import[] = ("SET @last_insert_id_1 = LAST_INSERT_ID();");


    $import[] = ("INSERT INTO `tblparent`(`parentfirstname`, `parentmiddlename`, `parentlastname`,
    `gender`, `religion`, `category`,`status`, `deleted`)
    VALUES ('$fatherFirstName','$fatherMiddleName','$fatherLastName',
    '259','$religion', '$category', '1', '0'); ");

    $import[] = ("SET @last_insert_id_3 = LAST_INSERT_ID();");

    $import[] = ("INSERT INTO `tblparentcontact`(`parentid`, `relationid`,  `currentaddress1`,
    `currentaddress2`, `currentsuburbid`, `currentcityid`, `currentzipcode`, `currentstateid`,
    `currentcountryid`, `permaaddress1`, `permaaddress2`, `permasuburbid`, `permacityid`,
    `permastateid`, `permacountryid`, `permazipcode`, `mobile1`,`email1`,`status`)

    VALUES (@last_insert_id_3, '214', '$currentaddress1','$currentaddress2',
    '$currentsuburbid', '$cityid', '$currentzipcode','$stateid', '$countryid',
    '$permaaddress1','$permaaddress2', '$permasuburbid', '$cityid','$stateid','$countryid',
    '$permazipcode',  '$mobile', '$makeEmail', '1' );");

    $import[] = ("INSERT INTO `tblparent`(`parentfirstname`, `parentmiddlename`, `parentlastname`,
    `gender`, `religion`, `category`,`status`, `deleted`)

    VALUES ('$motherFirstName','$motherMiddleName','$motherLastName', '260',
    '$religion', '$category', '1', '0');");

    $import[] = ("SET @last_insert_id_4 = LAST_INSERT_ID();");

    $import[] = ("INSERT INTO `tblparentcontact`(`parentid`, `relationid`,  `currentaddress1`,
    `currentaddress2`, `currentsuburbid`, `currentcityid`, `currentzipcode`, `currentstateid`,
    `currentcountryid`, `permaaddress1`, `permaaddress2`, `permasuburbid`, `permacityid`,
    `permastateid`, `permacountryid`, `permazipcode`, `mobile1`, `email1`, `status`)

    VALUES (@last_insert_id_4, '215', '$currentaddress1','$currentaddress2',
    '$currentsuburbid', '$cityid', '$currentzipcode','$stateid', '$countryid',
    '$permaaddress1','$permaaddress2', '$permasuburbid', '$cityid','$stateid',
    '$countryid','$permazipcode',  '$mobile', '$makeEmail', '1'  );");

    foreach ($scholarnumberArray as $key => $value) {
    $studentDetails = getStdid($value);
    $stdid = $studentDetails['studentid'];
    $studentName = $studentnameArray[$key];
    $classdetails = getclassDetails($classArray[$key], $sectionArray[$key]);
    $classid = $classdetails['classid'];
    $sectionid = $classdetails['sectionid'];
    $dob = date('Y-m-d', strtotime($dobArray[$key]));


    $FirstName = $studentName;
    $MiddleName = '';
    $LastName = '';

    if (strpos($studentName, ' ') > 0) {
    $name = explode(" ", $studentName);
    $FirstName = cleanVar($name[0]);
    $LastName = $name[1];
    }
    $clsSecAssocid = $classdetails['clsecassocid'];
    $gender = $genderArray[$key];
    $religion = '';
    $studenttype = $studenttypeArray[$key];
    $email = $makeEmail;
    $doj = date('Y-m-d', strtotime($dateofjoiningArray[$key]));
    $studentHeight = $studentWeightArray[$key];
    $studentWeight = $studentWeightArray[$key];
    $housename = $housenameArray[$key];
    $studentBloodGroup = $studentBloodGroupArray[$key];
    $visionLeft = $visionLeftArray[$key];
    $visionRight = $visionRightArray[$key];
    $medicalhistory = $medicalhistoryArray[$key];




    $import[] = ("INSERT INTO `tblstudent`(`scholarnumber`, `instsessassocid`,`firstname`,
    `middlename`,`lastname`, `status`, `deleted`, `datecreated`)

    VALUES('$value','$intsessassocid','$FirstName',
    '$MiddleName','$LastName', '1', '0', '$doj' );");


    $import[] = ("SET @last_insert_id_2 = LAST_INSERT_ID();");

    $import[] = ("INSERT INTO `tblstudentdetails`( `studentid`, `dob`, `gender`, `religion`, `category`,
    `percentgrade`, `previousclass`, `previousresult`, `previousschool`, `passportnum`,
    `dateofjoining`, `housename`, `profilepicture`, `conveyancerequired`,
    `admissionreferencedby`, `otheradditionalinformation`, `status`, `datecreated`)

    VALUES(@last_insert_id_2, '$dob','$gender', '$religion',  '$category',
    '$percentgrade', '$previousclass',  '$previousresult', '$previousschool',
    '$passportnum', '$doj', '$housename',  '$profilepicture','$conveyancerequired',
    '$admissionreferencedby','$otheradditionalinformation','1', '$doj');");

    $import[] = ("INSERT INTO `tblstudentcontact`( `studentid`, `currentaddress1`, `currentaddress2`,
    `currentsuburbid`,`currentcityid`, `currentzipcode`,`currentstateid`, `currentcountryid`,
    `permaaddress1`, `permaaddress2`, `permasuburbid`, `permacityid`,
    `permastateid`, `permacountryid`, `permazipcode`, `mobile`, `phone1`,
    `phone2`, `fax1`, `fax2`, `email`, `emeregencycontactname`,
    `emeregencyphoneno`, `emeregencycontactaddress`, `status`, `datecreated`)

    VALUES (@last_insert_id_2,  '$currentaddress1',  '$currentaddress2',
    '$currentsuburbid', '$cityid', '$currentzipcode','$stateid', '$countryid',
    '$permaaddress1','$permaaddress2', '$permasuburbid','$cityid','$stateid',
    '$countryid','$permazipcode', '$mobile','$phone1','$phone2','$fax1','$fax2',
    '$makeEmail','$fatherFirstName', '$mobile', '$currentaddress1','1', '$doj');");

    $import[] = ("INSERT INTO `tblstudentacademichistory`( `studentid`, `clsecassocid`, `academicstatus`,
    `studenttype`, `status`, `datecreated`)
    VALUES (@last_insert_id_2 , '$clsSecAssocid', '1', '$studenttype', '1', '$doj' );");

    $import[] = ("INSERT INTO `tblmedicalinfo`(`studentid`, `medicalhistory`, `allergyinfo`,
    `frequentillness`, `regulardocname`, `regulardocaddress`, `regulardocmobile`,
    `regulardocphone`, `regulardocemail`, `regularhospname`, `regularhospphone`,
    `regularhospemail`, `regularhospaddress`, `height`, `weight`, `bloodgroup`,
    `lefteyesight`, `righteyesight`, `identificationmark1`, `identificationmark2`,
    `doctorremark`, `datecreated`)

    VALUES (@last_insert_id_2, '$medicalhistory' , '$allergyinfo' ,'$frequentillness' ,
    '$regulardocname','$regulardocaddress', '$regulardocmobile','$regulardocphone',
    '$regulardocemail', '$regularhospname', '$regularhospphone', '$regularhospemail',
    '$regularhospaddress', '$studentHeight', '$studentWeight','$studentBloodGroup',
    '$visionLeft', '$visionRight','$identificationmark1','$identificationmark2',
    '$doctorremark', '$doj' );");


    $import[] = ("INSERT INTO `tbluserdetailsassoc`(`userid`, `isstudent`,`studentid`,
    `isemployee`,`employeeid`)  VALUES(@last_insert_id_1,'1',@last_insert_id_2,'0','NULL');
    ");

    $import[] = ("INSERT INTO `tbluserparentassociation`(`userid`, `studentid`, `parentid`,
    `status`, `datecreated`) VALUES (@last_insert_id_1,@last_insert_id_2,
    @last_insert_id_3,'1', '$doj');");

    $import[] = ("INSERT INTO `tbluserparentassociation`(`userid`, `studentid`, `parentid`,
    `status`, `datecreated`) VALUES (@last_insert_id_1,@last_insert_id_2,
    @last_insert_id_4,'1', '$doj');");
    }
    }
    }

    // echoThis($import); die;
    $result = dbInsert($import);
    //unset($result);
    echoThis("Total number of rows were: " . $cnt);
    }
 

  // ================================================ //
  /* Sql1 is used to get all student data whose siblings are not found
   * we can individually run both sql to enter student
   * with & without siblings
   * as in siblings case parent details needs to be entered only once in db
   */

/*
  function processImportStudent() {

      $intsessassocid = $_SESSION['instsessassocid'];
      $pass = '$2y$10$gFscDWwWKR8Iven6RuE3Juha/yV9bjwEt3aSbxYuAqKBsHhQAdUNe';
      $sql = "SELECT * FROM `student_data`
              WHERE `mobile` NOT IN (SELECT `mobile` FROM `student_data`
              GROUP BY `mobile` HAVING COUNT(*) > 1 AND 
             `mobile` != '-' )";
      $result = dbSelect($sql);
      if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
              $csvScholarNumber = $row['scholarnumber'];
              $csvFristName = cleanVar($row['studentname']);
              $csvMiddleName = '';
              $csvLastName = '';
              $csvFatherFirstName = cleanVar($row['fathername']);
              $csvFatherMiddleName = '';
              $csvFatherLastName = '';
              $csvMotherFirstName = cleanVar($row['mothername']);
              $csvMotherMiddleName = '';
              $csvMotherLastName = '';
              $makeEmail = $csvFatherFirstName . "_" . $row['mobile'] . "@temp.com";
              if (strpos($row['studentname'], ' ') > 0) {
                  $name = explode(" ", $row['studentname']);
                  $csvFristName = cleanVar($name[0]);
                  $csvLastName = $name[1];
              }
              if (strpos($row['fathername'], ' ') > 0) {
                  $name = explode(" ", $row['fathername']);
                  $csvFatherFirstName = cleanVar($name[0]);
                  $csvFatherLastName = $name[1];
                  $makeEmail = $csvFatherFirstName . "_" . $row['mobile'] . "@temp.com";
              }
              if (strpos($row['mothername'], ' ') > 0) {
                  $name = explode(" ", $row['mothername']);
                  $csvMotherFirstName = cleanVar($name[0]);
                  $csvMotherLastName = $name[1];
              }
              $classDetails = getclassDetails($row['class'], $row['section']);

              $csvStudenttype = $row['studenttype'];
              $csvClassid = $classDetails['classid'];
              $csvSectionid = $classDetails['sectionid'];
              $csvClsSecAssocid = $classDetails['clsecassocid'];
              $csvGender = $row['gender'];
              $csvReligion = '';
              $csvCategory = $row['category'];
              $csvCurrentaddress1 = cleanVar($row['address']);
              $csvCurrentaddress2 = 'Jodhpur';
              $csvCurrentsuburbid = '';
              $csvCityid = 1;
              $csvCurrentzipcode = 342001;
              $csvStateid = 1;
              $csvCountryid = 1;
              $csvPermaaddress1 = cleanVar($row['address']);
              $csvPermaaddress2 = NULL;
              $csvPermasuburbid = '';
              $csvPermastateid = 1;
              $csvCountryid = 1;
              $csvPermazipcode = 342001;
              $csvMobile = $row['mobile'];
              $csvPhone1 = $row['landlineno'];
              $csvPhone2 = NULL;
              $csvEmail = $makeEmail;
              $csvFax1 = NULL;
              $csvFax2 = NULL;
              $csvPercentgrade = '';
              $csvPreviousclass = '';
              $csvPreviousresult = '';
              $csvPreviousschool = '';
              $csvPassportnum = '';
              $csvDateofjoining = date('Y-m-d', strtotime($row['doj']));
              $csvDob = date('Y-m-d', strtotime($row['dob']));

              $csvStatus = '1';
              $csvProfilepicture = NULL;
              $csvConveyancerequired = '';
              $csvAdmissionreferencedby = '';
              $csvOtheradditionalinformation = '';
              $csvDatecreated = date('Y-m-d', strtotime($row['doj']));

              $csvStudentHeight = $row['height'];
              $csvStudentWeight = $row['weight'];
              $csvHousename = $row['house'];
              $csvStudentBloodGroup = $row['bloodgroup'];
              $csvVisionLeft = $row['visionleft'];
              $csvVisionRight = $row['visionright'];
              $medicalhistory = $row['dentalhygiene'];

              $identificationmark1 = '';
              $identificationmark2 = '';
              $doctorremark = '';
              $allergyinfo = '';
              $frequentillness = '';
              $regulardocname = '';
              $regulardocaddress = '';
              $regulardocmobile = '';
              $regulardocphone = '';
              $regulardocemail = '';
              $regularhospname = '';
              $regularhospphone = '';
              $regularhospemail = '';
              $regularhospaddress = '';


              $import[] = ("INSERT INTO `tbluser`(`username`, `password`, `roleid`, `status`, `instsessassocid`)
    VALUES('$makeEmail','$pass','3','1', '$intsessassocid');");

              $import[] = ("SET @last_insert_id_1 = LAST_INSERT_ID();");


              $import[] = ("INSERT INTO `tblstudent`(`scholarnumber`, `instsessassocid`,`firstname`,
    `middlename`,`lastname`, `status`, `deleted`, `datecreated`)
    VALUES('$csvScholarNumber','$intsessassocid','$csvFristName',
    '$csvMiddleName','$csvLastName', '1', '0', '$csvDatecreated' );");


              $import[] = ("SET @last_insert_id_2 = LAST_INSERT_ID();");


              $import[] = ("INSERT INTO `tblstudentdetails`( `studentid`, `dob`, `gender`, `religion`, `category`,
    `percentgrade`, `previousclass`, `previousresult`, `previousschool`, `passportnum`,
    `dateofjoining`, `housename`, `profilepicture`, `conveyancerequired`, `pickuppointid`,
    `admissionreferencedby`, `otheradditionalinformation`, `status`, `datecreated`)

    VALUES(@last_insert_id_2, '$csvDob','$csvGender', '$csvReligion',  '$csvCategory',
    '$csvPercentgrade', '$csvClassid',  '$csvPreviousresult', '$csvPreviousschool',
    '$csvPassportnum', '$csvDateofjoining', '$csvHousename',  '$csvProfilepicture',
    '$csvConveyancerequired', '', '$csvAdmissionreferencedby',
    '$csvOtheradditionalinformation',
    '1', '$csvDatecreated');");



              $import[] = ("INSERT INTO `tblstudentcontact`( `studentid`, `currentaddress1`, `currentaddress2`,
    `currentsuburbid`,`currentcityid`, `currentzipcode`,`currentstateid`, `currentcountryid`,
    `permaaddress1`, `permaaddress2`, `permasuburbid`, `permacityid`,
    `permastateid`, `permacountryid`, `permazipcode`, `mobile`, `phone1`,
    `phone2`, `fax1`, `fax2`, `email`, `emeregencycontactname`,
    `emeregencyphoneno`, `emeregencycontactaddress`, `status`, `datecreated`)

    VALUES (@last_insert_id_2,  '$csvCurrentaddress1',  '$csvCurrentaddress2',
    '$csvCurrentsuburbid', '$csvCityid', '$csvCurrentzipcode',
    '$csvStateid', '$csvCountryid', '$csvPermaaddress1',
    '$csvPermaaddress2', '$csvPermasuburbid', '$csvCityid','$csvStateid',  '$csvCountryid',
    '$csvPermazipcode',  '$csvMobile',  '$csvPhone1',  '$csvPhone2', '$csvFax1', '$csvFax2',
    '$makeEmail','$csvFatherFirstName', '$csvMobile', '$csvCurrentaddress1','1',
    '$csvDatecreated');");



              $import[] = ("INSERT INTO `tblstudentacademichistory`( `studentid`, `clsecassocid`, `academicstatus`,
    `studenttype`, `status`, `datecreated`)
    VALUES (@last_insert_id_2 , '$csvClsSecAssocid', '1', '$csvStudenttype', '1', '$csvDatecreated' );");

              $import[] = ("INSERT INTO `tblmedicalinfo`(`studentid`, `medicalhistory`, `allergyinfo`,
    `frequentillness`, `regulardocname`, `regulardocaddress`, `regulardocmobile`,
    `regulardocphone`, `regulardocemail`, `regularhospname`, `regularhospphone`,
    `regularhospemail`, `regularhospaddress`, `height`, `weight`, `bloodgroup`,
    `lefteyesight`, `righteyesight`, `identificationmark1`, `identificationmark2`,
    `doctorremark`, `datecreated`)

    VALUES (@last_insert_id_2, '$medicalhistory' , '$allergyinfo' ,'$frequentillness' ,
    '$regulardocname','$regulardocaddress', '$regulardocmobile',
    '$regulardocphone', '$regulardocemail', '$regularhospname', '$regularhospphone',
    '$regularhospemail', '$regularhospaddress', '$csvStudentHeight', '$csvStudentWeight',
    '$csvStudentBloodGroup','$csvVisionLeft', '$csvVisionRight','$identificationmark1',
    '$identificationmark2', '$doctorremark', '$csvDatecreated' );");


              $import[] = ("INSERT INTO `tbluserdetailsassoc`(`userid`, `isstudent`,`studentid`,
    `isemployee`,`employeeid`)  VALUES(@last_insert_id_1,'1',@last_insert_id_2,'0','NULL');
    ");

              $import[] = ("INSERT INTO `tblparent`(`parentfirstname`, `parentmiddlename`, `parentlastname`,
    `gender`, `religion`, `category`,`status`, `deleted`, `datecreated`)
    VALUES ('$csvFatherFirstName','$csvFatherMiddleName','$csvFatherLastName',
    '259','$csvReligion', '$csvCategory', '1', '0', '$csvDatecreated'); ");

              $import[] = ("SET @last_insert_id_3 = LAST_INSERT_ID();");

              $import[] = ("INSERT INTO `tblparentcontact`(`parentid`, `relationid`,  `currentaddress1`,
    `currentaddress2`, `currentsuburbid`, `currentcityid`, `currentzipcode`, `currentstateid`,
    `currentcountryid`, `permaaddress1`, `permaaddress2`, `permasuburbid`, `permacityid`,
    `permastateid`, `permacountryid`, `permazipcode`, `mobile1`,
    `email1`,`status`, `datecreated`)

    VALUES (@last_insert_id_3, '214', '$csvCurrentaddress1','$csvCurrentaddress2',
    '$csvCurrentsuburbid', '$csvCityid', '$csvCurrentzipcode','$csvStateid', '$csvCountryid',
    '$csvPermaaddress1','$csvPermaaddress2', '$csvPermasuburbid', '$csvCityid','$csvStateid',  '$csvCountryid',
    '$csvPermazipcode',  '$csvMobile', '$makeEmail', '1' ,'$csvDatecreated'  );");

              $import[] = ("INSERT INTO `tblparent`(`parentfirstname`, `parentmiddlename`, `parentlastname`,
    `gender`, `religion`, `category`,`status`, `deleted`, `datecreated`)
    VALUES ('$csvMotherFirstName','$csvMotherMiddleName','$csvMotherLastName',
    '260','$csvReligion', '$csvCategory', '1', '0', '$csvDatecreated');");

              $import[] = ("SET @last_insert_id_4 = LAST_INSERT_ID();");

              $import[] = ("INSERT INTO `tblparentcontact`(`parentid`, `relationid`,  `currentaddress1`,
    `currentaddress2`, `currentsuburbid`, `currentcityid`, `currentzipcode`, `currentstateid`,
    `currentcountryid`, `permaaddress1`, `permaaddress2`, `permasuburbid`, `permacityid`,
    `permastateid`, `permacountryid`, `permazipcode`, `mobile1`,
    `email1`,`status`, `datecreated`)

    VALUES (@last_insert_id_4, '215', '$csvCurrentaddress1','$csvCurrentaddress2',
    '$csvCurrentsuburbid', '$csvCityid', '$csvCurrentzipcode','$csvStateid', '$csvCountryid',
    '$csvPermaaddress1','$csvPermaaddress2', '$csvPermasuburbid', '$csvCityid','$csvStateid',  '$csvCountryid',
    '$csvPermazipcode',  '$csvMobile', '$makeEmail', '1' ,'$csvDatecreated'  );");

              $import[] = ("INSERT INTO `tbluserparentassociation`(`userid`, `studentid`, `parentid`,
    `status`, `datecreated`) VALUES (@last_insert_id_1,@last_insert_id_2,
    @last_insert_id_3,'1', '$csvDatecreated');");

              $import[] = ("INSERT INTO `tbluserparentassociation`(`userid`, `studentid`, `parentid`,
    `status`, `datecreated`) VALUES (@last_insert_id_1,@last_insert_id_2,
    @last_insert_id_4,'1', '$csvDatecreated');");
          }
          //echoThis($import);die;
          
          $result = dbInsert($import);
      }
  }
*/
  // ===================function end========================================= // 

  function getStdid($schno) {
      $instsessassocid = $_SESSION['instsessassocid'];
      $sql = "SELECT t1.studentid, t2.clsecassocid
    FROM `tblstudent` as t1,
    `tblstudentacademichistory` as t2
    WHERE t1.scholarnumber = '$schno'
    AND t1.studentid=t2.studentid
    AND t1.instsessassocid='$instsessassocid'";

      $result = dbSelect($sql);
      if (mysqli_num_rows($result) > 0) {
          $row = mysqli_fetch_assoc($result);
          $studentDetails['studentid'] = $row['studentid'];
          $studentDetails['clsecassocid'] = $row['clsecassocid'];
          return $studentDetails;
      } else
      //for all empty records, store the details in a file to be confirmed later.
      //before return 0.
          $customErrMsg = $schno;

      $customErrMsg = "$schno\n\n";
      $fileHandler = fopen("/opt/lampp/htdocs/360/error/import_error.txt", "a+");
      fwrite($fileHandler, $customErrMsg);

      return 0;
  }

  function getclassDetails($classname, $sectionnname) {
      $intsessassocid = $_SESSION['instsessassocid'];
      $sql = "SELECT
  t1.classid,
  t2.sectionid,
  t3.clsecassocid
FROM
  `tblclassmaster` AS t1,
  tblsection AS t2,
  `tblclsecassoc` AS t3
WHERE
  t1.classname = '$classname' AND t2.sectionname = '$sectionnname' 
  AND t1.classid = t3.classid AND t2.sectionid = t3.sectionid
  AND t3.instsessassocid = $intsessassocid
        ";

      $result = dbSelect($sql);
      $row = mysqli_fetch_assoc($result);
      $returnArray['classid'] = $row['classid'];
      $returnArray['sectionid'] = $row['sectionid'];
      $returnArray['clsecassocid'] = $row['clsecassocid'];

      return $returnArray;
  }

  function getstudentmasterData($masterCollectionName) {
      $sql = "SELECT `mastercollectionid` FROM `tblmastercollection` WHERE `collectionname` = '$masterCollectionName' ";
      $result = dbSelect($sql);
      $row = mysqli_fetch_assoc($result);
      $returnArray = $row['mastercollectionid'];
      return $returnArray;
  }
?>


