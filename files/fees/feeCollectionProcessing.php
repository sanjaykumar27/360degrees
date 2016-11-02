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

<link href="<?php echo DIR_ASSET; ?>/css/feeform.css" rel="stylesheet">
<script type="text/javascript">
    function showHideDiv(divName) {
        $('#' + divName).modal('show');
    }
   $(document).ready(function ($) {
        $('#checkall').on('click', function(){ 
            var childClass = $(this).attr('data-child');
            $('.'+childClass+'').prop('checked', this.checked);
        });
 }); 
 
 function popUp(url, w, h) {
        var left = (screen.width / 2) - (w / 2);
        var top = (screen.height / 2) - (h / 2);
        var sw = (screen.width * .60);
        var sh = (screen.height * .60);

        window.open(url, 'pop-up', 'width=' + sw + ', height=' + sh + ', top=' + top + ', left=' + left);

    }
</script>
<script type="text/javascript">

//----this piece of code is for toggling of installments div----//

    function showHideDiv(divName, divid) {
    $('#' + divName).modal('show');
    $('#' + divid).prop('disabled', false);
    }
//----div toggling code end here----//
    $(document).ready(function () {
<?php if (isset($_GET['tc']) && $_GET['tc'] == 'y') {
    ?>
        $('#feedetails').toggle();
        $('#feepenalties').toggle()
<?php 
} else {
    ?>
        $('#tcfeesdetails').toggle()
<?php 
}

?>
    $("#feeinfo").hide();
    $("#penaltyinfo").hide();
    $("#tcinfo").hide();
    $("#feeinfobtn").click(function() {
    $("#feeinfo").toggle();
    });
    $("#penaltyinfobtn").click(function() {
    $("#penaltyinfo").toggle();
    });
    $("#tcinfobtn").click(function() {
    $("#tcinfo").toggle();
    });
    // for editing fees
    $('#feeeditremarks').hide();
    $('#editfees').click(function(){
    $('#feeeditremarks').show();
    $('#feeeditremarks').attr("required", true);
    $("#netinstallmentfees").prop("disabled", false);
    });
    
    
    $("#netinstallmentfees").change(function(){
    var installmentField = $("#netinstallmentfees").val();
    var otherFeesField = $("#netotherfees").val();
    var newValue = parseInt(installmentField) + parseInt(otherFeesField);
    $("#grandTotal").val(newValue);
    })

            var originalFees = 0;
    $('#PayNow').click(function(){

    var originalValue = calculateFees();
    if (parseInt(originalValue) !== parseInt($("#netinstallmentfees").val())){
    $('#feeconfirmation').modal('show');
    var adjustedAmount = parseInt($('#netinstallmentfees').val()) + parseInt($('#netotherfees').val());
    $('#feeadjustedvalue').val(adjustedAmount);
    $('#feeoriginalvalue').val(calculateFees(true));
    $('#verifypassword').click(function(){
    $.ajax({
    type: "POST",
            url: "verifypassword.php",
            data: "password=" + $('#password').val(),
            success: function (data) {
            if ($.trim(data) === '1'){
            $("#imform").submit();
            }
            else{
            alert('Password not verified');
            }


            }
    });
    })

    }
    else{
    $("#imform").submit();
    }

    });
    $('#netinstallmentfees').bind('input', function () {
    addotherpenalty('ChequeBounce', '200', 'ChequeBounce');
    });
    });
    function calculateFees (other = false){

    var originalFees = 0;
    for (i = 1; i < $('input[class="feeinstallment_boxes"]').length; i++) {
    if (document.getElementById('feeinstallment[' + i + ']').checked) {
    originalFees += parseInt(document.getElementById('feeinstallmentamount[' + i + ']').value);
    if (other){
    originalFees += parseInt(document.getElementById('totalOtherFees[' + i + ']').value);
    }

    }

    }

    return originalFees;
    }

    function chequemode(val) {
    if (document.getElementById(val).checked) {
    $("#chequenumber,#bankname").prop("disabled", false);
    }
    else {
    $("#chequenumber,#bankname").prop("disabled", true);
    }

    }

    function addOtherFeeAmount(num, chkboxlen, duedate) {
    window.feeinstallements = chkboxlen;
    var updatedFees = 0;
    var updatedOtherFees = document.getElementById('netotherfees').value - document.getElementById('totalOtherFees[' + num + ']').value;
    for (i = 0; i < chkboxlen; i++) {
    if (document.getElementById('otherfeehead[' + num + '][' + i + ']').checked) {
    updatedFees += parseInt(document.getElementById('otherFeecharged[' + num + '][' + i + ']').value);
    }
    }

    document.getElementById('totalOtherFees[' + num + ']').value = updatedFees;
    document.getElementById('netotherfees').value = parseInt(updatedOtherFees) + parseInt(updatedFees);
    document.getElementById('grandTotal').value = parseInt(document.getElementById('netinstallmentfees').value) + parseInt(document.getElementById('netotherfees').value);
    }

    function updateTotalAmount(installmentToPay, Id) {

    var feeActualValue = document.getElementById('netinstallmentfees').value;
    var netotherfeeValue = document.getElementById('netotherfees').value;
    var updatedinstallmentValue = 0;
    var updatedotherValue = 0;
    var checkBox = document.getElementById('feeinstallment[' + Id + ']');
    if (checkBox.checked) {
    updatedinstallmentValue = parseInt(feeActualValue) + parseInt(installmentToPay);
    updatedotherValue = parseInt(netotherfeeValue) + parseInt(document.getElementById('totalOtherFees[' + Id + ']').value);
    }
    else {
    updatedinstallmentValue = parseInt(feeActualValue) - parseInt(installmentToPay);
    updatedotherValue = parseInt(netotherfeeValue) - parseInt(document.getElementById('totalOtherFees[' + Id + ']').value);
    }

    document.getElementById("netinstallmentfees").value = updatedinstallmentValue;
    document.getElementById('netotherfees').value = updatedotherValue;
    document.getElementById('grandTotal').value = parseInt(updatedinstallmentValue) + parseInt(updatedotherValue);
    }

    function addotherpenalty(Id, otheramount, otherfeehead) {

    var chkBox;
    var totalFees;
    totalFees = parseInt(document.getElementById('netinstallmentfees').value) + parseInt(document.getElementById('netotherfees').value)

    chkBox = document.getElementById('otherpenalty[' + Id + ']');
    if (chkBox.checked) {
    totalFees = parseInt(totalFees) + parseInt(otheramount);
    $('#' + otherfeehead).prop('disabled', false);
    }
    else {
    totalFees = parseInt(totalFees) - parseInt(otheramount);
    $('#' + otherfeehead).prop('disabled', true);
    }

    document.getElementById('grandTotal').value = 'Rs ' + totalFees;
    }

    function updatefeeinstallment(Id, num){

    var updatedFees = 0;
    var TotalFees = document.getElementById('feeinstallmentamount[' + Id + ']').value;
    var netFees = document.getElementById('netinstallmentfees').value;
    if (document.getElementById('feeinstallment[' + Id + ']').checked){
    netFees = parseInt(netFees) - parseInt(TotalFees);
    }

    if (document.getElementById('feecomponent[' + Id + '][' + num + ']').checked) {
    updatedFees = parseInt(document.getElementById('feeinstallmentamount[' + Id + ']').value) + parseInt(document.getElementById('feecomponent[' + Id + '][' + num + ']').value)
    }
    else{
    updatedFees = parseInt(document.getElementById('feeinstallmentamount[' + Id + ']').value) - parseInt(document.getElementById('feecomponent[' + Id + '][' + num + ']').value)
    }

    document.getElementById('feeinstallmentamount[' + Id + ']').value = updatedFees;
    if (document.getElementById('feeinstallment[' + Id + ']').checked){
    netFees = parseInt(netFees) + parseInt(updatedFees);
    }
    document.getElementById('netinstallmentfees').value = netFees;
    document.getElementById('grandTotal').value = 'Rs. ' + (parseInt(netFees) + parseInt(document.getElementById('netotherfees').value));
    }

