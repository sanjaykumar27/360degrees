<?php
/*
 * 360School Empowerment System.
 * Developer: Ankur Mishra (amishra@ebizneeds.com.au) | www.ebizneeds.com.au 
 * Page details here: Master for fees head and related processing 
 * Updates here: 
 */

//call the main config file, functions file and header
require_once "../../config/config.php";
require_once DIR_FUNCTIONS;

// keep before rendering header
// better this, this is not a good way to write this page. 

if (!isset($_GET['e'])) {
    $collectedfee = Collectedfee();
    //echoThis($collectedfee); die;
    $installemntArray = createInstallmentArray();

    $conveyanceFees = getConveyanceAmount(cleanVar($_GET['sid']));
}
require_once VIEW_HEADER;
?>

<script type="text/javascript">
    $(document).ready(function ($) {
        $('#checkall').on('click', function () {
            var childClass = $(this).attr('data-child');
            $('.' + childClass + '').prop('checked', this.checked);
        });

    });

</script>

<div class ="container">
    <div class="span11">
        <?php
        renderMsg();
        if (!isset($_GET['pop-up'])) {
            ?>
            <ul class="nav nav-tabs" role="tablist">
                <li><a href="studentPersonal.php<?php echo isEditable(); ?>">Personal</a></li>
                <li><a href="studentParent.php<?php echo isEditable(); ?>">Parents</a></li>
                <li><a href="studentMedical.php<?php echo isEditable(); ?>">Medical</a></li>
                <li><a href="studentFees.php<?php echo isEditable(); ?>">Fees Rule</a></li>
                <li><a href="studentDocument.php<?php echo isEditable(); ?>">Documents</a></li>
                <li class="active"><a href="studentFeeDetails.php<?php echo isEditable(); ?>">Fee Details</a></li>
            </ul>
        <?php } ?>
        <span class="clearfix">&nbsp;<br></span>

        <table class="table table-bordered table-hover" id="duefeedetails">
            <thead>
                <tr>
                    <th>Installments</th>
                    <th>Installment Amount</th>
                    <th>Due Date</th>
                    <th>Action </th>
                </tr>
            </thead>
            <?php
            $j = $i = 1;
            if (isset($installemntArray)) {
                foreach ($installemntArray as $key => $value) {

                    $totalAmount = 0;
                    $otherfee = 0;
                    $InstallmentMonthname = date('F', strtotime($key));
                    $InstallmentMonth = "<span class=\"text-danger\">$InstallmentMonthname</span> ";
                    $modalShow = "onclick=\"showModal('displayduefeecontent$j')\" ";
                    $status = " <button  class=\"btn btn-danger\"  
                       onClick=\"popUp('../fees/feeCollectionProcessing.php?studentid=" . $_GET['sid'] . "&pop-up=y',1100,500)\">
                                        Pay Now
                                    </button>";
                    if (date('Y-m-d') <= $key) {
                        $InstallmentMonth = "<span class=\"text-primary\">$InstallmentMonthname</span> ";
                        $status = " <button  class=\"btn btn-info\"  
                       onClick=\"popUp('../fees/feeCollectionProcessing.php?studentid=" . $_GET['sid'] . "&pop-up=y',1100,500)\">
                                        Pay Now
                                    </button>";
                    }


                    if (!empty($collectedfee) && array_key_exists($key, $collectedfee)) {
                        $InstallmentMonth = "<span class=\"text-success\">$InstallmentMonthname</span> ";
                        $modalShow = "onclick=\"showModal('displaycollectedfeecontent$i')\" ";
                        $status = "<h5 class=\"text-success\">
                                       Paid
                                    </h5>";
                        $totalAmount += $collectedfee[$key]['feeinstallmentamount'];
                        if (isset($value['otherfees'])) {
                            foreach ($value['otherfees'] as $othkey => $othval) {
                                $totalAmount += implode('', $othval);
                            }
                        }
                        $i++;
                    } else {
                        $totalAmount = $value['Total Fees'];
                        if ($conveyanceFees != 0) {
                            $totalAmount += $conveyanceFees;
                        }
                    }
                    ?>

                    <tr>
                        <td><?php echo $InstallmentMonth ?></td>
                        <td><a href="JavaScript:(void);" <?php echo $modalShow ?>><?php echo formatCurrency($totalAmount) ?></a></td>
                        <td><?php echo(date('d/m/Y', strtotime($key))) ?></td>
                        <td><?php echo $status ?></td>
                    </tr>
                    <?php
                    $j++;
                }
            }
            ?>

        </table>
        <?php
        /*
         * Modal displaying due fee details
         */
        $p = 1;
        $otherFees = 0;
        if (isset($installemntArray)) {
            foreach ($installemntArray as $key => $value) {
                $conveyanceFees = getConveyanceAmount(cleanVar($_GET['sid']));
                $installmentAmount = $value['Total Fees'];
                $InstallmentMonthname = date('F', strtotime($key));
                unset($value['Total Fees']);
                unset($value['scholarnumber']);
                ?>

                <div class="modal fade"  role="dialog" aria-labelledby="fee-details-label" aria-hidden="true"
                     id="displayduefeecontent<?php echo $p ?>" >
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title" id="fee-details-label">Installment Details - <?php echo $InstallmentMonthname ?></h4>
                            </div>
                            <div class="modal-body">
                                <table class="table table-bordered table-striped" >  
                                    <tr class="info"> 
                                        <th>Fee Heads</th>
                                        <th>Amount</th>
                                    </tr> 
                                    <?php foreach ($value['feecomponents'] as $k => $val) {
                                        ?>
                                        <tr>
                                            <td><?php echo $k ?></td>
                                            <td><?php echo $val ?></td>
                                        </tr>
                                    <?php }
                                    ?>      
                                </table>

                                <table class="table table-bordered table-striped">
                                    <tr> 
                                        <th> Installment Amount</th>
                                        <th> Conveyance Fees</th>
                                        <th> Total Fees</th>
                                    </tr>
                                    <tr>
                                        <td><?php echo $installmentAmount ?></td>
                                        <td><?php echo $conveyanceFees ?></td>
                                        <td><?php echo($installmentAmount + $conveyanceFees) ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div><!-- modal-content -->
                    </div><!-- modal-dialog -->
                </div><!--modal -->
            </div> 
            <?php
            $p++;
        }
    }

    /*
     *  Modal showing collected fee details
     */
    $otherFeesDetail = 0;
    $p = 1;
    if (!empty($collectedfee)) {
        //echoThis($collectedfee);die;
        foreach ($collectedfee as $key => $value) {

            $totalAmount = 0;
            $otherFeesDetails = '';
            $studentid = $installemntArray[$key]['studentid'];

            unset($installemntArray[$key]['Total Fees']);
            unset($installemntArray[$key]['studentid']);
            unset($installemntArray[$key]['scholarnumber']);
            $InstallmentMonthname = date('F', strtotime($key));
            $installmentAmount = $value['feeinstallmentamount'];
            $otherFeesDetails = otherFees($value['studentid'], $value['feeinstallment']);
            if (!empty($otherFeesDetails)) {
                foreach ($otherFeesDetails as $othkey => $othval) {
                    $totalAmount += implode('', $othval);
                    $otherFeesDetail = $othkey . "=" . implode('', $othval);
                    ;
                }
            }

            $totalAmount += $installmentAmount;

            $duplicateReciept = " ../fees/feeReciept.php?duplicate=yes&pop-up=y&studentid=$studentid&ofd=$otherFeesDetail&totalFee=$totalAmount&recieptid=$value[receiptid]&$key=$installmentAmount"
            ?>

            <form name="imform" action="<?php echo PROCESS_FORM; ?>" method="post" >
                <div class="modal fade"  role="dialog" aria-labelledby="fee-details-label" 
                     aria-hidden="true"
                     id="displaycollectedfeecontent<?php echo $p ?>" >
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title" id="fee-details-label">Installment Details - <?php echo $InstallmentMonthname ?></h4>
                            </div>
                            <div class="modal-body">

                                <table class="table table-bordered table-striped" >  
                                    <tr class="info"> 
                                        <th><input type="checkbox" id="checkall" data-child="chk"> Check All</th>
                                        <th>Fee Heads</th>
                                        <th>Amount</th>
                                    </tr>   
                                    <tr>
                                        <?php
                                        foreach ($installemntArray[$key]['feecomponents'] as $k => $val) {
                                            $feecomponent = array_search($installemntArray[$key]['feecomponentid'][$k], $installemntArray[$key]['feecomponentid']);
                                            $feecomponentid = $installemntArray[$key]['feecomponentid'][$k];
                                            ?>
                                            <td>
                                                <input type="checkbox" class="chk"
                                                       name="feecollectiondetailid[<?php echo $value['feecollectiondetailid'] ?>][<?php echo $feecomponentid ?>]"  
                                                       id="feecollectiondetailid" value="<?php echo($val) ?>">
                                            </td>
                                            <td><?php echo $k ?></td>
                                            <td><?php echo(formatCurrency($val)) ?></td>
                                        </tr>

                                    <?php }
                                    ?>    
                                </table> 
                                <?php if (isset($installemntArray[$key]['otherfees'])) { ?>                 
                                    <table class="table table-bordered table-striped">
                                        <tr class="info"> 
                                            <th><input type="checkbox" id="checkall" data-child="chk"> Check All</th>
                                            <th>Other Fee Heads</th>
                                            <th>Amount</th>
                                        </tr>
                                        <?php foreach ($installemntArray[$key]['otherfees'] as $othk => $othval) {
                                            ?>
                                            <tr>
                                                <td>
                                                    <input type="checkbox" class="chk"
                                                           name="otherfees[<?php echo key($othval) ?>]"  
                                                           id="otherfees" value="<?php echo(implode('', $othval)) ?>">
                                                </td>

                                                <td><?php echo $othk ?></td>
                                                <td><?php echo(formatCurrency(implode('', $othval))) ?></td>


                                            </tr>
                                            <input type="hidden" name="othefeedetails[<?php echo $othk ?>]" 
                                                   value="<?php echo(implode('', $othval)) ?>" >
                                               <?php }
                                               ?>

                                    </table>
                                <?php } ?>

                                <table class="table table-bordered table-striped">
                                    <tr class="info"> 
                                        <th> Total Amount</th>
                                        <th> Fee Receipt</th>
                                        <th> Collected On</th>
                                        <th> Bank Name</th>
                                        <th> Cheque Number</th>
                                    </tr>
                                    <tr><td width='250'>
                                            <?php echo formatCurrency($totalAmount); ?>
                                        </td>
                                        <td><?php echo $value['receiptid'] ?> </td>
                                        <td><?php echo(date('d/m/Y', strtotime($value['datecreated']))) ?> </td>
                                        <td><?php echo $value['bankname'] ?> </td>
                                        <td><?php echo $value['chequenumber'] ?> </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Remarks</strong></td>
                                        <td colspan="4"><textarea name="Remarks" class="form-control"></textarea></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <a href="<?php echo $duplicateReciept ?>"> <button type="button" class="btn btn-success">Print Receipt</button></a>
                                <input type="submit" class="btn btn-success" name="submit"  value="Refund">
                            </div>
                        </div><!-- modal-content -->
                    </div><!-- modal-dialog -->
                </div><!--modal -->

                <input type="hidden" name="originalfeereceiptid" value="<?php echo $value['receiptid'] ?>">
                <input type="hidden" name="studentid" value="<?php echo $studentid ?>" >
                <input type="hidden" name="instituteabbrevation" value="<?php echo $value['instituteabbrevation'] ?>">
                <input type="hidden" name="sessionname" value="<?php echo $value['sessionname'] ?>">
            </form>
            <?php
            $p++;
        }
    }
    ?>

