<?php
/*
 * 360School Empowerment System.
 * Developer: Ankur Mishra (amishra@ebizneeds.com.au) | www.ebizneeds.com.au
 * Page details here: Master for fees head and related processing
 * Updates here:
 */
//call the main config file, functions file and header
require_once "../config/config.php";
require_once DIR_FUNCTIONS;
require_once VIEW_HEADER;
?>

<form action="<?php echo PROCESS_FORM; ?>" method="post" name="imform">
<div class ="container">
<div class="span11">

 <?php renderMsg();
     $studentdetails = studentDetailsSql();//echoThis($studentdetails); die; else{addError('CollectedFeedetails');}
     createCollectedFeeArray();
    ?>

<div class ="row">
 <div class="col-lg-6">
   <strong> Student Details </strong>:  <?php echo($studentdetails['firstname']." ".$studentdetails['middlename'] ." ". $studentdetails['lastname']);?>
   <small> (Scholar No: <?php echo($studentdetails['scholarnumber']); ?> 
   | Class: <?php echo($studentdetails['classdisplayname'] ."&nbsp". strtoupper($studentdetails['sectionname']));?>) </small>
     </div>

    <div class="col-lg-3">
      Fathers' Name:
        <b>   <?php echo($studentdetails['parentfirstname'] ."&nbsp". $studentdetails['parentmiddlename']." ".$studentdetails['parentlastname']);?></b>
    </div>
    
     <div class="col-lg-2">
      Enrolled In- :
        <b>   <?php echo($studentdetails['sessionname']);?></b>
    </div>
       
      
</div><!----class row ends here---->
    <span class="clearfix">&nbsp;<br></span>
    <span class="clearfix">&nbsp;<br></span>
    
<div class="row">
     <div class="col-lg-2">
       Siblings 
       <?php $siblingdetails = siblingDetails();
           if (!empty($siblingdetails)) {
               ?>
       <input type="button" id="siblingYes" name="siblingYes" class="btn btn-success" value="Yes" 
       data-toggle="modal" data-target="#myModal">
		   <?php 
           } else {
               ?>
		   <input type="button" id="siblingNO" name="siblingNO" class="btn" value="None">
		   <?php 
           } ?>
    </div>
    
</div><!----class row ends here---->

<span class="clearfix">&nbsp;<br></span>
<span class="clearfix">&nbsp;<br></span>