</script>
<form action="<?php echo PROCESS_FORM; ?>" method="post" name="imform" id="imform">
    <style>
        body{
            padding-top: 5px;
        }
    </style>
    <?php
    $HtmlArray = studentDetailsSql();
    $arr = feeRuleSql();
    ?>
    <!-- hidden field-->
    <input type='hidden' name='studentid' value='<?php echo $HtmlArray[0]['studentid'] ?>' />    
    <input type='hidden' name='clsecassocid' value='<?php echo $HtmlArray[0]['clsecassocid'] ?>' />
    <input type='hidden' name='instituteabbrevation' value='<?php echo $HtmlArray[0]['instituteabbrevation'] ?>' />
    <input type='hidden' name='sessionname' value='<?php echo $HtmlArray[0]['sessionname'] ?>' />

    <!--  Student details start---------------------------------------------------------- -->
    <span class="clearfix">&nbsp;<br></span>        
    <div class="container"  >
        <div class="panel panel-primary panel-width" id="studentinfobtn">
            <div class="panel-heading"><b>Student Information: </b>
                <span style="float:right">
                    <i class="fa fa-caret-square-o-down fa-lg" aria-hidden="true"></i>
                </span>
            </div>
            <div class="panel-body" id="studentinfo">

                <table class="table" >
                    <tr>
                        <td>
                            <strong>SCHOLAR No.</strong> 
                        </td>
                        <td>
                            <?php echo($HtmlArray[0]['scholarnumber']) ?>
                        </td>
                        <td>
                            <strong>CLASS </strong>
                        </td>
                        <td>
                            <?php
                            echo(strtoupper($HtmlArray[0]['classdisplayname'])
                            . "-" . strtoupper($HtmlArray[0]['sectionname'])
                            )
                            ?>

                        </td>
                    </tr>  
                    <tr>
                        <td>
                            <strong>STUDENT NAME</strong> 
                        </td>
                        <td>
                            <?php
                            echo(strtoupper($HtmlArray[0]['firstname']) . " " .
                            strtoupper($HtmlArray[0]['middlename']) . " " .
                            strtoupper($HtmlArray[0]['lastname'])
                            );
                            ?>
                        </td>
                        <td>
                            <strong>FATHER'S NAME </strong> 
                        </td>
                        <td>
                            <?php
                            echo(strtoupper($HtmlArray[0]['parentfirstname']) . " " .
                            strtoupper($HtmlArray[0]['parentmiddlename']) . " " .
                            strtoupper($HtmlArray[0]['parentlastname'])
                            );
                            ?> 
                        </td>
                    </tr>

                    <tr>
                        <td colspan="4">
                            <strong>Fee Rule(s) : </strong>
                            <?php
                            if (!empty($arr)) {
                                foreach ($arr as $key => $value) {
                                    $feecomponent = strtoupper($value['feecomponent']);
                                    $feeruleamount = $value['feeruleamount'];
                                    $feerule = $value['feerulename']; ?>
                                    <a href="#" data-toggle="tooltip" data-placement="right" 
                                       data-original-title="Relaxation Of Rs <?php echo $feeruleamount ?> on <?php echo $feecomponent ?>">
                                           <?php echo($feerule) ?> 
                                    </a> 
                                    <?php

                                }
                            } else {
                                echo "N/A (<a href=\"../student/studentFees.php?sid=" . cleanVar($_GET['studentid']) . "&mode=edit&pop-up=y\">Apply here</a>)";
                            }
                            ?>
                        </td></tr>
                </table>
            </div>
        </div>
    </div>
    <!--  Student details end---------------------------------------------------------- -->

    <!-- Fee Panel Start ----------------------------------------------- -->
    <div class="container" >
        <div class="panel panel-info">
            <div class="panel-heading" id="feeinfobtn"><b>Fee Due </b>
                <span style="float:right">
                    <i class="fa fa-caret-square-o-down fa-lg" aria-hidden="true"></i>
                </span>
            </div>
            <div class="panel-body" id="feeinfo">
                <div class="col-sm-12">
                    <small class="text-warning"> * shows cheque Bounced against paying installment</small>
                    <table class="table table-responsive" >
                        <tr class="danger">
                            <th></th>
                            <th> Installment </th>
                            <th> Amount</th>
                            <th> Other Fees </th>
                        </tr>

                        <?php
                        $studentid = cleanVar($_GET['studentid']);
                        $feecollected = feeStatus();
                        $feeDetails = array();
                        $getInstallmentArray = createInstallmentArray();

                        $j = 1;
                        $instNo = 1;
                        $i = 0;
                        $totalOtherFees = 0;
                        $netInstallmentFees = 0;
                        $grandTotal = 0;
                        $totalinstallmentFee = 0;
                        $installmentNo = count($getInstallmentArray);

                        foreach ($getInstallmentArray as $key => $value) {
                            $otherFeesDetails = otherFeeSql(null);
                            $installmentMonth = date('M', strtotime($key));
                            $duedate = date('d/m/Y', strtotime($key));
                            $lateFeeAmount = implode('', LateFees($key, $value['totalamount']));
                            $totalinstallmentFees = 0;
                            $otherFeeAmount = 0;
                            $lateFees = 0;
                            $status = " ";
                            $checked = "";
                            $disabled = "disabled";

                            if (array_key_exists($key, $feecollected)) {
                                $feeAmount = $feecollected[$key]['feeinstallmentamount'];
                                $collectiondate = date('d/m/Y', strtotime($feecollected[$key]['datecreated']));
                                
                                $feereciept = $feecollected[$key]['receiptid'];
                                echo "
                             <tr class=\"success\">
                                    <td>Inst $instNo</td>
                                    <td>$installmentMonth - $feeAmount</td>
                                    <td>Date - $collectiondate </td>
                                    <td>Reciept -  $feereciept </td>
                            </tr>
                                 
                               ";
                                $instNo ++;
                                continue;
                            } else {
                                if ($key <= date("Y-m-d")) {
                                    $status = "text-danger";
                                    $checked = "checked";
                                    $totalinstallmentFee += $value['totalamount'];
                                }

                                $lateFees = $lateFeeAmount;
                                $netInstallmentFees += $value['totalamount'];
                                //$totalinstallmentFee += $value['totalamount'];
                                //$grandTotal += $totalinstallmentFees;
                                $count = 0;

                                foreach ($otherFeesDetails as $arrkey => $val) {
                                    //$otherFeeAmount = 0;
                                    $otherFeeHead = $val['otherfeehead'];
                                    $otherfeeHeadId = $val['feeotherchargesid'];

                                    if ($otherFeeHead == "Late Fees") {
                                        $otherFeeAmount += $lateFees;
                                        $totalinstallmentFees = $value['totalamount'] + $lateFees;
                                    } elseif ($otherFeeHead == "Conveyance Fees" && getTransportFees() != 0) {
                                        $otherFeeAmount += getTransportFees();
                                        $totalinstallmentFees += getTransportFees();
                                    } else {
                                        $otherFeeHead = $val['feeotherchargesid'];
                                        $otherFeeAmount += $val['amount'];
                                    }
                                    $count++;
                                }
                            }

                            if ($checked == "checked") {
                                $totalOtherFees += $otherFeeAmount;
                            }

                            // Check whether the installment is paid and due again for cheque bounce case
                            $chequeBounceInst = getchequeBounceAmt(); ?> 

                            <tr>
                                <td>
                                    <div class="checkbox"><label>
                                            <input type="checkbox"   
                                                   class="feeinstallment_boxes"  <?php echo $checked ?> 
                                                   name="feeinstallment[<?php echo($j - 1) ?>]" 
                                                   id="feeinstallment[<?php echo $j ?>]"
                                                   onClick="JavaScript: updateTotalAmount(<?php echo $value['totalamount'] ?>,<?php echo $j ?>)" 
                                                   value="<?php echo $duedate ?>" >

                                            <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                                        </label></div>
                                </td>

                                <td> 
    <?php
    $chqFlag = 0;
                            if (is_array($chequeBounceInst) && (array_key_exists($key, $chequeBounceInst))) {
                                $chqBounce = "<strong>*</strong>";
                                $chqFlag = 1;
                            } else {
                                $chqBounce = '';
                            } ?>
                                    <span class="<?php echo($status) ?>"> Inst <?php echo($instNo . "-" . $installmentMonth . $chqBounce) ?>  </span>
                                </td>

                                           <!--    DUe Date    <td> <?php echo $duedate ?> </td>   -->

                                <td class="col-lg-4">

                                    <div class="input-group">
                                        <input type="text" class='form-control'  name="feeinstallmentamount[]" 
                                               id="feeinstallmentamount[<?php echo $j ?>]" 
                                               value="<?php echo $value['totalamount'] ?>" >
                                        <input type="hidden" name="installmentAmount[]" id="installmentAmount[<?php echo $j ?>]"
                                               value="<?php echo $value['totalamount'] ?>" >
                                        <span class="input-group-btn"> 
                                            <button class="btn btn-secondary" type="button"  name="search" id="search">
                                                <a href="javascript:(void);" onClick="JavaScript: showHideDiv('displaycontent<?php echo $j ?>')" > 
                                                    <image src="<?php echo DIR_ASSET ?>/images/show_more.png" width="19" height="19" border="0" alt="Edit Field" />
                                                </a>
                                            </button>
                                        </span>   
                                    </div><!-- /input-group -->

                                </td>

                                <td class="col-lg-4">

                                    <div class="input-group">

                                        <input type="text" name="totalOtherFees[<?php echo $j ?>]" class="form-control"
                                               id ="totalOtherFees[<?php echo $j ?>]" disabled="true"
                                               value="<?php echo $otherFeeAmount ?>"   >

                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="button"  name="search" id="search" >
                                                <a href="javascript:(void);" 
                                                   onClick="JavaScript: showHideDiv('displayotherfees<?php echo $j ?>', 'totalOtherFees[<?php echo $j ?>]')" > 
                                                    <image src="<?php echo DIR_ASSET ?>/images/edit_icon.png"  width="20" height="20" border="0" alt="Edit Field" />
                                                </a>
                                            </button>
                                        </span>   
                                    </div><!-- /input-group -->
                                </td>
                            </tr>

    <?php
    $j++;
                            $instNo++;
                        }

