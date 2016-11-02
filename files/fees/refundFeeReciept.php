<?php
/*
 * 360 - School Empowerment System.
 * Developer: Ankur Mishra (amishra@ebizneeds.com.au) | www.ebizneeds.com.au
 * Page details here: Master for fees head and related processing
 * Updates here:
 */
//call the main config file, functions file and header
require_once "../../config/config.php";
require_once DIR_FUNCTIONS;
require_once VIEW_HEADER;
?>
<link href="<?php echo DIR_ASSET; ?>/css/feeReciept.css" rel="stylesheet"> 
<?php
if (isset($_GET['tc']) && ($_GET['tc'] == 'y')) {
    //$url = DB_HOST."/360/studentservices/studentTCDetails.php?status=paid&studentid=".$_GET['studentid'];
    $url = "../../studentservices/studentTCDetails.php?status=paid&studentid=".$_GET['studentid'];
    echo "
	<<script type=\"text/javascript\">
		opener.location.href = '$url';
	</script>	
	";
}
$details = studentDetailsSql();
$totalFeesPaid = 0;
?>
<script type="text/javascript"> 
window.onload=function(){self.print();} 
</script> 
    
    <table class="backgroundtb" border="0">
        <tr class="tr-head"> <!-- Heading row / brand-->
                <td colspan="4"class="tr-td"></td>
                <td class="space"></td>
                <td colspan="4"class="tr-td"></td>
            </tr>
            
            <!-- brand subtitle-->
            <tr class="subtitle">
                <td colspan="4"align="right"></td>
                <td></td>
                <td colspan="4"align="right"></td>
            </tr>
            
            <!-- space between subtitle and main part -->
            <tr class="td-space">
                <td colspan="4"></td>
                <td></td>
                <td colspan="4"></td>
            </tr>
              <!-- main printing part -->
              <?php 
                 $break = "<br>";
                 $duplicate = "";
                 if (isset($_GET['duplicate']) && $_GET['duplicate'] == 'yes') {
                     $duplicate = "(Duplicate)";
                 }
                 
               ?>
            
            <tr class="recieptno"><!-- printing reciept number -->  
                <td ></td><td class="reciept-h"><?php echo(cleanVar($_GET['recieptid']).$duplicate) ?></td>
                <td class="date"></td><td><?php echo date('d/m/Y') ?></td>
                <td></td>
                <td ></td><td class="reciept-h"><?php echo(cleanVar($_GET['recieptid']).$duplicate) ?></td>
                <td class="date"></td><td><?php echo date('d/m/Y') ?></td>
            </tr>
            
            <!-- Printing scholar number --> 
            <tr class="recieptno"> 
                <td class="reciept"></td><td><?php echo(cleanVar($details[0]['scholarnumber'])) ?></td>
                <td class="date"></td><td><?php echo(cleanVar(strtoupper($details[0]['classname']). "- ".strtoupper($details[0]['sectionname']))) ?></td>
                <td></td>
                <td class="reciept"></td><td><?php echo(cleanVar($details[0]['scholarnumber'])) ?></td>
                <td class="date"></td><td><?php echo(cleanVar(strtoupper($details[0]['classname']). "- ".strtoupper($details[0]['sectionname']))) ?></td>
            </tr>
            
            
            <!-- printing NAME-->  
            <tr class="recieptno">
                <td class="reciept"></td><td colspan="3" class=" size-2"><?php echo(cleanVar($details[0]['firstname'] . "  " . $details[0]['middlename'] . "  " .$details[0]['lastname'])) ?></td>
                <td></td>
                <td class="reciept"></td><td class=" size-2" colspan="3"><?php echo(cleanVar($details[0]['firstname'] . "  " . $details[0]['middlename'] . "  " .$details[0]['lastname'])) ?></td>
            </tr>
            
            
             <!-- printing Fathers's NAME--> 
            <tr class="recieptno"> 
                <td class="reciept"></td><td colspan="3"><?php echo(cleanVar($details[0]['parentfirstname'] . "  " . $details[0]['parentmiddlename'] . "  " .$details[0]['parentlastname'])) ?></td>
                <td></td>
                <td class="reciept"></td><td colspan="3"><?php echo(cleanVar($details[0]['parentfirstname'] . "  " . $details[0]['parentmiddlename'] . "  " .$details[0]['parentlastname'])) ?></td>
            </tr>
            
            
            <!-- empty row -->
           <tr class="recieptno"> 
                <td ></td><td colspan="3"></td>
                <td></td>
                <td ></td><td colspan="3"></td>
            </tr>
     
    <?php
    $installementsname = ''; $otherFeeHeads = '';
    if (!isset($_GET['tcfees']) && empty($_GET['tcfees'])) {
        $totalFeesPaid =  $_GET['totalFee'];
        $installmentsArray = generateInstallments();
        $installmentAmount = array_sum($installmentsArray) ;
      // $totalFeesPaid += $installmentAmount ;
       if (isset($_GET['ofd']) && !empty($_GET['ofd'])) {
           $otherFeeHeads =  generateOtherFee('otherfeeheadname');
       }
       
        foreach ($installmentsArray as $key => $value) {
            $installementsname .= strtoupper($key)." - ";
        }
        $installementsname = rtrim($installementsname, '-')
    ?>
            
            <!-- installment's row -->
            <tr class=" tdd"> 
                <td align="center"><b>Amount <br>Refunded</b></td>
                 <td class="size-3" colspan="2" align="center"></td>
                <td align="center"><?php echo(cleanVar($_GET['totalFee'])) ?></td>
                <td></td>
                <td align="center" ><b>Amount <br>Refunded</b></td>
                <td class="size-3" colspan="2" align="center"></td>
                <td align="center"><?php  echo(cleanVar($_GET['totalFee']))  ?></td>
            </tr>
    <?php 
    } else {
        $totalFeesPaid += cleanVar($_GET['tcfees']); ?>
        <tr class=" tdd"> 
                <td align="center"><b>TC Fees.</b></td>
                <td align="center"><?php echo(cleanVar($_GET['tcfees'])) ?></td>
                <td></td>
               <td align="center"><b>TC Fees.</b></td>
                <td align="center"><?php echo(cleanVar($_GET['tcfees'])) ?></td>
            </tr>
  <?php 
    }   ?>  
            
            <!-- other fee -->
            <?php
             if (!empty($otherFeeHeads)) {
                 $totalFeesPaid += generateOtherFee(''); ?>
                
            <tr class=" tdd-i" > 
               <td align="center"><b>Other Fees</b></td>
                <td colspan="2" class="size-3" align="center"><?php echo $otherFeeHeads ?></td>
                <td align="center" align="center"><?php echo(generateOtherFee('')) ?></td>
                <td></td>
                <td  align="center"><b>Other Fees</b></td>
                <td colspan="2" class="size-3" align="center"><?php echo $otherFeeHeads ?></td>
                <td  align="center"><?php echo(generateOtherFee('')) ?></td>
            </tr>
             <?php 
             } ?> 
            
            <!-- total amount -->
            <tr class="recieptno"> 
                 <td colspan="3" class="recieptnoo" align="center"> <b>In words</b> <?php echo(convertNum2Words($totalFeesPaid)) ?></td><td align="center"><?php echo $totalFeesPaid; ?></td>
                <td></td>
                <td colspan="3" class="recieptnoo" align="center"><b>In words</b>  <?php echo(convertNum2Words($totalFeesPaid)) ?></td><td align="center"><?php echo $totalFeesPaid; ?></td>
            </tr>
        
	     <?php
                if (isset($_GET['chequenumber'])) {
                    ?>
             <tr class="recieptno"> 
                <td colspan="3" align="center"> <?php echo($_GET['chequenumber']." of ". $_GET['bankname'])  ?></td>
                 
                <td></td>
                
                <td colspan="3" align="center"> <?php echo($_GET['chequenumber']." of ". $_GET['bankname']) ?>
            </tr>
            
            <?php 
                } ?>  
	        
	     <!-- footer part and logo -->
             <tr class="footer tr-head"> 
                <td colspan="4"c></td>
                <td class="space"></td>
                <td colspan="4"></td>
             </tr>
        </table>
	       