</div>

<?php
require VIEW_FOOTER;

function studentDetailsSql() {
    if ((isset($_GET['sid'])) && (is_numeric($_GET['sid']))) {
        $studentid = cleanVar($_GET['sid']);
        $sql = "SELECT * FROM
            `tblstudent` AS t1, 
            `tblstudentacademichistory` AS t2, 
            `tblclassmaster` AS t3, 
            `tblsection` AS t4,
            `tblinstsessassoc` As t5,
            `tblinstitute` AS t6,
            `tblacademicsession` AS t7,
            `tblclsecassoc` AS t8,
            `tbluserparentassociation` AS t9,
            `tblparent` AS t10,
            `tblstudentdetails` AS t11

          
            WHERE t1.studentid = $studentid
            AND t1.studentid = t2.studentid
            AND t1.studentid = t11.studentid
            AND t2.clsecassocid = t8.clsecassocid
            AND t3.classid = t8.classid 
            AND t4.sectionid = t8.sectionid
            AND t1.instsessassocid = t5.instsessassocid
            AND t5.instituteid = t6.instituteid
            AND t5.academicsessionid = t7.academicsessionid 
            AND t9.studentid = t1.studentid
            AND t10.parentid = t9.parentid
            AND t1.deleted != 1 
            AND t1.instsessassocid = '$_SESSION[instsessassocid]'
            ";

        $result = dbSelect($sql);
        $row = mysqli_fetch_assoc($result);
        return $row;
    } else {

        addError(NULL, NULL, $_SERVER['PHP_SELF'] . "?e=studentdetails");
    }
}