$grandTotal = $totalinstallmentFee + $totalOtherFees;
?>   
                        <tr>
                            <td colspan="2" class="danger" align="right"> <strong> Total</strong></td>
                            <td class="danger" >
                                <div class="input-group">
                                    <input type="text" class='form-control' disabled id="netinstallmentfees" name="netinstallmentfees" 
                                           value="<?php echo $totalinstallmentFee ?>" />
                                    <input type="hidden" name="totalinstallmentValue" id="totalinstallmentValue" value="<?php echo $totalinstallmentFee ?>">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-success" name="editfees" id="editfees">
                                            Edit
                                        </button>
                                    </span>
                                </div>
                                <label id="feeeditremarks"> Remarks
                                    <textarea class="form-control" name="feeeditremarks" id="feeeditremarks" ></textarea>
                                </label>
                            </td>

                            <td class="danger" >
                                <input type="text" class='form-control' id="netotherfees" name="netotherfees"  
                                       value="<?php echo $totalOtherFees ?>"/>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!--  -Fee form ending--------------------------  -->
<?php
$chequeBounceInst = getchequeBounceAmt();
$dispalystatus = "Penalties ";
if ($chequeBounceInst != 0) {
    $dispalystatus = " Penalties (Applicable)";
}
?>
    <div class="container" id="feepenalties">
        <div class="panel panel-danger">
            <div class="panel-heading" id="penaltyinfobtn"><b><?php echo $dispalystatus ?> </b>
                <span style="float:right">
                    <i class="fa fa-caret-square-o-down fa-lg" aria-hidden="true"></i>
                </span>

            </div>
            <div class="panel-body" id="penaltyinfo">

                <table class="table table-responsive" border="0">
                    <tr>
                        <td colspan="3"  align="right"> <strong>Other Penalties</strong></td>