<div class="row">
  
  <h3> Fee Collection Details </h3><br>
    <table>
       <tr>
            <th>Installments</th>
            <th>Amount(in Rs)</th>
            <th>Collection Date</th>
            <th>Reciept No</th>
        </tr> 
              
        <tr>
            <td>
            <?php if (!empty($collectedfee)) {
               foreach ($CollectedMonthnamearray as $key => $value) {
                   echo($value) ;
               }
           }
            ?>
	    </td>
            <td>Amount(in Rs)</th>
            <td>Collection Date</td>
            <td>Reciept No</td>
        </tr>       
              
              
    <div class="col-lg-2">
     <label>Installments </label>
    
     </div>
     
    <div class="col-lg-3">
      <label> Amount(in Rs)</label>
         <?php if (!empty($collectedfee)) {
                foreach ($collectedfee as $key => $value) {
                    echo("<b>Rs ".$value['feeinstallmentamount']."</b><br><br>");
                    $totalamountcollected += $value['feeinstallmentamount'];
                }
            } else {
            echo("<div class=\"bs-example\" style=\"width:950;\">
         <div class=\"alert alert-warning\">
         All fee instalments of this student seems to be Due. Kindly pay instalments regularly to avoid inconvenience in future
         </div>
         </div>");
        }
    ?>
     </div> 
     
</div>

<span class="clearfix">&nbsp;<br></span>

<div class="row">
   <div class="col-md-9">
       <div class="controls" align="Right">
      Total Fee Collected &nbsp &nbsp;
        <?php echo("<b>Rs " .$totalamountcollected."</b>") ; ?>
  </div>
</div>
</div>

</div><!-----------span11 closed------->
</div><!-----------container closed------->

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
      
      <h4 class="modal-title" id="myModalLabel">Siblings Details</h4>
      
      </div>
      
      <div class="modal-body">
        <table class="table table-condensed table-hover" > 
          
            <tr class="info">
        
           <th align="center">S.No</th>
            <th align="center">Scholar No.</th>
            <th align="center">First/Last Name</th>
            </tr> 
   
       <?php

        $details = siblingDetails();
        $i = 1 ;
          if (!empty($details)) {
              foreach ($details as $key => $value) { //echoThis($details); die;
       ?>
       
        
        <tr>
           <td><a href="collectedFee.php?studentid=<?php echo $value['studentid'] ?>"><?php echo $i ?></td></a>
	       <td><a href="collectedFee.php?studentid=<?php echo $value['studentid'] ?>"><?php echo $value['scholarnumber'] ?></td></a>
	       <td><a href="collectedFee.php?studentid=<?php echo $value['studentid'] ?>"><?php echo($value['firstname']." ".$value['middlename']." ".$value['lastname'])?></td></a>
        </tr>
      	 <?php
           $i++;
              }
          }
          ?>   
        
        </table>
   
       </div>
       
       <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
       
      </div>
    
    </div>
  </div>
</div>

<?php
require VIEW_FOOTER;

function studentDetailsSql()
{
    $instsessassocid = $_SESSION['instsessassocid'];
    if ((isset($_GET['studentid']))) {
        $studentid = cleanVar($_GET['studentid']);
        $sql = "SELECT *FROM `tblstudent` AS t1, 
		  `tblstudentdetails` AS t2, 
		  `tblclassmaster` AS t3, 
		  `tblsection` AS t4,
		  `tbluserparentassociation` AS t5,
		  `tblparent` AS t6,
		  `tblinstsessassoc` AS t7,
		  `tblinstitute` AS t8,
		  `tblacademicsession` AS t9

          WHERE t1.studentid = $studentid
          AND t1.studentid = t2.studentid
          AND t3.classid = t2.classid 
		  AND t2.sectionid = t4.sectionid
		  AND t1.userid = t5.userid 
		  AND t5.parentid = t6.parentid
		  AND t7.instsessassocid = $instsessassocid 
		  AND t7.instituteid = t8.instituteid
		  AND t7.academicsessionid = t9.academicsessionid
		 
		  ";
         // echoThis($sql); die;
          $result = dbSelect($sql);
        $row = mysqli_fetch_assoc($result);
        return $row;
    } else {
        addError('studentdetails');
    }
}
  
function siblingDetails()
{
    if (isset($_GET['studentid'])) {
        $studentid = cleanVar($_GET['studentid']);
    
        $sql = "SELECT  t1.scholarnumber, t1.studentid,t1.userid,t2.parentid, t2.userid
          
           FROM `tblstudent` AS t1, 
		  `tbluserparentassociation` AS t2,
		  `tblparent` AS t3
		  
		  WHERE t1.studentid = $studentid
          AND t1.userid = t2.userid
		  AND t2.parentid = t3.parentid
		  
		  ";
        $result = dbSelect($sql);
        $row = mysqli_fetch_assoc($result);
     
        $parentid = $row['parentid'];
        $userid = $row['userid'];
    
        unset($sql);
        unset($row);
        unset($result);
    
        $sql = "SELECT t3.studentid, t3.scholarnumber,  t3.firstname, t3.middlename, t3.lastname, t4.classid, t4.sectionid,
           t5.classdisplayname, t6.sectionname
          
          
		  FROM `tbluserparentassociation` AS t1,
		  `tblparent` AS t2,
		  `tblstudent` AS t3,
		  `tblstudentdetails` AS t4,
		  `tblclassmaster` AS t5,
		  `tblsection` AS t6
		  
		  
		  WHERE NOT t1.userid = $userid
		  AND t1.parentid = $parentid
		  AND t1.parentid = t2.parentid
		  AND t1.userid = t3.userid
		  AND t3.studentid = t4.studentid
		  AND t4.classid = t5.classid
		  AND t4.sectionid = t6.sectionid
		  
		  ";
        $siblingdetail = array();
        if ($result = dbSelect($sql)) {
            while ($row = mysqli_fetch_assoc($result)) {
                $siblingdetail[] = $row;
            }
    
            return $siblingdetail;
        }
        return 0 ;
    }
}
    
 
function Collectedfee()
{
    $feedetails = array();
    $studentid = cleanVar($_GET['studentid']);
    
    
    if ((isset($_GET['date1'])) && (isset($_GET['date2']))) {
        $studentid = cleanVar($_GET['studentid']);
        $date1 = cleanVar($_GET['date1']);
        $date2 = cleanVar($_GET['date2']);
        
        $sql = " SELECT t1.studentid, t2.feecollectionid, t2.feeinstallment, t2.feeinstallmentamount
			FROM `tblfeecollection` AS t1,
			`tblfeecollectiondetail` AS t2

			WHERE t2.feeinstallment BETWEEN '$date1' AND '$date2'
			AND t1.studentid = $studentid
			AND t1.feecollectionid = t2.feecollectionid
		"; //echoThis($sql); die;
    } else {
        $sql = " SELECT t1.studentid, t2.feecollectionid, t2.feeinstallment, t2.feeinstallmentamount
			FROM `tblfeecollection` AS t1,
			`tblfeecollectiondetail` AS t2

			WHERE t1.studentid = $studentid
			AND t1.feecollectionid = t2.feecollectionid
		";
    }
    
    $result = dbSelect($sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $feedetails[] =  $row;
    }
    return $feedetails;
}
 
 function createCollectedFeeArray()
 {
     $j =0;
     $totalamountcollected = 0;
    
     $collectedfee = Collectedfee();//echoThis($collectedfee); die;
        if (!empty($collectedfee)) {
            foreach ($collectedfee as $key =>$value) {
                $CollectedMonth[] = date('m', strtotime($collectedfee[$key]['feeinstallment']));
                $CollectedMonthname[] = date('F', strtotime($collectedfee[$key]['feeinstallment']));
                $CollectedMonthArray = array_unique($CollectedMonth);
                $CollectedMonthNames = array_unique($CollectedMonthname);
                $CollectedMonthnameArray = $CollectedMonthNames;
            }
            foreach ($CollectedMonthNames as $k => $val) {
                $CollectedMonthnamearray[$j]= $val;
                $j++;
            }
   // echoThis($CollectedMonthnamearray); die;
        }
      
     return $CollectedMonthnamearray;
 }