function createInstallmentArray() {
    $studentDetails = studentDetailsSql();
    $HtmlArray = feeComponentsSql();
    //echoThis($HtmlArray);die;
    $newOptions = array();
    $i = 0;
    $totalamount = array();

    if (isset($HtmlArray) && $HtmlArray != 0) {
        foreach ($HtmlArray as $option) {

            $duedate = $option['duedate'];
            $feecomponents = $option['feecomponent'];
            $amount = $option['amount'];
            $newOptions[$duedate]['feecomponents'][$feecomponents] = $amount;
            $newOptions[$duedate]['feecomponentid'][$feecomponents] = $option['feecomponentid'];
        }
    }

    foreach ($newOptions as $key => $value) {
        $newOptions[$key]['Total Fees'] = array_sum($value['feecomponents']);
        $newOptions[$key]['scholarnumber'] = $studentDetails['scholarnumber'];
        $newOptions[$key]['studentid'] = $studentDetails['studentid'];
        $otherFeeArray = otherFees($studentDetails['studentid'], $key);

        if (!empty($otherFeeArray)) {
            foreach ($otherFeeArray as $k => $val) {
                $newOptions[$key]['otherfees'] = $otherFeeArray;
            }
        }
    }

    return $newOptions;
}

function feeComponentsSql() {
    $conveyance = "";
    $pickuppoint = "";
    $studentdetails = studentDetailsSql();
    //echoThis($studentdetails);die;
    $feeruledetails = feeRuleSql();
    $feeRuleInstallment = getInstFeeRuleAssoc();

    if (!empty($feeruledetails)) {
        foreach ($feeruledetails as $key => $value) {
            $feeruleamount[] = $feeruledetails[$key]['feeruleamount'];
            $feerulecomponents[] = $feeruledetails[$key]['feecomponent'];
            $feerulemode[] = $feeruledetails[$key]['feerulemodeid'];
            $feeruletype[] = $feeruledetails[$key]['feeruletype'];
        }
    }


    $classid = $studentdetails['classid'];
    if (!isset($classid)) {
        addError('CollectedFeedetails');
        $classid = 0;
    }
    $sql = " SELECT t1.feestructureid, t3.feecomponent, t3.feecomponentid, t2.feestructureid, t2.amount, t2.duedate, 
                t2.isrefundable, t2.frequency 
                    
                   FROM `tblfeestructure` AS t1,
		  `tblfeestructuredetails` AS t2,
		  `tblfeecomponent` AS t3
		  
                   WHERE t1.classid = $classid AND
                   t1.instsessassocid = $_SESSION[instsessassocid]
		   AND t1.feestructureid = t2.feestructureid
		   AND t1.feecomponentid = t3.feecomponentid
                   AND t1.status = 1
		   ORDER BY t2.duedate ASC 
           ";
    //echoThis($sql);die;
    $result = dbSelect($sql);
    if (mysqli_num_rows($result) != 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $feedetails[] = $row;
        }


        if (!empty($feeruledetails)) {
            foreach ($feeruledetails as $key => $value) {
                foreach ($feedetails as $k => $val) {
                    if ($value['feecomponent'] == $val['feecomponent'] && in_array($val['duedate'], $feeRuleInstallment)) {
                        $val['amount'] = updateFees($value['feeruletype'], $value['feerulemodeid'], $val['amount'], $value['feeruleamount']);
                        $feedetails[$k]['amount'] = $val['amount'];
                    } else {
                        $feedetails[$k]['amount'] = $val['amount'];
                    }
                }
            }
            return($feedetails);
        } else {
            return($feedetails);
        }
    } else {
        return 0;
    }
}