<?php
$OtherFeeDetails = otherFeeSql(true);
$disabled = 'disabled="true"';

$chqAmt = 0;
$checked = "";
$i = 1;

foreach ($OtherFeeDetails as $othkey => $othvalue) {
    $OtherFeeHead = $othvalue['otherfeehead'];
    if (!empty($chequeBounceInst)) {
        $disabled = "";
        $checked = "checked='checked'";
        $chqAmt = implode('', array_unique(array_values($chequeBounceInst)));
        $grandTotal += $chqAmt;
    } ?>
                            <td align="right">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" <?php echo $checked ?> 
                                               name="otherpenalty[<?php echo $OtherFeeHead ?>]" id="otherpenalty[]" 
                                               value="<?php echo $chqAmt ?>" 
                                               onclick="addotherpenalty('<?php echo $OtherFeeHead ?>', '<?php echo $chqAmt ?>', '<?php echo(str_replace(' ', '', $OtherFeeHead)) ?>')">  
    <?php echo $OtherFeeHead ?>
                                        <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                                    </label>
                                </div>
                            </td>

                            <td >
                                <input type="text" class='form-control' 
                                       name="<?php echo $OtherFeeHead ?>" <?php echo $disabled ?> 
                                       id="<?php echo(str_replace(' ', '', $OtherFeeHead)); ?>" 
                                       value="<?php echo $chqAmt ?>">
                            </td>
<?php 
} ?>   

                    </tr>
                </table>
            </div>
        </div>
    </div>
    <!-- penalty section ending ----------------------- -->

    <!-- TC ----------------------------------- -->
    <div class="container" id="tcfeesdetails">
        <div class="panel panel-warning">
            <div class="panel-heading" id="tcinfobtn"><b>TC</b>
                <span style="float:right">
                    <i class="fa fa-caret-square-o-down fa-lg" aria-hidden="true"></i>
                </span>
            </div>
            <div class="panel-body" id="tcinfo">
                <table class="table table-responsive">
                    <tr>
                        <td align="right"><label>TC Amount:</label></td>
                        <td width="25%"><input type="text" name="tcfeesamount" class="form-control"></td>
                        <td width="25%"></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <!-- Caution money section ends here --------------------- -->
    <div class="container">
        <div class="panel panel-info">
            <div class="panel-heading" id="paymentinfobtn"><b>Payment Mode</b></div>
            <div class="panel-body" id="paymentinfo">

                <table class="table">
                    <tr>
                        <td colspan="3"  align="right"> <strong>Fee Mode</strong></td>
                        <td>
                            <input type="radio" name="feemodeid" checked="checked" id="cash"  value="305" > &nbsp; <strong>Cash</strong></td>
                        <td >
                            <input type="radio" name="feemodeid" id="cheque" value="304" 
                                   onclick="JavaScript: chequemode(this.id);"> &nbsp; <strong>Cheque</strong>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="3"  align="right"> <strong></strong></td>
                        <td>
                            <input type="text" class="form-control"  id="bankname" name="bankname" disabled="" placeholder="Bank Name">
                        </td>
                        <td>
                            <input type="text" class="form-control"  id="chequenumber" name="chequenumber" disabled="" placeholder="Cheque Number">
                        </td>
                    </tr>



                    <tr>
                        <td colspan="3"  align="right"> <strong>Remarks</strong></td>

                        <td colspan="2">
                            <textarea class='form-control' name="remarks" id="remarks"></textarea>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="3"  align="right"> <strong>Amount Payable</strong></td>

                        <td colspan="2">
                            <input type="text" name="grandTotal" id="grandTotal"  class="form-control"  readonly
                                   value= "<?php echo(" " . $grandTotal); ?>" />
                        </td>
                    </tr>

                    <tr>
                        <td colspan="6" align="right" >
                            <input type="button" id="PayNow" name="PayNow" value="Pay Now" class="btn btn-success">
                        </td>
                    </tr>

                </table>
            </div>
        </div>
    </div>



    <!-- --------------------------                 -->