<?php


function studentDetailsSql()
{
    $instsessassocid = $_SESSION['instsessassocid'];
    
    if (isset($_GET['studentid'])) {
        $studentid = cleanVar($_GET['studentid']);
        $sql = "SELECT *FROM 
                `tblstudent` AS t1, 
		`tblstudentacademichistory` AS t2, 
		`tblclassmaster` AS t3, 
		`tblsection` AS t4,
                `tbluserparentassociation` AS t5,
                `tblparent` AS t6,
                `tblfeecollection` AS t7,
                `tblfeecollectiondetail` AS t8,
		`tblinstsessassoc` As t9,
                `tblinstitute` AS t10,
		`tblacademicsession` AS t11,
		`tblclsecassoc` AS t13
          
          
                WHERE t1.studentid = $studentid
                AND t1.studentid = t2.studentid
                AND t2.clsecassocid = t13.clsecassocid
                AND t3.classid = t13.classid 
                AND t4.sectionid = t13.sectionid
                AND t9.instsessassocid = t1.instsessassocid
                AND t9.instituteid = t10.instituteid
                AND t9.academicsessionid = t11.academicsessionid
                AND t1.studentid = t5.studentid
                AND t5.parentid = t6.parentid
                AND t1.studentid = t7.studentid
                AND t7.feecollectionid = t8.feecollectionid
	";
        
        $result = dbSelect($sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $details[] = $row ;
        }
        return $details;
    }
}
function generateInstallments()
{
    $urlComponentsArray = cleanVar($_GET);
   
    $installmentArray = array();
    foreach ($urlComponentsArray as $k => $val) {
        $feeComponent = getComponentName($k);
        $installmentArray[$feeComponent] = $val;
    }
    return ($installmentArray);
}

function getComponentName($componentid)
{
    $sql = "SELECT  t1.feecomponent FROM `tblfeecomponent` AS t1 WHERE t1.feecomponentid = '$componentid' ";
    $result = dbSelect($sql);
    $row = mysqli_fetch_assoc($result);
    return $row['feecomponent'];
}

function generateOtherFee($returnType)
{
    $otherFeeDetails = $_GET['ofd'];
    $otherFeeDetails = explode("|", rtrim($otherFeeDetails, '|'));
    $otherFeeHeadsName = array();
  
    $i = 0 ;
    $otherFee = 0 ;
    $otherFeeHeadsName = '';
    foreach ($otherFeeDetails as $key => $value) {
        $newArray =  explode("=", $value);
        $otherFeeHeadsName  .= $newArray[0] . "+";
        $otherFee += $newArray[1] ;
        $i++;
    }
    $otherFeeHeadsName = rtrim($otherFeeHeadsName, "+");
    if ($returnType == "otherfeeheadname") {
        return $otherFeeHeadsName ;
    } else {
        return $otherFee ;
    }
}