function updateFees($type, $mode, $amount, $value) {

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

function getInstFeeRuleAssoc() {

    $studentid = cleanVar($_GET['sid']);

    if (is_numeric($studentid)) {

        $sql = "SELECT t2.installment
        
              FROM  `tblstudfeeruleassoc` AS t1,
              `tblstudfeeruleinstasssoc` AS t2
              
              WHERE t1.studentid = $studentid
              AND t1.studfeeruleassocid = t2.studfeeruleassocid
              AND t2.status = 1 ";

        $result = dbSelect($sql);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $installmentArray[] = $row['installment'];
            }
            return $installmentArray;
        } else {
            return 0;
        }
    } else {
        addError("studentdetails");
    }
}

function feeRuleSql() {

    $studentid = cleanVar($_GET['sid']);

    $sql = "SELECT *
		
        FROM `tblfeerule` AS t1,
        `tblfeeruledetail` AS t2, 
        `tblstudfeeruleassoc` AS t3,
        `tblfeecomponent` AS t4

        WHERE t3.studentid = $studentid
        AND t1.feeruleid = t2.feeruleid 
        AND t1.feeruleid = t3.feeruleid
        AND t2.feecomponentid = t4.feecomponentid
        AND t1.feerulestatus = 1
        AND t1.deleted != 1
        ";

    if (($result = dbSelect($sql)) && (($num_row = mysqli_num_rows($result)) != 0)) {

        while ($row = mysqli_fetch_assoc($result)) {
            $feeruledetails[] = $row;
        }
        return($feeruledetails);
    }
}