<?php
$j = 1;
foreach ($getInstallmentArray as $key => $value) {
    $installmentMonth = date('F', strtotime($key));
    $recieptno = "";
    $collectionDate = "";
    $duedate = date('d/m/Y', strtotime($key));
    if (array_key_exists($key, $feecollected)) {
        $recieptno = $feecollected[$key]['receiptid'];
        $collectionDate = date("d/m/Y", strtotime($feecollected[$key]['datecreated']));
    }

    $otherFeesDetails = otherFeeSql(null);
    $installmentMonth = date('M', strtotime($key));
    $lateFeeAmount = implode('', LateFees($key, $value['totalamount']));

    $totalinstallmentFees = 0;
    $lateFees = 0;
    if ($key <= date("Y-m-d")) {
        if (array_key_exists($key, $feecollected)) {
            continue;
        } else {
            $status = "text-danger";
            $checked = "checked";
            $lateFees = $lateFeeAmount;
        }
        $disabled = "";
    } else {
        $status = " ";
        $checked = "";
        $disabled = "disabled";
    } ?>
        <div class="modal fade"  role="dialog" aria-labelledby="fee-details-label" aria-hidden="true"
             id="displaycontent<?php echo $j ?>" style="display:none">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="fee-details-label">Installment Details - <?php echo $installmentMonth ?></h4>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered table-striped" >  
                            <tr>
    <?php
    $TotalAmount = $value['totalamount'];
    unset($value['totalamount']);
    $chk = 0;
    foreach ($value as $k => $val) {
        ?>
                                    <td>
                                        <input type="checkbox" checked="checked" 
                                               id="feecomponent[<?php echo $j ?>][<?php echo $chk ?>]"
                                               value="<?php echo $val['amount'] ?>"
                                               onclick="Javascript : updatefeeinstallment('<?php echo $j ?>', '<?php echo $chk ?>')" >

                                    </td>
                                    <td colspan="2"><?php echo $k ?></td>
                                    <td>
                                        <input type="text" disabled="disabled" id="installmentoriginalAmount" class="form-control" 
                                               value="<?php echo $val['originalamount'] ?>" >
                                    </td>
                                    <td>
                                        <input type="text" id="installmentAmount[<?php echo $j ?>][<?php echo $chk ?>]" class="form-control" 
                                               value="<?php echo $val['amount'] ?>">
                                    </td>

                                </tr>
        <?php
        $chk++;
    } ?>    
                        </table> 

                    </div>
                </div><!-- modal-content -->
            </div><!-- modal-dialog -->
        </div><!--modal -->


        <div class="modal fade"  role="dialog" aria-labelledby="fee-details-label" aria-hidden="true"
             id="displayotherfees<?php echo $j ?>" style="display:none">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="fee-details-label">Other Fee Details - <?php echo $installmentMonth ?> </h4>
                    </div>
                    <div class="modal-body">


                        <table class="table table-bordered table-striped" >  
                            <tr> 
    <?php
    $count = 0;

    foreach ($otherFeesDetails as $key => $val) {
        $checked = "checked";
        $disabled = "";
        $otherFeeHead = $val['otherfeehead'];
        $otherFeeAmount = $lateFees;
        if ($otherFeeHead != "Late Fees") {
            $otherFeeAmount = $val['amount'];
            $checked = "";
            $disabled = "";
        }
        if ($otherFeeHead == "Conveyance Fees") {
            if (getTransportFees() != 0) {
                $otherFeeAmount = getTransportFees();
                $checked = "checked";
                $disabled = "";
            } else {
                $otherFeeAmount = 0;
                $checked = "";
                $disabled = "disabled";
            }
        } ?>  

                                    <td><input type="checkbox" <?php echo $checked ?> name='otherfeehead[<?php echo $j ?>][<?php echo $count ?>]' 
                                               id='otherfeehead[<?php echo $j ?>][<?php echo $count ?>]' 
                                               value='<?php echo $val['feeotherchargesid'] ?>'
        <?php echo $disabled ?>  />
                                    </td>

                                    <td colspan='2' > <?php echo $val['otherfeehead'] ?></td>

                                    <td > Amount</td>
                                    <td><input type="text" name=otherFeecharged[<?php echo $j ?>][<?php echo $count ?>]'
                                               id='otherFeecharged[<?php echo $j ?>][<?php echo $count ?>]' 
                                               class='form-control' 
                                               value="<?php echo $otherFeeAmount ?>" <?php echo $disabled ?> /> 
                                    </td>

                                </tr>

                                <input type="hidden" name="otherfees[<?php echo $j ?>][<?php echo $count ?>]"        
                                       id="otherfees[<?php echo $duedate ?>][<?php echo $j ?>]"
                                       value="<?php echo $otherFeeHead ?>" <?php echo $disabled ?>>
        <?php
        $count++;
    } ?>
                        </table>        

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success"  data-dismiss="modal"
                                onclick="addOtherFeeAmount(<?php echo $j ?>, <?php echo $count ?>, '<?php echo $duedate ?>');">
                            Done
                        </button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    <?php
    $j++;
}
?>
    <!------Fee edit modal---->
    <div class="modal fade bs-example-modal-lg"  role="dialog" aria-labelledby="myLargeModalLabel" id="feeconfirmation" style="display:none">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-body">
                    You have made changes in the calculation. Please confirm the following
<?php $studentDetails = studentDetailsSql(); ?>
                    <table class="table table-bordered table-striped" >  
                        <tr> 
                            <td colspan='1'><label> Student Name : </label>
<?php echo($studentDetails[0]['firstname'] . " " . $studentDetails[0]['middlename'] . " " . $studentDetails[0]['lastname']) ?>
                            </td>
                            <td> <label>Scholar Number : </label>
<?php echo($studentDetails[0]['scholarnumber']) ?>
                            </td>
                            <td> <label>Class : </label>
<?php echo($studentDetails[0]['classname'] . "-" . $studentDetails[0]['sectionname']) ?>
                            </td>
                        </tr>

                        <tr> 
                            <td><label> Original Amount: </label>
                                <input type="text" class="form-control" name="feeoriginalvalue" id="feeoriginalvalue"  >
                            </td>
                            <td> <label>Adjusted Amount : </label>
                                <input type="text" class="form-control"  name="feeadjustedvalue" id="feeadjustedvalue"  >
                            </td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                    <form class="form-inline">
                        <div class="form-group">
                            <label for="password">Enter Password</label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-default" >Cancel</button>
                    <button type="button" id="verifypassword" class="btn btn-success">Confirm</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

</form>


<?php
require VIEW_FOOTER;

function createInstallmentArray()
{
    $HtmlArray = feeComponentsSql();

    $newOptions = array();
    $i = 0;
    $totalamount = array();

    foreach ($HtmlArray as $option) {
        $duedate = $option['duedate'];
        $feecomponents = $option['feecomponent'];
        $amount = $option['amount'];
        $originalamount = $option['originalamount'];
        $newOptions[$duedate][$feecomponents]['amount'] = $amount;
        $newOptions[$duedate][$feecomponents]['originalamount'] = $originalamount;
    }

    foreach ($newOptions as $key => $value) {
        $total = 0;
        foreach ($value as $k => $val) {
            $total += $val['amount'];
            $newOptions[$key]['totalamount'] = $total;
        }
    }
    return $newOptions;
}