function Collectedfee() {
    $feedetails = array();
    if ((isset($_GET['edid']))) {
        $studentid = cleanVar($_GET['edid']);
    } elseif ((isset($_GET['sid']))) {
        $studentid = cleanVar($_GET['sid']);
    }


    $sql = "  SELECT t1.studentid, t1.receiptid ,t2.feecollectionid, t2.feecollectiondetailid, 
               t2.feeinstallmentamount,  t2.collectiontype, t2.datecreated , t3.feeinstallment,
               t5.instituteabbrevation, t6.sessionname
               
              FROM `tblfeecollection` AS t1, 
              `tblfeecollectiondetail` AS t2,
              `tblfeeinstallmentdates` AS t3,
              `tblinstsessassoc` AS t4,
              `tblinstitute` AS t5,
              `tblacademicsession` AS t6
              
              WHERE t1.studentid = '$studentid' 
              AND t1.feecollectionid = t2.feecollectionid 
              AND t2.feecollectiondetailid = t3.feecollectiondetailid 
              AND t1.instsessassocid = t4.instsessassocid
              AND t4.instituteid = t5.instituteid
              AND t4.academicsessionid = t6.academicsessionid
              AND (t2.feestatus = 1 OR t2.feestatus = 0)
              AND t2.refundstatus = 0
              AND t1.instsessassocid = '$_SESSION[instsessassocid]'
              ORDER BY t3.feeinstallment ASC 
              
             ";

    $result = dbSelect($sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $feedetails[$row['feeinstallment']] = $row;
    }
    if (!empty($feedetails)) {
        foreach ($feedetails as $key => $value) {
            $chequeDetails = getChequeDetails($value['feecollectionid']);
            if ($chequeDetails != 0) {
                $feedetails[$key]['bankname'] = $chequeDetails['bankname'];
                $feedetails[$key]['chequenumber'] = $chequeDetails['chequenumber'];
            } else {
                $feedetails[$key]['bankname'] = "-";
                $feedetails[$key]['chequenumber'] = "-";
            }
            $feedetails[$key]['ConveyanceFees'] = getConveyanceAmount($studentid);
        }

        return $feedetails;
    } else {
        return 0;
    }
}

function getConveyanceAmount($studentId) {

    $conveyanceAmount = NULL;
    $sqlConveyance = " SELECT t2.amount
                       FROM tblstudentdetails as t1, tblpickuppoint as t2 
                       WHERE t1.pickuppointid = t2.pickuppointid 
                       AND t1.studentid = $studentId ";

    $resConveyance = dbSelect($sqlConveyance);

    if (mysqli_num_rows($resConveyance) > 0) {
        $row = mysqli_fetch_assoc($resConveyance);
        $conveyanceAmount = $row['amount'];
    }
    return $conveyanceAmount;
}