function studentDetailsSql()
{
    if (isset($_GET['studentid']) && is_numeric($_GET['studentid'])) {
        $studentID = cleanVar($_GET['studentid']);


        $sql = "SELECT *FROM
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
            `tblinstsessassoc` As t11,
            `tblinstitute` AS t12,
            `tblacademicsession` AS t13,
            `tblstudentdetails` AS t14

            WHERE t1.studentid = $studentID
            AND t1.studentid = t2.studentid
            AND t1.studentid = t14.studentid
            AND t2.clsecassocid = t8.clsecassocid
            AND t3.classid = t8.classid 
            AND t4.sectionid = t8.sectionid
            AND t1.instsessassocid = t5.instsessassocid
            AND t5.instituteid = t6.instituteid
            AND t5.academicsessionid = t7.academicsessionid 
            AND t9.studentid = t1.studentid
            AND t10.parentid = t9.parentid
            AND t11.instsessassocid = t1.instsessassocid
            AND t11.instituteid = t12.instituteid
            AND t11.academicsessionid = t13.academicsessionid
            AND t1.status =1
            AND t1.deleted != 1
		  
		  ";

        $result = dbSelect($sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $studentdetail[] = $row;
        }
        return $studentdetail;
    }
}

function feeComponentsSql()
{
    $conveyance = "";
    $pickuppoint = "";
    $studentdetails = studentDetailsSql();
    $feeruledetails = getfeeRuleSql();

    $feeRuleInstallment = getInstFeeRuleAssoc();
    
    $sessionstartdate = getSessionDetails();
    $dateofJoining = $studentdetails[0]['dateofjoining'];

    if (!empty($feeruledetails)) {
        foreach ($feeruledetails as $key => $value) {
            $feeruleamount[] = $feeruledetails[$key]['feeruleamount'];
            $feerulecomponents[] = $feeruledetails[$key]['feecomponent'];
            $feerulemode[] = $feeruledetails[$key]['feerulemodeid'];
            $feeruletype[] = $feeruledetails[$key]['feeruletype'];
        }
    }


    $classid = $studentdetails[0]['classid'];
    $sql = " SELECT t1.feestructureid, t3.feecomponent, t2.feestructureid, t2.amount, t2.duedate, 
                t2.isrefundable, t2.frequency 
                    
                   FROM `tblfeestructure` AS t1,
		  `tblfeestructuredetails` AS t2,
		  `tblfeecomponent` AS t3
		  
                   WHERE t1.classid = $classid
		   AND t1.feestructureid = t2.feestructureid
		   AND t1.feecomponentid = t3.feecomponentid
                   AND t1.status = 1
                   ORDER BY t2.duedate ASC 
           ";


    $result = dbSelect($sql);
    if (mysqli_num_rows($result) != 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $feedetails[] = $row;
        }
    }

    if (!empty($feeruledetails)) {
        
        foreach ($feeruledetails as $key => $value) {
            foreach ($feedetails as $k => $val) {
               
                if ($value['feecomponent'] == $val['feecomponent'] && in_array($val['duedate'], $feeRuleInstallment)) {
                    //echoThis($value['feecomponent']." == ". $val['feecomponent']);
                    $feedetails[$k]['originalamount'] = $val['amount'];
                    $val['amount'] = updateFees($value['feeruletype'], $value['feerulemodeid'], $val['amount'], $value['feeruleamount']);
                    $feedetails[$k]['amount'] = $val['amount'];
                } else {
                    $feedetails[$k]['amount'] = $val['amount'];
                    $feedetails[$k]['originalamount'] = $val['amount'];
                }
            }
        }

        return($feedetails);
    } else {
        foreach ($feedetails as $key => $value) {
            $feedetails[$key]['originalamount'] = $value['amount'];
        }
        return($feedetails);
    }
}

function getfeeRuleSql()
{
    $studentid = cleanVar($_GET['studentid']);

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
        GROUP BY t4.feecomponent, t1.feeruleid
        ";
    if (($result = dbSelect($sql)) && (($num_row = mysqli_num_rows($result)) != 0)) {
        while ($row = mysqli_fetch_assoc($result)) {
            $feeruledetails[] = $row;
        }
        return($feeruledetails);
    }
}

function feeRuleSql()
{
    $studentid = cleanVar($_GET['studentid']);

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
        GROUP BY t1.feeruleid
        ";
    
    if (($result = dbSelect($sql)) && (($num_row = mysqli_num_rows($result)) != 0)) {
        while ($row = mysqli_fetch_assoc($result)) {
            $feeruledetails[] = $row;
        }
        return($feeruledetails);
    }
}

function getInstFeeRuleAssoc()
{
    $installmentArray = array();
    $studentid = cleanVar($_GET['studentid']);

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
}

function updateFees($type, $mode, $amount, $value)
{
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

function otherFeeSql($frequency)
{
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
            AND t3.mastercollectiontype = 'Fee Frequency'
            $sqlStr
            AND t3.mastercollectiontypeid = t4.mastercollectiontypeid
            AND t4.mastercollectionid = t2.frequency
            AND t1.status = 1
            AND t1.deleted != 1
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

function totalFeeAmount($feeArray)
{
    $arr = feeComponentsSql();
    $status = feeStatus();
    $totalamount = 0;

    foreach ($feeArray as $key => $value) {
        foreach ($value as $k => $val) {
            if (array_key_exists($key, $status)) {
                continue;
            } elseif ($key <= date('Y-m-d')) {
                $totalamount += $val;
            }
        }
    }
    return $totalamount;
}

function feeStatus()
{
    $feeCollected = array();
    $studentid = cleanVar($_GET['studentid']);

    $sql = " SELECT t1.studentid, t2.feecollectionid, t3.feeinstallment, t2.feeinstallmentamount,
                t1.receiptid, t1.datecreated

                 FROM `tblfeecollection` AS t1,
                `tblfeecollectiondetail` AS t2,
                `tblfeeinstallmentdates` AS t3

                 WHERE t1.studentid = $studentid
                AND t1.feecollectionid = t2.feecollectionid
                AND t2.feecollectiondetailid = t3.feecollectiondetailid
                AND t2.feestatus != '2'
                AND t2.refundstatus = 0
                
 ";
    $result = dbSelect($sql);

    while ($row = mysqli_fetch_assoc($result)) {
        $feeCollected[$row['feeinstallment']] = $row;
    }
    return $feeCollected;
}

function LateFees($duedate, $installmentamount)
{
    $feeamount = 0;
    $totaldays = 0;
    $otherFeeDetails = otherFeeSql(null);
   
    foreach ($otherFeeDetails as $key => $value) {
        foreach ($value as $k => $val) {
            if ($value['otherfeehead'] == 'Late Fees') {
                continue;
            } else {
                unset($otherFeeDetails[$key]);
            }
        }
    }

    if ($duedate < date('Y-m-d')) {
        $datediff = date_diff(date_create($duedate), date_create(date('Y-m-d')));
        $totaldays += $datediff->format("%R%a days");
    }
    
    foreach ($otherFeeDetails as $key => $value) {
        if ($value['status'] == 1 && $value['otherfeehead'] == 'Late Fees') {
            $calcAmount = OtherFeeCalculate($value['chargemode'], $value['otherfeetype'], $value['frequency'], $value['amount'], $installmentamount, $totaldays);
        }
    }
    
    return ($calcAmount);
}

function otherFees($duedate, $installmentamount)
{
    $feeamount = 0;
    $totaldays = 0;
    $otherFeeDetails = otherFeeSql(null);

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

function OtherFeeCalculate($chargemode, $otherfeetype, $frequency, $amount, $feeamount, $totaldays)
{
    $updatedAmt = 0;
    //echoThis($totaldays); die;
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
    } else {
        return 0;
    }
}

function OtherFeesCalculateAmount($totaldays, $amount, $frequency)
{
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

function getTransportFees()
{
    $studentid = cleanVar($_GET['studentid']);

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

function getchequeBounceAmt()
{
    $studentid = cleanVar($_GET['studentid']);
    $sql = "SELECT t3.amount , t4.feeinstallment
            FROM `tblfeecollection` AS t1,
            `tblfeecollectiondetail` AS t2,
            `tblotherfeepenalties` AS t3,
            `tblfeeinstallmentdates` AS t4

            WHERE t1.instsessassocid = '$_SESSION[instsessassocid]'
            AND t1.studentid = '$studentid'
            AND t1.feecollectionid = t2.feecollectionid
            AND t2.feestatus = '2' 
            AND t1.studentid = t3.studentid
            AND t1.feecollectionid = t3.feecollectionid
            AND t2.feecollectiondetailid = t4.feecollectiondetailid
            
            AND t3.status = 0
";

    $result = dbSelect($sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $duedates[$row['feeinstallment']] = $row['amount'];
        }
        return $duedates;
    } else {
        return 0;
    }
}

function getSessionDetails()
{
    $sql = "SELECT t2.sessionstartdate, t2.sessionenddate 
            FROM `tblinstsessassoc` AS t1,
            `tblacademicsession` AS t2
            
            WHERE  t1.academicsessionid = t2.academicsessionid
        ";

    $result = dbSelect($sql);
    $row = mysqli_fetch_assoc($result);
    return $row['sessionstartdate'];
}

/*
 * This function is used to check
 * whether student has paid the amount charged only once during scholl tenure
 * eg Admission fees , Caution Money etc.
 * Frequency = 3 Depicts charging the component only once from student
 * During time of admission
 */

function studentFixChargesStatus($studentid, $dateofJoining)
{
    $where = '';
    if (!empty($dateofJoining)) {
    }
    $sql = "SELECT t1.feecomponentid, t2.duedate, t2.amount, t2.frequency,
            t3.datecreated as collectiondate,  t5.feecomponent

            FROM `tblfeestructure` AS t1,
            `tblfeestructuredetails` AS t2,
            `tblfeecollection` as t3,
            `tblfeecollectiondetail` as t4,
            `tblfeecomponent` as t5,
            `tblfeeinstallmentdates` as t6,
            `tblstudentacademichistory` as t7,
            `tblclsecassoc` as t8

            WHERE t1.feestructureid = t2.feestructureid
            AND t1.feecomponentid = t5.feecomponentid
            AND t7.studentid = $studentid
            AND t7.clsecassocid = t8.clsecassocid
            AND t1.classid = t8.classid
            AND t7.studentid = t3.studentid
            AND t3.feecollectionid = t4.feecollectionid
            AND t4.feecollectiondetailid = t6.feecollectiondetailid
            AND t2.duedate = t6.feeinstallment
            AND t2.frequency  = 3
            
            ";

    $result = dbSelect($sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $feedetails[$row['feecomponent']][$row['collectiondate']] = $row['amount'];
        }
        return $feedetails;
    } else {
        return 0;
    }
}