function OtherFees($studentid, $duedate) {

    // all installment fees have collection type id = 316
    $feedetails = array();
    $sql = "SELECT t2.feecollectiondetailid, t3.otherfeehead, t2.feeinstallmentamount
            
            FROM `tblfeecollection` AS t1,
            `tblfeecollectiondetail` AS t2,
            `tblfeeothercharges` AS t3,
            `tblfeeotherchargesdetails` AS t4,
            `tblfeepenaltydetails` as t5,
            `tblmastercollectiontype` AS t6,
            `tblmastercollection` AS t7
            
            WHERE t1.studentid = '$studentid'
            AND t1.feecollectionid = t2.feecollectionid 
            AND t2.collectiontype = t3.feeotherchargesid 
            AND t3.feeotherchargesid = t4.feeotherchargesid 
            AND t2.feecollectiondetailid = t5.feecollectiondetailid
            AND t5.feeinstallmentid = '$duedate'
            AND t6.mastercollectiontype = 'collection type'
            AND t6.mastercollectiontypeid = t7.mastercollectiontypeid
            AND t7.collectionname != 'Fees'
            AND t2.feestatus = 1
            ";


    $result = dbSelect($sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $feedetails[$row['otherfeehead']][$row['feecollectiondetailid']] = $row['feeinstallmentamount'];
    }

    return $feedetails;
}

// check is edit mode is enabled, if so return true. 
// e = edit  
function isEditable() {
    $str = substr($_SERVER['REQUEST_URI'], strpos($_SERVER['REQUEST_URI'], '?'));

    if (isset($_GET['sid']) && is_numeric($_GET['sid']) && $_GET['sid'] > 0)
        return $str;
    else
        return false;
}

function getDueOtherFees($duedate, $installmentamount) {
    $feeamount = 0;
    $totaldays = 0;
    //echoThis($installmentamount); die;
    $otherFeeDetails = otherFeeSql(NULL);

    if ($duedate < date('Y-m-d')) {
        $datediff = date_diff(date_create($duedate), date_create(date('Y-m-d')));
        $totaldays += $datediff->format("%R%a days");
    }

    foreach ($otherFeeDetails as $key => $value) {
        if ($value['status'] == 1 && $value['otherfeehead'] != 'Late Fees') {
            $calcAmount = OtherFeeCalculate($value['chargemode'], $value['otherfeetype'], $value['frequency'], $value['amount'], $installmentamount, $totaldays);
        }
    }
    return ($calcAmount);
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
        return($otherfeeDetails);
    } else {
        return 0;
    }
}

function OtherFeeCalculate($chargemode, $otherfeetype, $frequency, $amount, $feeamount, $totaldays) {
    $updatedAmt = 0;

    if (!empty($amount)) {
        if (strtolower($chargemode) == 263) {

            //daily basis.
            if ($frequency == 303) {
                $dueAmount = (($amount / 100) * $duedate ) * $feeamount;
                $diffAmount = $dueAmount;
            }
            //weekly basis.
            elseif ($frequency == 302) {
                $daysfromWeek = ceil($duedate / 7);
                $dueAmount = (($amount / 100) * $daysfromWeek ) * $feeamount;
                $diffAmount = $dueAmount;
            }
            return $diffAmount;
        } else {
            $calculatedAmt = OtherFeesCalculateAmount($totaldays, $amount, $frequency);
            return $calculatedAmt;
        }
    } else {
        return 0;
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
            $dueAmount = (($daysfromWeek ) * $amount);
            $diffAmount[] = $dueAmount;

            return $diffAmount;
            break;

        default:
            return $amount;
    } //end of switch statement
}

function getChequeDetails($feecollectionid) {
    $sql = "SELECT `bankname`, `chequenumber` FROM `tblfeecheque` WHERE `feecollectionid` = '$feecollectionid' ";
    if (mysqli_num_rows(dbSelect($sql)) > 0) {
        $result = mysqli_fetch_assoc(dbSelect($sql));
        $returnArray['chequenumber'] = $result['chequenumber'];
        $returnArray['bankname'] = $result['bankname'];
        return $returnArray;
    } else {
        return 0;
    }
}
?>